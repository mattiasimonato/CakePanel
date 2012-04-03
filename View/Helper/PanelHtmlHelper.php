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
		
		$defaults = array( 'titleTag'=>'h3', 'headerClass'=>'', 'headerStyle'=>'', 'bodyClass'=>'', 'bodyStyle'=>'' );
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
		$header = PowerString::tpl( self::$widgetHeaderTpl, array(
			'content' 	=> $title,
			'class'		=> $options['headerClass'],
			'style'		=> $options['headerStyle']
		),array(
			' style=""',
			'-header "' => '-header"'		
		));
		
		// Create che content block
		$body = PowerString::tpl( self::$widgetBodyTpl, array(
			'content' 	=> $content,
			'class'		=> $options['bodyClass'],
			'style'		=> $options['bodyStyle']
		),array(
			' style=""',
			'-content "' => '-content"'	
		));
		
		// Mix up all pieces
		return PowerString::tpl( self::$widgetTpl,array(
			'content' => ( $header . $body )	
		),array(
			'id="" ',		
		));
		
	}
	
	
}