$(document).ready(() => {
    $('#openDrawer').on('click', function () {
        $('#sidebarDrawer').removeClass('-translate-x-full');

        $('#categoryViewSection')
            .removeClass('grid-cols-1 xl:grid-cols-[100px_1fr]')
            .addClass('grid-cols-12');

        $('#categorySidebar')
            .removeClass('col-span-1')
            .addClass('col-span-12 xl:col-span-3');

        $('#categoryLayout')
            .addClass('col-span-12 xl:col-span-9');

        $('#categorySidebar > div > div').addClass('min-h-[900px]');
    });

    $('#closeDrawer').on('click', function () {
        $('#sidebarDrawer').addClass('-translate-x-full');

        setTimeout(function () {
            $('#categoryViewSection')
                .removeClass('grid-cols-12')
                .addClass('grid-cols-1 xl:grid-cols-[100px_1fr]');

            $('#categorySidebar')
                .removeClass('col-span-12 xl:col-span-3');

            $('#categoryLayout')
                .removeClass('col-span-12 xl:col-span-9');

            $('#categorySidebar > div > div').removeClass('min-h-[900px]');
        }, 100);
    });

    $("#flip1").click(function () {
        $(".panel").slideToggle(() => {
            const isOpen = $(".panel").is(":visible");

            // Use .children() instead of .find()
            if (isOpen) {
                $("#flip1").children(".fa-chevron-right").hide();
                $("#flip1").children(".fa-chevron-down").show();
            } else {
                $("#flip1").children(".fa-chevron-right").show();
                $("#flip1").children(".fa-chevron-down").hide();
            }
        });
    });
});

