<?php

App::import( 'View/Helper', 'SessionHelper' );


class PanelSessionHelper extends SessionHelper {
	
	
	
	public function flash( $key = 'flash', $attrs = array() ) {
		
		$type = ( $key == 'flash' ) ? '' : '.' . $key ;
		
		if ( $key == 'auth' ) $type = '.alert';
		
		if ( empty($attrs['element']) ) $attrs['element'] = 'CakePanel.flash' . $type;
		
		return parent::flash( $key, $attrs );
		
	}
	
	
	
}