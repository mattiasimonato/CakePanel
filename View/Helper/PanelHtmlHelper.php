<?php

App::import( 'View/Helper', 'CakePower.PowerHtmlHelper' );

class PanelHtmlHelper extends PowerHtmlHelper {
	
	public static $widgetTpl 		= '<div id="{id}" class="widget {class}">{content}</div>';
	public static $widgetHeaderTpl 	= '<div class="widget-header {class}" style="{style}">{content}</div>';
	public static $widgetBodyTpl 	= '<div class="widget-content {class}" style="{style}">{content}</div>';
	
	
	
	
	
/**	
 * It build a Canvas theme widget box.
 * 
 * @param unknown_type $title
 * @param unknown_type $content
 * @param unknown_type $options
 * 
 * echo $this->Html->widget( 'title', 'content' );
 * 
 * // Associative array input option.
 * echo $this->Html->widget(array(
 *     'title' => 'jiojo',
 *     'content' => array(
 *         $this->Html->tag(),
 *         $this->Form->input()
 *     ),
 *     'options' => array()
 * ));
 * 
 */
	public function widget( $title = '', $content = '', $options = array() ) {
		
		
		
		
		// Respond to a single param associative array input
		if ( is_array($title) ) {
			
			$defaults = array( 'title'=>'', 'content'=>'', 'options'=>array() );
			$title += $defaults;
			
			$options 	= $title['options'];
			$content 	= $title['content'];
			$title		= $title['title'];
			
		}
		
		$defaults = array( 'titleTag'=>'h3', 'id'=>'', 'class'=>'', 'headerClass'=>'', 'headerStyle'=>'', 'bodyClass'=>'', 'bodyStyle'=>'' );
		if ( !is_array($options) ) $options = array( 'class'=>$options );
		$options += $defaults;
		
		// Templates customization
		if ( empty($options['headerTpl']) ) 	$options['headerTpl'] 	= self::$widgetHeaderTpl;
		if ( empty($options['bodyTpl']) ) 		$options['bodyTpl']		= self::$widgetBodyTpl;
		if ( empty($options['widgetTpl']) ) 	$options['widgetTpl']	= self::$widgetTpl;
		
		
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
		$header = PowerString::tpl( $options['headerTpl'], array(
			'content' 	=> $title,
			'class'		=> $options['headerClass'],
			'style'		=> $options['headerStyle']
		),array(
			' style=""',
			'-header "' => '-header"'		
		));
		
		// Create che content block
		// The content block uses a placeholder for the content data to avoid content's tags
		// to be parsed here.
		
		$contentStaticPlaceholder = '--' . time() . '--';
		
		$body = PowerString::tpl( $options['bodyTpl'], array(
			'content' 	=> $contentStaticPlaceholder,
			'class'		=> $options['bodyClass'],
			'style'		=> $options['bodyStyle']
		),array(
			' style=""',
			'-content "' => '-content"'	
		));
		
		
		// Mix up all pieces
		return str_replace( $contentStaticPlaceholder, $content, PowerString::tpl( $options['widgetTpl'],array(
			'content' 	=> ( $header . $body ),
			'class'		=> $options['class'],
			'id'		=> $options['id']	
		),array(
			'id="" ',		
		)));
		
	}
	
	
	
	
	
/**	
 * Tab Pabel Widget
 * 
 * CONTENT:
 * associative array with tab_id as key and an array of tab options described as follow
 * - title: tab's title
 * - content: tab's content
 * - options: tab's wrapper dom options (extends $options['tabOptions'])
 * 
 * OPTIONS:
 * - class: adds classes to the widget container
 * - active_tab: setup the active tab id. accepts [first,last] magic key to set up the first or the last panel
 * - tabOptions: an array of html properties to set up default values for each panel. 
 * 
 */
	public function tabsWidget( $title = '', $content = array(), $options = array() ) {
		
		// Options default values.
		$options += array( 'class'=>'', 'active_tab'=>'first', 'tabOptions'=>array() );
		
		// Optional overrides.
		$options['class'].= ' widget-tabs';
		
		// Strict widget overrides.
		$options['titleTag'] 	= '';
		$options['bodyTpl'] 	= '{content}';
		
		$head = '';
		$body = '';
		
		// Forst or last active tab customization
		if ( $options['active_tab'] == 'first' ) 	$options['active_tab'] = array_shift(array_keys($content));
		if ( $options['active_tab'] == 'last' ) 	$options['active_tab'] = array_pop(array_keys($content));
		
		foreach ( $content as $tab_id=>$tab ) {
			
			$tab+= array( 'options'=>array() );
			
			$dom_id = 'wdg-tabs-' . $tab_id;
			
			
			// Setup active tab
			$active = false;
			if ( isset($tab['active']) ) 				$active = true;
			if ( $tab_id == $options['active_tab'] ) 	$active = true;
			
			// AAARRRGGGHHH: bisogna aggiungere lo spazio alla fine se no viene fuori un margine destro bianco
			// nei tab in visualizzazione, assurdo!
			$head .= $this->tag( 'li', $this->link($tab['title'],'#'.$dom_id), array(
				'class' => ( $active ) ? 'active' : ''
			)) . " ";
			
			
			
			// Creates panel's body
			$tab['options'] = PowerSet::merge( $options['tabOptions'], $tab['options'] );
			$tab['options'] += array( 'id'=>'', 'class'=>'' );
			$tab['options']['id'] 		= $dom_id;
			$tab['options']['class']	.= ' ' . 'widget-content';
			
			$body .= $this->tag('div', $tab['content'], $tab['options']);
		
		}
		
		// Complete the heading wrappers
		$head = $this->tag( 'ul', $head, array(
			'class' => 'tabs right'
		));
		
		$head = $this->tag( 'h3', $title, array(
			'class' => 'icon aperture'
		)) . $head;
		
		return $this->widget( $head, $body, $options );
		
	} 
	
	
	
	
	
	
	/**
	 * 24 cols grid system support.
	 */
	public function grid( $cols = 24, $content = '', $options = array() ) {
		
		$options += array( 'class'=>'' );
		$options['class'] = 'grid-' . $cols . ' ' . $options['class'];
		
		// Supporto for lists of contents
		if ( is_array($content) ) {
			
			$tmp = '';
			
			foreach ( $content as $line ) $tmp.= $line;
			
			$content = $tmp;
			
		}
		
		return $this->tag( 'div', $content, $options );
	
	}
	
