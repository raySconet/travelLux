$(document).ready(() => {
    $('#openDrawer').on('click', function () {
        $('#sidebarDrawer').removeClass('-translate-x-full');

        $('#categoryViewSection')
            .removeClass('grid-cols-1 xl:grid-cols-[100px_1fr]')
            .addClass('grid-cols-12');

        $('#categorySidebar')
            .removeClass('col-span-1')
            .addClass('col-span-12 xl:col-span-3');

        $('#categoryLayout')
            .addClass('col-span-12 xl:col-span-9');

        $('#categorySidebar > div > div').addClass('min-h-[900px]');
    });

    $('#closeDrawer').on('click', function () {
        $('#sidebarDrawer').addClass('-translate-x-full');

        setTimeout(function () {
            $('#categoryViewSection')
                .removeClass('grid-cols-12')
                .addClass('grid-cols-1 xl:grid-cols-[100px_1fr]');

            $('#categorySidebar')
                .removeClass('col-span-12 xl:col-span-3');

            $('#categoryLayout')
                .removeClass('col-span-12 xl:col-span-9');

            $('#categorySidebar > div > div').removeClass('min-h-[900px]');
        }, 100);
    });

    $(document).on('click', '.flip', function () {
        const panelSelector = $(this).data("target");
        const panel = $(panelSelector);
        const isOpen = panel.is(":visible");

        if(isOpen) {
            $(this).addClass('rounded').removeClass('rounded-t');
        } else {
            $(this).removeClass('rounded').addClass('rounded-t');
        }

        // Toggle icons
        $(this).children(".fa-chevron-right").toggle(isOpen);
        $(this).children(".fa-chevron-down").toggle(!isOpen);

        // Toggle panel
        panel.slideToggle();
    });

    $(document).on('change', 'input[type="checkbox"][data-user-id]', function () {
        const allCheckboxes = $('input[type="checkbox"][data-user-id]');

        // Uncheck all others
        allCheckboxes.not(this).prop('checked', false);

        // Check the clicked one
        $(this).prop('checked', true);

        console.log('Selected User ID:', $(this).data('user-id'));
    });

    $('#openAddCategoryModal').on('click', function() {
        $('#addCategoryModal').removeClass('hidden');
    });

    // Close modal
    $('#closeAddCategoryModal').on('click', function() {
        $('#addCategoryModal').addClass('hidden');
    });

    $('#closeErrorModal').on('click', function() {
        $('#errorModal').addClass('hidden');
    });

    $('#closeSuccessModal').on('click', function() {
        $('#successModal').addClass('hidden');
    });

    // Optional: Close when clicking outside modal content
    $('#addCategoryModal, #errorModal, #successModal').on('click', function(e) {
        if ($(e.target).is(this)) {
            $(this).addClass('hidden');
        }
    });

    // Initialize Spectrum on the square (div)
    $('#colorBox').css('background-color', '#14548d');
    $('#colorBox').spectrum({
        color: "#14548d",
        showPalette: true,
        showInput: true,
        allowEmpty: false,
        preferredFormat: "hex",
        showInitial: true,
        palette: [
            ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'],
            ['#000000', '#FFFFFF', '#808080', '#C0C0C0', '#800000', '#808000'],
            ['#3490dc', '#f97316', '#10b981', '#8b5cf6', '#f43f5e', '#eab308'],
            ['#1abc9c', '#2ecc71', '#9b59b6', '#34495e', '#16a085', '#27ae60'],
            ['#e67e22', '#e74c3c', '#f39c12', '#d35400', '#c0392b', '#f1c40f'],
            ['#2980b9', '#3498db', '#5dade2', '#85c1e9', '#aed6f1', '#d6eaf8'],
            ['#a0522d', '#cd853f', '#deb887', '#f5deb3', '#ffe4b5', '#faf0e6'],
            ['#ffb6c1', '#ffcccb', '#ffe4e1', '#e0ffff', '#d8bfd8', '#e6e6fa'],
            ['#39ff14', '#ccff00', '#ff073a', '#fe019a', '#ff6ec7', '#dfff00'],
            ['#6c757d', '#adb5bd', '#ced4da', '#dee2e6', '#e9ecef', '#f8f9fa'],
            ['#2e8b57', '#3cb371', '#6b8e23', '#556b2f', '#228b22', '#66cdaa'],
            ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997']
        ],

        // Make it act like an input
        change: function(color) {
        const hex = color.toHexString();
        $('#colorBox').css('background-color', hex);     // Update square visually
        $('#colorInput').val(hex);                       // Store value (optional)
        console.log("Selected color:", hex);
        },
        move: function(color) {
        // Live update while dragging
        $('#colorBox').css('background-color', color.toHexString());
        },
        showButtons: false,
        show: function () {
            $('.sp-container').addClass('z-50'); // Optional: bring to front if overlapping UI
        }
    });

    // Open picker on click
    $('#colorBox').on('click', function () {
        $(this).spectrum('show');
    });

    // Optional: hide picker when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#colorBox, .sp-container').length) {
        $('#colorBox').spectrum('hide');
        }
    });

    // add category steps

    // step 1
    $('#submitCategoryBtn').on('click', function () {
        $('#addCategoryForm').submit();
    });

    // step 2
    $('#addCategoryForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $button = $('#submitCategoryBtn');

        // Clear previous inline errors & modal errors
        $('#modalErrorContent').empty();
        $('#errorModal').addClass('hidden');

        $button.prop('disabled', true).text('Saving...');
        // console.log($form.serialize());
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $form[0].reset();
                $('#colorBox').css('background-color', '#14548d;');
                $('#addCategoryModal').addClass('hidden');
                $('#modalSuccessContent').html(response.message);
                $('#successModal').removeClass('hidden');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};

                    if (errors.name) {
                        let errorHtml = '<ul class="text-sm text-red-600 space-y-1">';
                        errors.name.forEach(function (error) {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul>';
                        $('#errorCategoryName').html(errorHtml);
                    }
                } else {
                    $('#modalErrorContent').html('An unexpected error occurred. Please try again.');
                    $('#errorModal').removeClass('hidden');
                }
            },
            complete: function (response) {
                $button.prop('disabled', false).text('Add Category');
            }
        });
    });

    const data = getEventsCases();
    renderEventCases(data);
});

