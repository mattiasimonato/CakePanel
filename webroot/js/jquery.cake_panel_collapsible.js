/**
 * CakePanel - Collapsible Plugin
 * ==============================
 *
 * Implements behaviors for collapsible containers.
 *
 */


	
;(function($){	
	
	$.fn.cakePanelCollapsible = function( cfg ) {
		
		/**
		 * Startup the plugin on a set of controls.
		 */
		
		cfg = cfg || {};
		
		cfg = $.extend( true, {
			panel:			'.collapsible',
			handler: 		'.box-head',
			content: 		'.box-body',
			collapsedClass:	'collapsed',
			groupAttr:		'data-collapsible-group'
			
		}, cfg );
		
		$(this).each( __loop, [cfg] );
		
		return this;
		
	};
	
	
	
	/**
	 * Startup Loop
	 */
	
	var __loop = function(cfg) {
		
		var _this		= this;
		var $this 		= $(this);
		
		$this.find(cfg.panel).each( __initPanel, [ $this, cfg ] );
		
	};
	
	var __initPanel = function( $ui, cfg ) {
		
		var $panel		= __getPanel( this, cfg );
		
		
		// Setup UI actions
		$panel.$handler.bind( 'click', function(e) {
			
			this.blur();
			e.preventDefault();
			e.stopPropagation();
			
			if ( $panel.$content.is(':visible') ) {
				
				__closePanel.call( $panel, $ui, cfg );
				
				
			} else {
				
				// grouped panel accordion
				if ( $panel.attr(cfg.groupAttr) ) {
					$('['+cfg.groupAttr+'='+$panel.attr(cfg.groupAttr)+']').each( __closePanel, [ $ui, cfg ] );
				}
				
				__openPanel.call( $panel, $ui, cfg );
				
			}
			
		});
		
	}
	
	
	
	/**
	 * Logic actions to the accordion's panels
	 */
	
	var __getPanel = function( dom, cfg ) {
		
		var $panel		= $(dom);
		$panel.$handler = $panel.find(cfg.handler);
		$panel.$content	= $panel.find(cfg.content);
		
		return $panel;
		
	};
	
	var __openPanel = function( $ui, cfg ) {
	
		var $panel = __getPanel( this, cfg );
		
		$panel.$content.slideDown();
		
		$panel.removeClass(cfg.collapsedClass);	
		
		$ui.activePanel = this;
		
	}
	
	var __closePanel = function( $ui, cfg ) {
		
		var $panel = __getPanel( this, cfg );
		
		$panel.$content.slideUp(function(){
			$panel.addClass(cfg.collapsedClass);
		});
				
		$ui.activePanel = null;
		
	}
	
	
	
	
	
	
	/**
	 * Auto Start
	 */
	$(document).ready(function(){
		
		$('body').cakePanelCollapsible();
		
	});
	
	
	
})(jQuery);