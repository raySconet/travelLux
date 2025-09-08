$(document).ready(() => {
    // Add Edit Buttons
    $(document).on('click', '.addClientInfoButton', function() {

        let $container = $(this).parent().parent();
        let $newItem = $container.clone(); // duplicate first
        $(this).removeClass("addClientInfoButton bg-[#14548d]")
            .addClass("removeClientInfoButton bg-[#a51a1a]")
            .children()
            .removeClass("fa-plus")
            .addClass("fa-minus");
        $(".appendDuplicates").append($newItem); // add to end
    });

    $(document).on('click', '.removeClientInfoButton', function() {
        let $btn = $('.removeClientInfoButton');
        // disable button temporarily
        $btn.prop('disabled', true);
        $(this).parent().parent().remove();

        // enable button again after 1 second
        setTimeout(() => {
            $btn.prop('disabled', false);
        }, 500);
    });
    // End Add Edit Buttons


})