function getEventsCases() {
    const categories = [
        {
            id: "1",
            panelId: "panel1",
            label: "Discharged",
            colorClass: "#aa2c41",
            items: [
                {
                    tag: "DISCHARGED",
                    description: "NA - DISCHARGED - MICHAEL THOMAS + JANE THOMAS (11 yrs)",
                    from: "Mon 8/18/2025 08:00 AM",
                    to: "Mon 8/18/2025 10:00 AM"
                },
                {
                    tag: "DISCHARGED",
                    description: "HRM - DISCHARGED - CLARA LOPEZ *VOLUNTARY RELEASE*",
                    from: "Thu 8/21/2025 01:30 PM",
                    to: "Thu 8/21/2025 02:00 PM"
                }
            ]
        },
        {
            id: "2",
            panelId: "panel2",
            label: "FS",
            colorClass: "#22c55e",  // green-500
            items: [
                {
                    tag: "FS",
                    description: "HRM - FS - KIMBERLY JOHNSON + jeremiah johnson (9 yrs) *COMMERCIAL*",
                    from: "Thu 8/7/2025 12:00 AM",
                    to: "Fri 8/8/2025 12:00 AM"
                },
                {
                    tag: "FS",
                    description: "HRM - LIT - BRANDYE BEST *COMMERCIAL*",
                    from: "Fri 8/1/2025 12:00 AM",
                    to: "Sat 8/2/2025 12:00 AM"
                },
                {
                    tag: "FS",
                    description: "NA - FS - CESAR BORUNDA cesar + cesar alexander borunda (12 yrs) $50K",
                    from: "Mon 7/28/2025 12:00 AM",
                    to: "Tue 7/29/2025 12:00 AM"
                },
                {
                    tag: "FS",
                    description: "SB*** - FS - JOSE SOLORZANO $100K",
                    from: "Thu 7/24/2025 12:00 AM",
                    to: "Fri 7/25/2025 12:00 AM"
                },
                {
                    tag: "FS",
                    description: "SB*** - FS - ALMA GOMEZ $60K",
                    from: "Thu 7/24/2025 12:00 AM",
                    to: "Fri 7/25/2025 12:00 AM"
                }
            ]
        },
        {
            id: "3",
            panelId: "panel3",
            label: "Meeting",
            colorClass: "#3b82f6",  // blue-500
            items: [
                {
                    tag: "MTG",
                    description: "HRM - MTG - TEAM STRATEGY SESSION - JOHN DOE + SARAH LEE",
                    from: "Mon 9/8/2025 09:00 AM",
                    to: "Mon 9/8/2025 10:30 AM"
                },
                {
                    tag: "MTG",
                    description: "NA - MTG - CLIENT KICKOFF - LISA KIM + PRODUCT TEAM *INTERNAL*",
                    from: "Wed 9/10/2025 13:00 PM",
                    to: "Wed 9/10/2025 14:00 PM"
                },
                {
                    tag: "MTG",
                    description: "SB - MTG - VENDOR REVIEW - JAMES NGUYEN + PROCUREMENT",
                    from: "Fri 9/12/2025 15:00 PM",
                    to: "Fri 9/12/2025 16:30 PM"
                }
            ]
        },
        {
            id: "4",
            panelId: "panel4",
            label: "Released",
            colorClass: "#eab308",  // yellow-500
            items: [
                {
                    tag: "RELEASED",
                    description: "HRM - RELEASE - EMILY CARTER *FINALIZED CONTRACT*",
                    from: "Tue 8/5/2025 10:00 AM",
                    to: "Tue 8/5/2025 10:15 AM"
                }
            ]
        },
        {
            id: "5",
            panelId: "panel5",
            label: "Pending",
            colorClass: "#ec4899",  // pink-500
            items: []
        },
        {
            id: "6",
            panelId: "panel6",
            label: "Cancelled",
            colorClass: "#6b7280",  // gray-500
            items: []
        },
        {
            id: "7",
            panelId: "panel7",
            label: "Hearings",
            colorClass: "#6366f1",  // indigo-500
            items: []
        },
        {
            id: "8",
            panelId: "panel8",
            label: "Depos",
            colorClass: "#8b5cf6",  // purple-500
            items: []
        },
        {
            id: "9",
            panelId: "panel9",
            label: "Mediation",
            colorClass: "#14b8a6",  // teal-500
            items: []
        },
        {
            id: "10",
            panelId: "panel10",
            label: "Docket Call",
            colorClass: "#f97316",  // orange-500
            items: []
        },
        {
            id: "11",
            panelId: "panel11",
            label: "Lit",
            colorClass: "#f43f5e",  // rose-500
            items: []
        },
        {
            id: "12",
            panelId: "panel12",
            label: "Lit Deadlines",
            colorClass: "#f59e0b",  // amber-500
            items: []
        },
        {
            id: "13",
            panelId: "panel13",
            label: "Negotiating",
            colorClass: "#10b981",  // emerald-500
            items: []
        },
        {
            id: "14",
            panelId: "panel14",
            label: "New Referral",
            colorClass: "#06b6d4",  // cyan-500
            items: []
        },
        {
            id: "15",
            panelId: "panel15",
            label: "Other",
            colorClass: "#71717a",  // zinc-500
            items: []
        },
        {
            id: "16",
            panelId: "panel16",
            label: "SOL",
            colorClass: "#84cc16",  // lime-500
            items: []
        },
        {
            id: "17",
            panelId: "panel17",
            label: "To Do",
            colorClass: "#d946ef",  // fuchsia-500
            items: []
        },
        {
            id: "18",
            panelId: "panel18",
            label: "Treating",
            colorClass: "#0ea5e9",  // sky-500
            items: []
        },
    ];


    return categories;
}

