import './bootstrap';

$(document).ready(() => { 
    
    if (window.existingRows && window.existingRows.length > 0) {

        window.existingRows.forEach(row => {

            let productOptions = '<option value="-1">--Select product--</option>';

            window.products.forEach(p => {
                productOptions += `
                    <option value="${p.id}" ${p.id == row.product_id ? 'selected' : ''}>
                        ${p.product_name}
                    </option>`;
            });

            let destinationOptions = '<option value="-1">--Select destination--</option>';

            window.destinations.forEach(d => {
                if (d.product_id == row.product_id) {
                    destinationOptions += `
                        <option value="${d.id}" ${d.id == row.destination_id ? 'selected' : ''}>
                            ${d.destination_name}
                        </option>`;
                }
            });

            const html = `
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center form-row">

                    <div class="relative mt-3 md:col-span-2">
                        <label class="text-sm">Product</label>
                        <select name="product_id[]" class="product-select w-full  border-b-2 border-[#dee2e6]">
                            ${productOptions}
                        </select>
                    </div>

                    <div class="relative mt-3 md:col-span-2">
                        <label class="text-sm">Destination</label>
                        <select name="destination_id[]" class="destination-select w-full  border-b-2 border-[#dee2e6]">
                            ${destinationOptions}
                        </select>
                    </div>

                    <button type="button" class="text-[#bdbdbd] text-xl delete-row flex mt-6 cursor-pointer">
                        <i class="fas fa-trash"></i>
                    </button>

                </div>
            `;

            $('#productDestinationContainer').append(html);
        });
    }


    $('#addRowBtn').on('click', function () {

        let productOptions = '<option value="-1">--Select product--</option>';

        window.products.forEach(p => {
            productOptions += `<option value="${p.id}">${p.product_name}</option>`;
        });

        const row = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center form-row">

            <div class="relative mt-3 md:col-span-2">
                <label class="text-sm">Product</label>
                <select name="product_id[]" class="product-select w-full  border-b-2 border-[#dee2e6]">
                    ${productOptions}
                </select>
            </div>

            <div class="relative mt-3 md:col-span-2">
                <label class="text-sm">Destination</label>
                <select name="destination_id[]" class="destination-select w-full  border-b-2 border-[#dee2e6]">
                    <option value="-1">--Select destination--</option>
                </select>
            </div>

            <button type="button" class="text-[#bdbdbd] text-xl delete-row flex mt-6 cursor-pointer">
                <i class="fas fa-trash"></i>
            </button>

        </div>
        `;

        $('#productDestinationContainer').append(row);
    });


    $(document).on('change', '.product-select', function () {

        const $row = $(this).closest('.form-row');
        const productId = $(this).val();
        const $destination = $row.find('.destination-select');

        $destination.html('<option value="-1">--Select destination--</option>');

        window.destinations.forEach(d => {
            if (d.product_id == productId) {
                $destination.append(`
                    <option value="${d.id}">
                        ${d.destination_name}
                    </option>
                `);
            }
        });

    });


    $(document).on('click', '.delete-row', function () {
        $(this).closest('.grid').remove();
    });


    function itemActions() {
        return `
            <div class="flex place-content-end gap-4 mt-6">
                <button type="button" class="text-[#000] text-xl delete-row2 flex mt-3 cursor-pointer">
                    <i class="fas fa-trash"></i>
                </button>

                <button type="button" class="text-[#000] text-xl move-up flex mt-3 cursor-pointer">
                    <i class="fas fa-arrow-up"></i>
                </button>

                <button type="button" class="text-[#000] text-xl move-down flex mt-3 cursor-pointer">
                    <i class="fas fa-arrow-down"></i>
                </button>
            </div>
        `;
    }

    $(document).on('click', '.add-item', function (e) {
        e.preventDefault();
        let type = $(this).data('type');
        let html = '';
        if (type === 'title') {
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Title</p>
                <div class="relative mt-3">
                    <input type="text" data-preview="title"  placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm ">Text</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'acknowledgement'){
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Acknowledgement (Checkbox)</p>
                <div class="relative mt-3">
                    <input type="text" data-preview="acknowledgement" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Text</label>
                </div>

                <div>
                    <input type="checkbox" for="acknowledgement">
                    <label for="acknowledgement">Required</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'plainTextBlock'){
            html = `
            <div class="form-item plain-text-block bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Plain Text Block</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm ">Text</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'customerInputBlock'){
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Text Input</p>
                <div class="relative mt-3">
                    <input type="text" data-preview="customerInput" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Text</label>
                </div>

                <div>
                    <input type="checkbox" for="customerInputBlockRequired">
                    <label for="customerInputBlockRequired">Required</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'customerDeclaration'){
            html = `
            <div class="form-item customer-declaration bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Customer Declaration</p>
                <div>
                    <input type="checkbox" for="customerDeclarationRequired">
                    <label for="customerDeclarationRequired">Required</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'choice'){
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Choice (Radio Buttons)</p>
                <div>
                    <input type="checkbox" for="requiredChoice">
                    <label for="requiredChoice">Required</label>
                </div>

                <div>
                    <input type="checkbox" class="display-row-format">
                    <label>Display In Row Format</label>
                </div>

                <div class="choiceText flex gap-4 relative mt-3">
                    <div class=" flex-1 relative">
                        <input type="text" data-preview="choiceText" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1 mb-5 w-full" />
                        <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Choice Text</label>
                    </div>
                    <button type="button" class="text-[#bdbdbd] text-xl delete-row3 flex mt-3 cursor-pointer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200 mt-3">
                    <i class="fas fa-plus"></i>Add Choice
                </button>

                ${itemActions()}

            </div>
            <div class="addChoice mt-4 space-y-3"></div>`;
        }else if(type === 'dropdown'){
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Dropdown</p>
                <div class="relative mt-3">
                    <input type="text" data-preview="dropdownLabel" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Dropdown Label</label>
                </div>

                <div>
                    <input type="checkbox" for="requiredChoice">
                    <label for="requiredChoice">Required</label>
                </div>

                <div class="choiceText flex gap-4 relative mt-3">
                    <div class="relative flex-1">
                        <input type="text" placeholder=" " class="choice-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1 mb-5 w-full" />
                        <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Choice Text</label>
                    </div>
                    <button type="button" class="text-[#bdbdbd] text-xl delete-row3 flex mt-3 cursor-pointer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button  type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200 mt-3">
                    <i class="fas fa-plus"></i>Add Choice
                </button>

                ${itemActions()}
            </div>
            <div class="addChoice mt-4 space-y-3"></div>`;
        }else if(type === 'signatureBlock'){
            html = `
            <div class="form-item bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Signature Block</p>
                <div>
                    <input type="checkbox" for="signatureBlockRequired">
                    <label for="signatureBlockRequired">Required</label>
                </div>

                ${itemActions()}
            </div>`;
        }else if(type === 'HTML'){
            html = `
            <div class="form-item flex-item bg-white shadow rounded-none p-6 mt-1" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">HTML</p>
                <label class="text-sm">Content</label>
                <textarea id="content" name="content" rows="3" class="w-full border-b border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] resize-none pt-1 pb-1"></textarea>

                ${itemActions()}
            </div>`;
        }
        $('#form-items-container').append(html);
        generatePreview();
        const $dropdown = $(this).closest('el-dropdown');
        const $menu = $dropdown.find('el-menu[popover]');
        $menu.attr('data-closed', 'true');
    });

    $(document).on('click', '.addChoiceBtn', function () {
        const choiceRow = `
            <div class="choiceText flex gap-4 relative mt-3">
                <div class="flex-1 relative">
                    <input type="text" placeholder=" " class="choice-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1"/>
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2 peer-focus:top-0 peer-focus:text-sm">
                        Choice Text
                    </label>
                </div>

                <button type="button" class="text-[#bdbdbd] text-xl delete-row3 mt-3 cursor-pointer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        $(this).before(choiceRow);
    });

    $(document).on('click', '.delete-row2', function () {
        const $item = $(this).closest('div.bg-white');
        $item.remove();
        generatePreview();
    });

    $(document).on('click', '.delete-row3', function () {
        const $item = $(this).closest('div.choiceText');
        $item.remove();
        generatePreview();
    });

    $(document).on('click', '.move-up', function () {
        const $item = $(this).closest('.form-item');
        const $prev = $item.prev('.form-item');
        if ($prev.length) {
            $item.insertBefore($prev);
        }
        generatePreview();
    });


    $(document).on('click', '.move-down', function () {
        const $item = $(this).closest('.form-item');
        const $next = $item.next('.form-item');
        if ($next.length) {
            $item.insertAfter($next);
        }
        generatePreview();
    });

    function generatePreview() {

        let previewHtml = '';

        $('#form-items-container .form-item').each(function () {

            const item = $(this);

            // TITLE
            if (item.find('[data-preview="title"]').length) {

                const text = item.find('[data-preview="title"]').val() || 'Title';

                previewHtml += `
                    <h2 class="text-2xl font-bold">
                        ${text}
                    </h2>
                `;
            }

            // ACKNOWLEDGEMENT
            else if (item.find('[data-preview="acknowledgement"]').length) {

                const text = item.find('[data-preview="acknowledgement"]').val() || '';

                previewHtml += `
                    <div class="flex items-center gap-2">
                        <input type="checkbox">
                        <label>${text}</label>
                    </div>
                `;
            }

            // PLAIN TEXT
            else if (item.find('.plain-text-block').length) {

                const text = item.find('input[type="text"]').val() || '';

                previewHtml += `
                    <p>${text}</p>
                `;
            }

            // CUSTOMER INPUT
            else if (item.find('[data-preview="customerInput"]').length) {

                const text = item.find('[data-preview="customerInput"]').val() || '';

                previewHtml += `
                    <div class="relative pt-2">
                        <input type="text" class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent">

                        <label class="absolute left-0 bottom-1 text-sm pointer-events-none">${text}</label>
                    </div>
                `;
            }

            // CUSTOMER DECLARATION
            else if (item.find('.customer-declaration').length) {

                previewHtml += `
                    <div class="mt-4">
                        <div class="grid grid-cols-2 gap-6">

                            <div>
                                <p class="mb-2 text-sm">I,</p>

                                <div class="relative">
                                    <input type="text" placeholder=" " class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent">

                                    <label class="absolute left-0 bottom-1 text-sm text-[#495057] pointer-events-none">Enter Full Name Here</label>
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-sm">On</p>

                                <div class="relative">
                                    <input type="date" class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]">
                                </div>
                            </div>

                        </div>
                    </div>
                `;
            }


            // RADIO BUTTONS
            else if (item.find('p:contains("Choice (Radio Buttons)")').length) {

                const displayRow = item.find('.display-row-format').is(':checked');

                previewHtml += `
                    <div class="${displayRow ? 'flex gap-6 items-center flex-wrap' : 'space-y-2'}">
                `;


                const firstChoice = item.find('[data-preview="choiceText"]').val();

                if (firstChoice) {
                    previewHtml += `
                        <div class="flex items-center gap-2">
                            <input type="radio">
                            <label>${firstChoice}</label>
                        </div>
                    `;
                }


                item.find('.choice-input').each(function () {

                    const choice = $(this).val();

                    if (choice) {
                        previewHtml += `
                            <div class="flex items-center gap-2">
                                <input type="radio">
                                <label>${choice}</label>
                            </div>
                        `;
                    }
                });

                previewHtml += `</div>`;
            }

            // DROPDOWN
            else if (item.find('p:contains("Dropdown")').length) {

                const label = item.find('[data-preview="dropdownLabel"]').val() || '';

                previewHtml += `
                    <div>
                        <label class="block mb-1">${label}</label>
                        <select class="w-full border-b border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] px-0 py-2 bg-transparent">
                        <option value="" class="text-[#495057]" selected disabled>--Select--</option>
                `;

                item.find('.choice-input').each(function () {
                    const choice = $(this).val();

                    if (choice) {
                        previewHtml += `
                            <option>${choice}</option>
                        `;
                    }
                });

                previewHtml += `
                        </select>
                    </div>
                `;
            }

            // SIGNATURE BLOCK
            else if (item.find('p:contains("Signature Block")').length) {

                previewHtml += `
                    <div class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <p class="mb-2 text-sm">Customer Signature</p>

                                <div class="relative">
                                    <input type="text" placeholder="Type Full Name Here To sign" class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]">
                                </div>
                            </div>


                            <div>
                                <p class="mb-2 text-sm">Date</p>

                                <div class="relative">
                                    <input type="date" placeholder="Select Date" class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]">
                                </div>
                            </div>

                        </div>
                    </div>
                `;
            }

            // HTML BLOCK
            else if (item.find('textarea').length) {

                const html = item.find('textarea').val() || '';

                previewHtml += `
                    <div class="mt-3">
                        ${html}
                    </div>
                `;
            }
        });

        $('#form-preview-container').html(previewHtml);

        const clonedItems = $('#form-items-container').clone();

        clonedItems.find('input[type="text"]').each(function () {
            $(this).attr('value', $(this).val());
        });

        clonedItems.find('input[type="checkbox"]').each(function () {

            if ($(this).is(':checked')) {
                $(this).attr('checked', 'checked');
            } else {
                $(this).removeAttr('checked');
            }

        });

        clonedItems.find('textarea').each(function () {
            $(this).text($(this).val());
        });

        $('#form_items_html_content').val(
            clonedItems.html()
        );

        $('#preview_form_html_content').val(
            previewHtml
        );
    }

    $(document).on('keyup change', '.field-input, .choice-input, textarea', function () {
        generatePreview();
    });

    $('form').on('submit', function () {
        generatePreview();
    });

});
