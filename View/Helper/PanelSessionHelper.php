<?php

App::import( 'View/Helper', 'CakePower.PowerSessionHelper' );


class PanelSessionHelper extends PowerSessionHelper {
	
	
/**	
 * Apply a custom template element based on the flash type message.
 */
	public function flash( $key = null, $attrs = array() ) {
		
		// key is set to i can stup the template and output the flash.
		if ( !empty($key) && is_string($key) ) {
			
			$type = ( $key == 'flash' ) ? '' : '.' . $key ;
			
			if ( $key == 'auth' ) $type = '.alert';
			
			if ( empty($attrs['element']) ) $attrs['element'] = 'CakePanel.flash' . $type;
			
			return parent::flash( $key, $attrs );
		
		}
		
		// blank key or list of flash to setup. pass to the CakePower's flashes utility.
		return $this->flashes( $key, $attrs );
		
	}
	
	
	
}