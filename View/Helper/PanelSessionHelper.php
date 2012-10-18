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



/**
 * CakePanel
 */
App::import( 'View/Helper', 'CakePower.PowerSessionHelper' );


class PanelSessionHelper extends PowerSessionHelper {
	
	
	
	
	
/**	
 * Apply a custom template element based on the flash type message.
 * You can manually set the element to render with the "element" attr as CakePHP teach.
 * 
 * CakePanel tries to run a custom tamplate based on flash type:
 * success,error,alert,message
 * 
 * This rule is made by a string template:
 * "CakePanel.flash/{type}"
 * 
 * You can change this string template to fit your need into the global PowerConfig object:
 * PowerConfig::set( 'plugin.CakePanel.flashTpl', 'SubFolder/prefix.{type}' )
 * -> /App/Elements/SubFolder/prefix.type.ctp
 * 
 * You can also extend this helper and implement the "flashTpl" property with your custom
 * value: CakePanel's flashTpl will be applied only if an empty template was found!
 * 
 * http://movableapp.com/2012/08/cakephp-custom-flash-messages-templates-by-cakepower/
 * 
 */
	
	public function beforeRender() {
		
		if ( empty($this->flashTpl) ) {
			$this->flashTpl = PowerConfig::get( 'plugin.CakePanel.flashTpl', 'CakePanel.flash/{key}' );	
		}
	
	}
	
}