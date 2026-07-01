import './bootstrap';
// import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Alpine.start();
$(document).ready(() => {

    // ----------------------------------------------------------- //
    // start system users //
    // ----------------------------------------------------------- //

    // start email as username
    const emailInput = $('#email');
    const usernameInput = $('#username');
    const emailAsUsernameCheckbox = $('input[name="email_as_username"][value="1"]');

    function syncUsernameWithEmail() {

        if (emailAsUsernameCheckbox.is(':checked')) {

            usernameInput.val(emailInput.val());

        }

    }

    emailAsUsernameCheckbox.on('change', function () {

        if ($(this).is(':checked')) {

            syncUsernameWithEmail();

            usernameInput.prop('readonly', true);

        } else {

            usernameInput.prop('readonly', false);

        }

    });

    emailInput.on('input', function () {

        syncUsernameWithEmail();

    });

    if (emailAsUsernameCheckbox.is(':checked')) {

        usernameInput.prop('readonly', true);

        syncUsernameWithEmail();

    }
    // end email as username

    // start profile photo
    $('#editProfilePhoto').on('click', function () {

        $('#profile_photo').trigger('click');

    });

    $('#profile_photo').on('change', function (e) {

        const file = e.target.files[0];

        if (file) {

            const reader = new FileReader();

            reader.onload = function (event) {

                $('#profilePhotoPreview').attr('src', event.target.result);

            };

            reader.readAsDataURL(file);

        }

    });
    // end profile photo

    // ----------------------------------------------------------- //
    // end system users //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start timeline task //
    // ----------------------------------------------------------- //
    const $timelineProductSelect = $('#product_id');
    const $timelineDestinationSelect = $('#destination_id');

    if ($timelineProductSelect.length && $timelineDestinationSelect.length && !$('#resort_id').length && !$('#cruise_itinerary_id').length) {
        const allTimelineDestinationOptions = $timelineDestinationSelect.find('option').clone();
        const oldTimelineDestination = $timelineDestinationSelect.val();

        function filterTimelineDestinations() {
            const selectedProduct = $timelineProductSelect.val();

            const currentDestination = oldTimelineDestination || $timelineDestinationSelect.val();

            $timelineDestinationSelect.html('<option value="">--Select Destination--</option>');

            allTimelineDestinationOptions.each(function() {
                const $option = $(this);
                const productId = $option.data('product');

                if ($option.val() !== '' && productId !== undefined && productId.toString() === selectedProduct) {
                    $timelineDestinationSelect.append($option.clone());
                }
            });

            if (!$timelineDestinationSelect.find('option[value="' + currentDestination + '"]').length && currentDestination) {
                const oldOption = allTimelineDestinationOptions.filter(`[value="${currentDestination}"]`);
                $timelineDestinationSelect.append(oldOption.clone());
            }

            $timelineDestinationSelect.val(currentDestination);
        }

        $timelineProductSelect.on('change', function() {
            $timelineDestinationSelect.val('');
            filterTimelineDestinations();
        });

        filterTimelineDestinations();
    }
    // ----------------------------------------------------------- //
    // end timeline task //
    // ----------------------------------------------------------- //



    // ----------------------------------------------------------- //
    // start form manager //
    // ----------------------------------------------------------- //
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
    // ----------------------------------------------------------- //
    // end form manager //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start automated emails //
    // ----------------------------------------------------------- //
    const $masterProduct = $('#product_list option').clone();
    const $masterDestination = $('#destination_list option').clone();
    const $masterResort = $('#resort_list option').clone();
    const $masterCruise = $('#cruise_itinerary_list option').clone();

    const rowAutomatedEmail = `
        <div class="email-row grid grid-cols-1 md:grid-cols-5 gap-x-5 gap-y-4 items-end">

            <div class="relative">
                <label>Product</label>
                <select name="product_list[]" class="product_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="-1">--Select Product--</option>
                </select>
            </div>

            <div class="relative">
                <label>Destination</label>
                <select  name="destination_list[]"  class="destination_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="-1">--Select Destination--</option>
                </select>
            </div>

            <div class="relative">
                <label>Resort/Ship</label>
                <select name="resort_list[]" class="resort_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="-1">--Select Resort/Ship--</option>
                </select>
            </div>

            <div class="relative">
                <label>Cruise/Type</label>
                <select name="cruise_itinerary_list[]" class="cruise_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="-1">--Select Cruise/Type--</option>
                </select>
            </div>

            <div class="flex justify-center items-center">
                <button type="button" class="delete-row text-[#989898] text-2xl mb-3 cursor-pointer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>`
    ;

    function initDynamicEmailRow($row) {

        const $product = $row.find('.product_list');
        const $destination = $row.find('.destination_list');
        const $resort = $row.find('.resort_list');
        const $cruise = $row.find('.cruise_list');

        $product.html($masterProduct.clone());

        $product.val('');
        $destination.html('<option value="-1">--Select Destination--</option>');
        $resort.html('<option value="-1">--Select Resort/Ship--</option>');
        $cruise.html('<option value="-1">--Select Cruise/Type--</option>');

        function filterDest() {

            const productId = $product.val();

            $destination.html('<option value="-1">--Select Destination--</option>');

            if (!productId) {

                $resort.html('<option value="-1">--Select Resort/Ship--</option>');
                $cruise.html('<option value="-1">--Select Cruise/Type--</option>');

                return;
            }

            $masterDestination.each(function () {

                const $opt = $(this);

                if ($opt.val() && $opt.data('product') == productId) {
                    $destination.append($opt.clone());
                }
            });

            $destination.val('');

            filterResort();
        }

        function filterResort() {

            const destId = $destination.val();

            $resort.html('<option value="-1">--Select Resort/Ship--</option>');

            if (!destId) {

                $cruise.html('<option value="-1">--Select Cruise/Type--</option>');

                return;
            }

            $masterResort.each(function () {

                const $opt = $(this);

                if ($opt.val() && $opt.data('destination') == destId) {
                    $resort.append($opt.clone());
                }
            });

            $resort.val('');

            filterCruise();
        }

        function filterCruise() {

            const resortId = $resort.val();

            $cruise.html('<option value="-1">--Select Cruise/Type--</option>');

            if (!resortId) {
                return;
            }

            $masterCruise.each(function () {

                const $opt = $(this);

                if ($opt.val() && $opt.data('resort') == resortId) {
                    $cruise.append($opt.clone());
                }
            });

            $cruise.val('');
        }

        $product.on('change', filterDest);
        $destination.on('change', filterResort);
        $resort.on('change', filterCruise);
    }

    $('#addRowAutomatedEmails').on('click', function () {
        const $row = $(rowAutomatedEmail);
        $('#addRowAutomatedEmailsContainer').append($row);
        initDynamicEmailRow($row);
    });

    function toggleReservationSection() {
        if ($('#email_type').val() === 'Reservation Reminder') {
            $('#reservationReminderSection').css('display', 'grid');
        } else {
            $('#reservationReminderSection').hide();
        }
    }

    toggleReservationSection();

    $(document).on('change', '#email_type', function () {
        toggleReservationSection();
    });

    const $emailProduct = $('#product_list');
    const $emailDestination = $('#destination_list');
    const $emailResort = $('#resort_list');
    const $emailCruise = $('#cruise_itinerary_list');

    if ($emailProduct.length) {

        const allDestinations = $emailDestination.find('option').clone();
        const allResorts = $emailResort.find('option').clone();
        const allCruises = $emailCruise.find('option').clone();

        function filterEmailDestinations() {
            const product = $emailProduct.val();

            $emailDestination.html('<option value="-1">--Select Destination--</option>');

            allDestinations.each(function () {
                const $opt = $(this);

                if ($opt.val() && $opt.data('product') == product) {
                    $emailDestination.append($opt.clone());
                }
            });

            filterEmailResorts();
        }

        function filterEmailResorts() {
            const destination = $emailDestination.val();

            $emailResort.html('<option value="-1">--Select Resort/Ship--</option>');

            allResorts.each(function () {
                const $opt = $(this);

                if ($opt.val() && $opt.data('destination') == destination) {
                    $emailResort.append($opt.clone());
                }
            });

            filterEmailCruises();
        }

        function filterEmailCruises() {
            const resort = $emailResort.val();

            $emailCruise.html('<option value="-1">--Select Cruise/Type--</option>');

            allCruises.each(function () {
                const $opt = $(this);

                if ($opt.val() && $opt.data('resort') == resort) {
                    $emailCruise.append($opt.clone());
                }
            });
        }

        $emailProduct.on('change', filterEmailDestinations);
        $emailDestination.on('change', filterEmailResorts);
        $emailResort.on('change', filterEmailCruises);

        filterEmailDestinations();
    }


    const $rowsContainer = $('#addRowAutomatedEmailsContainer');

    const productValues = ($rowsContainer.data('products') || '').toString().split(',');
    const destinationValues = ($rowsContainer.data('destinations') || '').toString().split(',');
    const resortValues = ($rowsContainer.data('resorts') || '').toString().split(',');
    const cruiseValues = ($rowsContainer.data('cruises') || '').toString().split(',');

    if (productValues.length > 1) {

        for (let i = 1; i < productValues.length; i++) {

            const $row = $(rowAutomatedEmail);

            $rowsContainer.append($row);

            initDynamicEmailRow($row);

            const $product = $row.find('.product_list');
            const $destination = $row.find('.destination_list');
            const $resort = $row.find('.resort_list');
            const $cruise = $row.find('.cruise_list');

            $product.val(productValues[i]).trigger('change');

            setTimeout(() => {

                $destination.val(destinationValues[i]).trigger('change');

                setTimeout(() => {

                    $resort.val(resortValues[i]).trigger('change');

                    setTimeout(() => {

                        $cruise.val(cruiseValues[i]).trigger('change');

                    }, 0);

                }, 0);

            }, 0);
        }
    }
    const attachBtn = $('#attachBtn');
    const attachmentsInput = $('#attachments');
    const attachmentsTableBody = $('#attachmentsTableBody');

    attachBtn.on('click', function () {
        attachmentsInput.trigger('click');
    });

    let dt = new DataTransfer();

    attachmentsInput.on('change', function () {

        const emptyRow = attachmentsTableBody.find('.empty-attachments-row');
        if (emptyRow.length) emptyRow.remove();

        Array.from(this.files).forEach(file => {
            dt.items.add(file);

            const rowId = 'new-file-' + file.name + '-' + Date.now();

            const row = `
                <tr id="${rowId}" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                        <button type="button" onclick="removeSelectedFile('${rowId}', '${file.name}')">
                            <i class="fas fa-trash text-[#989898] cursor-pointer"></i>
                        </button>
                    </td>
                    <td class="px-4 py-3 text-gray-600 border-t-2 border-b-2 border-[#dee2e6]">
                        ${file.name}
                    </td>
                </tr>
            `;

            attachmentsTableBody.append(row);
        });

        attachmentsInput[0].files = dt.files;
    });

    window.removeSelectedFile = function (rowId, fileName) {

        $('#' + rowId).remove();

        const newDt = new DataTransfer();

        Array.from(attachmentsInput[0].files).forEach(file => {
            if (file.name !== fileName) {
                newDt.items.add(file);
            }
        });

        dt = newDt;
        attachmentsInput[0].files = dt.files;

        if (attachmentsTableBody.find('tr').length === 0) {
            attachmentsTableBody.html(`
                <tr class="empty-attachments-row">
                    <td colspan="2" class="text-center py-3 text-gray-400">
                        No attachments
                    </td>
                </tr>
            `);
        }
    };
    // ----------------------------------------------------------- //
    // end automated emails //
    // ----------------------------------------------------------- //


    // ----------------------------------------------------------- //
    // start itinerary //
    // ----------------------------------------------------------- //

    // start import bookings
    window.openImportReservationBookings = function(){
        $('#importReservationBookingsModal').removeClass('hidden');
    }

    window.closeImportReservationBookings = function(){
        $('#importReservationBookingsModal').addClass('hidden');
    }
    // end import bookings

    // start duplicate itinerary
    window.openDuplicateItineraryModal = function(name, date){

        $('#duplicateItineraryModal').removeClass('hidden');

        $('#duplicateName').val(name );
        $('#duplicateDate').val(date);
    }

    window.closeDuplicateItineraryModal = function(){
        $('#duplicateItineraryModal').addClass('hidden');
    }

    $(document).on('submit', '#duplicateItineraryForm', function(e){

        e.preventDefault();

        $.ajax({
            url: `/itinerary/${itineraryData.id}/duplicate`,
            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $('#duplicateName').val(),
                date: $('#duplicateDate').val(),
            },

            success: function(response){

                closeDuplicateItineraryModal();

                window.location.href = response.redirect;
            },

            error: function(xhr){

                console.log(xhr.responseText);
            }
        });
    });
    // end duplicate itinerary

    // start edit itinerary
    window.openEditItineraryModal = function(){

        $('#editItineraryName').val(itineraryData.name);
        $('#editItineraryDate').val(itineraryData.date);

        $('#editItineraryModal').removeClass('hidden');
    }

    window.closeEditItineraryModal = function(){
        $('#editItineraryModal').addClass('hidden')
    }

    $('#editItineraryForm').on('submit', function(e){

        e.preventDefault();

        $.ajax({
            url: `/itinerary/${itineraryData.id}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT',
                name: $('#editItineraryName').val(),
                date: $('#editItineraryDate').val(),
            },

            success: function(response){

                itineraryData.name = $('#editItineraryName').val();
                itineraryData.date = $('#editItineraryDate').val();

                $('#editItineraryModal').addClass('hidden');

                $('#editItineraryNamePencil').closest('h4').find('span').text(itineraryData.name);
            },

            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });
    // end edit itinerary

    // start add day
    $(document).on('click', '#addDayBtn', function () {

        const itineraryId = $(this).data('itinerary-id');

        $.ajax({
            url: `/itinerary/${itineraryId}/add-day`,
            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                location.reload();
            }
        });
    });

    window.openDeleteDayModal = function(){
        $('#deleteDayModal').removeClass('hidden');
    }

    let selectedDayId = null;

    $(document).on('click', '.delete-day-btn', function () {

        selectedDayId = $(this).data('day-id');

        $('#deleteDayModal').removeClass('hidden');
    });

    window.closeDeleteDayModal = function () {

        $('#deleteDayModal').addClass('hidden');

        selectedDayId = null;
    }

    $(document).on('click', '#confirmDeleteDayBtn', function () {

        if (!selectedDayId) return;

        $.ajax({
            url: `/itinerary/day/${selectedDayId}`,
            type: 'DELETE',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                closeDeleteDayModal();

                location.reload();
            }
        });
    });
    // end add day

    // start render itinerary day events
    let selectedDay = null;
    $(document).on('click', '.itinerary-day', function () {

        $('.itinerary-day').removeClass('bg-gray-100');
        $(this).addClass('bg-gray-100');

        selectedDay = $(this).data('day');

        renderDayHeader(selectedDay);
        renderDayEvents(selectedDay);
        toggleDayActions();
    });

    function renderDayEvents(day) {

        let html = '';

        if (!day.events || day.events.length === 0) {
            html = ` <div class="text-gray-400 text-lg text-center mt-20"></div> `;
        } else {

            day.events.forEach(event => {
                let formattedTime = '';

                if ( event.eventTime && event.eventTime !== '-1') {

                    const [hours, minutes] = event.eventTime.split(':');

                    const hour = parseInt(hours);

                    const suffix = hour >= 12 ? 'PM' : 'AM';

                    const formattedHour = hour % 12 || 12;

                    formattedTime = `${formattedHour}:${minutes} ${suffix}`;
                }

                let htmlBlock = '';

                if (event.eventType == 1 && event.itineraryEventFormActivitySubcategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-day text-[#2C3E50]"></i>

                                    <span class="text-sm text-gray-700 font-extrabold">
                                        ${formattedTime}
                                    </span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}

                            </div>

                            <div class="text-2xl">
                                ${event.itineraryActivityFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryActivityFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryActivityFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormProvider
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Provider</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormProvider}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryActivityFormAmount && event.itineraryActivityFormCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormCurrency ?? ''}
                                                    ${event.itineraryActivityFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 1 && event.itineraryEventFormActivitySubcategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-utensils text-[#90d5ec]"></i>

                                    <span class="text-sm text-[#009fd5] font-extrabold">
                                        ${formattedTime}
                                    </span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryActivityFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryActivityFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryActivityFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryActivityFormProvider
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Provider</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormProvider}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryActivityFormAmount && event.itineraryActivityFormCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryActivityFormCurrency ?? ''}
                                                    ${event.itineraryActivityFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 2 && event.itineraryEventFormLodgingSubcategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bed text-[#fea700]"></i>

                                    <span class="text-sm text-[#fea700] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#fea700] font-extrabold">CHECK-IN</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryLodgingFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryLodgingFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryLodgingFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormRoomBedType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Room/Bed Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormRoomBedType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryLodgingFormAmount && event.itineraryLodgingFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormAmountCurrency ?? ''}
                                                    ${event.itineraryLodgingFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 2 && event.itineraryEventFormLodgingSubcategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bed text-[#fea700]"></i>

                                    <span class="text-sm text-[#fea700] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#fea700] font-extrabold">CHECK-OUT</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : '' }
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryLodgingFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryLodgingFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryLodgingFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryLodgingFormRoomBedType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Room/Bed Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormRoomBedType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryLodgingFormAmount && event.itineraryLodgingFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryLodgingFormAmountCurrency ?? ''}
                                                    ${event.itineraryLodgingFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 3 && event.itineraryEventFormFlightSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-plane text-[#ca5]"></i>

                                    <span class="text-sm text-[#ca5] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#ca5] font-extrabold">DEPARTURE</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryFlightFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryFlightFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormAirline
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAirline}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormFlightNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormFlightNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>


                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormGate
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Gate</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormGate}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormTerminal
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Terminal</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormTerminal}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormSeatTicketDetails
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Seat/ Ticket Details</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormSeatTicketDetails}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryFlightFormAmount && event.itineraryFlightFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAmountCurrency ?? ''}
                                                    ${event.itineraryFlightFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 3 && event.itineraryEventFormFlightSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-plane text-[#ca5]"></i>

                                    <span class="text-sm text-[#ca5] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#ca5] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryFlightFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryFlightFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-5 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormAirline
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAirline}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormFlightNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Airline</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormFlightNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormGate
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Gate</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormGate}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>


                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryFlightFormTerminal
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Terminal</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormTerminal}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryFlightFormSeatTicketDetails
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Seat/ Ticket Details</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormSeatTicketDetails}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryFlightFormAmount && event.itineraryFlightFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryFlightFormAmountCurrency ?? ''}
                                                    ${event.itineraryFlightFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 4 && event.itineraryTransportationFormSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-train text-[#f49ac1]"></i>

                                    <span class="text-sm text-[#f49ac1] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#f49ac1] font-extrabold">DEPARTURE</span>
                                </div>

                                ${!VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryTransportationFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryTransportationFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormTransportationNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Transportation Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormTransportationNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryTransportationFormAmount && event.itineraryTransportationFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormAmountCurrency ?? ''}
                                                    ${event.itineraryTransportationFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 4 && event.itineraryTransportationFormSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-train text-[#f49ac1]"></i>

                                    <span class="text-sm text-[#f49ac1] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#f49ac1] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryTransportationFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryTransportationFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-3 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryTransportationFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">

                                ${
                                    event.itineraryTransportationFormTransportationNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Transportation Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormTransportationNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryTransportationFormAmount && event.itineraryTransportationFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryTransportationFormAmountCurrency ?? ''}
                                                    ${event.itineraryTransportationFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 5 && event.itineraryCruiseFormSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-ship text-[#6c9]"></i>

                                    <span class="text-sm text-[#6c9] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#6c9] font-extrabold">DEPARTURE</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryCruiseFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryCruiseFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormCabinNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCabinType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryCruiseFormAmount && event.itineraryCruiseFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormAmountCurrency ?? ''}
                                                    ${event.itineraryCruiseFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 5 && event.itineraryCruiseFormSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-ship text-[#6c9]"></i>

                                    <span class="text-sm text-[#6c9] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                    <span class="text-[#6c9] font-extrabold">ARRIVAL</span>
                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryCruiseFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryCruiseFormNote ?? ''}
                            </div>

                            <div class="border-t-2 border-[#eee] my-2"></div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormCabinNumber
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Number</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinNumber}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCabinType
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Cabin Type</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCabinType}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormCarrier
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Carrier</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormCarrier}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    event.itineraryCruiseFormConfirmation
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Confirmation</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormConfirmation}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>

                            <div class="grid grid-cols-4 gap-4 text-sm">

                                ${
                                    event.itineraryCruiseFormBookedThrough
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Booked Through</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormBookedThrough}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                                ${
                                    (event.itineraryCruiseFormAmount && event.itineraryCruiseFormAmountCurrency)
                                        ? `
                                            <div>
                                                <div class="font-bold text-base">Price</div>
                                                <div class="text-[#212121] mt-1">
                                                    ${event.itineraryCruiseFormAmountCurrency ?? ''}
                                                    ${event.itineraryCruiseFormAmount ?? ''}
                                                </div>
                                            </div>
                                        `
                                        : ''
                                }

                            </div>
                        </div>
                    `;
                } else if (event.eventType == 6 && event.itineraryEventFormInfoSubCategory == 1) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-[#627e8c]"></i>

                                    <span class="text-sm text-[#627e8c] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>`: ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryInfoFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryInfoFormNote ?? ''}
                            </div>

                        </div>
                    `;
                } else if (event.eventType == 6 && event.itineraryEventFormInfoSubCategory == 2) {
                    htmlBlock = `
                        <div class="border border-[#ccc] bg-white p-4 flex flex-col gap-3 cursor-pointer event-card" data-event='${JSON.stringify(event)}'>

                            <div class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-[#627e8c]"></i>

                                    <span class="text-sm text-[#627e8c] font-extrabold">
                                        ${formattedTime}
                                    </span>

                                </div>

                                ${ !VIEW_ONLY ? `<i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-event-btn" data-event-id="${event.id}"></i>` : ''}
                            </div>

                            <div class="text-2xl">
                                ${event.itineraryInfoFormTitle ?? ''}
                            </div>

                            <div class="text-base">
                                ${event.itineraryInfoFormNote ?? ''}
                            </div>

                        </div>
                    `;
                }

                html += htmlBlock;
            });
        }

        $('#dayEventsContainer').html(html);
    }

    // start delete event
    let selectedEventId = null;

    $(document).on('click', '.delete-event-btn', function (e) {
        e.stopPropagation();

        selectedEventId = $(this).data('event-id');

        $.ajax({
            url: `/itinerary/event/${selectedEventId}`,
            type: 'DELETE',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function () {

                if (selectedDay) {

                    selectedDay.events = selectedDay.events.filter(event => {
                        return event.id != selectedEventId;
                    });

                    renderDayEvents(selectedDay);
                }
            }
        });
    });
    // end delete event

    function toggleDayActions() {

        if (selectedDay && selectedDay.id) {

            if (isEditingDay) {

                $('#doneBtn').removeClass('hidden');

                $('#editBtn, #addEventBtn').addClass('hidden');

            } else {

                $('#editBtn, #addEventBtn').removeClass('hidden');

                $('#doneBtn').addClass('hidden');
            }

        } else {

            $('#editBtn, #addEventBtn, #doneBtn').addClass('hidden');
        }
    }

    $(document).on('click', '#editBtn', function () {

        if (!selectedDay) return;

        isEditingDay = true;

        $('#dayViewMode').addClass('hidden');
        $('#dayEditMode').removeClass('hidden');

        $('#editDayDate').val(selectedDay.dayDate ?? '');

        $('#editDayTitle').val(selectedDay.dayTitle ?? '');

        toggleDayActions();
    });

    $(document).on('click', '#doneBtn', function () {

        if (!selectedDay) return;

        $.ajax({
            url: `/itinerary/day/${selectedDay.id}`,
            type: 'PUT',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                date: $('#editDayDate').val(),
                title: $('#editDayTitle').val()
            },

            success: function () {

                selectedDay.dayTitle = $('#editDayTitle').val();

                selectedDay.dayDate = $('#editDayDate').val();

                isEditingDay = false;

                $('#dayEditMode').addClass('hidden');

                $('#dayViewMode').removeClass('hidden');

                renderDayHeader(selectedDay);

                const sidebarDay = $('.itinerary-day').filter(function () {
                    return $(this).data('day').id == selectedDay.id;
                });

                sidebarDay.find('span.text-sm').text(
                    selectedDay.dayDate
                        ? new Date(selectedDay.dayDate + "T00:00:00")
                            .toLocaleDateString('en-US', {
                                month: 'long',
                                day: '2-digit'
                            })
                        : ''
                );

                sidebarDay.attr('data-day', JSON.stringify(selectedDay));

                toggleDayActions();
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    let isEditingDay = false;

    $(document).ready(function () {

        toggleDayActions();

        const firstDay = $('.itinerary-day').first();

        if (firstDay.length) {

            firstDay.trigger('click');
        }
    });

    function renderDayHeader(day) {

        const header = document.getElementById('dayHeader');

        if (!day) {
            header.classList.add('hidden');
            return;
        }

        header.classList.remove('hidden');

        const divider = document.querySelector('#dayViewMode .w-px');

        document.getElementById('dayMonth').innerText = '';
        document.getElementById('dayDate').innerText = '';

        if (day.dayDate) {

            const fullDate = new Date(day.dayDate + "T00:00:00");

            if (!isNaN(fullDate.getTime())) {

                document.getElementById('dayMonth').innerText = fullDate.toLocaleString('en-US', { month: 'long' });

                document.getElementById('dayDate').innerText = fullDate.getDate();

                divider.classList.remove('hidden');
            }

        } else {

            divider.classList.add('hidden');
        }

        document.getElementById('dayNumber').innerText = `Day ${day.dayNumber ?? ''}`;

        document.getElementById('dayTitle').innerText = day.dayTitle ?? '';

        $('#dayEditMode').addClass('hidden');

        $('#dayViewMode').removeClass('hidden');

        isEditingDay = false;

        toggleDayActions();
    }
    // end render itinerary day events

    // start manage event
    window.openManageEventItineraryModal = function () {
        editingEventId = null;

        if (!selectedDay) return;

        $('#itineraryEventDayId').val(selectedDay.id);

        $('#manageEventItineraryModal').removeClass('hidden');

        setTimeout(() => {

            if (!$('.event_note').next('.note-editor').length) {

                $('.event_note').summernote({
                    height: 350,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['codeview']]
                    ]
                });

            }

        }, 100);
    }

    window.closeManageEventItineraryModal = function () {

        $('#manageEventItineraryModal').addClass('hidden');

        editingEventId = null;

        $('#manageEventForm')[0].reset();

        $('.event_note').summernote('code', '');

        $('.validation-error').text('').addClass('hidden');

        selectedCategory = categories[0];
        selectedSubcategory = categories[0].subcategories[0];

        renderCategories();
        renderSubcategories();
        renderSections();
    };

    const categories = [
        {
            id: 1,
            name: 'Activity',
            slug: 'activity',
            icon: 'fa-solid fa-calendar-days',

            subcategories: [
                {
                    id: 11,
                    name: 'Activity',
                    slug: 'activity',
                    icon: 'fa-solid fa-calendar-days'
                },
                {
                    id: 12,
                    name: 'Food/Drink',
                    slug: 'food-drink',
                    icon: 'fa-solid fa-utensils'
                }
            ]
        },

        {
            id: 2,
            name: 'Lodging',
            slug: 'lodging',
            icon: 'fa-solid fa-bed',

            subcategories: [
                {
                    id: 21,
                    name: 'Check-in',
                    slug: 'check-in',
                },
                {
                    id: 22,
                    name: 'Check-out',
                    slug: 'check-out',
                }
            ]
        },

        {
            id: 3,
            name: 'Flight',
            slug: 'flight',
            icon: 'fa-solid fa-plane',

            subcategories: [
                {
                    id: 31,
                    name: 'Departure',
                    slug: 'departure',
                    icon: 'fa-solid fa-plane-departure'
                },
                {
                    id: 32,
                    name: 'Arrival',
                    slug: 'arrival',
                    icon: 'fa-solid fa-plane-arrival'
                }
            ]
        },

        {
            id: 4,
            name: 'Transportation',
            slug: 'transportation',
            icon: 'fa-solid fa-bus',

            subcategories: [
                {
                    id: 41,
                    name: 'Departure',
                    slug: 'departure-transportation',
                },
                {
                    id: 42,
                    name: 'Arrival',
                    slug: 'arrival-transportation',
                }
            ]
        },

        {
            id: 5,
            name: 'Cruise',
            slug: 'cruise',
            icon: 'fa-solid fa-ship',

            subcategories: [
                {
                    id: 51,
                    name: 'Departure',
                    slug: 'departure-cruise',
                },
                {
                    id: 52,
                    name: 'Arrival',
                    slug: 'arrival-cruise',
                }
            ]
        },

        {
            id: 6,
            name: 'Info',
            slug: 'info',
            icon: 'fa-solid fa-circle-info',

            subcategories: [
                {
                    id: 61,
                    name: 'Info',
                    slug: 'info',
                    icon: 'fa-solid fa-circle-info'
                },
                {
                    id: 62,
                    name: 'City Guide',
                    slug: 'city-guide',
                    icon: 'fa-solid fa-book-open'
                }
            ]
        }
    ];

    let editingEventId = null;
    let selectedCategory = categories[0];
    let selectedSubcategory = categories[0].subcategories[0];

    function renderCategories() {

        let html = '';

        categories.forEach(category => {

            const activeClass = selectedCategory.id === category.id ? 'bg-[#6c757d] text-white' : 'bg-white text-[#495057] hover:bg-[#f1f3f5]';

            html += `
                <button type="button" class="category-btn h-[32px] px-3 border border-[#6c757d] text-[14px] flex items-center gap-2 transition-all ${activeClass} cursor-pointer" data-id="${category.id}" >
                    <i class="${category.icon}"></i>
                    <span>${category.name}</span>
                </button>
            `;
        });

        $('#categoryContainer').html(html);
    }

    function renderSubcategories() {

        let html = '';

        selectedCategory.subcategories.forEach(sub => {

            const activeClass = selectedSubcategory.id === sub.id ? 'bg-[#6c757d] text-white' : 'bg-white text-[#495057] hover:bg-[#f1f3f5]';

            html += `
                <button type="button" class="subcategory-btn h-[32px] px-3 border border-[#6c757d] text-[14px] flex items-center gap-2 transition-all ${activeClass} cursor-pointer" data-id="${sub.id}" >
                    <i class="${sub.icon}"></i>
                    <span>${sub.name}</span>
                </button>
            `;
        });

        $('#subcategoryContainer').html(html);
    }

    function renderSections() {

        $('.dynamic-section').addClass('hidden');

        $('.dynamic-section').find('input, select, textarea').prop('disabled', true);


        const key = `${selectedCategory.slug}-${selectedSubcategory.slug}`;

        const activeSection = $(`.${key}-section`);

        activeSection.removeClass('hidden');

        activeSection.find('input, select, textarea').prop('disabled', false);

        $('#eventType').val(selectedCategory.id);

        $('#subcategoryId').val(selectedSubcategory.id);
    }

    function initEventManager() {

        renderCategories();
        renderSubcategories();
        renderSections();
    }

    $(document).on('click', '.category-btn', function () {

        const id = $(this).data('id');

        selectedCategory = categories.find(c => c.id == id);

        selectedSubcategory = selectedCategory.subcategories[0];

        renderCategories();
        renderSubcategories();
        renderSections();
    });

    $(document).on('click', '.subcategory-btn', function () {

        const id = $(this).data('id');

        selectedSubcategory = selectedCategory.subcategories.find(s => s.id == id);

        renderSubcategories();
        renderSections();
    });

    initEventManager();

    $(document).on('click', '.event-card', function () {

        const event = $(this).data('event');

        editingEventId = event.id;

        // ACTIVITY
        if (event.eventType == 1) {

            selectedCategory = categories.find(c => c.id == 1);

            if (event.itineraryEventFormActivitySubcategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 11);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 12);
            }
        }

        // LODGING
        else if (event.eventType == 2) {

            selectedCategory = categories.find(c => c.id == 2);

            if (event.itineraryEventFormLodgingSubcategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 21);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 22);
            }
        }

        // FLIGHT
        else if (event.eventType == 3) {

            selectedCategory = categories.find(c => c.id == 3);

            if (event.itineraryEventFormFlightSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 31);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 32);
            }
        }

        // TRANSPORTATION
        else if (event.eventType == 4) {

            selectedCategory = categories.find(c => c.id == 4);

            if (event.itineraryTransportationFormSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 41);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 42);
            }
        }

        // CRUISE
        else if (event.eventType == 5) {

            selectedCategory = categories.find(c => c.id == 5);

            if (event.itineraryCruiseFormSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 51);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 52);
            }
        }

        // INFO
        else if (event.eventType == 6) {

            selectedCategory = categories.find(c => c.id == 6);

            if (event.itineraryEventFormInfoSubCategory == 1) {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 61);
            } else {
                selectedSubcategory = selectedCategory.subcategories.find(s => s.id == 62);
            }
        }

        renderCategories();
        renderSubcategories();
        renderSections();

        $('#manageEventItineraryModal').removeClass('hidden');

        setTimeout(() => {

            // ACTIVITY
            $('[name="itineraryActivityFormTitle"]').val(event.itineraryActivityFormTitle);
            $('[name="itineraryActivityFormBookedThrough"]').val(event.itineraryActivityFormBookedThrough);
            $('[name="itineraryActivityFormConfirmation"]').val(event.itineraryActivityFormConfirmation);
            $('[name="itineraryActivityFormProvider"]').val(event.itineraryActivityFormProvider);
            $('[name="itineraryActivityFormTime"]').val(event.itineraryActivityFormTime);
            $('[name="itineraryActivityFormDuration"]').val(event.itineraryActivityFormDuration);
            $('[name="itineraryActivityFormTimezone"]').val(event.itineraryActivityFormTimezone);
            $('[name="itineraryActivityFormAmount"]').val(event.itineraryActivityFormAmount);
            $('[name="itineraryActivityFormCurrency"]').val(event.itineraryActivityFormCurrency);

            // LODGING
            $('[name="itineraryLodgingFormTitle"]').val(event.itineraryLodgingFormTitle);
            $('[name="itineraryLodgingFormBookedThrough"]').val(event.itineraryLodgingFormBookedThrough);
            $('[name="itineraryLodgingFormConfirmation"]').val(event.itineraryLodgingFormConfirmation);
            $('[name="itineraryLodgingFormRoomBedType"]').val(event.itineraryLodgingFormRoomBedType);
            $('[name="itineraryLodgingFormTime"]').val(event.itineraryLodgingFormTime);
            $('[name="itineraryLodgingFormDuration"]').val(event.itineraryLodgingFormDuration);
            $('[name="itineraryLodgingFormTimezone"]').val(event.itineraryLodgingFormTimezone);
            $('[name="itineraryLodgingFormAmount"]').val(event.itineraryLodgingFormAmount);
            $('[name="itineraryLodgingFormAmountCurrency"]').val(event.itineraryLodgingFormAmountCurrency);

            // FLIGHT
            $('[name="itineraryFlightFormTitle"]').val(event.itineraryFlightFormTitle);
            $('[name="itineraryFlightFormBookedThrough"]').val(event.itineraryFlightFormBookedThrough);
            $('[name="itineraryFlightFormConfirmation"]').val(event.itineraryFlightFormConfirmation);
            $('[name="itineraryFlightFormAirline"]').val(event.itineraryFlightFormAirline);
            $('[name="itineraryFlightFormFlightNumber"]').val(event.itineraryFlightFormFlightNumber);
            $('[name="itineraryFlightFormTerminal"]').val(event.itineraryFlightFormTerminal);
            $('[name="itineraryFlightFormGate"]').val(event.itineraryFlightFormGate);
            $('[name="itineraryFlightFormSeatTicketDetails"]').val(event.itineraryFlightFormSeatTicketDetails);
            $('[name="itineraryFlightFormTime"]').val(event.itineraryFlightFormTime);
            $('[name="itineraryFlightFormDuration"]').val(event.itineraryFlightFormDuration);
            $('[name="itineraryFlightFormTimezone"]').val(event.itineraryFlightFormTimezone);
            $('[name="itineraryFlightFormAmount"]').val(event.itineraryFlightFormAmount);
            $('[name="itineraryFlightFormAmountCurrency"]').val(event.itineraryFlightFormAmountCurrency);

            // TRANSPORTATION
            $('[name="itineraryTransportationFormTitle"]').val(event.itineraryTransportationFormTitle);
            $('[name="itineraryTransportationFormBookedThrough"]').val(event.itineraryTransportationFormBookedThrough);
            $('[name="itineraryTransportationFormConfirmation"]').val(event.itineraryTransportationFormConfirmation);
            $('[name="itineraryTransportationFormCarrier"]').val(event.itineraryTransportationFormCarrier);
            $('[name="itineraryTransportationFormTransportationNumber"]').val(event.itineraryTransportationFormTransportationNumber);
            $('[name="itineraryTransportationFormTime"]').val(event.itineraryTransportationFormTime);
            $('[name="itineraryTransportationFormDuration"]').val(event.itineraryTransportationFormDuration);
            $('[name="itineraryTransportationFormTimezone"]').val(event.itineraryTransportationFormTimezone);
            $('[name="itineraryTransportationFormAmount"]').val(event.itineraryTransportationFormAmount);
            $('[name="itineraryTransportationFormAmountCurrency"]').val(event.itineraryTransportationFormAmountCurrency);

            // CRUISE
            $('[name="itineraryCruiseFormTitle"]').val(event.itineraryCruiseFormTitle);
            $('[name="itineraryCruiseFormBookedThrough"]').val(event.itineraryCruiseFormBookedThrough);
            $('[name="itineraryCruiseFormConfirmation"]').val(event.itineraryCruiseFormConfirmation);
            $('[name="itineraryCruiseFormCarrier"]').val(event.itineraryCruiseFormCarrier);
            $('[name="itineraryCruiseFormCabinType"]').val(event.itineraryCruiseFormCabinType);
            $('[name="itineraryCruiseFormCabinNumber"]').val(event.itineraryCruiseFormCabinNumber);
            $('[name="itineraryCruiseFormTime"]').val(event.itineraryCruiseFormTime);
            $('[name="itineraryCruiseFormDuration"]').val(event.itineraryCruiseFormDuration);
            $('[name="itineraryCruiseFormTimezone"]').val(event.itineraryCruiseFormTimezone);
            $('[name="itineraryCruiseFormAmount"]').val(event.itineraryCruiseFormAmount);
            $('[name="itineraryCruiseFormAmountCurrency"]').val(event.itineraryCruiseFormAmountCurrency);

            // INFO
            $('[name="itineraryInfoFormTitle"]').val(event.itineraryInfoFormTitle);
            $('[name="itineraryInfoFormTime"]').val(event.itineraryInfoFormTime);

            // NOTES
            let note = '';

            if (event.eventType == 1) {
                note = event.itineraryActivityFormNote ?? '';
            }

            else if (event.eventType == 2) {
                note = event.itineraryLodgingFormNote ?? '';
            }

            else if (event.eventType == 3) {
                note = event.itineraryFlightFormNote ?? '';
            }

            else if (event.eventType == 4) {
                note = event.itineraryTransportationFormNote ?? '';
            }

            else if (event.eventType == 5) {
                note = event.itineraryCruiseFormNote ?? '';
            }

            else if (event.eventType == 6) {
                note = event.itineraryInfoFormNote ?? '';
            }

            $('.event_note').summernote('code', note);

        }, 100);
    });

    $('#manageEventForm').on('submit', function (e) {

        e.preventDefault();

        const formData = new FormData(this);

        if (editingEventId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
           url: editingEventId ? `/itinerary/event/${editingEventId}` : '/itinerary/event/store',

            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {

                if (editingEventId) {

                    const index = selectedDay.events.findIndex(e => e.id == editingEventId);

                    if (index !== -1) {
                        selectedDay.events[index] = response.event;
                    }

                } else {

                    selectedDay.events.push(response.event);
                }

                selectedDay.events.sort((a, b) => {
                    return (a.eventTime || '').localeCompare(b.eventTime || '');
                });

                renderDayEvents(selectedDay);

                closeManageEventItineraryModal();

                $('#manageEventForm')[0].reset();

                $('.event_note').summernote('code', '');

                editingEventId = null;
            },

            error: function (xhr) {

            $('.validation-error').text('').addClass('hidden');

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                Object.keys(errors).forEach(function(field) {

                    const input = $(`[name="${field}"]`);

                    input.closest('.relative').find('.validation-error').text(errors[field][0]).removeClass('hidden');
                });
            }
        }
        });
    });
    // end manage event

    // start itinerary attachments
    function renderAttachmentsSection() {

        let attachmentsHtml = '';

        if (itineraryAttachments.length > 0) {

            itineraryAttachments.forEach(file => {

                attachmentsHtml += `
                    <div class="border border-[#ccc] bg-white p-2 flex flex-col gap-3 cursor-pointer attachment-row">

                        <div class="flex justify-between gap-3">

                            <div class="flex flex-col">
                                <a href="/storage/attachments/itineraries/${file.id}.${file.extension}" target="_blank" class="text-base text-[#007bff]">
                                    ${file.name}.${file.extension}
                                </a>
                            </div>

                            ${
                                !VIEW_ONLY
                                ? `
                                    <i class="far fa-times-circle text-[#c60000] mt-1 text-lg cursor-pointer delete-attachment" data-id="${file.id}"></i>
                                `
                                : ''
                            }

                        </div>

                    </div>
                `;
            });

        } else {

            attachmentsHtml = `
                <div class="text-gray-400 text-center mt-8">

                </div>
            `;
        }

        $('#dayHeader').addClass('hidden');

        $('#dayEventsContainer').html(`
            <div class="flex flex-col gap-5">

                ${
                    !VIEW_ONLY
                    ? `
                        <div>
                            <button id="addItineraryAttachmentBtn" class="space-x-2 bg-[#B6844A] text-white font-semibold py-2 px-7 rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#B6844A] hover:text-[#B6844A] transition-all duration-200 ">
                                <i class="fas fa-paperclip mr-2"></i>
                                Attachments
                            </button>
                        </div>
                    `
                    : ''
                }

                <div id="itineraryAttachmentsList">
                    ${attachmentsHtml}
                </div>

            </div>
        `);

        $('.itinerary-day').removeClass('bg-gray-100');

        selectedDay = null;

        toggleDayActions();
    }

    $(document).on('click', '#attachmentsBtn', function () {

        renderAttachmentsSection();
    });

    $(document).on('click', '#addItineraryAttachmentBtn', function () {

        $('#itineraryAttachmentsInput').trigger('click');
    });

    $(document).on('change', '#itineraryAttachmentsInput', function () {

        const files = this.files;

        if (!files.length) return;

        const formData = new FormData();

        Array.from(files).forEach(file => {
            formData.append('attachments[]', file);
        });

        $.ajax({
            url: `/itinerary/${itineraryData.id}/attachments`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {

                response.attachments.forEach(file => {

                    itineraryAttachments.push(file);
                });

                renderAttachmentsSection();

                $('#itineraryAttachmentsInput').val('');
            },

            error: function(xhr) {

                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.delete-attachment', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/itinerary/attachments/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {

                const index = itineraryAttachments.findIndex(a => a.id == id);
                if (index !== -1) {
                    itineraryAttachments.splice(index, 1);
                }

                renderAttachmentsSection();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
    // end itinerary attachments

    // start change cover photo
    $('#changeItineraryCoverPhotoBtn').on('click', function () {
        $('#coverPhotoInput').click();
    });

    $('#coverPhotoInput').on('change', function () {

        const file = this.files[0];

        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        $.ajax({
            url: `/itinerary/${itineraryData.id}/cover-photo`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                location.reload();
            }
        });
    });
    // end change cover photo

    // start pdf copy link
    window.copyItineraryLink = function (url) {

        navigator.clipboard.writeText(url)
            .then(function () {

                $('#copySuccessOverlay').removeClass('hidden').addClass('flex');

                setTimeout(function () {

                    $('#copySuccessOverlay').addClass('hidden').removeClass('flex');

                }, 1500);

            })
            .catch(function () {

                alert('Failed to copy link');

            });
    };
    // end pdf copy link

    // ----------------------------------------------------------- //
    // end itinerary //
    // ----------------------------------------------------------- //
});
