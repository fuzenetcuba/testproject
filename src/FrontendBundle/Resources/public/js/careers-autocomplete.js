// ES6 template string style
Vue.config.delimiters = ['${', '}'];

Vue.http.options.emulateJSON = true;

var vm = new Vue({
    el: '.careers',
    data: {
        opening_categories: [],
        businesses: [],

        currentOpeningCategory: '-1',
        currentBusiness: '-1',

        loading: false,
    },

    methods: {
        fetchData: function (target) {
            var formData = new FormData(document.forms[1]);
            var input = '';

            formData.append('categories', this.currentOpeningCategory);
            formData.append('business', this.currentBusiness);

            if (target.name.match(/business/)) {
                input = 'business';
            } else {
                input = 'categories';
            }

            formData.append(input, target.value);

            this.loading = true;

            this.$http.post(
                Routing.generate('careers_autocomplete'),
                formData
            ).then(function (response) {
                this.businesses = JSON.parse(response.body.business);
                this.opening_categories = JSON.parse(response.body.categories);
                this.loading = false;
            });
        }
    },

    created: function() {
        var formData = new FormData();

        this.$http.post(
            Routing.generate('careers_autocomplete'),
            formData
        ).then(function (response) {
            this.businesses = JSON.parse(response.body.business);
            this.opening_categories = JSON.parse(response.body.categories);
        });
    }
});