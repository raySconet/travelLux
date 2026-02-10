$(document).ready(function() {
    window.openEditReservationModal = function() {
        $('#editReservationModal').removeClass('hidden');
    }

    window.closeEditReservationModal = function() {
        $('#editReservationModal').addClass('hidden');
    }

    window.openReportDateRangeModal = function() {
        $('#reportRangeModal').removeClass('hidden');
    }

    window.closeReportDateRangeModal = function() {
        $('#reportRangeModal').addClass('hidden');
    }

});
