$(document).ready(function() {
    window.openVendorModal = function(product) {
        $('#vendorModal').removeClass('hidden');

        $('#product_name').text(product.product_name ?? '');
        $('#vendor_bdm').val(product.vendor_bdm ?? '');
        $('#bdm_phone_number').val(product.bdm_phone_number ?? '');
        $('#bdm_email').val(product.bdm_email ?? '');
        $('#notes').val(product.notes ?? '');

        $('#vendorForm').attr('action', '/vendor-list/' + product.id);
    }

    window.closeVendorModal = function() {
        $('#vendorModal').addClass('hidden');
    }
});
