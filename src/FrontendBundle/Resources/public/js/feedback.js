Vue.component('star-rating', {

  props: {
    'name': String,
    'value': null,
    'id': String,
    'disabled': Boolean,
    'required': Boolean
  },

  template: '<div class="star-rating">\
        <label class="star-rating__star" v-for="rating in ratings" \
        :class="{\'is-selected\': ((value >= rating) && value != null), \'is-disabled\': disabled}" \
        v-on:click="set(rating)" v-on:mouseover="star_over(rating)" v-on:mouseout="star_out">\
        <input class="star-rating star-rating__checkbox" type="radio" :value="rating" :name="name" \
        v-model="value" :disabled="disabled">☆</label></div>',

  /*
   * Initial state of the component's data.
   */
  data: function() {
    return {
      temp_value: null,
      ratings: [1, 2, 3, 4, 5]
    };
  },

  methods: {
    /*
     * Behaviour of the stars on mouseover.
     */
    star_over: function(index) {
      var self = this;

      if (!this.disabled) {
        this.temp_value = this.value;
        return this.value = index;
      }

    },

    /*
     * Behaviour of the stars on mouseout.
     */
    star_out: function() {
      var self = this;

      if (!this.disabled) {
        return this.value = this.temp_value;
      }
    },

    /*
     * Set the rating of the score
     */
    set: function(value) {
      var self = this;

      if (!this.disabled) {
        // Make some call to a Laravel API using Vue.Resource
        
        this.temp_value = value;
        return this.value = value;
      }
    }
  }
});

window.Vue.component('radio', {
  props: {
    name: {
      type: String,
      required: false
    },
    className: {
      type: String,
      required: false
    },
    id: {
      type: String,
      required: false
    },
    value: {
      type: String,
      required: false
    },
    required: {
      type: Boolean,
      required: false,
      default: false
    },
    checked: {
      type: Boolean,
      required: false,
      default: false
    },
    label: {
      type: String,
      required: true
    },
    inverted: {
      type: Boolean,
      required: false,
      default: false
    },
  },
  methods: {
    updateInput: function(event) {
      this.$emit('input', event.target.value);
    }
  },
  template: "<div class=\"custom-radio\" v-bind:class=\"{ inverted: inverted }\"><label v-bind:for=\"id\"><input type=\"radio\" v-bind:name=\"name\" v-bind:class=\"className\" v-bind:id=\"id\" v-bind:checked=\"checked\" v-bind:value=\"value\" v-bind:required=\"required\" v-on:change=\"updateInput\">{{ label }}</label></div>"
});

var vm = new Vue({
    el: "#feedback-section",

    data: {
        done: false,

        form: {
            satisfied: true,
            recommend: true,
            recommendExplain: '',
            stores: false,
            storesExplain: '',
            food: false,
            foodExplain: '',
            rate: 3,
            contact: false,
            name: '',
            email: '',
            phone: '',
        }
    },

    methods: {
        submitForm: function() {
            var formData = new FormData(); // empty data form

            for (var item in this.form) {
                formData.append(item, this.form[item]);
            }

            this.$http.post(
                Routing.generate('survey_store'),
                formData
            ).then(function (response) {
                this.done = true;
            }).catch(function (error) {
                // 422 - unprocessable entity
                this.error = true;
                this.errorMessage = error.body.error;
            });
        }
    }
    
});