let signaturePad = null;

$(function () {

    const canvas = document.getElementById("travelInsuranceWaiverSignature");

    if (canvas) {
        signaturePad = new SignaturePad(canvas);

        $("#clearSignature").on("click", function () {
            signaturePad.clear();
        });
    }

    $(document).on("click", "#printCreditCardForm", function (e) {
        e.preventDefault();

        const printWindow = window.open("", "PRINT" + Date.now(), "");

        const clone = document.getElementById("creditCardForm").cloneNode(true);

        const originalFields = document.querySelectorAll("#creditCardForm input, #creditCardForm textarea, #creditCardForm select");
        const clonedFields   = clone.querySelectorAll("input, textarea, select");

        originalFields.forEach((field, index) => {

            const cloned = clonedFields[index];

            if (!cloned) return;

            if (field.type === "checkbox" || field.type === "radio") {
                cloned.checked = field.checked;

                if (field.checked) {
                    cloned.setAttribute("checked", "checked");
                } else {
                    cloned.removeAttribute("checked");
                }
            } else {
                cloned.value = field.value;
                cloned.setAttribute("value", field.value);
            }
        });

        if (signaturePad && !signaturePad.isEmpty()) {

            const signatureCanvas = clone.querySelector("#travelInsuranceWaiverSignature");

            if (signatureCanvas) {

                const img = document.createElement("img");

                img.src = signaturePad.toDataURL();

                img.style.border = "1px solid #ddd";
                img.style.width = signatureCanvas.width + "px";
                img.style.height = signatureCanvas.height + "px";

                signatureCanvas.replaceWith(img);
            }
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>

                ${document.head.innerHTML}

                <style>

                    input{
                        border:none !important;
                        border-bottom:2px solid #bdbdbd !important;
                        border-radius:0 !important;
                        background:transparent !important;
                        height:30px !important;
                    }

                    button.noprint{
                        display:none !important;
                    }

                    @page{
                        margin:0;
                    }

                    body{
                        padding:20px;
                    }

                </style>

            </head>

            <body>

                ${clone.outerHTML}

            </body>

            </html>
        `);

        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };

    });

});