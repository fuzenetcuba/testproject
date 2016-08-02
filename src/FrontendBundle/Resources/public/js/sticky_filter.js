$(document).ready(function () {
});

var cbpAnimatedFilter = (function () {
    var docElem = document.documentElement,
        header = document.querySelector('.filter-deals'),
        didScroll = false,
        changeHeaderOn = 100;

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
            $(header).addClass('filter-fixed-top')
        }
        else {
            $(header).removeClass('filter-fixed-top')
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