function renderEventCases(data) {
    const container = $("#categoryLayoutContent");
    container.empty();

    data.forEach(category => {
        const itemCount = category.items.length;

        const baseColor = category.colorClass;
        const darkerBorderColor = darkenHexColor(baseColor, 20);

        const borderStyle = `style="border-color: ${darkerBorderColor}; background-color: ${baseColor};"`;

        const $categoryTitle = $(`
            <div
                id="flip${category.id}"
                data-target="#${category.panelId}"
                class="flex items-center gap-2 flip bg-[#e4e9eec7] text-gray-900 py-2 px-4 rounded cursor-pointer"
            >
                <i class="fa-solid fa-chevron-right w-[15px] h-[15px]"></i>
                <i class="fa-solid fa-chevron-down w-[15px] h-[15px]" style="display:none"></i>
                <div class="w-[13px] h-[26px] border" ${borderStyle}></div>
                <label>${category.label}: ${itemCount} item(s)</label>

                <div class="ml-auto">
                    <button title="Edit" class="text-green-600 hover:text-green-800 cursor-pointer">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <button title="Delete" class="text-red-600 hover:text-red-800 ml-2 cursor-pointer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `);

        // Category content panel bg-[#eaf1ff] border border-[#cacaff]
        const $categoryContent = $(`
            <div
                id="${category.panelId}"
                class="panel bg-[#f8f9fb3d] text-gray-800 p-2"
                style="display:none"
            ></div>
        `);

        if (itemCount === 0) {
            const $noItemsPanel = $(`
                <div class="grid grid-cols-12 gap-2 mb-1">
                    <div class="col-span-12 my-auto italic text-gray-400 text-center py-2">No events/cases here</div>
                </div>
            `);
            $categoryContent.append($noItemsPanel);
        } else {
            category.items.forEach(item => {
                const $panel = $(`
                    <div class="grid grid-cols-12 gap-2 mb-1">
                        <div class="col-span-2 flex gap-2">
                            <div class="w-[13px] h-[26px] border" style="background-color: ${baseColor}; border-color: ${darkerBorderColor};"></div>
                            <label class="my-auto">${item.tag}</label>
                        </div>
                        <div class="col-span-6 my-auto">${item.description}</div>
                        <div class="col-span-2 my-auto">${item.from}</div>
                        <div class="col-span-2 my-auto">${item.to}</div>
                    </div>
                `);
                $categoryContent.append($panel);
            });
        }

        const $categoryDiv = $('<div class="mb-1"></div>');
        $categoryDiv.append($categoryTitle, $categoryContent);
        container.append($categoryDiv);
    });
}

// Darken hex color by a percentage (0-100)
function darkenHexColor(hex, percent) {
    // Remove #
    hex = hex.replace('#', '');
    // Parse r,g,b
    let r = parseInt(hex.substring(0,2), 16);
    let g = parseInt(hex.substring(2,4), 16);
    let b = parseInt(hex.substring(4,6), 16);
    // Darken each channel
    r = Math.floor(r * (1 - percent/100));
    g = Math.floor(g * (1 - percent/100));
    b = Math.floor(b * (1 - percent/100));
    // Convert back to hex string
    r = r.toString(16).padStart(2, '0');
    g = g.toString(16).padStart(2, '0');
    b = b.toString(16).padStart(2, '0');
    return `#${r}${g}${b}`;
}
