


/**
 * CakePanel
 * UI notification methods
 */
 	
 	
 	Panel.defaults.noty = {
 		theme:	'noty_theme_default',
		type: 	'alert',
		text:	'notification',
		layout:	'bottomRight'

 	};
 	
 	
 	


	/**
	 * Throws notifications through the "noty" jQuery plugin.
	 *
	 * Panel.msg("this is a message");
	 * Panel.ok("all stuff done!");
	 *
	 * the configuration of the noty plugin may be done
	 */

	Panel.msg = function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'information',
			text:	msg
		})));
		
	};
	
	Panel.alert = function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'alert',
			text:	msg
		})));
		
	};
	
	Panel.ok = function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'success',
			text:	msg
		})));
		
	};
	
	Panel.ko = function( msg ) {
		
		Panel._private.notifications.push(noty($.extend({},Panel.defaults.noty,{
			type:	'error',
			text:	msg
		})));
		
	};
	
	
	
	
	
	/**
	 * receive an ajax generated response object form CakePanek methods an throw
	 * the correct notification method based on status
	 */
	Panel.notify = function( data ) {
		
		switch ( data.status ) {
			
			case 'ok': 		Panel.ok(data.message); 	break;
			case 'ko': 		Panel.ko(data.message); 	break;
			case 'alert': 	Panel.alert(data.message); 	break;
			default:		Panel.msg(data.message); 	break;
			
		}
		
	};
	
	
	
	Panel.confirm = function( cfg ) {
	
		var cfg = $.extend({},{
			type:			'confirm',
			title:			'Please Confirm:',
			text:			'do you really want to complete this action?',
			ok:				function() {},
			cancel:			function() {}
		},cfg);
		
		cfg.callback 		= cfg.ok;
		cfg.cancelCallback 	= cfg.cancel;
		
		$.alert(cfg);
	
	};
	