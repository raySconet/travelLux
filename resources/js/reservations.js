$(document).ready(function() {
    window.openReservationsTasksModal = function() {
        $('#reservationsTasksModal').removeClass('hidden');
    }

    window.closeReservationsTasksModal = function() {
        $('#reservationsTasksModal').addClass('hidden');
    }

    window.openReservationPaymentsModal = function(){
        $('#reservationPaymentsModal').removeClass('hidden');
    }

    window.closeReservationPaymentsModal = function(){
        $('#reservationPaymentsModal').addClass('hidden');
    }

    window.openTravelingWithModal = function(){
        $('#travelingWithModal').removeClass('hidden');
    }

    window.closeTravelingWithModal = function(){
        $('#travelingWithModal').addClass('hidden');
    }

    window.openDiningInfoModal = function(){
        $('#diningInfoModal').removeClass('hidden');
    }

    window.closeDiningInfoModal = function(){
        $('#diningInfoModal').addClass('hidden');
    }

    window.openGiftsModal = function(){
        $('#giftsModal').removeClass('hidden');
    }

    window.closeGiftsModal = function(){
        $('#giftsModal').addClass('hidden');
    }

    window.openPhoneNotesModal = function(){
        $('#phoneNotesModal').removeClass('hidden');
    }

    window.closePhoneNotesModal = function(){
        $('#phoneNotesModal').addClass('hidden');
    }

    window.openCommissionFeesModal = function(){
        $('#commissionFeesModal').removeClass('hidden')
    }

    window.closeCommissionFeesModal = function(){
        $('#commissionFeesModal').addClass('hidden');
    }

    window.openReservationAddCustomerModal = function(){
        $('#reservationAddCustomerModal').removeClass('hidden');
    }

    window.closeReservationAddCustomerModal = function(){
        $('#reservationAddCustomerModal').addClass('hidden');
    }

    window.openAttentionModal = function(){
        $('#attentionReservationModal').removeClass('hidden');
    }

    window.closeAttentionModal = function(){
        $('#attentionReservationModal').addClass('hidden');
    }

    window.handleBulkDelete = function() {

        const checked = $('.reservation-checkbox:checked');

        if (checked.length === 0) {
            openAttentionModal();
            return;
        }

        $('#bulkDeleteForm').submit();
    };
    
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
});
