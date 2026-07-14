$(document).ready(function() {
    // start intake form
    $(document).on('click', '#customerIntakeFormSubmitBtn', function(e){

        e.preventDefault();

        customerIntakeFormSubmit();

    });

    function customerIntakeFormSubmit()
    {
        let encryption = $("#encryption").val();

        $("#customerIntakeFormContent input[type='text'], #customerIntakeFormContent input[type='email']").each(function () {
            $(this).attr("value", $(this).val());
        });

        $("#customerIntakeFormContent textarea").each(function () {
            $(this).text($(this).val());
        });

        $("#customerIntakeFormContent input[type='checkbox'], #customerIntakeFormContent input[type='radio']").each(function () {

            if ($(this).is(":checked")) {
                $(this).attr("checked", "checked");
            } else {
                $(this).removeAttr("checked");
            }

        });

        let intakeFormContentHTML = $("#customerIntakeFormContent").prop("outerHTML");

        $.ajax({

            url: "/customer/intake-form/submit",

            method: "POST",

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                encryption: encryption,
                intakeFormContentHTML: intakeFormContentHTML,

                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                passportsRadio: $("input[name='passportsRadio']:checked").val()
            },

            dataType:"JSON",

            beforeSend:function(){
                console.log("Submitting...");
            },


            success:function(response){

                if(response.flag == -1){

                    alert(response.message);

                }else{

                    $("body").html(response.html);

                }

            },


            error: function(xhr) {

                if (xhr.status === 422) {

                    $(".validation-error").html("");

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        $("#" + field + "-error").html(
                            '<p class="validation-error text-sm text-red-600">' + messages[0] + '</p>'
                        );
                    });

                } else {

                    alert("Failed To Submit Trip Inquiry Form. Please Try Again.");

                }
            }

        });

    }
    // end intake form
});
