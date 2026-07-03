import './bootstrap';

$(document).ready(() => {

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

});
