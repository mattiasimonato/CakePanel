

/**
 * CakePanel
 * Form utility methods
 */






/**
 * Handle an hard coded form error message and transform it into a
 * floating notification with "tipsy" jQuery Plugin
 */	
	Panel.formErrors = function() {
			
			$(this).each(function(){
				
				// Sniffing starts from the multi field mode.
				var $field 	= $(this);
				var $block 	= $field.parent();
				
				// Go back to the single field mode to sniff errors.
				var $errors = $block.find('.error-message');
				if ( !$errors.length ) {
					$block = $block.parent();
					$errors = $block.find('.error-message');
				}
				
				// Stop the script if no errors were found.
				if ( !$errors.length ) { $field.css('background','green'); }
				
				// Collect errors in a non-dom element.
				var $msg = $('<div>');
				$errors.appendTo($msg);
				
				
				
				// Fetch the gravity option from the input's attributes.
				gravity = $field.data('tipsy-gravity');
				if ( !gravity ) gravity = $.fn.tipsy.autoNS;
				
				// Init tipsy.
				$field.tipsy({
					title:		function(){ return $msg.html(); },
					html:		true,
					gravity: 	gravity,
					fade:		true,
					trigger:	'manual'
				}).tipsy('show');
				
				// Setup a custom error class and tip's closin behavior.
				var $tip = $field.data('tipsy').tip();
				$tip.addClass('tipsy-error').bind('click',function(){ $(this).fadeOut(); });
				
				// Setup a tip's closing behavior binded to the focus on the input field.
				$field.bind('focus',function(){
					$(this).tipsy("hide").removeClass('form-error');
				});
				
				
			
			});
		};












/**
 *
 * Submit a form through ajax with model's validation support.
 *
 * Panel.ajaxForm.call( $('#theForm') );
 *
 */

	Panel.ajaxForm = function( cfg ) {
		
		var _form 	= this;
		var $form 	= $(this);
		
		
		var cfg 	= $.extend({},{
			
			errorMsg: "Ajax update failed. \r Would you send the form normally?",
			
			onSubmit: function( e ) {
				
				e.preventDefault();
				
				// Trigger an event 
				$form.trigger('beforeAjaxSubmit');
				
				$form.ajaxSubmit({
					dataType:  	'json',
					success:	cfg.onSuccess,
					error:		cfg.onError
				});
				
			},
			
			onSuccess: function( data ) {
				
				if ( !data ) return cfg.onError.call();
				
				// Handle redirect request
				if ( data.redirect ) return Panel.redirect({ url:data.redirect, msg:data.message, type:data.status });
				
				if ( data.status == 'ko' ) {
					
					if ( data.validationErrors ) {
						if ( cfg.onValidationError.call( data, data.validationErrors) === false ) return;
						
					} else {
						return cfg.onError.call();
					}
					
				}
				
				Panel.notify(data);
				
			},
			
			onError: function() {
				
				if ( confirm(cfg.errorMsg,1) ) $form.unbind('submit').trigger('submit');
				
			},
			
			onValidationError: function ( errors ) {
				
				Panel.notify(this);
				
				// Multi model form
				for ( var field in errors.fields ) {
					
					var $error = $('<div>');
					$error.addClass('error-message').html(errors.fields[field]);
					
					var $field = $('#'+field);
					$field.addClass('form-error');
					$field.after( $error );
					
				}
				
				// Single model form
				for ( var model in errors.models ) {
					
					for ( var field in errors.models[model] ) {
						
						var $error = $('<div>');
						$error.addClass('error-message').html(errors.models[model][field][0]);
						
						var $field = $form.find('#'+field);
						$field.addClass('form-error');
						$field.after( $error );
						
					}
					
				}
				
				Panel.formErrors.call($('form.form .form-error'));
				
				return false;
				
			},
			
		t:'e'},cfg);
		
		
		
		$form.bind( 'submit', cfg.onSubmit );
		
	};
