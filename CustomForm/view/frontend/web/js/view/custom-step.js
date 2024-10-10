define([
    'ko',
    'jquery',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'mage/url',
    'mage/storage'
], function (ko, $, Component, _, stepNavigator, urlBuilder, storage) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Kitchen365_CustomForm/custom-step'
        },

        isVisible: ko.observable(true),

        initialize: function () {
            this._super();

            stepNavigator.registerStep(
                'step_code',
                null,
                'Additional Info',
                this.isVisible,
                _.bind(this.navigate, this),
                15
            );

            return this;
        },

        navigate: function () {
            this.isVisible(true);
        },

        navigateToNextStep: function () { 
            var formData = {
                how_many_years_business: $('#how_many_years_business').val(),
                how_did_you_hear_about_us: $('#how_did_you_hear_about_us').val(),
                has_showroom: $('#has_showroom').val(),
                has_sales_rep: $('#has_sales_rep').val(),
                sales_rep_name: $('#sales_rep_name').val()
            };

            if (this.validateForm(formData)) { 
                var saveUrl = urlBuilder.build('customform/index/save');  
                storage.post(
                    saveUrl,
                    JSON.stringify(formData),
                    false
                ).done(function (response) {
                    if (response.success) { 
                        stepNavigator.next();
                    } else {
                        $('#form-error-message').text('Fail to save the data. Please try again.');
                    }
                }).fail(function () {
                    $('#form-error-message').text('Failed to save the data. Please try again.');
                });
            }
        },

        validateForm: function (formData) {
            var isValid = true;
            var errorMessage = '';
 
            $('#form-error-message').text('');
 
            if (!/^\d+$/.test(formData.how_many_years_business)) {
                errorMessage += 'Please enter only digits for "How Many Years in Business".<br>';
                isValid = false;
            }
 
            if (!/^[a-zA-Z\s]+$/.test(formData.how_did_you_hear_about_us)) {
                errorMessage += 'Please enter only letters for "How Did You Hear About Us".<br>';
                isValid = false;
            }
 
            if (!/^[a-zA-Z\s]+$/.test(formData.sales_rep_name)) {
                errorMessage += 'Please enter only letters for "Sales Rep Name".<br>';
                isValid = false;
            }
 
            if (!formData.has_showroom || !formData.has_sales_rep || !formData.how_many_years_business || !formData.how_did_you_hear_about_us || !formData.sales_rep_name) {
                errorMessage += 'Please fill in all required fields.<br>';
                isValid = false;
            }
 
            if (!isValid) {
                $('#form-error-message').html(errorMessage);
            }

            return isValid;
        }
    });
});
