// ============================================================ //
// Start of Statements
// ============================================================ //
// Routes and CSRF token come from window.STMT_CONFIG, which is set in commissions/statements.blade.php

var STMT_PDFJS_URL = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js";
var STMT_PDFJS_WORKER_URL = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
var STMT_MAX_FILE_SIZE = 20 * 1024 * 1024;

var STMT_FIELDS = ["reservationNumber", "clientName", "groupNumber", "agencyCommission", "checkoutDate", "hotelName"];
var STMT_SUMMARY_WORDS_REGEX = /\b(sub\s?-?total|total|totals|currency|payment|document|cheque|check\s*(no|number|#|amount|date)|balance|remittance|statement|amount due)\b/i;

// Order matters: more specific fields are tested first
var STMT_HEADER_PATTERNS = {
    hotelName: [
        /^(hotel|property|resort|lodging|vendor|supplier)( name)?$/,
        /^(product|product name|tour|tour name|excursion|activity|service)$/
    ],
    groupNumber: [
        /^group( (number|no|num|#|code|id))?$/,
        /^group#$/,
        /^block( code)?$/
    ],
    reservationNumber: [
        /^(reservation|res|booking|confirmation|Confirmation Number|confirm|invoice number|Invoice Number|invoice|Invoice|Invoice #|#|trip reference|order #|order number|locator|conf)( (number|no|num|#|code|id|ref|reference))?$/,
        /^(res|conf|booking|confirmation)#$/,
        /^(reference|ref|ref #|reference #|reference number|booking id|order id|record locator|locator|pnr|itinerary( (number|no|#))?|booking ref(erence)?)$/
    ],
    clientName: [
        /^(guest|client|customer|passenger|traveler|traveller|lead passenger|lead client|Client Name|pax)( (name|names|last name))?$/,
        /^(name|guest names|last name first name|lname fname)$/
    ],
    agencyCommission: [
        /^(agency |net |gross |total )?(commission|comm|amount|line total|paid amount|amount paid|remittance amount|com)( (amount|amt|due|paid|earned|payable|usd|\$))?$/,
        /^(amount paid|payment amount|paid amount|commission \(usd\))$/
    ],
    checkoutDate: [
        /^(check out|checkout|check-out|c\/o|departure|depart|dep|date|trip date|invoice date|out)( date)?$/,
        /^(end date|date out)$/
    ]
};

var STMT_SKIP_LINE_REGEX = /^(sub\s?-?total|grand total|totals?\b|page \d+|continued|balance|amount due|total due)/i;

var STMT_MONTHS = { jan: 1, feb: 2, mar: 3, apr: 4, may: 5, jun: 6, jul: 7, aug: 8, sep: 9, oct: 10, nov: 11, dec: 12 };

var stmtSelectedFile = null;
var stmtPdfJsPromise = null;
var stmtDeleteStatementId = -1;

// ------------------------------------------------------------ //
// Auto-start when the Statements page is open
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("stmtDropzone")) {
        loadStatementsView();
    }
});

// ------------------------------------------------------------ //
function loadStatementsView()
{
    stmtSelectedFile = null;
    stmtDeleteStatementId = -1;

    stmtBindEvents();
    stmtLoadPdfJs().catch(function () {});

    $(".tooltip").remove();
    stmtInitTooltips(document);
}

// ------------------------------------------------------------ //
// Laravel plumbing helpers
// ------------------------------------------------------------ //
function stmtRoute(name)
{
    return (window.STMT_CONFIG && window.STMT_CONFIG.routes && window.STMT_CONFIG.routes[name]) || "";
}

function stmtCsrfToken()
{
    return (window.STMT_CONFIG && window.STMT_CONFIG.csrf) || $('meta[name="csrf-token"]').attr("content") || "";
}

function stmtShowError(message)
{
    var $modal = $("#errorModal");

    if (!$modal.length) {
        alert(message);
        return;
    }

    $("#modalErrorContent").text(message);
    $modal.removeClass("hidden");

    $(document).off("click.stmtError").on("click.stmtError", "#closeErrorModal", function () {
        $("#errorModal").addClass("hidden");
    });
}

function stmtInitTooltips(scope)
{
    if ($.fn.tooltip) {
        $(scope).find("[data-toggle='tooltip']").tooltip({ "trigger": "hover" });
    }
}

function stmtOpenModal(selector)
{
    $(selector).removeClass("hidden").addClass("flex");
}

function stmtCloseModal(selector)
{
    $(selector).addClass("hidden").removeClass("flex");
}

// ------------------------------------------------------------ //
function stmtBindEvents()
{
    $(document).off(".statements");

    $(document).on("click.statements", "#stmtDropzone", function () {
        $("#stmtFileInput").trigger("click");
    });

    $(document).on("keydown.statements", "#stmtDropzone", function (e) {
        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            $("#stmtFileInput").trigger("click");
        }
    });

    $(document).on("change.statements", "#stmtFileInput", function () {
        var file = this.files && this.files[0];
        $(this).val("");

        if (file) stmtHandleFile(file);
    });

    $(document).on("dragenter.statements dragover.statements", "#stmtDropzone", function (e) {
        e.preventDefault();
        $(this).addClass("stmtDragOver");
    });

    $(document).on("dragleave.statements", "#stmtDropzone", function (e) {
        e.preventDefault();
        if (!this.contains(e.relatedTarget)) $(this).removeClass("stmtDragOver");
    });

    $(document).on("drop.statements", "#stmtDropzone", function (e) {
        e.preventDefault();
        $(this).removeClass("stmtDragOver");

        var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
        if (files && files.length) stmtHandleFile(files[0]);
    });

    $(document).on("dragover.statements drop.statements", function (e) {
        if ($("#stmtDropzone").length && !$(e.target).closest("#stmtDropzone").length) {
            e.preventDefault();
        }
    });

    $(document).on("click.statements", "#stmtFileRemoveBtn, #stmtClearBtn", function () {
        $(".tooltip").remove();
        stmtResetUpload();
    });

    $(document).on("click.statements", "#stmtAddRowBtn", function () {
        var $row = stmtAppendRow({});
        stmtRefreshRowNumbers();
        $row.find(".stmtCellInput").first().trigger("focus");
    });

    $(document).on("click.statements", ".stmtRemoveRowBtn", function () {
        $(".tooltip").remove();
        $(this).closest("tr").remove();

        if ($("#stmtEditTableBody tr").length === 0) stmtAppendRow({});

        stmtRefreshRowNumbers();
        stmtUpdateTotal();
    });

    $(document).on("input.statements", ".stmtAmountInput", function () {
        $(this).removeClass("stmtInvalid");
        stmtUpdateTotal();
    });

    $(document).on("blur.statements", ".stmtAmountInput", function () {
        var value = $.trim($(this).val());
        if (value === "") return;

        var amount = stmtParseAmount(value);
        if (amount === "") {
            $(this).addClass("stmtInvalid");
        } else {
            $(this).val(amount).removeClass("stmtInvalid");
        }
        stmtUpdateTotal();
    });

    $(document).on("blur.statements change.statements", ".stmtDateInput", function () {
        var value = $.trim($(this).val());
        if (value === "") {
            $(this).removeClass("stmtInvalid");
            return;
        }

        var date = stmtParseDate(value);
        if (date === "") {
            $(this).addClass("stmtInvalid");
        } else {
            $(this).val(date).removeClass("stmtInvalid");
        }
    });

    $(document).on("input.statements", "#stmtStatementName", function () {
        $("#stmtStatementNameErrorMsg").text("");
    });

    $(document).on("click.statements", "#stmtSaveBtn", function () {
        stmtSaveStatement();
    });

    $(document).on("click.statements", ".stmtViewItemsBtn", function () {
        $(".tooltip").remove();
        stmtOpenItemsModal($(this).data("statementId"));
    });

    $(document).on("click.statements", ".stmtDeleteBtn", function () {
        $(".tooltip").remove();
        stmtDeleteStatementId = $(this).data("statementId");
        $("#stmtDeleteName").text($(this).data("statementName"));
        stmtOpenModal("#stmtDeleteModal");
    });

    $(document).on("click.statements", "#stmtConfirmDeleteBtn", function () {
        stmtDeleteStatement();
    });

    // Modal closing: close buttons, backdrop click, Escape key
    $(document).on("click.statements", ".stmtModalClose", function () {
        stmtCloseModal($(this).closest(".stmtModal"));
    });

    $(document).on("click.statements", ".stmtModal", function (e) {
        if (e.target === this) stmtCloseModal(this);
    });

    $(document).on("keydown.statements", function (e) {
        if (e.key === "Escape") stmtCloseModal(".stmtModal");
    });
}

async function stmtHandleFile(file)
{
    var extension = (file.name.split(".").pop() || "").toLowerCase();
    var isPdf = extension === "pdf" || file.type === "application/pdf";
    var isText = extension === "txt" || file.type === "text/plain";

    if (!isPdf && !isText) {
        stmtShowError("Please Select A PDF Or TXT File.");
        return;
    }

    if (file.size > STMT_MAX_FILE_SIZE) {
        stmtShowError("The File Is Larger Than 20 MB.");
        return;
    }

    stmtSelectedFile = file;

    $("#stmtFileIcon i").attr("class", isPdf ? "far fa-file-pdf" : "far fa-file-alt");
    $("#stmtFileName").text(file.name);
    $("#stmtFileMeta").text(stmtFormatFileSize(file.size));
    $("#stmtDropzone").hide();
    $("#stmtFileInfo").css("display", "flex");
    $("#stmtResultsCard").hide();
    stmtSetFileStatus("scanning", "Preparing...");

    try {
        var result;

        if (isPdf) {
            var pdfjsLib = await stmtLoadPdfJs();
            var buffer = await file.arrayBuffer();

            var lines = await stmtExtractPdfLines(pdfjsLib, buffer, function (pageNumber, totalPages) {
                if (stmtSelectedFile === file) {
                    stmtSetFileStatus("scanning", "Reading page " + pageNumber + " of " + totalPages + "...");
                }
            });

            if (stmtSelectedFile !== file) return; // Another file was picked meanwhile

            result = stmtParseStatement(lines);
        } else {
            stmtSetFileStatus("scanning", "Reading text...");

            var text = await file.text();

            if (stmtSelectedFile !== file) return;

            result = stmtParseText(text);
        }

        stmtRenderResults(result, file);

        if (result.rows.length) {
            stmtSetFileStatus("done", result.rows.length + (result.rows.length === 1 ? " row found" : " rows found"));
        } else {
            stmtSetFileStatus("warning", "Nothing detected");
        }
    } catch (error) {
        console.error(error);

        if (stmtSelectedFile !== file) return;

        var message = (error && error.name === "PasswordException")
            ? "This PDF Is Password Protected. Please Upload An Unlocked Copy."
            : "Failed To Read The File. You Can Still Enter The Rows Manually.";

        stmtSetFileStatus("error", "Scan failed");
        stmtShowError(message);
        stmtRenderResults({ mode: "error", rows: [], notice: message }, file);
    }
}

// ------------------------------------------------------------ //
function stmtLoadPdfJs()
{
    if (window.pdfjsLib) {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = STMT_PDFJS_WORKER_URL;
        return Promise.resolve(window.pdfjsLib);
    }

    if (!stmtPdfJsPromise) {
        stmtPdfJsPromise = new Promise(function (resolve, reject) {
            var script = document.createElement("script");
            script.src = STMT_PDFJS_URL;

            script.onload = function () {
                if (!window.pdfjsLib) {
                    stmtPdfJsPromise = null;
                    reject(new Error("pdf.js did not load"));
                    return;
                }

                window.pdfjsLib.GlobalWorkerOptions.workerSrc = STMT_PDFJS_WORKER_URL;
                resolve(window.pdfjsLib);
            };

            script.onerror = function () {
                stmtPdfJsPromise = null;
                reject(new Error("Failed to load pdf.js"));
            };

            document.head.appendChild(script);
        });
    }

    return stmtPdfJsPromise;
}

// ------------------------------------------------------------ //
async function stmtExtractPdfLines(pdfjsLib, buffer, onProgress)
{
    var pdf = await pdfjsLib.getDocument({ data: new Uint8Array(buffer) }).promise;
    var allLines = [];

    for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
        if (onProgress) onProgress(pageNumber, pdf.numPages);

        var page = await pdf.getPage(pageNumber);
        var content = await page.getTextContent();
        var items = [];

        content.items.forEach(function (item) {
            if (!item.str || !item.str.trim()) return;

            if (stmtIsRuleText(item.str)) return;

            var x = item.transform[4];
            var y = item.transform[5];
            var height = Math.abs(item.transform[3]) || Math.abs(item.height) || 10;

            items.push({ text: item.str, x0: x, x1: x + (item.width || 0), y: y, height: height });
        });

        stmtGroupItemsIntoLines(items).forEach(function (line) {
            line.page = pageNumber;
            allLines.push(line);
        });

        page.cleanup();
    }

    pdf.destroy();

    return allLines;
}

// ------------------------------------------------------------ //
function stmtGroupItemsIntoLines(items)
{
    items.sort(function (a, b) {
        return (b.y - a.y) || (a.x0 - b.x0);
    });

    var lines = [];

    items.forEach(function (item) {
        var tolerance = Math.max(2, item.height * 0.45);
        var line = null;

        for (var i = lines.length - 1; i >= 0 && i >= lines.length - 3; i--) {
            if (Math.abs(lines[i].y - item.y) <= tolerance) {
                line = lines[i];
                break;
            }
        }

        if (!line) {
            line = { y: item.y, items: [] };
            lines.push(line);
        }

        line.items.push(item);
    });

    return lines.map(function (line) {
        line.items.sort(function (a, b) { return a.x0 - b.x0; });

        var cells = [];
        var lineHeight = 0;

        line.items.forEach(function (item) {
            var previous = cells[cells.length - 1];
            var gap = previous ? item.x0 - previous.x1 : Infinity;
            var columnGap = Math.max(3, item.height * 0.9);

            lineHeight = Math.max(lineHeight, item.height);

            if (previous && gap <= columnGap) {
                var needsSpace = gap > item.height * 0.15 && !/\s$/.test(previous.text) && !/^\s/.test(item.text);

                previous.text += (needsSpace ? " " : "") + item.text;
                previous.x1 = Math.max(previous.x1, item.x1);
                previous.parts.push({ text: item.text, x0: item.x0, x1: item.x1, spaceBefore: needsSpace });
            } else {
                cells.push({
                    text: item.text,
                    x0: item.x0,
                    x1: item.x1,
                    parts: [{ text: item.text, x0: item.x0, x1: item.x1, spaceBefore: false }]
                });
            }
        });

        cells = cells
            .map(function (cell) {
                cell.text = stmtCleanText(cell.text);
                return cell;
            })
            .filter(function (cell) {
                return cell.text !== "";
            });

        return {
            y: line.y,
            height: lineHeight || 10,
            cells: cells,
            text: cells.map(function (cell) { return cell.text; }).join("  ")
        };
    }).filter(function (line) {
        return line.cells.length > 0;
    });
}

function stmtParseStatement(lines)
{
    if (!lines.length) {
        return {
            mode: "empty",
            rows: [],
            notice: "No readable text was found in this file. If it is a scanned image, please enter the rows manually."
        };
    }

    var tableRows = stmtParseTable(lines);

    if (tableRows.length) {
        return { mode: "table", rows: tableRows };
    }

    var single = stmtParseKeyValue(lines);

    if (Object.keys(single).length) {
        return {
            mode: "document",
            rows: [stmtBuildRow(single)],
            notice: "No table was detected, so the details were read from the document text. Please review them before saving."
        };
    }

    return {
        mode: "none",
        rows: [],
        notice: "No statement details could be detected in this file. Please enter the rows manually."
    };
}


var STMT_TEXT_CHAR_WIDTH = 6;
var STMT_TEXT_LINE_HEIGHT = 12;
var STMT_COMPANY_SUFFIX_REGEX = /,?\s+(llc|l\.l\.c\.?|inc\.?|incorporated|ltd\.?|limited|corp\.?|corporation)$/i;


function stmtParseText(text)
{
    var normalized = String(text || "")
        .replace(/^\uFEFF/, "")
        .replace(/\r\n?/g, "\n")
        .replace(/\f/g, "\n");

    if ($.trim(normalized) === "") {
        return { mode: "empty", rows: [], notice: "This text file is empty. Please enter the rows manually." };
    }

    var remittanceRows = stmtParseRemittanceText(normalized);

    if (remittanceRows.length) {
        return { mode: "table", rows: remittanceRows };
    }

    return stmtParseStatement(stmtTextToLines(normalized));
}

// ------------------------------------------------------------ //
function stmtParseRemittanceText(text)
{
    var rows = [];
    var currentVendor = "";
    var pendingRow = null;

    text.split("\n").forEach(function (rawLine) {
        var line = stmtCleanText(stmtExpandTabs(rawLine));

        if (line === "") return;

        var vendorMatch = line.match(/^(?:paying agent for|on behalf of)\s+(.+)$/i);

        if (vendorMatch) {
            currentVendor = stmtCleanCompanyName(vendorMatch[1]);
            pendingRow = null;
            return;
        }

        var payment = stmtParseRemittancePaymentLine(line);

        if (payment) {
            pendingRow = stmtBuildRow({ agencyCommission: payment.netAmount, hotelName: currentVendor });
            rows.push(pendingRow);
            return;
        }

        if (pendingRow) {
            var details = stmtParseRemittanceDescription(line);

            if (details) {
                ["reservationNumber", "clientName", "checkoutDate"].forEach(function (field) {
                    if (details[field] && !pendingRow[field]) pendingRow[field] = details[field];
                });
                return;
            }
        }

        pendingRow = null;
    });

    var looksLikeRemittance = rows.some(function (row) { return row.reservationNumber || row.clientName; }) ||
        /\bnet\s+amount\b|\bpaying agent\b|\bremittance\b/i.test(text);

    return looksLikeRemittance ? rows : [];
}

// ------------------------------------------------------------ //
function stmtParseRemittancePaymentLine(line)
{
    var tokens = line.split(" ");

    if (tokens.length < 4 || !/^\d{6,}$/.test(tokens[0])) return null;

    var amounts = [];

    for (var i = tokens.length - 1; i > 0; i--) {
        if (!/^-?[\d,]+\.\d{2}-?$/.test(tokens[i])) break;
        amounts.unshift(tokens[i]);
    }

    if (amounts.length < 2) return null;

    var net = amounts[amounts.length - 1];
    var isNegative = /^-|-$/.test(net);
    var value = stmtParseAmount(net.replace(/-/g, ""));

    if (value === "") return null;

    if (isNegative) value = (-parseFloat(value)).toFixed(2);

    return { netAmount: value };
}

// ------------------------------------------------------------ //
function stmtParseRemittanceDescription(line)
{
    if (STMT_SUMMARY_WORDS_REGEX.test(line)) return null;

    var tokens = line.split(" ");
    var reservationIndex = -1;

    for (var i = 0; i < tokens.length; i++) {
        var token = tokens[i];
        var isAmount = /^-?[\d,]*\.\d{2}-?$/.test(token);

        if (!isAmount && /\d/.test(token) && token.replace(/[^A-Za-z0-9]/g, "").length >= 4) {
            reservationIndex = i;
            break;
        }
    }

    if (reservationIndex === -1 || reservationIndex > 2) return null;

    var reservationNumber = tokens[reservationIndex].replace(/^#/, "").replace(/[,;:]+$/, "");
    var rest = tokens.slice(reservationIndex + 1);
    var checkoutDate = "";

    if (rest.length) {
        var lastToken = rest[rest.length - 1];
        var parsedDate = stmtParseCompactDate(lastToken) || stmtParseDate(lastToken);

        if (parsedDate) {
            checkoutDate = parsedDate;
            rest.pop();
        }
    }

    var clientName = rest.join(" ");

    if (clientName && (!/[A-Za-z]/.test(clientName) || /\d{3,}/.test(clientName))) {
        clientName = "";
    }

    return {
        reservationNumber: reservationNumber,
        clientName: clientName,
        checkoutDate: checkoutDate
    };
}

// ------------------------------------------------------------ //
function stmtParseCompactDate(token)
{
    if (!/^\d{8}$/.test(String(token || ""))) return "";

    var candidates = [
        { month: +token.substr(0, 2), day: +token.substr(2, 2), year: +token.substr(4, 4) },
        { year: +token.substr(0, 4), month: +token.substr(4, 2), day: +token.substr(6, 2) }
    ];

    for (var i = 0; i < candidates.length; i++) {
        var c = candidates[i];

        if (c.year < 1990 || c.year > 2100) continue;

        var date = new Date(c.year, c.month - 1, c.day);

        if (date.getFullYear() === c.year && date.getMonth() === c.month - 1 && date.getDate() === c.day) {
            return stmtPad(c.month) + "/" + stmtPad(c.day) + "/" + c.year;
        }
    }

    return "";
}

// ------------------------------------------------------------ //
function stmtCleanCompanyName(name)
{
    return stmtCleanText(name).replace(STMT_COMPANY_SUFFIX_REGEX, "").trim();
}

// ------------------------------------------------------------ //
function stmtTextToLines(text)
{
    var rawLines = text.split("\n");
    var lines = [];

    rawLines.forEach(function (rawLine, index) {
        var expanded = stmtExpandTabs(rawLine);

        if ($.trim(expanded) === "" || stmtIsRuleText(expanded)) return;

        var cells = [];
        var cellRegex = /\S+(?: \S+)*/g;
        var cellMatch;

        while ((cellMatch = cellRegex.exec(expanded)) !== null) {
            var start = cellMatch.index;
            var cellText = stmtCleanText(cellMatch[0]);

            if (cellText === "") continue;

            var parts = [];
            var wordRegex = /\S+/g;
            var wordMatch;

            while ((wordMatch = wordRegex.exec(cellMatch[0])) !== null) {
                var wordStart = start + wordMatch.index;

                parts.push({
                    text: wordMatch[0],
                    x0: wordStart * STMT_TEXT_CHAR_WIDTH,
                    x1: (wordStart + wordMatch[0].length) * STMT_TEXT_CHAR_WIDTH,
                    spaceBefore: parts.length > 0
                });
            }

            cells.push({
                text: cellText,
                x0: start * STMT_TEXT_CHAR_WIDTH,
                x1: (start + cellMatch[0].length) * STMT_TEXT_CHAR_WIDTH,
                parts: parts
            });
        }

        if (!cells.length) return;

        lines.push({
            y: (rawLines.length - index) * STMT_TEXT_LINE_HEIGHT,
            height: 10,
            page: 1,
            cells: cells,
            text: cells.map(function (cell) { return cell.text; }).join("  ")
        });
    });

    return lines;
}

// ------------------------------------------------------------ //
function stmtExpandTabs(line)
{
    var result = "";

    String(line || "").split("").forEach(function (character) {
        if (character === "\t") {
            var spaces = 8 - (result.length % 8);
            result += new Array(spaces + 1).join(" ");
        } else {
            result += character;
        }
    });

    return result;
}

// ------------------------------------------------------------ //
function stmtParseTable(lines)
{
    var rows = [];
    var columns = null;
    var firstHeaderIndex = -1;
    var segment = [];

    function flushSegment() {
        if (columns && segment.length) {
            rows = rows.concat(stmtParseTableSegment(segment, columns));
        }
        segment = [];
    }

    lines.forEach(function (line, index) {
        var headerColumns = stmtDetectHeader(line);

        if (headerColumns) {
            flushSegment();
            columns = headerColumns;
            if (firstHeaderIndex === -1) firstHeaderIndex = index;
            return;
        }

        if (!columns) return;

        if (STMT_SKIP_LINE_REGEX.test(line.cells[0].text)) {
            flushSegment();
            return;
        }

        if (segment.length && segment[segment.length - 1].page !== line.page) {
            flushSegment();
        }

        segment.push(line);
    });

    flushSegment();

    rows = stmtRemoveTotalRows(rows);

    if (rows.length && firstHeaderIndex > 0) {
        var preamble = stmtParseKeyValue(lines.slice(0, firstHeaderIndex));

        ["hotelName", "groupNumber"].forEach(function (field) {
            var noRowHasField = rows.every(function (row) { return row[field] === ""; });

            if (preamble[field] && noRowHasField) {
                rows.forEach(function (row) { row[field] = preamble[field]; });
            }
        });
    }

    return rows;
}

// ------------------------------------------------------------ //
function stmtRemoveTotalRows(rows)
{
    if (rows.length < 3) return rows;

    var amounts = rows.map(function (row) {
        return row.agencyCommission !== "" ? parseFloat(row.agencyCommission) : null;
    });

    var sum = amounts.reduce(function (total, amount) {
        return total + (amount !== null ? amount : 0);
    }, 0);

    return rows.filter(function (row, index) {
        var amount = amounts[index];

        if (amount === null || amount === 0) return true;

        var sumOfOthers = sum - amount;

        return Math.abs(sumOfOthers - amount) >= 0.01;
    });
}

// ------------------------------------------------------------ //
function stmtParseTableSegment(lines, headerColumns)
{
    var splitLines = lines.map(function (line) {
        return {
            y: line.y,
            height: line.height,
            page: line.page,
            cells: stmtSplitLineCells(line, headerColumns)
        };
    });

    var columns = stmtLearnColumnExtents(splitLines, headerColumns);

    var placed = splitLines.map(function (line) {
        var cells = [];

        line.cells.forEach(function (cell) {
            var column = stmtFindColumnByExtent(cell, columns);
            if (column && column.field) cells.push({ field: column.field, text: cell.text });
        });

        return { y: line.y, height: line.height || 10, cells: cells, row: null };
    });

    var anchorOrder = ["reservationNumber", "agencyCommission", "checkoutDate", "clientName"];
    var anchorField = null;

    function lineHasAnchor(line, field) {
        return line.cells.some(function (cell) {
            return cell.field === field && stmtNormalizeFieldValue(field, cell.text) !== "";
        });
    }

    for (var i = 0; i < anchorOrder.length && !anchorField; i++) {
        var candidate = anchorOrder[i];
        if (placed.some(function (line) { return lineHasAnchor(line, candidate); })) {
            anchorField = candidate;
        }
    }

    if (!anchorField) return [];

    var tableRows = [];

    placed.forEach(function (line) {
        if (!lineHasAnchor(line, anchorField)) return;

        line.row = { y: line.y, parts: {} };
        tableRows.push(line.row);

        line.cells.forEach(function (cell) {
            stmtAddRowPart(line.row, cell.field, line.y, cell.text);
        });
    });

    placed.forEach(function (line) {
        if (line.row) return;

        line.cells.forEach(function (cell) {
            var target = stmtNearestTableRow(tableRows, line, cell.field);
            if (target) stmtAddRowPart(target, cell.field, line.y, cell.text);
        });
    });

    var rows = [];

    tableRows.forEach(function (tableRow) {
        var row = {};
        var rawText = {};

        Object.keys(tableRow.parts).forEach(function (field) {
            var parts = tableRow.parts[field].sort(function (a, b) { return b.y - a.y; });

            if (field === "reservationNumber" && anchorField === "reservationNumber") {
                parts = parts.filter(function (part) { return part.y === tableRow.y; });
            }

            var joiner = (field === "reservationNumber" || field === "groupNumber") ? "" : " ";
            var text = parts.map(function (part) { return part.text; }).join(joiner);
            var value = stmtNormalizeFieldValue(field, text);

            rawText[field] = parts.map(function (part) { return part.text; }).join(" ");

            if (value !== "") row[field] = value;
        });

        var identityText = (rawText.reservationNumber || "") + " " + (rawText.clientName || "");
        if (STMT_SUMMARY_WORDS_REGEX.test(identityText)) return;

        var hasReservation = !!row.reservationNumber;
        var hasClientWithDetails = !!row.clientName && (!!row.agencyCommission || !!row.checkoutDate);

        if (hasReservation || hasClientWithDetails) {
            rows.push(stmtBuildRow(row));
        }
    });

    return rows;
}

// ------------------------------------------------------------ //
function stmtSplitLineCells(line, headerColumns)
{
    var cells = [];

    line.cells.forEach(function (cell) {
        if (!cell.parts || cell.parts.length < 2) {
            cells.push({ text: cell.text, x0: cell.x0, x1: cell.x1 });
            return;
        }

        var current = null;

        cell.parts.forEach(function (part) {
            var headerColumn = stmtHeaderColumnForPart(part, headerColumns);
            var startsNewCell = !current || (headerColumn && current.headerColumn && headerColumn !== current.headerColumn);

            if (startsNewCell) {
                current = { text: part.text, x0: part.x0, x1: part.x1, headerColumn: headerColumn };
                cells.push(current);
            } else {
                current.text += (part.spaceBefore ? " " : "") + part.text;
                current.x1 = Math.max(current.x1, part.x1);

                if (!current.headerColumn) current.headerColumn = headerColumn;
            }
        });
    });

    return cells
        .map(function (cell) {
            return { text: stmtCleanText(cell.text), x0: cell.x0, x1: cell.x1 };
        })
        .filter(function (cell) {
            return cell.text !== "";
        });
}

// ------------------------------------------------------------ //
function stmtHeaderColumnForPart(part, headerColumns)
{
    var best = null;
    var bestOverlap = 0;

    headerColumns.forEach(function (column) {
        var overlap = Math.min(part.x1, column.x1) - Math.max(part.x0, column.x0);

        if (overlap > bestOverlap) {
            bestOverlap = overlap;
            best = column;
        }
    });

    return best;
}

// ------------------------------------------------------------ //
function stmtAddRowPart(tableRow, field, y, text)
{
    if (!tableRow.parts[field]) tableRow.parts[field] = [];

    tableRow.parts[field].push({ y: y, text: text });
}

// ------------------------------------------------------------ //
function stmtNearestTableRow(tableRows, line, field)
{
    var maxDistance = Math.max(line.height, 8) * 2.5;
    var best = null;
    var bestDistance = Infinity;

    tableRows.forEach(function (tableRow) {
        var distance = Math.abs(tableRow.y - line.y);

        if (distance > maxDistance) return;

        if (distance < bestDistance - 1) {
            best = tableRow;
            bestDistance = distance;
            return;
        }

        if (best && Math.abs(distance - bestDistance) <= 1 && best.parts[field] && !tableRow.parts[field]) {
            best = tableRow;
            bestDistance = distance;
        }
    });

    return best;
}

// ------------------------------------------------------------ //
function stmtLearnColumnExtents(lines, headerColumns)
{
    var columns = headerColumns.map(function (column) {
        return {
            field: column.field,
            x0: column.x0,
            x1: column.x1,
            left: column.left,
            right: column.right,
            dataX0: column.x0,
            dataX1: column.x1
        };
    });

    lines.forEach(function (line) {
        line.cells.forEach(function (cell) {
            var hits = columns.filter(function (column) {
                return Math.min(cell.x1, column.x1) - Math.max(cell.x0, column.x0) > 0;
            });

            if (hits.length === 1) {
                hits[0].dataX0 = Math.min(hits[0].dataX0, cell.x0);
                hits[0].dataX1 = Math.max(hits[0].dataX1, cell.x1);
            }
        });
    });

    return columns;
}

// ------------------------------------------------------------ //
function stmtFindColumnByExtent(cell, columns)
{
    var best = null;
    var bestOverlap = 0;

    columns.forEach(function (column) {
        var overlap = Math.min(cell.x1, column.dataX1) - Math.max(cell.x0, column.dataX0);

        if (overlap > bestOverlap) {
            bestOverlap = overlap;
            best = column;
        }
    });

    return best || stmtFindColumn(cell, columns);
}

// ------------------------------------------------------------ //
function stmtDetectHeader(line)
{
    if (line.cells.length < 2) return null;

    var labelCells = 0;
    var valueLikeCells = 0;

    var columns = line.cells.map(function (cell) {
        if (/:$/.test(cell.text)) labelCells++;
        if (/\d{4,}|\$\s?\d/.test(cell.text)) valueLikeCells++;

        return { field: stmtMatchHeaderField(cell.text), x0: cell.x0, x1: cell.x1 };
    });

    if (labelCells > 0 || valueLikeCells > 1) return null;

    var seen = {};
    var distinctFields = 0;

    columns.forEach(function (column) {
        if (!column.field) return;

        if (seen[column.field]) {
            column.field = null;
        } else {
            seen[column.field] = true;
            distinctFields++;
        }
    });

    if (distinctFields < 2) return null;

    if (!seen.reservationNumber && !seen.clientName) return null;

    columns.sort(function (a, b) { return a.x0 - b.x0; });

    columns.forEach(function (column, i) {
        var previous = columns[i - 1];
        var next = columns[i + 1];

        column.left = previous ? (previous.x1 + column.x0) / 2 : -Infinity;
        column.right = next ? (column.x1 + next.x0) / 2 : Infinity;
    });

    return columns;
}

// ------------------------------------------------------------ //
function stmtFindColumn(cell, columns)
{
    var center = (cell.x0 + cell.x1) / 2;

    for (var i = 0; i < columns.length; i++) {
        if (center >= columns[i].left && center < columns[i].right) return columns[i];
    }

    return null;
}

// ------------------------------------------------------------ //
function stmtMatchHeaderField(text)
{
    var label = stmtNormalizeLabel(text);

    if (!label || label.length > 40) return null;

    var fields = Object.keys(STMT_HEADER_PATTERNS);

    for (var i = 0; i < fields.length; i++) {
        var patterns = STMT_HEADER_PATTERNS[fields[i]];

        for (var j = 0; j < patterns.length; j++) {
            if (patterns[j].test(label)) return fields[i];
        }
    }

    return null;
}

// ------------------------------------------------------------ //
function stmtNormalizeLabel(text)
{
    return String(text || "")
        .toLowerCase()
        .replace(/[.:*,]/g, " ")
        .replace(/\s+/g, " ")
        .trim();
}

// ------------------------------------------------------------ //
function stmtParseKeyValue(lines)
{
    var found = {};

    lines.forEach(function (line, lineIndex) {
        line.cells.forEach(function (cell, cellIndex) {
            var field = null;
            var value = "";

            var colonMatch = cell.text.match(/^([^:]{2,40}):\s*(.+)$/);

            if (colonMatch) {
                field = stmtMatchHeaderField(colonMatch[1]);
                value = colonMatch[2];
            }

            if (!field) {
                field = stmtMatchHeaderField(cell.text);

                if (field) {
                    var nextLine = lines[lineIndex + 1];
                    var nextCell = line.cells[cellIndex + 1] || (nextLine ? nextLine.cells[0] : null);

                    value = (nextCell && !stmtMatchHeaderField(nextCell.text)) ? nextCell.text : "";
                }
            }

            if (!field || found[field]) return;

            var normalized = stmtNormalizeFieldValue(field, value);
            if (normalized !== "") found[field] = normalized;
        });
    });

    var fullText = lines.map(function (line) { return line.text; }).join("\n");

    var fallbacks = {
        reservationNumber: /\b(?:confirmation|reservation|booking)\s*(?:number|no\.?|#|code)?\s*(?:is|:)?\s*#?\s*([A-Z0-9][A-Z0-9\-]{3,})/i,
        agencyCommission: /\bcommission[^$\d%\n]{0,30}(?:\$|usd|us\$)\s?(-?[\d,]+\.\d{2})/i,
        checkoutDate: /\b(?:check[\s-]?out|departure|depart)\b[^\n\d]{0,20}((?:[A-Za-z]{3,9}\.?\s+\d{1,2},?\s+\d{4})|(?:\d{1,2}[\/\-.]\d{1,2}[\/\-.]\d{2,4})|(?:\d{4}-\d{1,2}-\d{1,2})|(?:\d{1,2}[\s\-][A-Za-z]{3,9}[\s\-,]+\d{2,4}))/i
    };

    Object.keys(fallbacks).forEach(function (field) {
        if (found[field]) return;

        var match = fullText.match(fallbacks[field]);
        if (!match) return;

        var normalized = stmtNormalizeFieldValue(field, match[1]);
        if (normalized !== "") found[field] = normalized;
    });

    return found;
}

// ------------------------------------------------------------ //
function stmtNormalizeFieldValue(field, value)
{
    var text = stmtCleanText(value);

    if (text === "") return "";

    switch (field) {
        case "reservationNumber": {
            text = text.replace(/^(#|no\.?)\s*/i, "");

            var token = text.split(" ").filter(function (part) { return /\d/.test(part); })[0] || "";
            token = token.replace(/[,;]+$/, "");

            if (!token || token.length > 40) return "";
            if (/^\$/.test(token) || /^\d{1,2}[\/\-.]\d{1,2}[\/\-.]\d{2,4}$/.test(token)) return "";

            return token;
        }

        case "groupNumber":
            text = text.replace(/^(#|no\.?)\s*/i, "");
            return text.length <= 60 ? text : "";

        case "clientName":
            if (!/[A-Za-z]/.test(text) || /\d{3,}/.test(text)) return "";
            return text.substring(0, 120);

        case "hotelName":
            if (!/[A-Za-z]/.test(text)) return "";
            return text.substring(0, 150);

        case "agencyCommission":
            return stmtParseAmount(text);

        case "checkoutDate":
            return stmtParseDate(text);
    }

    return "";
}

// ------------------------------------------------------------ //
function stmtParseAmount(value)
{
    var cleaned = String(value || "").replace(/\s+/g, "");

    if (cleaned === "") return "";
    if (/%/.test(cleaned) && !/\$/.test(cleaned)) return "";

    var match = cleaned.match(/(\()?(-)?\$?(-)?(\d[\d,]*(?:\.\d+)?)(\))?/);
    if (!match) return "";

    var number = parseFloat(match[4].replace(/,/g, ""));
    if (isNaN(number)) return "";

    if ((match[1] && match[5]) || match[2] || match[3]) number = -number;

    return number.toFixed(2);
}

// ------------------------------------------------------------ //
function stmtParseDate(value)
{
    var text = String(value || "");
    var candidates = [];

    function monthFromName(name) {
        return STMT_MONTHS[String(name).substring(0, 3).toLowerCase()] || 0;
    }

    var patterns = [
        { regex: /\b(\d{4})[\/\-.](\d{1,2})[\/\-.](\d{1,2})\b/g, map: function (m) { return [m[1], m[2], m[3]]; } },
        { regex: /\b(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{2,4})\b/g, map: function (m) {
            var first = parseInt(m[1], 10);
            var second = parseInt(m[2], 10);
            return first > 12 ? [m[3], second, first] : [m[3], first, second];
        } },
        { regex: /\b(\d{1,2})[\s\-]([A-Za-z]{3,9})\.?[\s\-,]+(\d{2,4})\b/g, map: function (m) { return [m[3], monthFromName(m[2]), m[1]]; } },
        { regex: /\b([A-Za-z]{3,9})\.?\s+(\d{1,2})(?:st|nd|rd|th)?,?\s+(\d{2,4})\b/g, map: function (m) { return [m[3], monthFromName(m[1]), m[2]]; } }
    ];

    patterns.forEach(function (pattern) {
        var match;
        pattern.regex.lastIndex = 0;

        while ((match = pattern.regex.exec(text)) !== null) {
            var parts = pattern.map(match);
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);
            var day = parseInt(parts[2], 10);

            if (year < 100) year += 2000;

            var date = new Date(year, month - 1, day);
            var isValid = month >= 1 && month <= 12 && day >= 1 &&
                date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;

            if (isValid) candidates.push({ index: match.index, year: year, month: month, day: day });
        }
    });

    if (!candidates.length) return "";

    candidates.sort(function (a, b) { return a.index - b.index; });
    var last = candidates[candidates.length - 1];

    return stmtPad(last.month) + "/" + stmtPad(last.day) + "/" + last.year;
}

function stmtRenderResults(result, file)
{
    var badges = {
        table: result.rows.length + (result.rows.length === 1 ? " Row Detected" : " Rows Detected"),
        document: "Single Document",
        empty: "Manual Entry",
        none: "Manual Entry",
        error: "Manual Entry"
    };

    $("#stmtEditTableBody").empty();

    var rows = result.rows.length ? result.rows : [stmtBuildRow({})];
    rows.forEach(function (row) { stmtAppendRow(row); });

    $("#stmtStatementName").val(file.name.replace(/\.(pdf|txt)$/i, ""));
    $("#stmtStatementNameErrorMsg").text("");
    $("#stmtScanBadge").text(badges[result.mode] || "");

    if (result.notice) {
        $("#stmtScanNoticeText").text(result.notice);
        $("#stmtScanNotice").css("display", "flex");
    } else {
        $("#stmtScanNotice").hide();
    }

    stmtRefreshRowNumbers();
    stmtUpdateTotal();

    $("#stmtResultsCard").fadeIn(150);
}

// ------------------------------------------------------------ //
function stmtAppendRow(row)
{
    row = row || {};

    var $tr = $("<tr>", { "class": "stmtEditRow" });
    $tr.append($("<td>", { "class": "stmtRowNumber" }));

    STMT_FIELDS.forEach(function (field) {
        var $input = $("<input>", {
            "type": "text",
            "class": "stmtCellInput",
            "data-field": field,
            "autocomplete": "off"
        }).val(row[field] || "");

        if (field === "agencyCommission") {
            $input.addClass("stmtAmountInput").attr({ "inputmode": "decimal", "placeholder": "0.00" });
        } else if (field === "checkoutDate") {
            $input.addClass("stmtDateInput").attr("placeholder", "mm/dd/yyyy");
        }

        $tr.append($("<td>").append($input));
    });

    $tr.append(
        $("<td>", { "class": "stmtRowActions" }).append(
            $("<button>", {
                "type": "button",
                "class": "stmtIconBtn stmtRemoveRowBtn",
                "title": "Remove Row",
                "html": "<i class='far fa-trash-alt'></i>"
            })
        )
    );

    $("#stmtEditTableBody").append($tr);

    if ($.fn.datepicker) {
        $tr.find(".stmtDateInput").datepicker({
            "format": "mm/dd/yyyy",
            "autoclose": true,
            "forceParse": false,
            "clearBtn": true,
            "todayHighlight": true
        });
    }

    return $tr;
}

// ------------------------------------------------------------ //
function stmtRefreshRowNumbers()
{
    $("#stmtEditTableBody tr").each(function (index) {
        $(this).find(".stmtRowNumber").text(index + 1);
    });
}

// ------------------------------------------------------------ //
function stmtUpdateTotal()
{
    var total = 0;

    $("#stmtEditTableBody .stmtAmountInput").each(function () {
        var amount = stmtParseAmount($(this).val());
        if (amount !== "") total += parseFloat(amount);
    });

    $("#stmtTotalCommission").text(stmtFormatMoney(total));
}

// ------------------------------------------------------------ //
function stmtCollectRows()
{
    var rows = [];
    var hasInvalid = false;

    $("#stmtEditTableBody tr").each(function () {
        var row = {};
        var isEmpty = true;

        $(this).find(".stmtCellInput").each(function () {
            var $input = $(this);
            var field = $input.data("field");
            var value = $.trim($input.val());

            if (value !== "" && field === "agencyCommission" && stmtParseAmount(value) === "") {
                $input.addClass("stmtInvalid");
                hasInvalid = true;
            }

            if (value !== "" && field === "checkoutDate" && stmtParseDate(value) === "") {
                $input.addClass("stmtInvalid");
                hasInvalid = true;
            }

            if (value !== "") isEmpty = false;
            row[field] = value;
        });

        if (!isEmpty) rows.push(row);
    });

    return { rows: rows, hasInvalid: hasInvalid };
}

function stmtSaveStatement()
{
    if (!stmtSelectedFile) {
        stmtShowError("Please Select A File First.");
        return;
    }

    var statementName = $.trim($("#stmtStatementName").val());

    if (statementName === "") {
        $("#stmtStatementNameErrorMsg").text("Statement name is required.");
        $("#stmtStatementName").trigger("focus");
        return;
    }

    var collected = stmtCollectRows();

    if (collected.hasInvalid) {
        stmtShowError("Please Fix The Highlighted Cells (Amounts Like 125.00, Dates Like mm/dd/yyyy).");
        return;
    }

    if (!collected.rows.length) {
        stmtShowError("Please Add At Least One Row Before Saving.");
        return;
    }

    var formData = new FormData();
    formData.append("_token", stmtCsrfToken());
    formData.append("statementFile", stmtSelectedFile);
    formData.append("statementName", statementName);
    formData.append("statementRows", JSON.stringify(collected.rows));

    var $saveBtn = $("#stmtSaveBtn");
    var originalHtml = $saveBtn.html();

    $saveBtn.prop("disabled", true).html("<i class='fas fa-spinner fa-spin'></i><span>Saving...</span>");

    $.ajax({
        url: stmtRoute("save"),
        method: "POST",
        headers: { "X-CSRF-TOKEN": stmtCsrfToken() },
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            if (response && response.status == 1) {
                stmtResetUpload();
                stmtReloadSavedList();
                stmtFlash(response.message);
            } else {
                stmtShowError((response && response.message) || "Failed To Save Statement, Please Try Again.");
            }
        },
        error: function () {
            stmtShowError("Failed To Save Statement, Please Try Again.");
        },
        complete: function () {
            $saveBtn.prop("disabled", false).html(originalHtml);
        }
    });
}

// ------------------------------------------------------------ //
function stmtResetUpload()
{
    stmtSelectedFile = null;

    $("#stmtEditTableBody .stmtDateInput").each(function () {
        if ($.fn.datepicker) $(this).datepicker("destroy");
    });

    $("#stmtEditTableBody").empty();
    $("#stmtResultsCard").hide();
    $("#stmtFileInfo").hide();
    $("#stmtFileStatus").empty();
    $("#stmtDropzone").show();
    $("#stmtFileInput").val("");
}

// ------------------------------------------------------------ //
function stmtReloadSavedList()
{
    $.ajax({
        url: stmtRoute("list"),
        method: "POST",
        headers: { "X-CSRF-TOKEN": stmtCsrfToken() },
        success: function (data) {
            $(".tooltip").remove();
            $("#stmtSavedListContainer").html(data);
            stmtInitTooltips("#stmtSavedListContainer");
        },
        error: function () {
            stmtShowError("Failed To Reload Saved Statements, Please Refresh The Page.");
        }
    });
}

function stmtOpenItemsModal(statementId)
{
    $.ajax({
        url: stmtRoute("items"),
        method: "POST",
        headers: { "X-CSRF-TOKEN": stmtCsrfToken() },
        data: { statementId: statementId },
        dataType: "json",
        success: function (response) {
            if (!response || response.status != 1) {
                stmtShowError((response && response.message) || "Failed To Load Statement, Please Try Again.");
                return;
            }

            var statement = response.statement;
            var items = response.items || [];
            var createdDate = statement.createdAt ? statement.createdAt.split(" ")[0].split("T")[0] : "";

            $("#stmtItemsModalTitle").text(statement.name);
            $("#stmtItemsModalFileLink").attr("href", statement.fileUrl).text(statement.originalFileName);
            $("#stmtItemsModalUploaded").text(createdDate + (statement.uploadedBy ? " by " + statement.uploadedBy : ""));
            $("#stmtItemsModalCount").text(items.length);
            $("#stmtItemsModalTotal").text("$" + statement.totalCommission);

            var $body = $("#stmtItemsModalTableBody").empty();

            if (!items.length) {
                $body.append($("<tr>").append($("<td>", { colspan: 7, "class": "text-center stmtMutedText", text: "No rows in this statement." })));
            }

            items.forEach(function (item) {
                var isLinked = item.reservationId !== null;

                $body.append(
                    $("<tr>")
                        .append($("<td>").text(item.reservationNumber))
                        .append($("<td>").text(item.clientName))
                        .append($("<td>").text(item.groupNumber))
                        .append($("<td>").text(item.agencyCommission !== "" ? "$" + item.agencyCommission : ""))
                        .append($("<td>").text(item.checkoutDate))
                        .append($("<td>").text(item.hotelName))
                        .append($("<td>").append(
                            $("<span>", {
                                "class": "stmtLinkedPill" + (isLinked ? " stmtLinked" : ""),
                                "text": isLinked ? "Linked" : "Not Linked"
                            })
                        ))
                );
            });

            stmtOpenModal("#stmtItemsModal");
        },
        error: function () {
            stmtShowError("Failed To Load Statement, Please Try Again.");
        }
    });
}

// ------------------------------------------------------------ //
function stmtDeleteStatement()
{
    if (stmtDeleteStatementId <= 0) return;

    var $btn = $("#stmtConfirmDeleteBtn").prop("disabled", true);

    $.ajax({
        url: stmtRoute("delete"),
        method: "POST",
        headers: { "X-CSRF-TOKEN": stmtCsrfToken() },
        data: { statementId: stmtDeleteStatementId },
        dataType: "json",
        success: function (response) {
            if (response && response.status == 1) {
                stmtCloseModal("#stmtDeleteModal");
                stmtReloadSavedList();
                stmtFlash(response.message);
                stmtDeleteStatementId = -1;
            } else {
                stmtShowError((response && response.message) || "Failed To Delete Statement, Please Try Again.");
            }
        },
        error: function () {
            stmtShowError("Failed To Delete Statement, Please Try Again.");
        },
        complete: function () {
            $btn.prop("disabled", false);
        }
    });
}

function stmtBuildRow(values)
{
    var row = {};

    STMT_FIELDS.forEach(function (field) {
        row[field] = values && values[field] ? values[field] : "";
    });

    return row;
}

function stmtSetFileStatus(state, text)
{
    var icons = {
        scanning: "fas fa-spinner fa-spin",
        done: "fas fa-check-circle",
        warning: "fas fa-exclamation-triangle",
        error: "fas fa-times-circle"
    };

    $("#stmtFileStatus")
        .attr("class", "stmtFileStatus stmtStatus-" + state)
        .empty()
        .append($("<i>", { "class": icons[state] || "" }))
        .append($("<span>").text(text));
}

function stmtFlash(message)
{
    var $flash = $("#stmtFlashMessage");

    $flash.stop(true, true).empty()
        .append($("<i>", { "class": "fas fa-check-circle" }))
        .append($("<span>").text(message))
        .fadeIn(150);

    setTimeout(function () { $flash.fadeOut(300); }, 3500);
}

function stmtCleanText(value)
{
    return String(value || "")
        .replace(/_{3,}|-{3,}|={3,}|[─━═—–]{2,}|\.{4,}/g, " ")
        .replace(/\s+/g, " ")
        .trim();
}

function stmtIsRuleText(text)
{
    var stripped = String(text || "").replace(/\s+/g, "");

    if (stripped.length < 2) return false;

    return /^[_\-–—=─━═~·*|]+$/.test(stripped) || /^\.{3,}$/.test(stripped);
}

function stmtFormatMoney(amount)
{
    var sign = amount < 0 ? "-" : "";

    return sign + "$" + Math.abs(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function stmtFormatFileSize(bytes)
{
    if (bytes < 1024) return bytes + " B";
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB";

    return (bytes / (1024 * 1024)).toFixed(1) + " MB";
}

function stmtPad(number)
{
    return (number < 10 ? "0" : "") + number;
}

// ============================================================ //
// End of Statements
// ============================================================ //