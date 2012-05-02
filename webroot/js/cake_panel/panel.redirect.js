
/**
 * CakePanel
 * redirect behavior with messages.
 *
 * Panel.redirect('http://www.google.it');
 *
 * Panel.redirect({
 *     url: 'http://www.google.it',
 *     msg: 'you are being redirected'
 * });
 */


	Panel.redirect = function(cfg) {
		
		if ( typeof cfg == 'string' ) cfg = { url: cfg };
		
		cfg = $.extend({},{
			url: 			'/',
			msg: 			'... redirecting ...',
			type:			'', // ok|ko
			dialogClass:	'dialog-redirect',
			delay:			500
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
				},cfg.delay);
				
				
			}
			
		});
		
	};