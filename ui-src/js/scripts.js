/*
 Project: BardisCMS
 Authors: George Bardis
 */

// Create a closure to maintain scope of the '$' and CMS
(function (CMS, $, window, document, undefined) {

    $(function () {
        CMS.Config.init();
    });

    CMS.Config = {
        $body: $(document.body),
        init: function () {

            CMS.foundationConfig.init();
            CMS.UI.init();
            CMS.cookiePolicy();

            CMS.windowResize.init();

            if (CMS.Supports.touch) {
                CMS.touch.init();
            }

            $(window).load(function () {
            });
        }
    };

    CMS.foundationConfig = {
        init: function () {
            Foundation.Reveal.defaults.animationIn = 'fade-in';
            Foundation.Reveal.defaults.animationOut = 'fade-out';
            Foundation.Reveal.defaults.resetOnClose = true;
            Foundation.Reveal.defaults.closeOnClick = true;
            Foundation.Reveal.defaults.closeOnEsc = true;

            Foundation.Orbit.defaults.animInFromRight = 'fade-in';
            Foundation.Orbit.defaults.animOutToRight = 'fade-out';
            Foundation.Orbit.defaults.animInFromLeft = 'fade-in';
            Foundation.Orbit.defaults.animOutToLeft = 'fade-out';
            Foundation.Orbit.defaults.autoPlay = false;
            Foundation.Orbit.defaults.timerDelay = 8000;
            Foundation.Orbit.defaults.infiniteWrap = false;
            Foundation.Orbit.defaults.swipe = true;
            Foundation.Orbit.defaults.pauseOnHover = true;
            Foundation.Orbit.defaults.accessible = true;
            Foundation.Orbit.defaults.useMUI = true;
            Foundation.Orbit.defaults.bullets = true;
            Foundation.Orbit.defaults.navButtons = true;

            Foundation.Accordion.defaults.slideSpeed = 250;
            Foundation.Accordion.defaults.multiExpand = false;
            Foundation.Accordion.defaults.allowAllClosed = true;

            Foundation.OffCanvas.defaults.closeOnClick = true;

            // Start the foundation Plugins Configuration
            $(document).foundation();

            CMS.foundationConfig.setEnhancements();
        },
        setEnhancements: function(){
            // Enhance the sticky header (fix for miscalculation of sticky-container height when unstuck
            $('.header-sticky-container').on('sticky.zf.unstuckfrom:top', function(){
                let stickyContentHeight = $(this).find('.sticky').first().outerHeight(true);
                $(this).css('height', stickyContentHeight + 'px');
            });

            $('.header-sticky-container').on('sticky.zf.stuckto:top', function(){
            });

            // Initialize again the foundation Orbit plugin within modals after they open
            $('.reveal').on('open.zf.reveal', Foundation.util.throttle(function () {
                    $('.reveal [data-orbit]').each(function(carouselIndex) {
                        CMS.foundationConfig.reInitOrbit(this);
                    });
                }, 100)
            );
        },
        reInitOrbit: function (orbitElement) {
            let $orbitElement = $(orbitElement);
            let orbitSlider = new Foundation.Orbit($orbitElement);
            orbitSlider.destroy();

            $orbitElement.data('orbit', '');
            $orbitElement.attr('style', '');
            $orbitElement.find('ul').attr('style', '');
            $orbitElement.find('li').attr('style', '');
            orbitSlider = new Foundation.Orbit($orbitElement);
        }
    };

    CMS.UI = {
        init: function () {
            // Start the AJAX based forms
            // Usage CMS.Forms.ajaxSubmittedForm: function (formId, submitBtnId, dataType, overrideSuccess, resetForm)
            CMS.Forms.ajaxSubmittedForm('#contactform_form', '#contactFormBtn', 'json', true, true);
            CMS.Forms.ajaxSubmittedForm('#commentform_form', '#submitCommentBtn', 'json', true, true);

            // Start the Ajax based sonata user forms
            CMS.Forms.ajaxSubmittedForm('#sonata_user_custom_user_registration_form', '#userRegisterFormBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_generic_details_form', '#userGenericDetailsFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_contact_details_form', '#userContactDetailsFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_account_preferences_form', '#userAccountPreferencesFormBtn', 'json', true, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_change_password_form', '#userPasswordFormBtn', 'json', true, true);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_resetting_request', '#userResetPasswordFormBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_resetting_form', '#userResetFormBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#user_login_form', '#loginBtn', 'json', false, false);
            CMS.Forms.ajaxSubmittedForm('#sonata_user_filter_users_form', '#filterResultsBtn', 'json', false, false);

            // Start the date picker
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
                    btnElement.addClass('button-loading');

                    var formData = formElement.serializeArray();
                    formData.push({name: "isAjax", value: "true"});

                    var formAction = formElement.attr("action");

                    // Submit the form data
                    var $formAjaxRequest = $.post(formAction, formData, null, dataType);

                    // Always act when Ajax call is complete
                    $formAjaxRequest.always(function() {
                        btnElement.prop('disabled', false);
                        btnElement.removeClass('button-loading');
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

                            // Append new comment in the comments list if it was a comment form
                            if(typeof responseData.newComment !== 'undefined' && responseData.newComment.length >0){
                                var commentHtml = '<div class="row comment odd">';
                                commentHtml += '<div id="comment-' + responseData.newComment[0].id + '" class="large-12 small-12 columns panel">';
                                commentHtml += '<h4>' + responseData.newComment[0].title + '</h4>';
                                commentHtml += '<p>' + responseData.newComment[0].comment + '</p></div></div>';

                                $('.previous-comments').prepend(commentHtml);
                            }

                            if(overrideSuccess){
                                if(resetForm){
                                    formElement.trigger("reset");
                                }
                                if(responseData.formMessage && responseData.formMessage !== ''){
                                    $('<span class="form-message error form-error formSuccess callout is-visible success">' + responseData.formMessage + '</span>').hide().insertAfter(btnElement);
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
                                        $(formId + '_' + key + '_first').after($('<span class="formError error form-error is-visible">' + val.first[0] + '</span>').hide());
                                    }
                                    else if(val.hasOwnProperty("second")){
                                        $(formId + '_' + key + '_second').addClass('error');
                                        $(formId + '_' + key + '_second').after($('<span class="formError error form-error is-visible">' + val.second[0] + '</span>').hide());
                                    }
                                    else{
                                        $(formId + '_' + key).addClass('error');
                                        $(formId + '_' + key).after($('<span class="formError error form-error is-visible">' + val[0] + '</span>').hide());
                                    }
                                });
                            }

                            if(responseData.formMessage && responseData.formMessage !== ''){
                                $('<span class="form-message error form-error formError callout is-visible alert">' + responseData.formMessage + '</span>').hide().insertBefore(btnElement);
                            }

                            $('.formError').fadeIn(200);
                        }
                    });

                    // Handle the failed response due to error
                    $formAjaxRequest.fail(function(responseData, statusText, xhr) {
                        $(".formError").remove();
                        $(".formSuccess").remove();
                        $("label.error").removeClass('error');

                        $('<span class="form-message error form-error formError callout is-visible alert">There was a ' + statusText + ' error submitting the details. Please try again.</span>').hide().insertAfter(btnElement);
                        $('.formError').fadeIn(200);
                    });
                });
            }
        },
        datepicker: function(){
            CMS.Forms.$datepickerInputs.fdatepicker({
                autoShow: true,
                // initialDate: new Date().toJSON().slice(0, 10),
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

    CMS.windowResize = {
        init: function () {
            var currentBreakpoint = Foundation.MediaQuery.current;

            $(window).on('resize', Foundation.util.throttle(function () {
                    notifications.sendNotification(notifications.WINDOW_RESIZE);

                    // re initialise the Foundation Orbit carousels
                    $('[data-orbit]').each(function(carouselIndex) {
                        CMS.foundationConfig.reInitOrbit(this);
                    });
                }, 100)
            );

            // Foundation event listener for breakpoint changes
            $(window).on('changed.zf.mediaquery', function(event, newSize, oldSize){
                currentBreakpoint = Foundation.MediaQuery.current;
            });
        }
    };

})(window.CMS = window.CMS || {}, jQuery, window, document);
