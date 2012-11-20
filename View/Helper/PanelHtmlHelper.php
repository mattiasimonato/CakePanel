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
	
	
	
	
	
// --------------------------- //	
// ---[[   X T Y P E S   ]]--- //
// --------------------------- //	
	
	public function xtypeContainer($mode, $name, $text, $options) {
		switch ($mode) {
			case 'tag':
				return $this->Panel->container(PowerSet::extend($options,array(
					'content' => $text
				)));
		}
	}
	
	public function xtypeTable($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options':
				$options['allowEmpty'] = true;
				return array($name, $text, $options);
				
			case 'tag':
				return $this->Panel->table($options);
		}
	}
	
}




