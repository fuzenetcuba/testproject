$(document).ready(function () {
    $('.hide-filters').on('click', function () {
        console.log('toggle view');
        $('.filter-deals').toggleClass('collapsed');
    })
});

// var cbpAnimatedHeader = (function () {
//     var docElem = document.documentElement,
//         header = document.querySelector('.navbar-default'),
//         didScroll = false,
//         changeHeaderOn = 200;
//
//     function init() {
//         window.addEventListener('scroll', function (event) {
//             if (!didScroll) {
//                 didScroll = true;
//                 setTimeout(scrollPage, 250);
//             }
//         }, false);
//     }
//
//     function scrollPage() {
//         var sy = scrollY();
//         if (sy >= changeHeaderOn) {
//             $(header).addClass('navbar-scroll')
//         }
//         else {
//             $(header).removeClass('navbar-scroll')
//         }
//         didScroll = false;
//     }
//
//     function scrollY() {
//         return window.pageYOffset || docElem.scrollTop;
//     }
//
//     init();
//
// })();

// Activate WOW.js plugin for animation on scrol
new WOW().init();