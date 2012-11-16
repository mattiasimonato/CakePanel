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
	 * Adds CakePanel.Panel
	 * 
	 * @param View $View
	 * @param unknown_type $settings
	 */
	public function __construct( View $View, $settings = array() ) {
		
		$this->helpers[] = 'CakePanel.Panel'; 
		
		parent::__construct( $View, $settings );
	
	}
	
	
	/**
	 * accepts nested snippets by implementing it's name:
	 * 
	 * array(
	 *   'name' => 'container',
	 *   'title' => 'users',
	 *   'content' => array(
	 *     'name' => 'table',
	 *     'data' => $users
	 *   )
	 * )
	 * 
	 * 
	 */
	public function tag($name='div', $text = null, $options = array()) {
		
		// call with multiple params does not accept nested widgets
		if ( !is_array($name) || PowerSet::is_vector($name) ) return parent::tag($name,$text,$options);
		
		$name = PowerSet::extend(array('name'=>'div'),$name);
		
		switch( $name['name'] ) {
			
			case 'container':
				unset($name['name']);
				return $this->Panel->container($name);
				
			case 'table':
				unset($name['name']);
				return $this->Panel->table($name);
				
			
			default:
				return parent::tag($name,$text,$options);
				break;
		
		}
		
	}
	
}




