import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
$(document).ready(() => {

    // flatpickr(".datetimepicker", {
    //     enableTime: true,
    //     dateFormat: "m-d-Y H:i",
    //     time_24hr: true,
    //     // defaultDate: new Date(),
    // });

    $(document).ajaxStart(function () {
        $('#ajaxLoader').fadeIn();
    });

    $(document).ajaxStop(function () {
        $('#ajaxLoader').fadeOut(300);
    });




         const $modal = $('#insuranceModal');
    const $form = $('#insuranceForm');
    const $modalTitle = $('#modalTitle');

    // Open Add Insurance modal
    $('#addInsuranceBtn').click(function() {
        $form[0].reset();
        $('#insuranceId').val('');
        $modalTitle.text('Add Insurance');
        $modal.removeClass('hidden');
    });

    // Close modal
    $('#closeModal').click(function() {
        $modal.addClass('hidden');
    });

    // Edit button
    $('.editBtn').click(function() {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');

        $('#insuranceId').val(id);
        $form.find('input[name="insurance_name"]').val($tr.find('td:eq(0)').text());
        $form.find('input[name="claim_number"]').val($tr.find('td:eq(1)').text());
        $form.find('input[name="bi_adjuster"]').val($tr.find('td:eq(2)').text());
        $form.find('input[name="tel"]').val($tr.find('td:eq(3)').text());
        $form.find('input[name="email"]').val($tr.find('td:eq(4)').text());
        $form.find('input[name="fax"]').val($tr.find('td:eq(5)').text());

        $modalTitle.text('Edit Insurance');
        $modal.removeClass('hidden');
    });



    // Submit form via AJAX
    $form.submit(function(e) {
        e.preventDefault();

        const id = $('#insuranceId').val();
        let url = $form.attr('action'); // default store URL
        if(id) {
            url = `/insurance/${id}`; // edit URL
        }

        $.ajax({
            url: url,
            type: id ? 'PUT' : 'POST', // PUT for edit, POST for store
            data: $form.serialize(),
            success: function(response) {
                // Redirect to Insurance list page on success
                window.location.href = "/insurance";
            },
            error: function(xhr) {
                alert('Something went wrong. Please check your inputs.');
                console.log(xhr.responseText);
            }
        });
    });

    $('.deleteBtn').click(function() {
        const $tr = $(this).closest('tr');
        const id = $(this).data('id');

        if(confirm('Are you sure you want to delete this insurance?')) {
            $.ajax({
                url: `/insurance/${id}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content')}, // Laravel CSRF token
                success: function(response) {
                    if(response.success) {
                        $tr.remove(); // remove row from table
                    }
                },
                error: function(xhr) {
                    alert('Failed to delete insurance.');
                    console.log(xhr.responseText);
                }
            });
        }
    });
});
