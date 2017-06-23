Vue.component('pagination', {
    template: '<div class="form-group pagination m-l-lg">' +
    '<select v-model="currentItem" number @change="updatePagination" id="select_pagination" class="form-control minimal" name="pagination">' +
    '<option v-for="value in options"value="{{value}}">{{value}}</option>' +
    '</select>' +
    '</div>',

    // data: function () {
    //     return {
    //         options: [5, 10, 20, 25, 50],
    //         currentItem: 10,
    //         url: new Url
    //     }
    // },
    //
    // created: function () {
    //     this.currentItem = this.url.query.limit || 10;
    // },
    //
    // methods: {
    //     updatePagination: function () {
    //         this.url.query.limit = this.currentItem;
    //         window.location = this.url.toString();
    //     }
    // }
});

$(document).ready(function () {

    if ($("#select_pagination").length) {
        var url = new Url;

        $("#select_pagination").select2({
            minimumResultsForSearch: Infinity,
            data: [
                {id: '5', text: '5'},
                {id: '10', text: '10'},
                {id: '20', text: '20'},
                {id: '25', text: '25'},
                {id: '50', text: '50'},
            ],
            val: '10'
        }).on("select2:select", function (e) {
            url.query.limit = $("#select_pagination").val();
            window.location = url.toString();

        }).val(url.query.limit || "10").trigger("change");
    }
});
