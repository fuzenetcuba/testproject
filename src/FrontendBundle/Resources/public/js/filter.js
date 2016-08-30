/*
 * Fires callback when a user has finished typing. This is determined by the time elapsed
 * since the last keystroke and timeout parameter or the blur event--whichever comes first.
 *
 * @callback: function to be called when even triggers
 * @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
 * caused by blur.
 */
;(function ($) {
    $.fn.extend({
        doneTyping: function (callback, timeout) {
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function (el) {
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function (i, el) {
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('keyup keypress paste', function (e) {
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type == 'keyup' && e.keyCode != 8) return;

                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function () {
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur', function () {
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

$(document).ready(function () {
    $('.hide-filters').on('click', function () {
        console.log('toggle view');
        $('.filter-deals').toggleClass('collapsed');
    });
    $('.show-filters').on('click', function () {
        console.log('toggle view');
        $('.filter-deals').toggleClass('collapsed');
    });

    $(window).resize(function () {
        if ($(this).width() >= 1200) {
            filterWideScreen();
        } else {
            filterNormalScreen();
        }
    });

    if ($(window).width() >= 1200) {
        filterWideScreen();
    } else {
        filterNormalScreen();
    }

    function filterWideScreen() {
        $('.filter-deals').removeClass('collapsed');
        $('#filter-item-list').addClass('filter-item-list-collapsed');

        $('.hide-filters-action').on('click', function () {
            console.log('toggle view');
            $('.filter-deals').removeClass('collapsed');
        });
    }

    function filterNormalScreen() {
        $('.filter-deals').addClass('collapsed');
        $('#filter-item-list').removeClass('filter-item-list-collapsed');

        $('.hide-filters-action').on('click', function () {
            console.log('toggle view');
            $('.filter-deals').addClass('collapsed');
        });
    }

    updateDealRow = function (selector, data) {
        var dom = $('<div/>').html(data);

        $(document).find(selector).html(
            dom.find(selector).html()
        );
    };

    $('.filter-deals form').submit(function (event) {
        event.preventDefault();

        if ($(window).width() >= 1200) {
            $('.filter-deals').removeClass('collapsed');
        } else {
            $('.filter-deals').addClass('collapsed');
        }

        var form = $(this);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (data) {
            updateDealRow('div.row.deal-list', data);
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
            updateDealRow('div.row.deal-list', data);
        })
    });

    $('.filter-deals form select').on('change', function () {
        $(this).parent().parent().submit();
    });

    $('.filter-deals input[type="search"]').doneTyping(function () {
        $(this).parent().parent().submit();
    }, 300);
});