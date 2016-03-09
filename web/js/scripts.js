/*
 Project: BardisCMS
 Authors: George Bardis
 */

// Create a closure to maintain scope of the '$' and CMS
(function (CMS, $, window, document, undefined) {

    'use strict';

    $(function () {
        CMS.Config.init();
    });

    CMS.Config = {
        $body: $(document.body),
        init: function () {

            CMS.foundationConfig.init();
            CMS.UI.init();

            CMS.windowResize.init();

            if (CMS.Supports.touch) {
                CMS.touch.init();
            }

            if (CMS.environment.isMobile()) {
                CMS.mobileSpecific.init();
            }

            $(window).load(function () {

            });
        }
    };

    CMS.foundationConfig = {
        init: function () {

            // Start the foundation Plugins Configuration
            $(document).foundation({
                reveal: {
                    animation: 'fadeAndPop',
                    animation_speed: 350,
                    close_on_background_click: true,
                    dismiss_modal_class: 'close-reveal-modal',
                    bg_class: 'reveal-modal-bg'
                },
                orbit: {
                    animation: 'fade',
                    timer_speed: 8000,
                    pause_on_hover: true,
                    resume_on_mouseout: false,
                    animation_speed: 700,
                    stack_on_small: false,
                    navigation_arrows: true,
                    slide_number: false,
                    bullets: true,
                    timer: false,
                    variable_height: false
                },
                dropdown: {
                },
                offcanvas: {
                    open_method: 'move', // Sets method in which offcanvas opens, can also be 'overlap'
                    close_on_click: true
                }
            });
        }
    };

    CMS.UI = {
        init: function () {
            // Start the AJAX based forms
            CMS.Forms.ajaxSubmittedForm('#contactform', '#contactFormBtn', 'json', true, true);
            CMS.Forms.ajaxSubmittedForm('#add_comment_form', '#submitCommentBtn', 'json', true, true);

            // Start the Ajax based sonata user forms
            CMS.Forms.ajaxSubmittedForm('#fos_user_registration_form', '#userRegisterFormBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_generic_details_form', '#userGenericDetailsFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_contact_details_form', '#userContactDetailsFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_account_preferences_form', '#userAccountPreferencesFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_change_password_form', '#userPasswordFormBtn', 'json', true, true);
            CMS.Forms.ajaxSubmittedForm('#fos_user_resetting_request', '#userResetPasswordFormBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#fos_user_resetting_form', '#userRessetingFormBtn', 'json', false, false);

            // Start the datepicker
            CMS.Forms.datepicker();

            // Setup the filters for the filter search page
            CMS.Forms.setupFilters();
        }
    };

    CMS.Forms = {
        $datepickerInputs: $(".datepickerField"),
        setupFilters: function () {
            $('#resetFilters').change(function () {
                var checkboxes = $(this).closest('form').find(':checkbox').not(this);
                checkboxes.removeAttr('checked');
            });
        },
        ajaxSubmittedForm: function (formId, formSubmitBtnId, dataType, overrideSuccess, resetForm) {

            var formElement = $(formId);
            var btnElement = $(formSubmitBtnId);

            if (formElement.length > 0) {

                btnElement.on('click', function (e) {
                    e.preventDefault();

                    btnElement.prop('disabled', true);

                    var formData = formElement.serializeArray();
                    formData.push({name: "isAjax", value: "true"});

                    var formAction = formElement.attr("action");

                    // Submit the form data
                    var $formAjaxRequest = $.post(formAction, formData, null, dataType);

                    // Always act when Ajax call is complete
                    $formAjaxRequest.always(function() {
                        btnElement.prop('disabled', false);
                    });

                    // Handle the successfull JSON response
                    $formAjaxRequest.done(function (responseData) {
                        $(".formError").remove();
                        $(".formSuccess").remove();
                        $("label.error").removeClass('error');
                        $("input.error").removeClass('error');
                        $("select.error").removeClass('error');
                        $("textarea.error").removeClass('error');

                        if (responseData.hasErrors === false) {
                            if(overrideSuccess){
                                if(resetForm){
                                    formElement.trigger("reset");
                                }
                                if(responseData.formMessage && responseData.formMessage !== ''){
                                    $('<small class="formSuccess alert-box success">' + responseData.formMessage + '</small>').hide().insertAfter(btnElement);
                                    $('.formSuccess').fadeIn(200);
                                }
                            }
                            else if(responseData.redirectURL){
                                window.location.href = responseData.redirectURL;
                            }
                        }
                        else {
                            if (responseData.errors !== null) {

                                var errorArray = responseData.errors;

                                // Find type of input, return validation
                                $.each(errorArray, function (key, val) {
                                    if(val.hasOwnProperty("first")){
                                        $(formId + '_' + key + '_first').addClass('error');
                                        $(formId + '_' + key + '_first').after($('<small class="formError error">' + val.first[0] + '</small>').hide());
                                    }
                                    else if(val.hasOwnProperty("second")){
                                        $(formId + '_' + key + '_second').addClass('error');
                                        $(formId + '_' + key + '_second').after($('<small class="formError error">' + val.second[0] + '</small>').hide());
                                    }
                                    else{
                                        $(formId + '_' + key).addClass('error');
                                        $(formId + '_' + key).after($('<small class="formError error">' + val[0] + '</small>').hide());
                                    }
                                });
                            }

                            if(responseData.formMessage && responseData.formMessage !== ''){
                                $('<small class="formError alert-box alert">' + responseData.formMessage + '</small>').hide().insertAfter(btnElement);
                            }

                            $('.formError').fadeIn(200);
                        }
                    });

                    // Handle the failed response due to error
                    $formAjaxRequest.fail(function(responseData, statusText, xhr) {
                        $(".formError").remove();
                        $(".formSuccess").remove();
                        $("label.error").removeClass('error');

                        $('<small class="formError alert-box alert">There was a ' + statusText + ' error submitting the details. Please try again.</small>').hide().insertAfter(btnElement);
                        $('.formError').fadeIn(200);
                    });
                });
            }
        },
        datepicker: function(){
            CMS.Forms.$datepickerInputs.fdatepicker({
                autoShow: true,
                initialDate: new Date().toJSON().slice(0, 10),
                disableDblClickSelection: false,
                closeButton: true,
                pickTime: false,
                isInline: false
            });
        }
    };

    CMS.touch = {
        init: function () {

        }
    };

    CMS.mobileSpecific = {
        init: function () {

        }
    };

    CMS.windowResize = {
        init: function () {
            $(window).smartresize(function () {
                notifications.sendNotification(notifications.WINDOW_RESIZE);
            });
        }
    };

    CMS.sampleTest = {
        simpleTest: function (projectName) {
            this.projectName = projectName;

            return this.projectName + ' is starting. Welcome!';
        }
    };

})(window.CMS = window.CMS || {}, jQuery, window, document);
