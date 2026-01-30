$(document).ready(function() {
    window.openVendorModal = function(productName) {
        $('#vendorModal').removeClass('hidden');
    }

    window.closeVendorModal = function() {
        $('#vendorModal').addClass('hidden');
    }
});
