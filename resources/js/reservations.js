$(document).ready(function() {
    // start reservation attention modal
    window.openAttentionModal = function(){
        $('#attentionReservationModal').removeClass('hidden');
    }

    window.closeAttentionModal = function(){
        $('#attentionReservationModal').addClass('hidden');
    }
    // end reservation attention modal

    window.handleBulkDelete = function() {

        const checked = $('.reservation-checkbox:checked');

        if (checked.length === 0) {
            openAttentionModal();
            return;
        }

        $('#bulkDeleteForm').submit();
    };

    $(document).on('click', '#duplicateReservationBtn', function () {
        let reservationId = $(this).data('id');

        window.location.href = '/reservation-list/' + reservationId + '/duplicate';
    });


    // start reservation add customer
    window.openReservationAddCustomerModal = function(){
        $('#reservationAddCustomerModal').removeClass('hidden');
    }

    window.closeReservationAddCustomerModal = function(){
        $('#reservationAddCustomerModal').addClass('hidden');
    }
    // end reservation add customer


    // start number of nights calculation
    function calculateNights() {
        const checkinVal = $('input[name="checkin_date"]').val();
        const checkoutVal = $('input[name="checkout_date"]').val();

        if(!checkinVal || !checkoutVal) {
            $('.nights-display').text('Nights');
            return;
        }

        const checkinDate = new Date(checkinVal);
        const checkoutDate = new Date(checkoutVal);

        if(checkoutDate < checkinDate) {
            $('.nights-display').text('Invalid Dates');
            return;
        }

        const diffTime = Math.abs(checkoutDate - checkinDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        $('.nights-display').text(diffDays + ' Nights');
    }

    $('div[x-data^="dateDropdown"]').on('change', 'select', function() {
        setTimeout(calculateNights, 50);
    });

    calculateNights();
    //end of number of nights calculation


    // agent commission calculation
    function updateAgentCommission() {

        let totalAgency = parseFloat($('#agency_commission').val()) || 0;

        let agentRate = 0;

        if ($('#agent_id').is('select')) {
            let selectedOption = $('#agent_id option:selected');
            agentRate = parseFloat(selectedOption.data('commission')) || 0;
        } else {
            agentRate = parseFloat($('#agent_id').data('commission')) || 0;
        }

        let agentCommission = (totalAgency * agentRate) / 100;

        $('#agent_commission').val(agentCommission.toFixed(2));
    }

    $('#agency_commission').on('input', updateAgentCommission);

    $(document).on('change', '#agent_id', updateAgentCommission);

    updateAgentCommission();

    $('#agency_commission').on('input', updateAgentCommission);

    $('#agent_id').on('change', updateAgentCommission);
    // end agent commission calculation


    //Reservation Dropdown Filtering
    const savedProductId = window.reservationDefaults?.productId ?? '';
    const savedDestinationId = window.reservationDefaults?.destinationId ?? '';
    const savedResortId = window.reservationDefaults?.resortId ?? '';
    const savedCruiseId = window.reservationDefaults?.cruiseId ?? '';

    $('#product_id').on('change', function () {

        let productId = $(this).val();

        window.disableGlobalLoader = true;

        $.get('/ajax/destinations', { product_id: productId }, function (data) {

            let html = '<option value="">--Select Destination--</option>';

            data.forEach(d => {

                html += `<option value="${d.id}">${d.destination_name}</option>`;

            });

            $('#destination_id').html(html);

            $('#resort_id').html('<option value="">--Select Resort/Ship--</option>');

            $('#cruise_itinerary_id').html('<option value="">--Select Cruise Itinerary--</option>');

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    });

    $('#destination_id').on('change', function () {

        let destinationId = $(this).val();

        window.disableGlobalLoader = true;

        $.get('/ajax/resorts', { destination_id: destinationId }, function (data) {

            let html = '<option value="">--Select Resort/Ship--</option>';

            data.forEach(r => {

                html += `<option value="${r.id}">${r.resort_ship_name}</option>`;

            });

            $('#resort_id').html(html);

            $('#cruise_itinerary_id').html('<option value="">--Select Cruise Itinerary--</option>');

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    });

    $('#resort_id').on('change', function () {

        let resortId = $(this).val();

        window.disableGlobalLoader = true;

        $.get('/ajax/cruises', { resort_id: resortId }, function (data) {

            let html = '<option value="">--Select Cruise Itinerary--</option>';

            data.forEach(c => {

                html += `<option value="${c.id}">${c.cruise_name}</option>`;

            });

            $('#cruise_itinerary_id').html(html);

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    });

    function loadDestinations(productId, selectDestId, selectResortId, selectCruiseId) {

        if (!productId) return;

        window.disableGlobalLoader = true;

        $.get('/ajax/destinations', { product_id: productId }, function (data) {

            let html = '<option value="">--Select Destination--</option>';

            data.forEach(d => {

                html += `<option value="${d.id}">${d.destination_name}</option>`;

            });

            $('#destination_id').html(html);

            if (selectDestId) {

                $('#destination_id').val(selectDestId);

                loadResorts(selectDestId, selectResortId, selectCruiseId);

            }

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    }

    function loadResorts(destinationId, selectResortId, selectCruiseId) {

        if (!destinationId) return;

        window.disableGlobalLoader = true;

        $.get('/ajax/resorts', { destination_id: destinationId }, function (data) {

            let html = '<option value="">--Select Resort/Ship--</option>';

            data.forEach(r => {

                html += `<option value="${r.id}">${r.resort_ship_name}</option>`;

            });

            $('#resort_id').html(html);

            if (selectResortId) {

                $('#resort_id').val(selectResortId);

                loadCruises(selectResortId, selectCruiseId);

            }

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    }

    function loadCruises(resortId, selectCruiseId) {

        if (!resortId) return;

        window.disableGlobalLoader = true;

        $.get('/ajax/cruises', { resort_id: resortId }, function (data) {

            let html = '<option value="">--Select Cruise Itinerary--</option>';

            data.forEach(c => {

                html += `<option value="${c.id}">${c.cruise_name}</option>`;

            });

            $('#cruise_itinerary_id').html(html);

            if (selectCruiseId) {

                $('#cruise_itinerary_id').val(selectCruiseId);

            }

        }).always(function () {

            window.disableGlobalLoader = false;

        });

    }

    if (savedProductId) {

        loadDestinations(savedProductId, savedDestinationId, savedResortId, savedCruiseId);

    }
    // end reservation dropdown filtering


    // start reservation tasks
    window.openReservationsTasksModal = function() {
        const $modal = $('#reservationsTasksModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Task');

        const $form = $('#taskForm');
        $form.attr('action', $form.data('store-url'));
        $('#task_method').val('POST');

        $('#task_id_modal').val('');
        $('#task_name_modal').val('');
        $('#task_priority_modal').val(-1);
        $('#task_notes_modal').val('');

        const dateEl = $modal.find('[x-data]')[0];

        if (dateEl && dateEl._x_dataStack) {
            const alpineData = Alpine.$data(dateEl);
            alpineData.setDate('');
        }
    }

    window.openEditTaskModal = function (task) {
        const $modal = $('#reservationsTasksModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Task');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#taskForm');
        $form.attr('action', `/task/${task.id}`);
        $('#task_method').val('PUT');

        $('#task_id_modal').val(task.id ?? '');
        $('#task_name_modal').val(task.task_name ?? '');
        $('#task_priority_modal').val(task.priority ?? -1);
        $('#task_notes_modal').val(task.task_notes ?? '');

        const dateEl = $modal.find('[x-data]')[0];
        if (dateEl && dateEl._x_dataStack) {
            const alpineData = Alpine.$data(dateEl);
            alpineData.setDate(task.due_date ?? '');
        }
    }

    window.closeReservationsTasksModal = function() {
        $('#reservationsTasksModal').addClass('hidden');
    }
    // end of reservation task


    // start reservation payment
    window.openReservationPaymentsModal = function(){
        const $modal = $('#reservationPaymentsModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Payment');

        const $form = $('#paymentForm');
        $form.attr('action', $form.data('store-url'));
        $('#payment_method').val('POST');

        $('#payment_id_modal').val('');
        $('#payment_amount_modal').val('');
        $('#payment_type').val('');
        $('#payment_method_modal').val('');
        $('#check_number').val('');
        $('#credit_card_number').val('');
        $('#payment_notes_modal').val('');

        const dateEl = $modal.find('[x-data]')[0];

        if (dateEl && dateEl._x_dataStack) {

            const alpineData = Alpine.$data(dateEl);

            alpineData.setDate('');

        }
    }

    window.openEditPaymentModal = function (payment) {
        const $modal = $('#reservationPaymentsModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Payment');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#paymentForm');
        $form.attr('action', `/payment/${payment.id}`);
        $('#payment_method').val('PUT');

        $('#payment_id_modal').val(payment.id ?? '');
        $('#payment_amount_modal').val(payment.amount ?? '');
        $('#payment_type').val(payment.payment_type ?? '');
        $('#payment_method_modal').val(payment.payment_method ?? '');
        $('#check_number').val(payment.check_number ?? '');
        $('#credit_card_number').val(payment.credit_card_number ?? '');
        $('#payment_notes_modal').val(payment.notes ?? '');

        const dateEl = $modal.find('[x-data]')[0];
        if (dateEl && dateEl._x_dataStack) {
            const alpineData = Alpine.$data(dateEl);
            alpineData.setDate(payment.payment_date ?? '');
        }
    }

    $(document).on('click', '.unlink-reservation', function () {
        let linkedId = $(this).data('id');
        let reservationId = CURRENT_RESERVATION_ID;


        $.ajax({
            url: `/reservation/${reservationId}/unlink`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                linked_reservation_id: linkedId
            },
            success: function () {
                location.reload(); 
            },
            error: function (err) {
                alert(err.responseJSON?.message ?? 'Error unlinking');
            }
        });
    });

    window.closeReservationPaymentsModal = function(){
        $('#reservationPaymentsModal').addClass('hidden');
    }
    //end of reservation payment 


    // start reservation travel with
    window.openTravelingWithModal = function(){
        $('#travelingWithModal').removeClass('hidden');
    }

    function formatUSDate(dateString) {
        if (!dateString) return '-';

        let date = new Date(dateString);

        if (isNaN(date.getTime())) return dateString; 

        return date.toLocaleDateString('en-US');
    }

    $('#linked_customer').on('change', function () {
        let customerId = $(this).val();

        let $container = $('#possible-reservations');
        $container.html('');

        if (!customerId) return;

        $.ajax({
            url: `/customers/${customerId}/active-reservations`,
            method: 'GET',
            data: {
                current_reservation_id: CURRENT_RESERVATION_ID
            },
            dataType: 'json',
            success: function (data) {

                if (!data || data.length === 0) {
                    $container.html('');
                    return;
                }

                data.forEach(function (res) {
                    $container.append(`
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <div class="flex space-x-3">
                                    <p>Name: ${res.reservation_name ?? '-'}</p>
                                    <p>Number: ${res.reservation_number ?? '-'}</p>
                                </div>
                                <p class="text-[#bdbdbd]">
                                    Dates: ${formatUSDate(res.checkin_date)} - ${formatUSDate(res.checkout_date)}
                                </p>
                            </div>

                            <button 
                                type="button"
                                class="link-reservation space-x-2 bg-[#B6844A] text-white py-1 px-2 rounded hover:bg-white hover:text-[#B6844A] hover:border-[#B6844A] border transition cursor-pointer"
                                data-id="${res.id}">
                                <i class="fas fa-link"></i> Link
                            </button>
                        </div>
                        <hr class="border-[#dee2e6]">
                    `);
                });

            }
        });
    });

    $(document).on('click', '.link-reservation', function () {
        let linkedId = $(this).data('id');

        let reservationId = CURRENT_RESERVATION_ID; 

        $.ajax({
            url: `/reservation/${reservationId}/link`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                linked_reservation_id: linkedId
            },
                success: function () {
                    closeTravelingWithModal();
                    location.reload(); 
                },
                error: function (err) {
                    alert(err.responseJSON?.message ?? 'Error linking');
                }
            });
    });

    window.closeTravelingWithModal = function(){
        $('#travelingWithModal').addClass('hidden');

        $('#linked_customer').val('');

        $('#possible-reservations').html('');
    }
    // end of reservation travel with

    // start forms
    window.openFormPreviewModal = function(content){
        $('#formPreviewContent').html(content);
        $('#formPreviewModal').removeClass('hidden');
    }

    window.closeFormPreviewModal = function(){
        $('#formPreviewModal').addClass('hidden');
    }
    // end forms


    // start reservation dining notes
    window.openDiningInfoModal = function(){
        const $modal = $('#diningInfoModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Dining Note');

        const $form = $('#diningNoteForm');
        $form.attr('action', $form.data('store-url'));
        $('#dining_note_method').val('POST');

        $('#dining_note_id_modal').val('');
        $('#dining_time_modal').val('');
        $('#meal_modal').val('-1');
        $('#notes_modal').val('');

        const dateEl = $modal.find('[x-data]')[0];

        if (dateEl && dateEl._x_dataStack) {

            const alpineData = Alpine.$data(dateEl);

            alpineData.setDate('');

        }
    }

    window.openEditDiningInformationModal = function (diningNote) {
        const $modal = $('#diningInfoModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Dining Note');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');
        
        const $form = $('#diningNoteForm');
        $form.attr('action', `/diningNote/${diningNote.id}`);
        
        $('#dining_note_method').val('PUT');
        $('#dining_note_id_modal').val(diningNote.id ?? '');
        $('#dining_time_modal').val(diningNote.dining_time ?? '');
        $('#meal_modal').val(diningNote.meal ?? '');
        $('#notes_modal').val(diningNote.notes ?? '');
        
        const dateEl = $modal.find('[x-data]')[0];
        if (dateEl && dateEl._x_dataStack) {
            const alpineData = Alpine.$data(dateEl);
            alpineData.setDate(diningNote.dining_date ?? '');
        }
    }

    window.closeDiningInfoModal = function(){
        $('#diningInfoModal').addClass('hidden');
    }
    // end reservation dining notes


    // start reservation gift notes
    window.openGiftsModal = function(){
        const $modal = $('#giftsModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Gift');

        const $form = $('#giftForm');
        $form.attr('action', $form.data('store-url'));
        $('#gift_info_method').val('POST');

        $('#gift_info_id_modal').val('');
        $('#gift_type_modal').val('');
        $('#amount_modal').val('');
        $('#gifts_notes_modal').val('');

        const dateEl = $modal.find('[x-data]')[0];

        if (dateEl && dateEl._x_dataStack) {

            const alpineData = Alpine.$data(dateEl);

            alpineData.setDate('');

        }
    }

    window.openEditGiftInfoModal = function (gift) {
        const $modal = $('#giftsModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Gift');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#giftForm');
        $form.attr('action', `/gift/${gift.id}`);
        $('#gift_info_method').val('PUT');

        $('#gift_info_id_modal').val(gift.id ?? '');
        $('#gift_type_modal').val(gift.gift_type ?? '');
        $('#amount_modal').val(gift.amount ?? '');
        $('#gifts_notes_modal').val(gift.notes ?? '');

        const dateEl = $modal.find('[x-data]')[0];
        if (dateEl && dateEl._x_dataStack) {
            const alpineData = Alpine.$data(dateEl);
            alpineData.setDate(gift.gift_date ?? '');
        }
    }

    window.closeGiftsModal = function(){
        $('#giftsModal').addClass('hidden');
    }
    // end reservation gift notes


    // start reservation automated email 
    window.openReservationAutomatedEmailModal = function(message,fname,lname,cellphone,email){
        const $modal = $('#reservationAutomatedEmailModal');

        $('#reservationAutomatedEmailMessage').html(message);

        $('#reservationAutomatedEmailAgentName').text(
            `${fname} ${lname}`.trim()
        );

        $('#reservationAutomatedEmailAgentCellphone').text(cellphone);

        $('#reservationAutomatedEmailAgentEmail').text(email);

        $modal.removeClass('hidden');
    }

    window.closeReservationAutomatedEmailModal = function(){
        $('#reservationAutomatedEmailModal').addClass('hidden');
    }
    // end reservation automated email

    // start reservation phone notes
    window.openPhoneNotesModal = function(){
        const $modal = $('#phoneNotesModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Phone Notes');

        const $form = $('#phoneForm');
        $form.attr('action', $form.data('store-url'));
        $('#phone_note_method').val('POST');

        $('#phone_note_id_modal').val('');
        $('#category').val('');
        $('#caller_name').val('');
        $('#caller_phone_number').val('');
        $('#phone_notes_modal').val('');
    }

    window.openEditPhoneNote = function (phoneNote) {
        const $modal = $('#phoneNotesModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Phone Notes');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#phoneForm');
        $form.attr('action', `/phoneNote/${phoneNote.id}`);
        $('#phone_note_method').val('PUT');

        $('#phone_note_id_modal').val(phoneNote.id ?? '');
        $('#category').val(phoneNote.category ?? '');
        $('#caller_name').val(phoneNote.caller_name ?? '');
        $('#caller_phone_number').val(phoneNote.caller_phone_number ?? '');
        $('#phone_notes_modal').val(phoneNote.notes ?? '');
    }

    window.closePhoneNotesModal = function(){
        $('#phoneNotesModal').addClass('hidden');
    }
    // end reservation phone notes


    // start reservation commission fees
    window.openCommissionFeesModal = function(){
        const $modal = $('#commissionFeesModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Fee');

        const $form = $('#commissionFeeForm');
        $form.attr('action', $form.data('store-url'));
        $('#commission_fee_method').val('POST');

        $('#commission_fee_id_modal').val('');
        $('#fee_type').val('');
        $('#commission_fee_amount').val('');
        $('#commission_fee_notes').val('');
    }

    window.openEditCommissionFeesModal = function(commissionFee) {
        const $modal = $('#commissionFeesModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Fee');
        $modal.find('.modal-icon').removeClass('fa-plus-circle').addClass('fa-edit');

        const $form = $('#commissionFeeForm');
        $form.attr('action', `/commissionFee/${commissionFee.id}`);
        $('#commission_fee_method').val('PUT');
        

        $('#commission_fee_id_modal').val(commissionFee.id ?? '');
        $('#fee_type').val(commissionFee.fee_type ?? '');
        $('#commission_fee_amount').val(commissionFee.amount ?? '');
        $('#commission_fee_notes').val(commissionFee.notes ?? '');
    }

    window.closeCommissionFeesModal = function(){
        $('#commissionFeesModal').addClass('hidden');
    }
    // end reservation commission fees

    document.addEventListener('click', function(e) {
        if (e.target.closest('.reservationTasksSaveBtn')) {
            e.target.closest('form').submit();
        }

        if (e.target.closest('.diningInformationSaveBtn')) {
            e.target.closest('form').submit();
        }

        if (e.target.closest('giftsModalSaveBtn')) {
            e.target.closest('form').submit();
        }

        if (e.target.closest('phoneNotesModalSaveBtn')) {
            e.target.closest('form').submit();
        }

        if (e.target.closest('commissionFeesModalSaveBtn')) {
            e.target.closest('form').submit();
        }
    });

    // start reservation attachments
    const attachReservationBtn = $('#attachReservationBtn');
    const reservationInput = $('#reservationAttachments');
    const reservationTable = $('#reservationAttachmentsTable');

    attachReservationBtn.on('click', function () {
        reservationInput.trigger('click');
    });

    reservationInput.on('change', function () {

        const emptyRow = reservationTable.find('.empty-row');

        if (emptyRow.length) {
            emptyRow.remove();
        }

        Array.from(this.files).forEach((file, index) => {

            const rowId = 'res-file-' + index + '-' + Date.now();

            const row = `
                <div id="${rowId}" class="flex justify-between mt-5 attachment-row">

                    <div class="flex space-x-3">

                        <i class="fas fa-file text-[#000] text-2xl mt-3"></i>

                        <div class="flex flex-col">

                            <p class="text-base">
                                ${file.name}
                            </p>

                            <p class="text-[#989898] text-sm">
                                Size: ${file.size} Bytes
                            </p>

                        </div>

                    </div>

                    <div class="space-x-4">

                        <i title="Download Attachment" class="fas fa-cloud-download-alt text-[#bdbdbd] text-xl mt-3"></i>

                        <button type="button" onclick="removeReservationFile('${rowId}', '${file.name}')">

                        <i title="Delete Attachment" class="fas fa-trash text-[#bdbdbd] text-xl mt-3"></i>

                        </button>

                    </div>

                </div>
            `;

            reservationTable.append(row);
        });
    });

    function removeReservationFile(rowId, fileName)
    {
        $('#' + rowId).remove();

        const dt = new DataTransfer();

        Array.from(reservationInput[0].files).forEach(file => {
            if (file.name !== fileName) {
                dt.items.add(file);
            }
        });

        reservationInput[0].files = dt.files;

        if (reservationTable.find('.attachment-row').length === 0) {

            reservationTable.html(`
                <div class="text-gray-400 mt-5 empty-row">
                   
                </div>
            `);
        }
    }
    // end reservation attachments

    // start reservattion itinerary pdf 
    window.openReservationItineraryAttentionModal = function (){
        $('#reservationItineraryAttentionModal').removeClass('hidden');
    }

    window.closeReservationItineraryAttentionModal = function(){
        $('#reservationItineraryAttentionModal').addClass('hidden');
    }

    $('#openItineraryPdfBtn').on('click', function () {

        const itineraryId = $('#itinerary_trip_id').val();

        if (itineraryId == -1 || !itineraryId) {
            openReservationItineraryAttentionModal();
            return;
        }

        const pdfUrl = `/itinerary/${itineraryId}/pdf`;

        openPdf(pdfUrl);
    });
    // end reservation itinerary pdf

});
