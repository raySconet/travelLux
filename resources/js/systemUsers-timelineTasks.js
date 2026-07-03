import './bootstrap';

$(document).ready(() => {

    // ----------------------------------------------------------- //
    // start system users //
    // ----------------------------------------------------------- //

    // start email as username
    const emailInput = $('#email');
    const usernameInput = $('#username');
    const emailAsUsernameCheckbox = $('input[name="email_as_username"][value="1"]');

    function syncUsernameWithEmail() {

        if (emailAsUsernameCheckbox.is(':checked')) {

            usernameInput.val(emailInput.val());

        }

    }

    emailAsUsernameCheckbox.on('change', function () {

        if ($(this).is(':checked')) {

            syncUsernameWithEmail();

            usernameInput.prop('readonly', true);

        } else {

            usernameInput.prop('readonly', false);

        }

    });

    emailInput.on('input', function () {

        syncUsernameWithEmail();

    });

    if (emailAsUsernameCheckbox.is(':checked')) {

        usernameInput.prop('readonly', true);

        syncUsernameWithEmail();

    }
    // end email as username

    // start profile photo
    $('#editProfilePhoto').on('click', function () {

        $('#profile_photo').trigger('click');

    });

    $('#profile_photo').on('change', function (e) {

        const file = e.target.files[0];

        if (file) {

            const reader = new FileReader();

            reader.onload = function (event) {

                $('#profilePhotoPreview').attr('src', event.target.result);

            };

            reader.readAsDataURL(file);

        }

    });
    // end profile photo

    // ----------------------------------------------------------- //
    // end system users //
    // ----------------------------------------------------------- //

    // ----------------------------------------------------------- //
    // start timeline task //
    // ----------------------------------------------------------- //
    const $timelineProductSelect = $('#product_id');
    const $timelineDestinationSelect = $('#destination_id');

    if ($timelineProductSelect.length && $timelineDestinationSelect.length && !$('#resort_id').length && !$('#cruise_itinerary_id').length) {
        const allTimelineDestinationOptions = $timelineDestinationSelect.find('option').clone();
        const oldTimelineDestination = $timelineDestinationSelect.val();

        function filterTimelineDestinations() {
            const selectedProduct = $timelineProductSelect.val();

            const currentDestination = oldTimelineDestination || $timelineDestinationSelect.val();

            $timelineDestinationSelect.html('<option value="">--Select Destination--</option>');

            allTimelineDestinationOptions.each(function() {
                const $option = $(this);
                const productId = $option.data('product');

                if ($option.val() !== '' && productId !== undefined && productId.toString() === selectedProduct) {
                    $timelineDestinationSelect.append($option.clone());
                }
            });

            if (!$timelineDestinationSelect.find('option[value="' + currentDestination + '"]').length && currentDestination) {
                const oldOption = allTimelineDestinationOptions.filter(`[value="${currentDestination}"]`);
                $timelineDestinationSelect.append(oldOption.clone());
            }

            $timelineDestinationSelect.val(currentDestination);
        }

        $timelineProductSelect.on('change', function() {
            $timelineDestinationSelect.val('');
            filterTimelineDestinations();
        });

        filterTimelineDestinations();
    }
    // ----------------------------------------------------------- //
    // end timeline task //
    // ----------------------------------------------------------- //

});
