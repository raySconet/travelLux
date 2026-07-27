$(document).ready(function() {
    // start customers/reservations form
    $("#customerForm").on("submit", function (e) {

        e.preventDefault();

        customerFormSubmit();

    });

    window.customerFormSubmit = function () {

        let token = $("#token").val();

        let previewFormHtml = $("#formItemsParentContainer").html();

        $.ajax({

            url: "/customer/form/submit",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                token: token,
                previewFormHtml: previewFormHtml
            },

        success: function (data) {
                if (data.flag == -1) {

                    alert(data.message);

                    return;

                }
                $("body").html(data.html);

            },

            error: function (xhr) {

                alert(xhr.responseJSON.message);

            }

        });

    }
    // end customers/reservations form
});
