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
 * Use this class to create a widget box
 * @author peg
 * 
 * 
 * ALLA FINE HO DECISO DI MANTENERE LA FUNZIONE NELL'HELPER PERCHE' MENO PESANTE!!!
 * TANTO  SOLO UN BOX!
 * 
 *
 */
class PanelWidgetUI extends PanelHtmlHelper {
	
/**	
 * Widget code templates.
 */
	protected $widgetTpl 		= '<div id="{id}" class="widget {class}">{content}</div>';
	protected $widgetHeaderTpl 	= '<div class="widget-header {class}" style="{style}">{content}</div>';
	protected $widgetBodyTpl 	= '<div class="widget-content {class}" style="{style}">{content}</div>';
	

	
/**	
 * Internal properties
 */
	protected $_title 		= '';
	protected $_contents 	= '';
	protected $_options 	= array();
	
	
	public function __construct(View $View, $settings = array()) {
		
		// Parse internal settings and trigger the helper constructor.
		parent::__construct($View, $this->settings($settings) );
		
	}
	
	
	protected function settings( $settings = array() ) {
	
		$settings += array( 'title'=>array(), 'content'=>array(), 'options'=>array() );
		
		$this->_title 	= $settings['title'];
		$this->_content = $settings['content'];
		$this->_options = $settings['options'];
		
		unset($settings['title']);
		unset($settings['content']);
		unset($settings['options']);
		
		return $settings;
		
	}
	
	
	
	public function show( $settings=array() ) {
		
		// Reset the settings from inside the show.
		if ( !empty($settings) ) $this->settings($settings);
		
		$title 		= $this->_title;
		$content 	= $this->_content;
		$options 	= $this->_options;
		
		
		// Default options
		$defaults = array( 'titleTag'=>'h3', 'id'=>'', 'class'=>'', 'headerClass'=>'', 'headerStyle'=>'', 'bodyClass'=>'', 'bodyStyle'=>'' );
		$options += $defaults;
		
		
		// Setup title and content.
		if ( !empty($options['titleTag']) ) $title = $this->tag( $options['titleTag'], $title );
		
		// Reduce a list of contents to a content string.
		if ( is_array($content) ) {
			
			$tmp = '';
			
			foreach ( $content as $key=>$val ) {
				
				$tmp.= $val;
				
			}
			
			$content = $tmp;
			
		}
		
		
		
		
		// Create the header block
		$header = PowerString::tpl( $this->widgetHeaderTpl, array(
			'content' 	=> $title,
			'class'		=> $options['headerClass'],
			'style'		=> $options['headerStyle']
		),array(
			' style=""',
			'-header "' => '-header"'		
		));
		
		// Create che content block
		$body = PowerString::tpl( $this->widgetBodyTpl, array(
			'content' 	=> $content,
			'class'		=> $options['bodyClass'],
			'style'		=> $options['bodyStyle']
		),array(
			' style=""',
			'-content "' => '-content"'	
		));
		
		// Mix up all pieces
		return PowerString::tpl( $this->widgetTpl,array(
			'content' 	=> ( $header . $body ),
			'class'		=> $options['class'],
			'id'		=> $options['id']	
		),array(
			'id="" ',		
		));
	
	}
	
	
	
}