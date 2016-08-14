$(document).ready(function () {
    $('.hide-filters').on('click', function () {
        console.log('toggle view');
        $('.filter-deals').toggleClass('collapsed');
    });

    $('.filter-deals form').submit(function (event) {
        event.preventDefault();
        var form = $(this);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (data) {
            var dom = $('<div/>').html(data);

            $(document).find('div.row.deal-list').html(
                dom.find('div.row.deal-list').html()
            );
        });
    });

    $('.filter-deals label').on('click', function (event) {
        var self = $(this);
        var order = self.find('input').val();

        $.ajax({
            type: 'GET',
            url: /deals/.test(window.location) ? Routing.generate('deals', {'order': order}) :
                Routing.generate('business_list', {'order': order}),
        }).done(function (data) {
            var dom = $('<div/>').html(data);

            $(document).find('div.row.deal-list').html(
                dom.find('div.row.deal-list').html()
            );
        })
    });

    $('.filter-deals form select').on('change', function () {
        $(this).parent().parent().submit();
    })
});

var cbpAnimatedHeader = (function () {
    var docElem = document.documentElement,
        header = document.querySelector('.navbar-default'),
        didScroll = false,
        changeHeaderOn = 200;

    function init() {
        window.addEventListener('scroll', function (event) {
            if (!didScroll) {
                didScroll = true;
                setTimeout(scrollPage, 250);
            }
        }, false);
    }

    function scrollPage() {
        var sy = scrollY();
        if (sy >= changeHeaderOn) {
            $(header).addClass('navbar-scroll')
        }
        else {
            $(header).removeClass('navbar-scroll')
        }
        didScroll = false;
    }

    function scrollY() {
        return window.pageYOffset || docElem.scrollTop;
    }

    init();

})();

// Activate WOW.js plugin for animation on scrol
new WOW().init();