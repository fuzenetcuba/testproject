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
        adult: null,
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
        legal: null,
        crime: null,
        crimeExplain: '',
        background: null,
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
                return /pdf/.test(val.substr(val.lastIndexOf('.') + 1).toLowerCase());
            }

            return true;
        },

        lower: function (val, result) {
            return result;
        }
    },

    watch: {
        'availabilityHours' : function(val, oldVal) {
            if ('all' == val) {
                this.weekHours =  {
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
                };
            }
        }
    },

    computed: {
        coverFileName: function () {
            return this.cover.replace('C:\\fakepath\\', '');
        },

        cvFileName: function () {
            return this.cv.replace('C:\\fakepath\\', '');
        },

        mondayHasValue: function() {
            return this.weekHours.monday.from !== null;
        },

        mondayValid: function() {
            var diff = this.checkRange(this.weekHours.monday.from, this.weekHours.monday.to);

            // console.log(diff);

            // false - error
            // true - ok
            return diff >= 0;
        },

        tuesdayValid: function () {
            return this.checkRange(this.weekHours.tuesday.from, this.weekHours.tuesday.to) >= 0;
        },

        wednesdayValid: function () {
            return this.checkRange(this.weekHours.wednesday.from, this.weekHours.wednesday.to) >= 0;
        },

        thursdayValid: function () {
            return this.checkRange(this.weekHours.thursday.from, this.weekHours.thursday.to) >= 0;
        },

        fridayValid: function () {
            return this.checkRange(this.weekHours.friday.from, this.weekHours.friday.to) >= 0;
        },

        saturdayValid: function () {
            return this.checkRange(this.weekHours.saturday.from, this.weekHours.saturday.to) >= 0;
        },

        sundayValid: function () {
            return this.checkRange(this.weekHours.sunday.from, this.weekHours.sunday.to) >= 0;
        }
    },

    methods: {
        checkRange: function (d1, d2) {
            // console.log("d1: " + d1);
            // console.log("d2: " + d2);
            if (d1 == null || d2 == null) {
                // console.log("no diff");
                return 0;
            } else {
                var start = moment(new Date('1/1/1 ' + d1));
                var end = moment(new Date('1/1/1 ' + d2));

                return moment.duration(end.diff(start))._milliseconds;
            }
        },

        customMethod: function(employer) {
            var parts = employer.from.split('/');
            var start = moment(new Date(parts[2], parts[1] - 1, parts[0]));

            var parts = employer.to.split('/');
            var end = moment(new Date(parts[2], parts[1] - 1, parts[0]));

            console.log(moment.duration(end.diff(start))._milliseconds);

            return moment.duration(end.diff(start))._milliseconds > 0;
        },

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
                this.done = true;
            }).catch(function (error) {
                // 422 - unprocessable entity
                this.error = true;
                this.errorMessage = error.body.error;
            });
        }
    }
});