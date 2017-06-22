Vue.component('pagination', {
    template: '<div class="m-l-lg"><select v-model="currentItem" number @change="updatePagination" class="minimal" name="pagination"><option v-for="value in options"value="{{value}}">{{value}}</option></select></div>',

    data: function () {
        return {
            options: [5, 10, 20, 25, 50],
            currentItem: 10,
            url: new Url
        }
    },

    created: function () {
        this.currentItem = this.url.query.limit || 10;
    },

    methods: {
        updatePagination: function () {
            this.url.query.limit = this.currentItem;
            window.location = this.url.toString();
        }
    }
})