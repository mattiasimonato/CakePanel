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
 * Utility method to render a TableUI object with standard or custom (extended) object
 * 
 *     // view code - custom tableUI object
 *     class myTable extends PanelTableUI { ... }
 *     echo $this->Panel->tableUI( $list, 'myTable' );
 *     
 *     // view core - generic tableUI with configurations
 *     echo $this->Panel->tableUI( $list, array(
 * 	     'modelName' => 'Foo'
 *     ));
 * 
 * This method should auto load tableUI class if not present.
 * By default $tableUI is searched inside "Vendor" package but you should customize loading
 * search path as follow:
 * 
 *     echo $this->Panel->tableUI( $list, 'Plugin.Vendor/customTableObject' );
 *     echo $this->Panel->tableUI( $list, 'Plugin.Vendor/subpackage/customTableObject' );
 * 
 * this kind of $tableUI name will causes: 
 * 
 *     App::uses( 'customTableObject', 'Plugin.Vendor' );
 *     App::uses( 'customTableObject', 'Plugin.Vendor/subpackage' );
 *     
 * this approach allow to store 
 * 
 */
	public function table( $data, $settings = array() ) {
		
		if ( isset($data['data']) ) {
			$settings = $data;
			$data = $settings['data'];
			unset($settings['data']);
		}
		
		// string settings means custom object
		if ( is_string($settings) ) $settings = array( 'className'=>$settings );
		
		// apply defaults to settings
		if ( !is_array($settings) || empty($settings) ) $options = array();
		$settings+= array( 'className'=>'' );
		
		// define and import custom object
		$className = !empty($settings['className']) ? $settings['className'] : 'CakePanel.Vendor/PanelTableUI';
		list( $className, $package,  ) = packageCmp($className);
		App::uses( $className, $package );
		unset($settings['className']);
		
		// creates table object instance
		$obj = new $className( $this->_View, $settings );
		return $obj->render($data);
		
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
		$settings = PowerSet::todef( $settings,'className',array(
			'className' => '',
			'title'		=> '',
			'content'	=> ''
		));
		
		// fill settings from raw properties
		if ( !empty($title) ) 	$settings['title'] 		= $title;
		if ( !empty($content) ) $settings['content'] 	= $content;
		
		// define and import custom object
		$className = !empty($settings['className']) ? $settings['className'] : 'CakePanel.Vendor/PanelContainerUi';
		list( $className, $package,  ) = packageCmp($className);
		App::uses( $className, $package );
		unset($settings['className']);
		
		$obj = new $className( $this->_View, $settings );
		return $obj->render();
		
	}
	
}