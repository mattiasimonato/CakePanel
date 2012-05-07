<?php
/**
 * CakePanel - PanelFormHelper
 * 
 * Extends CakePHP's FormHelper to match the admin layout html structure as default values for
 * more often used methods.
 */



App::import( 'View/Helper', 'CakePower.PowerFormHelper' );

class PanelFormHelper extends PowerFormHelper {
	
	
	
	
	
	
/**	
 * Single input notation with default values accordling with the Canvas HTML
 */
	public function input( $name, $options = array() ) {
		
		if ( !is_array($options) ) $options = array( 'label'=>$options );
		
		if ( !isset($options['div']) || ( empty($options['div']) && $options['div'] !== false ) ) {
			$options['div'] = 'field-group';
			
		} else if ( is_array($options['div']) && empty($options['div']['class']) ) {
			$options['div']['class'] = 'field-group';
			
		}
		
		// Wraps works only if a div is required!
		if ( $options['div'] !== false ) {
			if ( !array_key_exists('between',$options) ) $options['between'] 	= '<div class="field">';
			if ( !array_key_exists('after',$options) ) $options['after'] 		= '</div>';
		}
		
		// tipsy default position.
		if ( !array_key_exists('data-tipsy-gravity',$options) ) $options['data-tipsy-gravity'] = 'sw';
		
		return parent::input( $name, $options );
	
	}
	
	
/**	
 * Multiple input notation accordling to the Canvas HTML!!!
 * 
 * $block_options may contain some HTML attributes to enrich the block container.
 * $block_options['label'] may contain some HTML attributes to enrich the label tag as block title!
 */
	public function inputs( $fields = array(), $blacklist = array(), $block_options = array() ) {
		
		$model = $this->model();
		
		if ( is_array($fields) ) {
			
			$fields+= array( 'legend'=>false, 'fieldset'=>false );
			
			if ( array_key_exists('legend',$fields) ) {
				$legend = $fields['legend'];
				unset($fields['legend']);
			}
			
			if ( array_key_exists('fieldset',$fields) ) {
				$fieldset = $fields['fieldset'];
				unset($fields['fieldset']);
			}
			
		} else {
			$legend = $fields;
			$fields = array();
		}
		
		if ( empty($fieldset) ) $fieldset = '';
		
		if ( empty($fields) ) $fields = array_keys($this->_introspectModel($model, 'fields'));
		
		
		// Start to build the fields from the list.
		ob_start();
		foreach ( $fields as $field=>$options ) {
			
			// Get boot fieldName and fieldName=>fieldOptions structure.
			if ( is_numeric($field) ) {
				$field 		= $options;
				$options 	= array();
			}
			
			// Output the inplut + label.
			ob_start();
			
			
			// Compose label's default settings.
			if ( empty($options['label']) ) {
				$labelText 		= null;
				$labelOptions 	= array();
				
			} else if ( is_array($options['label']) ) {
				$labelOptions 	= $options['label'];
				if ( empty($labelOptions['text']) ) $labelOptions['text'] = null;
				$labelText 		= $labelOptions['text'];
				unset($labelOptions['text']);
				
			} else {
				$labelText		= $options['label'];
				$labelOptions	= array();
			}
			
			unset($options['label']);
			
			// Echo the input
			echo $this->input($field,array_merge($options,array(
				'div' 		=> false,
				'label' 	=> false,
				'between'	=> '',
				'after'		=> ''
			)));
			
			// Echo the label
			echo $this->label( $field, $labelText, $labelOptions );
			
			echo $this->Html->tag('div', ob_get_clean(), array(
				'class'=>'field'
			));
		}
		
		
		
		
		// Create the multiple fields markup.
		
		$block_options+= array( 'label'=>array(), 'class'=>'field-group' );
		
		// Build the block legend
		if ( $legend ) $legend = $this->Html->tag( 'label', $legend, $block_options['label'] );
		unset($block_options['label']);
		
		// Append classes to the container.
		if ( isset($block_options['add_class']) ) {
			$block_options['class'].= ' ' . $block_options['add_class'];
			unset($block_options['add_class']);	
		}
		
		return $this->Html->tag('div',array(
			$legend,
			ob_get_clean()
		),$block_options);
		
	}
	
	

	
	
/**	
 * create()
 * Initialize a form tag with some defaults options.
 */
	public function create($model = null, $options = array()) {
		
		$options += array( 'class'=>'', 'ajax'=>false );
		
		// Default class for the panel form
		$options['class'] = 'form uniformForm ' . $options['class'];
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
		
		// Compose the basic label for the submit button
		if ( !is_array($options) ) $options = array( 'label'=>$options );
		if ( !isset($options['label']) ) $options['label'] = __('Save');
		
		$options += array( 'div'=>null );
		
		// Sets up the basic css class for the panel's grid system
		if ( $options['div'] !== false ) {
			if ( empty($options['div']) ) $options['div'] = array();
			if ( !isset($options['div']['class']) ) $options['div']['class'] = 'grid-24 form-actions';
		}
		
		return parent::end($options);
		
	}

}