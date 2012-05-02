
/**
 * Panel Name Space
 * it wrap CakePanel interface utilities
 */
var Panel = {











/**
 * Global configuration for behaviors.
 */	
	defaults: {
		
		// panel.notifications.js
		noty: {},
		
	t:'e'},
	
	
	
/**
 * Global access temporary storage.
 */
	_private: {
		
		// used by "noty" plugin
		notifications: []
		
	},
	
	
	
	
	










/**	
 * UI Messages
 * panel.notifications.js
 */
	
	msg: 		function( msg ) {},
	alert: 		function( msg ) {},
	ok: 		function( msg ) {},
	ko: 		function( msg ) {},
	notify: 	function( data ) {},
	
	confirm:	function(cfg) {},


	
	
/**
 * Blocks the ui and redirects to a new destination.
 * panel.redirect.js
 */
	redirect: function(cfg) {},
	
	
	




/**
 * Form Utilities.
 * panel.form.js
 */
 	
 	// Manipulate hard coded form error messages
 	formErrors: function() {},
 	
 	// Handle form ajax submissions
	ajaxForm: 	function( cfg ) {},




/**
 * Actions Link
 * search for links to actions and handle them with some logic
 */
	
	actionLinks: function() {},



/**
 * Initialization Logic
 */
	init:		function() {},
	
	
	
	
	
	
	
	
	
	
	
	

	
t:'e'}; // EndOf: -- Panel --



// Startup!
jQuery(function () { Panel.init(); });
	
	