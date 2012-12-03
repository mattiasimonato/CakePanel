<?php
/**
 * PanelHelper
 * ===========
 * 
 * Utilities to generate pieces of the admin panel UI in a DRY ever way!
 * 
 * 
 * @author peg
 *
 */
class PanelHelper extends AppHelper {
	
	
	public function title( $title = '' ) {
		
		$this->_View->assign( 'title_for_view', $title );
		
	}
	
	
	
	
/**
 * Utility method to render a PanelWidgetUI object with standard or custom class
 * 
 *     $this->Panel->container( 'content...', 'My Widget' );
 *     
 *     $this->Panel->container(array(
 *       'title'     => 'My Title',
 *       'content'   => 'foo...',
 *       'className' => 'MyPlugin.Vendor/MyCustomContainerClass'
 *     ));
 * 
 */	
	public function container( $content = '', $title = '', $settings = array() ) {
		
		// handle full array configuration
		if ( is_array($content) ) {
			$settings = $content;
			unset($content);
			unset($title);
		}
		
		// apply defaults to settings
		$settings = PowerSet::def( $settings,array(
			'className' => '',
			'title'		=> '',
			'text'		=> ''
		),'className');
		
		// fill settings from raw properties
		if ( !empty($title) ) 	$settings['title'] 		= $title;
		if ( !empty($content) ) $settings['text'] 		= $content;
		
		// define and import custom object
		$className = !empty($settings['className']) ? $settings['className'] : 'CakePanel.Vendor/PanelContainerUi';
		list( $className, $package,  ) = packageCmp($className);
		App::uses( $className, $package );
		unset($settings['className']);
		
		$obj = new $className( $this->_View, $settings );
		return $obj->render();
		
	}
	
}