	public function grid24( $content = '', $options = array() ) { return $this->grid( '24', $content, $options ); }
	public function grid23( $content = '', $options = array() ) { return $this->grid( '23', $content, $options ); }
	public function grid22( $content = '', $options = array() ) { return $this->grid( '22', $content, $options ); }
	public function grid21( $content = '', $options = array() ) { return $this->grid( '21', $content, $options ); }
	public function grid20( $content = '', $options = array() ) { return $this->grid( '20', $content, $options ); }
	public function grid19( $content = '', $options = array() ) { return $this->grid( '19', $content, $options ); }
	public function grid18( $content = '', $options = array() ) { return $this->grid( '18', $content, $options ); }
	public function grid17( $content = '', $options = array() ) { return $this->grid( '17', $content, $options ); }
	public function grid16( $content = '', $options = array() ) { return $this->grid( '16', $content, $options ); }
	public function grid15( $content = '', $options = array() ) { return $this->grid( '15', $content, $options ); }
	public function grid14( $content = '', $options = array() ) { return $this->grid( '14', $content, $options ); }
	public function grid13( $content = '', $options = array() ) { return $this->grid( '13', $content, $options ); }
	public function grid12( $content = '', $options = array() ) { return $this->grid( '12', $content, $options ); }
	public function grid11( $content = '', $options = array() ) { return $this->grid( '11', $content, $options ); }
	public function grid10( $content = '', $options = array() ) { return $this->grid( '10', $content, $options ); }
	public function grid09( $content = '', $options = array() ) { return $this->grid( '09', $content, $options ); }
	public function grid08( $content = '', $options = array() ) { return $this->grid( '08', $content, $options ); }
	public function grid07( $content = '', $options = array() ) { return $this->grid( '07', $content, $options ); }
	public function grid06( $content = '', $options = array() ) { return $this->grid( '06', $content, $options ); }
	public function grid05( $content = '', $options = array() ) { return $this->grid( '05', $content, $options ); }
	public function grid04( $content = '', $options = array() ) { return $this->grid( '04', $content, $options ); }
	public function grid03( $content = '', $options = array() ) { return $this->grid( '03', $content, $options ); }
	public function grid02( $content = '', $options = array() ) { return $this->grid( '02', $content, $options ); }
	public function grid01( $content = '', $options = array() ) { return $this->grid( '01', $content, $options ); } 
	
	
}






/**
 * Import UI Widgets libraries
 */
App::import( 'Vendor', 'CakePanel.PanelTableUi' );


