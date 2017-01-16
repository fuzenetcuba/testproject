'use strict';

// ES6 template string style
Vue.config.delimiters = ['${', '}'];

Vue.http.options.emulateJSON = true;

/**
 * Initialize Masker
 */
var Masker = {
    flags: {
        '9': /\d/, // any digit
        'A': /[A-Z]/, // any uppercase letter
        'a': /[a-z]/, // any lowercase letter
        'C': /[A-z0-9]/, // any alphanumeric character
        'c': /[A-z]/ // any letter
    }
};

/**
 * Parse input against format
 * @param  {String} input Text from input field
 * @param  {String} mask  Mask input from v-mask
 * @return {String}       Masked input
 */
Masker.parse = function (input, mask) {
    var o = '';

    for (var i = 0, x = 1; x && i < mask.length; i++) {
        var c = input.charAt(i);
        var m = mask.charAt(i);

        if (typeof this.flags[m] === 'undefined') o += m; else if (this.flags[m].test(c)) o += c; else x = 0;
    }

    return o;
};

Vue.directive('mask', {
    /**
     * Bind Masker.parse() on @input of attached v-mask $el
     */
    bind: function bind() {
        this.handler = function () {
            this.el.value = Masker.parse(this.el.value, this.expression);
        }.bind(this);

        this.el.addEventListener('input', this.handler);
    },


    /**
     * Remove Masker.parse() bind on @input of attached v-mask $el
     */
    unbind: function unbind() {
        this.el.removeEventListener('input', this.handler);
    }
});

var vm = new Vue({
    el: '#apply-form',

    data: {
        lastName: '',
        firstName: '',
        middleName: '',
        address: '',
        city: '',
        state: '',
        zipCode: '',
        securityNumber: '',
        adult: 0,
        availability: 'full',
        availabilityHours: 'all',
        weekHours: {
            monday: {
                from: null,
                to: null
            },
            tuesday: {
                from: null,
                to: null
            },
            wednesday: {
                from: null,
                to: null
            },
            thursday: {
                from: null,
                to: null
            },
            friday: {
                from: null,
                to: null
            },
            saturday: {
                from: null,
                to: null
            },
            sunday: {
                from: null,
                to: null
            }
        },
        startDate: '',
        salary: '',
        phone: '',
        hasDriverLicense: false,
        licenseNumber: '',
        licenseState: '',
        licenseExpiration: '',
        legal: true,
        crime: false,
        crimeExplain: '',
        background: true,
        backExplain: '',
        highschoolYears: 0,
        diploma: false,
        ged: false,
        schools: [
            {
                name: '',
                state: '',
            },
            {
                name: '',
                state: '',
            }
        ],
        collegeYears: 0,
        collegeSchool: '',
        collegeCity: '',
        major: '',
        degree: '',
        gpa: '',
        contactEmployer: true,
        nameForEmployer: '',
        employers: [
            {
                name: '',
                address: '',
                phone: '',
                position: '',
                from: '',
                to: '',
                mode: 'full',
                hours: '',
                salary: '',
                supervisor: '',
                department: '',
                leavingReason: '',
                duties: ''
            }
        ],
        skills: '',
        bestFit: '',
        references: [
            {
                name: '',
                phone: '',
                profesional: true
            }
        ],
        cv: '',
        cover: '',

        // application state
        done: false,
        formApplication: true,
        error: false,
        errorMessage: ''
    },

    validators: {
        pdf: function (val) {
            if ("" !== val.trim()) {
                return /pdf/.test(val.substr(val.lastIndexOf('.')+1).toLowerCase());
            }
            
            return true;
        },

        dateLowerThan: function (val) {
            console.log(val);
            return true;
        },

        dataGreaterThan: function (val) {
            return true;
        }
    },

    computed: {
        coverFileName: function () {
            return this.cover.replace('C:\\fakepath\\', '');
        },

        cvFileName: function () {
            return this.cv.replace('C:\\fakepath\\', '');
        }
    },

    methods: {
        addEmployer: function () {
            this.employers.push({
                name: '',
                address: '',
                phone: '',
                position: '',
                from: '',
                to: '',
                mode: 'full',
                hours: '',
                salary: '',
                supervisor: '',
                department: '',
                leavingReason: '',
                duties: ''
            });
        },

        removeEmployer: function () {
            this.employers.pop();
        },

        addReference: function () {
            this.references.push({
                name: '',
                phone: '',
                profesional: true
            })
        },

        removeReference: function () {
            this.references.pop();
        },

        submitForm: function () {
            var formData = new FormData(document.forms[1]);

            formData.append('lastName', this.lastName);
            formData.append('firstName', this.firstName);
            formData.append('middleName', this.middleName);
            formData.append('address', this.address);
            formData.append('city', this.city);
            formData.append('state', this.state);
            formData.append('zipCode', this.zipCode);
            formData.append('securityNumber', this.securityNumber);
            formData.append('adult', (this.adult ? 1 : 0));
            formData.append('phone', this.phone);
            formData.append('availability', this.availability);
            formData.append('availabilityHours', this.availabilityHours);
            formData.append('weekHours', JSON.stringify(this.weekHours));
            formData.append('startDate', this.startDate);
            formData.append('salary', this.salary);
            formData.append('hasDriverLicense', this.hasDriverLicense);
            formData.append('licenseNumber', this.licenseNumber);
            formData.append('licenseState', this.licenseState);
            formData.append('licenseExpiration', this.licenseExpiration);
            formData.append('legal', this.legal);
            formData.append('crime', this.crime);
            formData.append('crimeExplain', this.crimeExplain);
            formData.append('background', true);
            formData.append('backExplain', this.backExplain);
            formData.append('highschoolYears', this.highschoolYears);
            formData.append('diploma', this.diploma);
            formData.append('ged', this.ged);
            formData.append('schools', JSON.stringify(this.schools));
            formData.append('collegeYears', this.collegeYears);
            formData.append('collegeSchool', this.collegeSchool);
            formData.append('collegeCity', this.collegeCity);
            formData.append('major', this.major);
            formData.append('degree', this.degree);
            formData.append('gpa', this.gpa);
            formData.append('contactEmployer', this.contactEmployer);
            formData.append('nameForEmployer', this.nameForEmployer);
            formData.append('employers', JSON.stringify(this.employers));
            formData.append('skills', this.skills);
            formData.append('bestFit', this.bestFit);
            formData.append('references', JSON.stringify(this.references));
            formData.append('slug', document.querySelector('meta[name="slug"]').content);

            this.$http.post(
                Routing.generate('careers_store'),
                formData
            ).then(function (response) {
                if (response.body.error) {
                    this.error = true;
                    this.errorMessage = response.body.error;
                } else {
                    this.done = true;
                }
            });
        }
    }
});