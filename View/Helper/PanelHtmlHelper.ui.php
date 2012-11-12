<?php
/**
 * CakePANEL, CREDITS and LICENSING
 * =====================================
 *
 * @author: 	Marco Pegoraro (aka MPeg, @ThePeg)
 * @mail: 		marco(dot)pegoraro(at)gmail(dot)com
 * @blog:		http://movableapp.com
 * @web:		http://cakepower.org
 * 
 * This sofware is distributed under MIT license.
 * Please read "license.txt" document into plugin's root
 * 
 */




App::import( 'View/Helper', 'CakePower.PowerHtmlHelper' );

class PanelHtmlHelper extends PowerHtmlHelper {
	

/**	
 * Widgets containers
 */
	protected $_widgetUI = null;

	
	
	
	

/**
 * Shortcut method to the PanelWidgetUI component.
 */
	function widget( $title = '', $content = '', $options = array() ) {
		
		// Lazy load the instance of the widgetUI
		if ( empty($this->_widgetUI) ) $this->_widgetUI = new PanelWidgetUI($this->_View);
		
		if ( !is_array($title) ) return $this->_widgetUI->show(array(
			'title'		=> $title,
			'content'	=> $content,
			'options'	=> $options
		));
		
		return $this->_widgetUI->show($title);
		
	}
	
	
}






/**
 * Import UI Widgets libraries
 */
App::import( 'Vendor', 'CakePanel.PanelTableUi' );
App::import( 'Vendor', 'CakePanel.PanelWidgetUi' );


