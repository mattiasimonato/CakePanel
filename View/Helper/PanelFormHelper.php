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
 * CakePanel - PanelFormHelper
 * 
 * Extends CakePHP's FormHelper to match the admin layout html structure as default values for
 * more often used methods.
 */



App::import( 'View/Helper', 'CakePower.PowerFormHelper' );

class PanelFormHelper extends PowerFormHelper {
	
	
	
	
	
	
/**	
 * SINGLE INPUT
 * Single input notation with default values according to TwitterBootstrap styles
 */
	public function input( $name, $options = array() ) {
		
		// Default options structure
		if ( !is_array($options) ) $options = array( 'label'=>$options );
		$options+= array( 'error'=>array(), 'div'=>array() );
		
		
		// Setup the container class to fit TwitterBootstrap rules
		if ( !empty($options['div']) && is_string($options['div']) ) $options['div'] = array( 'class'=>$options['div'] );
		if ( is_array($options['div']) && empty($options['div']['class']) ) $options['div']['class'] = '';
		if ( is_array($options['div']) && strpos($options['div']['class'],'control-group') === false ) $options['div']['class'] .= ' control-group';
		
		
		// Setup the error string to fit TwitterBootstrap rules
		if ( is_string($options['error']) ) $options['error'] = array( 'attributes'=>$options['error'] );
		if ( is_array($options['error']) ) {

			$options['error'] += array( 'attributes'=>array() );
			
			if ( is_string($options['error']['attributes']) ) $options['error']['attributes'] = array( 'class'=>$options['error'] );
			
			if ( empty($options['error']['attributes']['class']) ) $options['error']['attributes']['class'] = 'help-block'; 
			
		}
		
		return parent::input( $name, $options );
	
	}
	
	
/**	
 * MULTIPLE FIELDS
 * - if you pass a "legend" field native CakePHP "inputs()" method will be used to create a fieldset region
 * - if no "legend" is present then creates a row of input blocks
 * 
 * ## Blocks Width:
 * you can control block's width in tree ways:
 * 
 * A. each block can set it's own div>class property to a spanX value.
 * B. you can define a default spanX for all blocks giving a "span=4" key to the method options.
 *    if a block define it's spanX then defaul is not applied!
 * C. automagically -  CakePOWER tries to split blocks to fit the row.
 *    2 blocks -> span6
 *    3 blocks -> span4
 *    4 blocks -> span3
 *    5 blocks -> span2
 * 
 * ## Inputs Width
 * you can setup inputs() so each input control will fit it's block container by giving a "fit=true" key to the method options.
 * a "span-fit" class will be appended to the input's options
 * 
 * 
 * ## Quick Values
 * you can set options with some quick values instead of array:
 * 
 * - "fit" 	-> array( 'fit'=>true )
 * - "4" 	-> array( 'span'=>4 )
 * - 4		-> array( 'span'=>4 )
 * 
 * 
 * ## Row Block Configuration
 * "options" property may contains options for the row block element such class name, styles, ids.
 *  
 */
	public function inputs( $fields = array(), $black_list = array(), $options = array() ) {
		
		// OVERLOAD
		// Use CakePHP original fieldset if a fieldset is required!
		if ( array_key_exists('legend',$fields) ) return parent::inputs( $fields, $black_list );
		
		
		
		/**
		 * Properties Configuration
		 */
		
		// Quick options values
		if ( is_string($options) || is_numeric($options) ) {
			
			if ( $options === 'fit' ) {
				$options = array( 'fit'=>true );
			} else {
				$options = array( 'span'=>$options );
			}
			
		}
		
		// Default options array
		$options+= array( 'span'=>'', 'fit'=>false, 'class'=>'' );
		
		// Setup the span width for input's block.
		// auto: tries to fit the row with given block. minimum width is set to "span2"
		// global: you can set a global with passing a "span=4" option to the inputs() method
		if ( empty($options['span']) ) $options['span'] = floor( 12 / count($fields) );
		if ( $options['span'] < 2 ) $options['span'] = 2;
		
		// Create span class name.
		$base_span = 'span' . $options['span'];
		
		
		
		
		/**
		 * Creates Input Blocks
		 */
		
		ob_start();
		foreach ( $fields as $field_name=>$field_options ) {
			
			// accept non detailed items
			if ( is_numeric($field_name) ) {
				$field_name 	= $field_options;
				$field_options 	= array();
			}
			
			// BLOCK SPAN - sets up the basic span width for each field's block if not defined!
			if ( empty($field_options['div']) ) $field_options['div'] = array();
			if ( is_array($field_options['div']) && empty($field_options['div']['class']) ) $field_options['div']['class'] = $base_span;
			
			
			// INPUT FIT - force the input to fit the block width
			if ( $options['fit'] === true ) {
				if ( empty($field_options['class']) ) $field_options['class'] = '';
				if ( strpos($field_options['class'],'span-fit') === false ) $field_options['class'] .= ' span-fit';
			}
			
			
			echo $this->input( $field_name, $field_options );
			
		}
		
		// Clear block's related options values
		unset($options['fit']);
		unset($options['span']);
		
		
		
		/**
		 * Creates the row block
		 * options keys are now used to configure che row-block so you can set additional class names, styles, ids, etc.
		 */
		
		$options['content'] = ob_get_clean(); 
		
		if ( strpos($options['class'],'row-fluid') === false ) $options['class'] .= ' row-fluid';
		
		return $this->Html->tag($options);
	
	}
	
	
	

	
	
/**	
 * create()
 * Initialize a form tag with some defaults options.
 */
	public function create($model = null, $options = array()) {
		
		$options += array( 'class'=>'', 'ajax'=>false );
		
		// Default class for the panel form
		$options['class'] = 'form ' . $options['class'];
		if ( isset($options['class-override']) ) $options['class'] = $options['class-override'];
		unset($options['class-override']);
		
		// Automagically ajaxForm provided by Panel js object
		if ( $options['ajax'] ) $options['data-ajax'] = true;
		unset($options['ajax']);
		
		// Compose the Ajax Redirect hidden option
		$_redirect = '';
		if ( isset($options['_redirect']) ) {
			$_redirect = $options['_redirect'];
			unset($options['_redirect']);
			
			$_redirect = $this->Html->tag(
				'div',
				$this->input( '_redirect',array( 'name'=>'_redirect', 'type'=>'hidden', 'value'=>$_redirect, 'label'=>false, 'div'=>false ) ),
				array( 'style'=>'display:none' )
			);
			
		}
		
		return parent::create($model,$options) . $_redirect;
		
	}
	

	
/**
 * end()
 * Setup some basic classes for the input.
 */
	public function end( $options = null ) {
		
		if ( empty($options) ) return parent::end($options);
		
		/* need to know why have written this hack for the previous panel! */
		
		// Compose the basic label for the submit button
		if ( !is_array($options) ) $options = array( 'label'=>$options );
		if ( !isset($options['label']) ) $options['label'] = __('Save');
		
		$options += array( 'div'=>null );
		
		// Sets up the basic css class for the panel's grid system
		if ( $options['div'] !== false ) {
			if ( empty($options['div']) ) $options['div'] = array();
			if ( !isset($options['div']['class']) ) $options['div']['class'] = 'form-actions';
		}
		
		return parent::end($options);
		
	}

}
