<?php
App::import( 'View/Helper', 'CakePower.PowerFormHelper' );

class PanelFormHelper extends PowerFormHelper {
	
	protected $formClass = 'form';
	
	protected $inputDefaults = array(
		'div' => array(
			'class' => 'control-group'
		),
		'label' => array(
			'class' => 'control-label'
		),
		'error' => array(
			'attributes' => array(
				'wrap'	=> 'div',
				'class' => 'help-inline'
			)
		),
		'format'	=> array('before', 'label', 'between', 'input', 'error', 'after' )
	);
	
	
	/**
	 * Translates inputs fieldset into an in-line input fields
	 * 
	 * every field should implement a "span" attribute to teach how to configure input's wrapper.
	 * you can configure this param in a manner of ways:
	 * 
	 * - numeric:
	 * will be translated into a "spanX" class for the cell to fit TwitterBootstrap's grid columns sizing
	 * 
	 * - CSS string:
	 * "background:red"
	 * will be applied as style property to the cell
	 * 
	 * - string:
	 * will be applied as class string to the cell
	 * 
	 * - array
	 * full tag() configuration array
	 * 
	 * ## INPUT's SIZE
	 * You can setup input's size with standard classing:
	 * .input-small .input-medium, ...
	 * 
	 * because cells lies into a row-fluid container you can set "span12" to each field to fit 100%
	 * of cell size! (this is applied by default)
	 * 
	 */
	public function inputs( $inputs = array() ) {
		
		$inputs = PowerSet::def($inputs);
		unset($inputs['fieldset']);
		unset($inputs['legend']);
		
		$_inputs = array();
		
		foreach ( $inputs as $field=>$settings ) {
			
			if ( is_numeric($field) ) {
				$field = $settings;
				$settings = array();
			}
			
			$settings = PowerSet::def($settings, array(
				'span' => 3,
				'class' => 'span12'
			), 'type');
			
			$span = $settings['span'];
			unset($settings['span']);
			
			$_input = array();
			
			if ( is_numeric($span) ) {
				$_input['class'] = 'span' . $span;
				
			} elseif ( is_array($span) ) {
				$_input = PowerSet::extend($_input,$span);
				
			} elseif ( strpos($span,':') ) {
				$_input['style'] = $span;
				
			} elseif ( is_string($span) ) {
				$_input['class'] = $span;
				
			}
			
			$_input['content'] = $this->input($field, $settings);
			
			$_inputs[] = $_input;
		
		}
		
		return $this->Html->tag(array(
			'class' 	=> 'row-fluid',
			'content' 	=> $_inputs
		));
	
	}
	
	
	
/**
	 * Generates a project custom inputs fields
	 */
	public function input( $fieldName, $options = array() ) {
		
		// Accepts a string option as field label
		if ( is_string($options) ) $options = array( 'label'=>$options );
		
		
		/**
		 * Handles special kinds of inputs
		 */
		if ( !empty($options['type']) ) {
			
			switch ( $options['type'] ) {
				
				case 'radio':
					$options+= array( 'options' );
					$values = $options['options'];
					
					unset($options['type']);
					unset($options['options']);
					
					return $this->radio( $fieldName, $values, $options );
					break;
				
				case 'checkbox':
					$options+= array( 'options'=>'' );
					$values = $options['options'];
					
					unset($options['type']);
					unset($options['options']);
					
					return $this->checkbox( $fieldName, $values, $options );
					break;
				
				case 'tos':
					unset($options['type']);
					return $this->tos( $fieldName, $options );
					break;
				
			}
			
		}
		
		/**
		 * Helper behavior
		 */
		if ( array_key_exists('helper',$options) ) {
			
			$options['data-helper'] = 'on';
			
			// opzioni di default per il comportamento widget
			if ( !array_key_exists('data-placement',$options) ) 		$options['data-placement'] 	= 'right';
			if ( !array_key_exists('data-trigger',$options) ) 			$options['data-trigger'] 	= 'focus';
			
			// estrapolare il titolo del widget
			if ( strpos($options['helper'],'-') !== false ) list( $title, $options['helper'] ) = PowerString::explodeFirstOccourrence( '-', $options['helper'] );
			
			// eredita un titolo predefinito:
			if ( empty($title) && !empty($options['label']) && is_string($options['label']) ) $title = $options['label'];
			if ( empty($title) ) $title = $fieldName;
			
			$options['data-original-title'] = trim($title);
			

			$options['data-content'] = trim($options['helper']);
			unset($options['helper']);
			
		}
		
		
		return parent::input( $fieldName, $options );
		
	}
	
	
	/**
	 * Radio Button
	 */
	public function radio( $fieldName, $radioValues, $options = array() ) {
		
		// options default values
		if ( empty($options) || !is_array($options) ) $options = array();
		$options+= array( 'inline'=>null );
		
		$radio = parent::radio($fieldName,$radioValues,array(
			'legend' 	=> false,
			'separator' => '--**--'
		));
		
		
		$radios = '';
		foreach ( explode('--**--',$radio) as $radio ) {
			
			preg_match_all("|<label(.*)>|U", $radio, $matches);
			$radio = str_replace( $matches[0][0], '', $radio );
			$radio = $matches[0][0] . $radio;
			
			$radios.= str_replace( '<label', '<label class="radio' . ( $options['inline'] === true ? ' inline' : '' ) . '"', $radio );
			
		}
		
		
		// clean inputs related options before wrapper generation
		unset($options['inline']);
		
		
		$field = $this->input($fieldName,$options);
		
		// replace text field with radio values
		preg_match_all("|<input(.*)/>|U", $field, $matches);
		$field = str_replace( $matches[0][0], $radios, $field );
	
		return $field;
		
	}
	
	/**
	 * Checkbox Field
	 */
	public function checkbox( $fieldName, $values, $options = array() ) {
		
		// accepts string value as option
		if ( !is_array($values) && is_string($options) ) $options = array( 'text'=>$options );
		
		// options default values
		if ( empty($options) || !is_array($options) ) $options = array();
		$options+= array( 'hiddenField'=>null, 'inline'=>null );
		
		// generates input markup
		$items = '';
		
		// many values
		if ( is_array($values) ) {
			
			foreach ( $values as $key=>$val ) {
				
				$items.= $this->Html->tag(array(
					'name'	=> 'label',
					'class' => 'checkbox' . ( $options['inline'] === true ? ' inline' : '' ),
					'content' => array(
						parent::checkbox( $fieldName, array(
							'value'			=> $key,
							'hiddenField'	=> empty($items) && $options['hiddenField']!==false
						)),
						$val
					)
				));

			}
		
		// single value with optional disclaimer
		} else {
			
			$options+= array( 'text'=>'' );
			
			$items.= $this->Html->tag(array(
				'name'	=> 'label',
				'class' => 'checkbox',
				'content' => array(
					parent::checkbox( $fieldName, array(
						'value'			=> $values,
						'hiddenField'	=> empty($items) && $options['hiddenField']!==false
					)),
					$options['text']
				)
			));
			
			unset($options['text']);
			
		}
		
		// clean inputs related options before wrapper generation
		unset($options['hiddenField']);
		unset($options['inline']);
		
		// creates a standard input box
		$field = $this->input( $fieldName, $options );
		
		// replace text field with radio values
		preg_match_all("|<input(.*)/>|U", $field, $matches);
		$field = str_replace( $matches[0][0], $items, $field );
		
		
		return $field;
		
	}
	
	
	
	
	
}