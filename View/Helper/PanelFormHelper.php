<?php
/**
 * CakePanel - PanelFormHelper
 * 
 * Extends CakePHP's FormHelper to match the admin layout html structure as default values for
 * more often used methods.
 */



App::import( 'View/Helper', 'FormHelper' );

class PanelFormHelper extends FormHelper {
	
	
	
	
	
	
/**	
 * Single input notation with default values accordling with the Canvas HTML
 */
	public function input( $name, $options = array() ) {
		
		if ( !isset($options['div']) || ( empty($options['div']) && $options['div'] !== false ) ) {
			$options['div'] = 'field-group';
			
		} else if ( is_array($options['div']) && empty($options['div']['class']) ) {
			$options['div']['class'] = 'field-group';
			
		}
		
		
		if ( !array_key_exists('between',$options) ) $options['between'] 	= '<div class="field">';
		if ( !array_key_exists('after',$options) ) $options['after'] 		= '</div>';
		
		// tipsy default position.
		if ( !array_key_exists('data-tipsy-gravity',$options) ) $options['data-tipsy-gravity'] = 'sw';
		
		return parent::input( $name, $options );
	
	}
	
	
/**	
 * Multiple input notation accordling to the Canvas HTML!!!
 */
	public function inputs( $fields = array() ) {
		
		$model = $this->model();
		
		if ( is_array($fields) ) {
			
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
		return String::insert('<div class="field-group"><label>:label</label>:fields</div>',array(
			'label' 	=> $legend,
			'fields' 	=> ob_get_clean()
		));
		
	}

}