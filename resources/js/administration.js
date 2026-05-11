import './bootstrap';
// import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(() => {

    // start timeline task
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
    // end timeline task

    // start forms manager
    if (window.existingRows && window.existingRows.length > 0) {

        window.existingRows.forEach(row => {

            let productOptions = '<option value="">--Select product--</option>';

            window.products.forEach(p => {
                productOptions += `
                    <option value="${p.id}" ${p.id == row.product_id ? 'selected' : ''}>
                        ${p.product_name}
                    </option>`;
            });

            let destinationOptions = '<option value="">--Select destination--</option>';

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

                    <button type="button" class="text-[#bdbdbd] text-xl delete-row flex mt-6">
                        <i class="fas fa-trash"></i>
                    </button>

                </div>
            `;

            $('#productDestinationContainer').append(html);
        });
    }


    $('#addRowBtn').on('click', function () {

        let productOptions = '<option value="">--Select product--</option>';

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
                    <option value="">--Select destination--</option>
                </select>
            </div>

            <button type="button" class="text-[#bdbdbd] text-xl delete-row flex mt-6">
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

        $destination.html('<option value="">--Select destination--</option>');

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


                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
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

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'plainTextBlock'){
            html = `
            <div class="form-item plain-text-block bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Plain Text Block</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="field-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm ">Text</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
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

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'customerDeclaration'){
            html = `
            <div class="form-item customer-declaration bg-white shadow rounded-none p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Customer Declaration</p>
                <div>
                    <input type="checkbox" for="customerDeclarationRequired">
                    <label for="customerDeclarationRequired">Required</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
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
                    <button type="button"
                        class="text-[#bdbdbd] text-xl delete-row3 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd]
                    transition-all duration-200 mt-3"><i class="fas fa-plus"></i>Add Choice</button>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>

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
                    <button type="button"
                        class="text-[#bdbdbd] text-xl delete-row3 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button  type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd]
                    transition-all duration-200 mt-3"><i class="fas fa-plus"></i>Add Choice</button>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
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

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'HTML'){
            html = `
            <div class="form-item flex-item bg-white shadow rounded-none p-6 mt-1" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">HTML</p>
                <label class="text-sm">Content</label>
                <textarea
                    id="content"
                    name="content"
                    rows="3"
                    class="w-full border-b border-[#bdbdbd] focus:outline-none focus:border-[#B6844A] resize-none pt-1 pb-1">
                </textarea>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
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
                    <input
                        type="text"
                        placeholder=" "
                        class="choice-input peer w-full border-b focus:outline-none focus:border-[#B6844A] pt-5 pb-1"
                    />
                    <label
                        class="absolute left-0 top-1 transition-all duration-200
                            peer-placeholder-shown:top-2
                            peer-focus:top-0 peer-focus:text-sm">
                        Choice Text
                    </label>
                </div>

                <button type="button"
                    class="text-[#bdbdbd] text-xl delete-row3 mt-3">
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

    $(document).on('click', '.fa-arrow-up', function () {
        const $item = $(this).closest('.form-item');
        const $prev = $item.prev('.form-item');
        if ($prev.length) {
            $item.insertBefore($prev);
        }
        generatePreview();
    });


    $(document).on('click', '.fa-arrow-down', function () {
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
                        <input
                            type="text"
                            class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent"
                        >

                        <label class="absolute left-0 bottom-1 text-sm pointer-events-none">
                            ${text}
                        </label>
                    </div>
                `;
            }

            // CUSTOMER DECLARATION
            else if (item.find('.customer-declaration').length) {

                previewHtml += `
                    <div class="mt-4">
                        <div class="grid grid-cols-2 gap-6">

                            <div>
                                <p class="mb-2 text-sm">
                                    I,
                                </p>

                                <div class="relative">
                                    <input
                                        type="text"
                                        placeholder=" "
                                        class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent"
                                    >

                                    <label class="absolute left-0 bottom-1 text-sm text-[#495057] pointer-events-none">
                                        Enter Full Name Here
                                    </label>
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-sm">
                                    On
                                </p>

                                <div class="relative">
                                    <input
                                        type="date"
                                        class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]"
                                    >
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
                                <p class="mb-2 text-sm">
                                    Customer Signature
                                </p>

                                <div class="relative">
                                    <input
                                        type="text"
                                        placeholder="Type Full Name Here To sign"
                                        class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]"
                                    >
                                </div>
                            </div>


                            <div>
                                <p class="mb-2 text-sm">
                                    Date
                                </p>

                                <div class="relative">
                                    <input
                                        type="date"
                                        placeholder="Select Date"
                                        class="w-full border-0 border-b border-[#bdbdbd] focus:outline-none focus:ring-0 focus:border-[#bdbdbd] px-0 pb-1 bg-transparent text-[#495057]"
                                    >
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

    // end forms manager

    // start automated Emails
    const $masterProduct = $('#product_list option').clone();
    const $masterDestination = $('#destination_list option').clone();
    const $masterResort = $('#resort_list option').clone();
    const $masterCruise = $('#cruise_itinerary_list option').clone();

    const rowAutomatedEmail = `
        <div class="email-row grid grid-cols-1 md:grid-cols-5 gap-x-5 gap-y-4 items-end">

            <div class="relative">
                <label>Product</label>
                <select name="product_list[]" class="product_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="">--Select Product--</option>
                </select>
            </div>

            <div class="relative">
                <label>Destination</label>
                <select  name="destination_list[]"  class="destination_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="">--Select Destination--</option>
                </select>
            </div>

            <div class="relative">
                <label>Resort/Ship</label>
                <select name="resort_list[]" class="resort_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="">--Select Resort/Ship--</option>
                </select>
            </div>

            <div class="relative">
                <label>Cruise/Type</label>
                <select name="cruise_itinerary_list[]" class="cruise_list w-full border-b-2 border-[#bdbdbd] mb-4">
                    <option value="">--Select Cruise/Type--</option>
                </select>
            </div>

            <div class="flex justify-center items-center">
                <button type="button" class="delete-row text-[#989898] text-2xl mb-3">
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
        $destination.html('<option value="">--Select Destination--</option>');
        $resort.html('<option value="">--Select Resort/Ship--</option>');
        $cruise.html('<option value="">--Select Cruise/Type--</option>');

        function filterDest() {

            const productId = $product.val();

            $destination.html('<option value="">--Select Destination--</option>');

            if (!productId) {

                $resort.html('<option value="">--Select Resort/Ship--</option>');
                $cruise.html('<option value="">--Select Cruise/Type--</option>');

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

            $resort.html('<option value="">--Select Resort/Ship--</option>');

            if (!destId) {

                $cruise.html('<option value="">--Select Cruise/Type--</option>');

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

            $cruise.html('<option value="">--Select Cruise/Type--</option>');

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

            $emailDestination.html('<option value="">--Select Destination--</option>');

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

            $emailResort.html('<option value="">--Select Resort/Ship--</option>');

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

            $emailCruise.html('<option value="">--Select Cruise/Type--</option>');

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
    // end automated emails

    // start itinerary
    window.openImportReservationBookings = function(){
        $('#importReservationBookingsModal').removeClass('hidden');
    }

    window.closeImportReservationBookings = function(){
        $('#importReservationBookingsModal').addClass('hidden');
    }
    // end itinerary
});
