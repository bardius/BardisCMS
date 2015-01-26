(function(CMS, $) {

    $(function() {
		CMS.foundationConfig.init();
    });
    // END DOC READY

    /* Optional triggers
     
     // WINDOW.LOAD
     $(window).load(function() {
     
     });

    // WINDOW.RESIZE
    $(window).resize(function() {
	
    });
     */

    CMS.foundationConfig = {
		
		init: function() {		
			
			// Start the foundation Javascript Plugins
			$(document).foundation({
				reveal : {
					animation: 'fadeAndPop',
					animation_speed: 350,
					close_on_background_click: true,
					dismiss_modal_class: 'close-reveal-modal',
					bg_class: 'reveal-modal-bg'
				},
				orbit : {
					animation: 'fade',
					timer_speed: 8000,
					pause_on_hover: true,
					resume_on_mouseout: false,
					animation_speed: 700,
					stack_on_small: true,
					navigation_arrows: true,
					slide_number: false,
					bullets: true,
					timer: false,
					variable_height: false
				},
				dropdown : {
				}
			});
        
			$('#resetFilters').change(function() {
				var checkboxes = $(this).closest('form').find(':checkbox').not(this);
				checkboxes.removeAttr('checked');
			});
			
			// Start the AJAX based contact form
			CMS.foundationConfig.ajaxSubmittedForm('#contactform', '#contactFormBtn');
			CMS.foundationConfig.ajaxSubmittedForm('#add_comment_form', '#submitCommentBtn');
		},
		    
	    ajaxSubmittedForm: function(formId, formSubmitBtnId) {
			
			var formElement = $(formId);
			var btnElement = $(formSubmitBtnId);
			
			if (formElement.length > 0) {

				btnElement.on('click', function(e) {
					e.preventDefault();

					var formData = formElement.serializeArray();
					formData.push({name: "isAjax", value: "true"});
					
					var formAction = formElement.attr("action");

					$.post(formAction, formData, function(responce) {

						$(".formError").remove();
						$("label.error").removeClass('error');

						if (responce.hasErrors === false) {
							formElement.trigger("reset");
							formElement.html('<p>' + responce.formMessage + '</p>');
						}
						else {
							if (responce.errors !== null) {
								
								var errorArray = responce.errors;
								
								$.each(errorArray, function(key, val) {
									console.log(key);
									console.log(formId + '_' + key);
									console.log(val[0]);
									// find type of input, return validation
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
		
		/*
		 * Intercharge custom query sample
		 * 
			$(document).foundation('interchange', {
				named_queries : {
					my_custom_query_for_max_200 : 'only screen and (max-width: 200px)'
				}
			 });
		 */
    };

})(window.CMS = window.CMS || {}, jQuery);