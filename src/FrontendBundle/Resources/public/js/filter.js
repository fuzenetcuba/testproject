$(document).ready(function () {
    $('.hide-filters').on('click', function () {
        console.log('toggle view');
        $('.filter-deals').toggleClass('collapsed');
    });

    updateDealRow = function (selector, data) {
        var dom = $('<div/>').html(data);

        $(document).find(selector).html(
            dom.find(selector).html()
        );
    }

    $('.filter-deals form').submit(function (event) {
        event.preventDefault();
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
    })

    $('.filter-deals form select').on('change', function () {
        $(this).parent().parent().submit();
    })
});