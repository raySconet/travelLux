$(document).ready(function() {
    var $expandButtons = $('.expand-btn');
    var $cards = $('.dashboard-card');
    var $grid = $('#dashboard-grid');

    $expandButtons.each(function() {
        $(this).on('click', function() {
            var $btn = $(this);
            var $card = $btn.closest('.dashboard-card');
            var isExpanded = $card.hasClass('expanded');

           
            $cards.each(function() {
                $(this).removeClass('expanded col-span-full hidden');
                $(this).find('.expand-btn').removeClass('fa-compress').addClass('fa-expand');
            });

            $grid.removeClass('lg:grid-cols-2');

            
            if (!isExpanded) {
                $cards.not($card).addClass('hidden');
                $card.addClass('expanded col-span-full');
                $btn.removeClass('fa-expand').addClass('fa-compress');
                $grid.addClass('grid-cols-1');
            } else {
                
                $grid.addClass('lg:grid-cols-2');
            }
        });
    });
});
