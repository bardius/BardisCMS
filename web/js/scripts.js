/*
 Project: BardisCMS
 Authors: George Bardis
 */

// Create a closure to maintain scope of the '$' and CMS
;
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
            CMS.Forms.ajaxSubmittedForm('#contactform', '#contactFormBtn');
            CMS.Forms.ajaxSubmittedForm('#add_comment_form', '#submitCommentBtn');

            // Setup the filters
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
        ajaxSubmittedForm: function (formId, formSubmitBtnId) {

            var formElement = $(formId);
            var btnElement = $(formSubmitBtnId);

            if (formElement.length > 0) {

                btnElement.on('click', function (e) {
                    e.preventDefault();

                    var formData = formElement.serializeArray();
                    formData.push({name: "isAjax", value: "true"});

                    var formAction = formElement.attr("action");

                    $.post(formAction, formData, function (responce) {

                        $(".formError").remove();
                        $("label.error").removeClass('error');

                        if (responce.hasErrors === false) {
                            formElement.trigger("reset");
                            formElement.html('<p>' + responce.formMessage + '</p>');
                        }
                        else {
                            if (responce.errors !== null) {

                                var errorArray = responce.errors;

                                // find type of input, return validation
                                $.each(errorArray, function (key, val) {
                                    $(formId + '_' + key).addClass('error');
                                    $(formId + '_' + key).after($('<small class="formError error">' + val[0] + '</small>').hide());
                                });
                            }

                            $('<small class="formError error">' + responce.formMessage + '</small>').hide().insertAfter(btnElement);

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

})(window.CMS = window.CMS || {}, jQuery, window, document);