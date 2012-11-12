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
 * PanelWidgetUI
 * -----------------------------------
 * Use this class to create a widget box.
 * A box should be created with or without title.
 *
 */
class PanelWidgetUI extends PanelHtmlHelper {
	
/**	
 * Widget's settings.
 */
	protected $widgetSettings 	= array(
		'class' => 'widget'
	);
	protected $titleSettings 	= array(
		'name'	=> 'h3',
		'class' => 'widget-header'
	);
	protected $contentSettings 	= array(
		'class' => 'widget-content'
	);
	
	protected $helperSettings = array();

	
/**	
 * Internal properties
 */
	
	private $_settings = array();
	
	
	public function __construct(View $View, $settings = array()) {
		
		// init widget settings
		$this->settings($settings);
		
		// parse internal settings and trigger the helper constructor.
		parent::__construct($View, $this->_settings['helper'] );
		
	}
	
	
	/**
	 * Extract particular settings trees and configure internal 
	 * property.
	 * 
	 * - title
	 * - content
	 * - helper
	 * 
	 * Every non-noble property is given to the widget tag() method!
	 */
	protected function settings( $settings = array() ) {
		
		if ( !is_array($settings) || empty($settings) ) $settings = array();
		$settings += array( 'title'=>'', 'content'=>'', 'helper'=>array() );
		
		$title 		= $settings['title'];
		$content 	= $settings['content'];
		$helper 	= $settings['helper'];
		
		unset($settings['title']);
		unset($settings['content']);
		unset($settings['helper']);
		
		$this->_settings = array(
			'title' 	=> $title,
			'content' 	=> $content,
			'helper' 	=> $helper,
			'widget' 	=> $settings
		);
		
	}
	
	
	
	
	
	
	/**
	 * Generates DOM
	 */
	
	public function show() {
		
		// obtain merged settings
		$widget = $this->extendDefaults( $this->widgetSettings, $this->_settings['widget'] );
		
		// merge with class's defaults
		$settings = PowerSet::merge($this->widgetSettings,$widget);
		
		// apply contentes
		$settings = PowerSet::merge($settings,array(
			'content' => array(
				$this->showTitle(),
				$this->showContent()
			)
		));
		
		return $this->tag( $settings );
		
	}
	
	protected function showTitle() {
		
		return $this->showBlock( $this->_settings['title'], $this->titleSettings );
		
	}
	
	protected function showContent() {
		
		return $this->showBlock( $this->_settings['content'], $this->contentSettings );
		
	}
	
	
	
	
	
	
	/**
	 * Apply some merge or replacement policy to many attributes who can live
	 * toghether with the parent like class names, styles, etc.
	 * 
	 * properties prefixed with "_" will completely override existing or
	 * inherited values!
	 * 
	 */
	protected function extendDefaults( $parent, $child ) {
		
		$parent += array( 'class'=>'', 'style'=>'', 'rel'=>'' );
		
		if ( !empty($child['class']) ) 	$child['class'] 	= $parent['class'] . ' ' . $child['class'];
		if ( !empty($child['style']) ) 	$child['style'] 	= $parent['style'] . ' ' . $child['style'];
		if ( !empty($child['rel']) ) 	$child['rel'] 		= $parent['rel'] . ' ' . $child['rel'];
		
		// "_style" replace "style" (for every key!)
		foreach ( array_keys($child) as $key ) {
			if ( strpos($key,'_') === 0 ) {
				$propKey = strtolower(substr($key,1,strlen($key)));
				$child[$propKey] = $child[$key];
				unset($child[$key]);
			}
		}
		
		return $child;
		
	}
	
	
	/**
	 * Create a bloc of content by setting internal content key from raw string or "text" key.
	 */
	protected function showBlock( $settings, $defaults ) {
		
		// null or false values will prevent to display block content.
		if ( !$settings ) return;
		
		// intercept string value
		if ( is_string($settings) ) $settings = array( 'text'=>$settings );
		
		// text->content key - tag() compatibiliy
		if ( isset($settings['text']) ) {
			$settings['content'] = $settings['text'];
			unset($settings['text']);
		}
		
		// obtain merged settings
		$settings = $this->extendDefaults( $defaults, $settings );
		
		return $this->tag(PowerSet::merge( $defaults, $settings ));
		
	}
	
	
	
}