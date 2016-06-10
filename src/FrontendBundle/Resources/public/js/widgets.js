$(document).ready(function () {
    //  select2 scripts for batch actions combobox
    $(".select2").select2();

    // tooltips
    $('[data-toggle="tooltip"]').tooltip();

    $('#myCarousel').carousel({
        interval: 1000000
    })

    $('.carousel .item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }

        next.children(':first-child').clone().appendTo($(this));

        for (var i = 0; i < 2; i++) {
            next = next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });
});