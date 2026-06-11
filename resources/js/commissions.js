$(document).ready(function() {
    window.openEditReservationCheckWriterModal = function() {
        $('#editReservationCheckWriterModal').removeClass('hidden');
    }

    window.closeEditReservationCheckWriterModal = function() {
        $('#editReservationCheckWriterModal').addClass('hidden');
    }

    window.openAddAgentReservationModal = function(){
        $('#addAgentReservationModal').removeClass('hidden');
    }

    window.closeAddAgentReservationModal = function(){
        $('#addAgentReservationModal').addClass('hidden');
    }

    window.openAddUnknownReservationModal = function(){
        $('#addUnknownReservationModal').removeClass('hidden');
    }

    window.closeAddUnknownReservationModal = function(){
        $('#addUnknownReservationModal').addClass('hidden');
    }
});
