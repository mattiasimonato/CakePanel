
/**
 * Panel Name Space
 * it wrap CakePanel interface utilities
 */
var Panel = {
	
	defaults: {
		
		noty: {
			theme:	'noty_theme_default',
			type: 	'alert',
			text:	'notification',
			layout:	'bottomRight'
		},
		
	t:'e'},
	
	
	
	
	
/**	
 * Logic Methods
 * these methos exposes the logic of the Panel behaviors.
 * 
 * parts of exposes logic may be contained into the "defaults" propery to allow 
 * to change some values or behaviors globally.
 */
	
	msg: function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'information',
			text:	msg
		})));
		
	},
	
	alert: function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'alert',
			text:	msg
		})));
		
	},
	
	ok: function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'success',
			text:	msg
		})));
		
	},
	
	ko: function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'error',
			text:	msg
		})));
		
	},
	
	/**
	 * receive an ajax generated response object form CakePanek methods an throw
	 * the correct notification method based on status
	 */
	notify: function( data ) {
		
		switch ( data.status ) {
			
			case 'ok': 		Panel.ok(data.message); break;
			case 'ko': 		Panel.ko(data.message); break;
			case 'alert': 	Panel.alert(data.message); break;
			default:		Panel.msg(data.message); break;
			
		}
		
	},
	
	
	/**
	 * Blocks the ui and redirects to a new destination.
	 */
	redirect: function(cfg) {
		
		if ( typeof cfg == 'string' ) cfg = { url: cfg };
		
		cfg = $.extend({},{
			url: 			'/',
			msg: 			'... redirecting ...',
			type:			'',
			dialogClass:	'dialog-redirect'
		},cfg);
		
		// Apply type based classes.
		if ( cfg.type == 'ok' ) cfg.type = 'confirm';
		if ( cfg.type == 'ko' ) cfg.type = 'error';
		if ( cfg.type ) cfg.dialogClass+= ' ' + cfg.dialogClass + '-' + cfg.type;
		
		// Compose the message box and the dialog panel
		$msg = $('<div>');
		$msg.addClass('dialog-redirect-msg').html(cfg.msg);
		
		var $d = $('<div>');
		$d.html( $msg );
		$d.dialog({
			
			modal: 			true,
			resizable: 		false,
			dialogClass:	cfg.dialogClass,
			show: 			'fade',
			open: function( e, ui ) {
				
				var padding = ( $d.height() - 40 - $msg.height() ) / 2;
				
				$msg.css({
					paddingTop: 	padding,
					paddingBottom: 	padding
				});
				
				// REDIRECT
				setTimeout(function(){
					document.location.href = cfg.url;
				},500);
				
				
			}
			
		});
		
	},
	
	
	/**
	 * Make a form works throught AJAX.
	 * Panel.ajaxForm.call( $('#theForm') );
	 */
	ajaxForm: function( cfg ) {
		
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
		
	},
	
	
	// private storage for supporting the framework
	_private: {
		
		notifications: []
		
	},
	
t:'e'};




;(function($) { 

	$(function () {
		
		var $formActions 	= null;
		var $contentHeader 	= $('#contentHeader');
		var $content		= $('#content');
		
		/**
		 * Sticky Toolbar
		 */
		
		$contentHeader.scrollSticky({
			onSticky: function() {
				$contentHeader.css({
					marginTop: '-5px',
					width: $('#content').outerWidth()
				});
			}
		});
		
		
		
		
		/**
		 * Form Stiky Actions Bar
		 */
		if ( $('.container form').length == 1 ) {
			
			var $formActions = $('.container form .form-actions');
			
			$formActions.html('<div class="wrap">'+$formActions.html()+'</div>').addClass('sticky-form-actions').css({
				top: 	$(window).height() - $formActions.outerHeight(),
				left:	$content.offset().left,
				width:	$content.outerWidth()
			});
			
		}
		
		/**
		 * Window Resize Events
		 */
		var onWindowResizeTimeout;
		$(window).resize(function(){
			
			// Adjust view's title bar dimensions
			if ( $contentHeader ) $contentHeader.css({
				width: $content.outerWidth()
			});
			
			// Adjust form actions bar size and fixed position.
			if ( $formActions ) $formActions.css({
				top: 	$(window).height() - $formActions.outerHeight(),
				left:	$content.offset().left,
				width:	$content.outerWidth()
			});
			
			
			// Delayed Events:
			// Performance optimization for operations that not require real time application.
			clearTimeout(onWindowResizeTimeout);
			onWindowResizeTimeout = setTimeout(function(){},300);
			
		});
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**
		 * Fetch error messages from the DOM and use Tipsy to display
		 * around the field.
		 *
		 * Use the "data-tipsy-gravity" attribute to setup the error tooltip position.
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
		
		// Handle static form errors.
		Panel.formErrors.call($('form.form .form-error'));
		
		
		
		/**
		 * Automagically ajaxForm
		 */
		$('form.ajaxForm, form[data-ajax]').each(function(){
			
			Panel.ajaxForm.call( $(this) );
			
		});
		
		
		
		
		
		
		/**
		 * JumpMenu
		 */
		$('form.jump-menu').each(function(){
			
			var $this = $(this);
			
			$this.find('input[type=submit]').hide();
			
			$this.find('select').bind('change',function(){
				$this.submit();
			});
			
		});
		
		
	}); 

})(jQuery);