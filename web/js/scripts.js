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
            CMS.Forms.ajaxSubmittedForm('#contactform', '#contactFormBtn', true);
            CMS.Forms.ajaxSubmittedForm('#add_comment_form', '#submitCommentBtn', true);

            // Start the Ajax based sonata user forms
            CMS.Forms.ajaxSubmittedForm('#fos_user_registration_form', '#userRegisterFormBtn', false);

            // Setup the filters for the filter search page
            CMS.Forms.setupFilters();
        }
    };

    CMS.Forms = {
        setupFilters: function () {
            $('#resetFilters').change(function () {
                var checkboxes = $(this).closest('form').find(':checkbox').not(this);
                checkboxes.removeAttr('checked');
            });
        },
        ajaxSubmittedForm: function (formId, formSubmitBtnId, overrideSuccess) {

            var formElement = $(formId);
            var btnElement = $(formSubmitBtnId);

            if (formElement.length > 0) {

                btnElement.on('click', function (e) {
                    e.preventDefault();

                    var formData = formElement.serializeArray();
                    formData.push({name: "isAjax", value: "true"});

                    var formAction = formElement.attr("action");

                    $.post(formAction, formData, function (response) {

                        $(".formError").remove();
                        $("label.error").removeClass('error');

                        if (response.hasErrors === false) {
                            if(overrideSuccess){
                                formElement.trigger("reset");
                                formElement.html('<p>' + response.formMessage + '</p>');
                            }
                            else if(response.redirectURL){
                                window.location.href = response.redirectURL;
                            }
                        }
                        else {
                            if (response.errors !== null) {

                                var errorArray = response.errors;

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

                            $('<small class="formError error">' + response.formMessage + '</small>').hide().insertAfter(btnElement);

                            $('.formError').fadeIn(200);
                        }
                    });
                });
            }
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
