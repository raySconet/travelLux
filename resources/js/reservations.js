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
        let selectedOption = $('#agent_id option:selected');
        let agentRate = parseFloat(selectedOption.data('commission')) || 0;


        let agentCommission = (totalAgency * agentRate) / 100;
        $('#agent_commission').val(agentCommission.toFixed(2));
    }

    $('#agency_commission').on('input', updateAgentCommission);

    $('#agent_id').on('change', updateAgentCommission);
    // end agent commission calculation


    //Reservation Dropdown Filtering
    const $productSelect = $('#product_id');
    const $destinationSelect = $('#destination_id');
    const $resortSelect = $('#resort_id');
    const $cruiseSelect = $('#cruise_itinerary_id');

    if ($productSelect.length && $destinationSelect.length && $resortSelect.length && $cruiseSelect.length) {
        const allDestinationOptions = $destinationSelect.find('option').clone();
        const allResortOptions = $resortSelect.find('option').clone();
        const allCruiseOptions = $cruiseSelect.find('option').clone();

        const oldDestination = $destinationSelect.val();
        const oldResort = $resortSelect.val();
        const oldCruise = $cruiseSelect.val();

        function filterDestinations() {
            const selectedProduct = $productSelect.val();

            $destinationSelect.html('<option value="">--Select Destination--</option>');

            allDestinationOptions.each(function() {
                const $option = $(this);

                if ($option.val() !== '' && $option.data('product').toString() === selectedProduct) {
                    $destinationSelect.append($option.clone());
                }
            });

            if ($destinationSelect.find('option[value="' + oldDestination + '"]').length) {
                $destinationSelect.val(oldDestination);
            } else {
                $destinationSelect.val('');
            }

            filterResorts();
        }

        function filterResorts() {
            const selectedDestination = $destinationSelect.val();

            $resortSelect.html('<option value="">--Select Resort/Ship--</option>');

            if (!selectedDestination) {
                filterCruises();
                return;
            }

            allResortOptions.each(function() {
                const $option = $(this);

                if ($option.val() !== '' && $option.data('destination').toString() === selectedDestination) {
                    $resortSelect.append($option.clone());
                }
            });

            if ($resortSelect.find('option[value="' + oldResort + '"]').length) {
                $resortSelect.val(oldResort);
            } else {
                $resortSelect.val('');
            }

            filterCruises();
        }

        function filterCruises() {
            const selectedResort = $resortSelect.val();

            $cruiseSelect.html('<option value="">--Select Cruise Itinerary--</option>');

            if (!selectedResort) {
                return;
            }

            allCruiseOptions.each(function() {
                const $option = $(this);

                if ($option.val() !== '' && $option.data('resort').toString() === selectedResort) {
                    $cruiseSelect.append($option.clone());
                }
            });

            if ($cruiseSelect.find('option[value="' + oldCruise + '"]').length) {
                $cruiseSelect.val(oldCruise);
            } else {
                $cruiseSelect.val('');
            }
        }

        $productSelect.on('change', function() {
            $destinationSelect.val('');
            $resortSelect.val('');
            $cruiseSelect.val('');
            filterDestinations();
        });

        $destinationSelect.on('change', function() {
            $resortSelect.val('');
            $cruiseSelect.val('');
            filterResorts();
        });

        $resortSelect.on('change', function() {
            $cruiseSelect.val('');
            filterCruises();
        });

        filterDestinations();
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

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#task_id').val('');
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

        if (task.due_date) {
            const dd = new Date(task.due_date);

            const year = dd.getFullYear();
            const month = dd.getMonth() + 1;
            const day = dd.getDate();

            const $yearSelect = $modal.find('[x-model="year"]');
            const $monthSelect = $modal.find('[x-model="month"]');
            const $daySelect = $modal.find('[x-model="day"]');

            if ($yearSelect.length) $yearSelect.val(year).trigger('change');
            if ($monthSelect.length) $monthSelect.val(month).trigger('change');
            if ($daySelect.length) $daySelect.val(day).trigger('change');
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

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#payment_id').val('');
    }

    window.openEditPaymentModal = function (payment) {
        const $modal = $('#reservationPaymentsModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Edit Payment');

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

        if (payment.payment_date) {
            const pd = new Date(payment.payment_date);

            const year = pd.getFullYear();
            const month = pd.getMonth() + 1;
            const day = pd.getDate();

            const $yearSelect = $modal.find('[x-model="year"]');
            const $monthSelect = $modal.find('[x-model="month"]');
            const $daySelect = $modal.find('[x-model="day"]');

            if ($yearSelect.length) $yearSelect.val(year).trigger('change');
            if ($monthSelect.length) $monthSelect.val(month).trigger('change');
            if ($daySelect.length) $daySelect.val(day).trigger('change');
        }
    }

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
                                class="space-x-2 bg-[#f18325] text-white py-1 px-2 rounded hover:bg-white hover:text-[#f18325] hover:border-[#f18325] border transition"
                            >
                                <i class="fas fa-link"></i> Link
                            </button>
                        </div>
                        <hr class="border-[#dee2e6]">
                    `);
                });

            }
        });
    });

    window.closeTravelingWithModal = function(){
        $('#travelingWithModal').addClass('hidden');

        $('#linked_customer').val('');

        $('#possible-reservations').html('');
    }
    // end of reservation travel with


    // start reservation dining notes
    window.openDiningInfoModal = function(){
        const $modal = $('#diningInfoModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Dining Note');

        const $form = $('#diningNoteForm');
        $form.attr('action', $form.data('store-url'));
        $('#dining_note_method').val('POST');

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#dining_note_id').val('');
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

        if (diningNote.dining_date) {
            const dd = new Date(diningNote.dining_date);

            const year = dd.getFullYear();
            const month = dd.getMonth() + 1;
            const day = dd.getDate();

            const $yearSelect = $modal.find('[x-model="year"]');
            const $monthSelect = $modal.find('[x-model="month"]');
            const $daySelect = $modal.find('[x-model="day"]');

            if ($yearSelect.length) $yearSelect.val(year).trigger('change');
            if ($monthSelect.length) $monthSelect.val(month).trigger('change');
            if ($daySelect.length) $daySelect.val(day).trigger('change');
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

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#gift_info_id').val('');
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

        if (gift.gift_date) {
            const gd = new Date(gift.gift_date);

            const year = gd.getFullYear();
            const month = gd.getMonth() + 1;
            const day = gd.getDate();

            const $yearSelect = $modal.find('[x-model="year"]');
            const $monthSelect = $modal.find('[x-model="month"]');
            const $daySelect = $modal.find('[x-model="day"]');

            if ($yearSelect.length) $yearSelect.val(year).trigger('change');
            if ($monthSelect.length) $monthSelect.val(month).trigger('change');
            if ($daySelect.length) $daySelect.val(day).trigger('change');
        }
    }

    window.closeGiftsModal = function(){
        $('#giftsModal').addClass('hidden');
    }
    // end reservation gift notes


    // start reservation phone notes
    window.openPhoneNotesModal = function(){
        const $modal = $('#phoneNotesModal');

        $modal.removeClass('hidden');
        $modal.find('h2').text('Add Phone Notes');

        const $form = $('#phoneForm');
        $form.attr('action', $form.data('store-url'));
        $('#phone_note_method').val('POST');

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#phone_note_id').val('');
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

        $modal.find('select').each(function () {
            $(this).val('');
        });

        $modal.find('input, textarea').each(function () {
            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else if ($(this).attr('type') !== 'hidden') {
                $(this).val('');
            }
        });

        $('#commission_fee_id').val('');
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

});
