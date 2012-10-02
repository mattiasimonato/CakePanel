/**
 * RequireJS Configuration
 */
require.config({
	
	//baseUrl: 'http://local.quotekit.it/js/',
	baseUrl: '../js/',
	
	paths: {
		
		// Libraries
		text:			'./libs/require/text',
		jquery: 		'./libs/jquery/jquery.172',
		underscore:		'./libs/underscore/underscore.133',
		backbone:		'./libs/backbone/backbone.master',
		backbonekit:	'./libs/backbonekit/kit.inc'
		
	},
	
	shim: {
		jquery: {
			exports: 	'$'
		},
		underscore: {
			exports: 	'_'
		},
		backbone: {
			deps: 		[ 'underscore', 'jquery' ],
			exports: 	'Backbone'
		},
		backbonekit: {
			deps:		[ 'backbone' ],
			exports:	'Backbone'
		}
	}
	
});









/**
 * Public Bootstrap Module
 */
define([ 'jquery', 'backbonekit', 'libs/bootstrap/bootstrap'
], function($, Backbone ){
	
	$(document).ready(function(){
		
		/*
		$('body').append("OK");
		
		(new(Backbone.View.extend({
			
			initialize: function() { this.$el.html("ONA"); }
			
		}))).appendTo('body');
		*/
		
	});
	
});