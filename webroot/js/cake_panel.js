;(function($) { 

	$(function () {
		
		
		
		
		/**
		 * Fetch error messages from the DOM and use Tipsy to display
		 * around the field.
		 *
		 * Use the "data-tipsy-gravity" attribute to setup the error tooltip position.
		 */
		$('form.form .form-error').each(function(){
			
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
			console.log(gravity);
			
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
		
		
	}); 

})(jQuery);