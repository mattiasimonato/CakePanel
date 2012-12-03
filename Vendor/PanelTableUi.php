<?php
/**
 * PanelTableUI
 * ============
 * 
 * @author peg
 *
 */

App::uses( 'PowerTableUi', 'CakePower.Vendor' );

class PanelTableUi extends PowerTableUi {
	
	
	protected $_settings = array(
		'model'			=> '',
		'sortable'		=> true,
		'translate'		=> true,
		'table' 		=> array(
			'class' 		=> 'table',
			'classes' 		=> '',
			'striped' 		=> true,
			'bordered' 		=> true,
			'hover' 		=> true,
			'condensed' 	=> false
		),
		'caption' 		=> array(),
		'thead' 		=> array(
			'tagName' 		=> 'th',
			'tr' 			=> array(),
			'th' 			=> array(),
			'td' 			=> array()
		),
		'tbody' 		=> array(
			'tagName' 		=> 'td',
			'tr' 			=> array(),
			'th' 			=> array(),
			'td' 			=> array()
		),
		'tfoot' 		=> array(
			'tagName' 		=> 'th',
			'tr' 			=> array(),
			'th' 			=> array(),
			'td' 			=> array()
		),
		'columns' 		=> array(),
		'helperConfig' 	=> array(),
		'data' 			=> array()
	);
	
	
	
protected function renderTbodyCellItem($name, $config=array()) {
		
		$config = PowerSet::extend(array(
			'model' => $this->settings['model'],
			'field' => 'id', 
			'label' => '',
			'url'	=> array(),
			'class'	=> ''
		),$config);
		
		$model = $config['model'];
		unset($config['model']);
		
		$field = $config['field'];
		unset($config['field']);
		
		$show = $config['label'];
		unset($config['label']);
		
		$url = $config['url'];
		unset($config['url']);
		
		
		// Apply defaults for known values
		switch ( strtolower($name) ) {
			
			case 'view':
				if ( !isset($config['icon']) ) 			$config['icon'] 	= 'zoom-in';
				if ( !isset($config['title']) ) 		$config['title'] 	= 'Open Item';
				if ( substr($name,0,1) === 'V' ) 		$config['class'].= ' btn-primary';
				break;
			
			case 'edit':
				if ( !isset($config['icon']) ) 			$config['icon'] 	= 'edit';
				if ( !isset($config['title']) ) 		$config['title'] 	= 'Edit Item';
				if ( substr($name,0,1) === 'E' ) 		$config['class'].= ' btn-primary';
				break;
				
			case 'delete':
				if ( !isset($config['icon']) ) 			$config['icon'] 			= 'trash';
				if ( !isset($config['title']) ) 		$config['title'] 			= 'Delete Item';
				if ( !isset($config['data-confirm']) ) 	$config['data-confirm'] 	= 'Are you sure?';
				if ( substr($name,0,1) === 'D' ) 		$config['class'].= ' btn-danger';
				break;
				
			case 'up':
				if ( !isset($config['icon']) ) 	$config['icon'] 	= 'arrow-up';
				if ( !isset($config['title']) ) 	$config['title'] 	= 'Move Up';
				if ( substr($name,0,1) === 'U' ) $config['class'].= ' btn-primary';
				break;
				
			case 'down':
				if ( !isset($config['icon']) ) 	$config['icon'] 	= 'arrow-down';
				if ( !isset($config['title']) ) 	$config['title'] 	= 'Move Down';
				if ( substr($name,0,1) === 'D' ) $config['class'].= ' btn-primary';
				break;
				
			case 'left':
				if ( !isset($config['icon']) ) 	$config['icon'] 	= 'arrow-left';
				if ( !isset($config['title']) ) 	$config['title'] 	= 'Move Left';
				if ( substr($name,0,1) === 'L' ) $config['class'].= ' btn-primary';
				break;
				
			case 'right':
				if ( !isset($config['icon']) ) 	$config['icon'] 	= 'arrow-right';
				if ( !isset($config['title']) ) 	$config['title'] 	= 'Move Right';
				if ( substr($name,0,1) === 'R' ) $config['class'].= ' btn-primary';
				break;
		
		}
		
		// apply icons to the link
		if ( !empty($config['icon']) ) {
			if ( strpos($config['class'],'btn-') !== false ) $config['icon'] .= ' icon-white';
			$show = '<i class="icon-' . $config['icon'] . '"></i> ' . $show;
			unset($config['icon']);
		}
		
		// default url composed by known action name and subject id
		if ( empty($url) ) {
			
			$url = array(
				'action' => strtolower($name),
				$this->_row[$model][$field]
			);
		
		// url was given as array, find and replace {id} value
		} elseif ( is_array($url) ) {
			foreach ( $url as $key=>$val ) {
				if ( strpos($val,'{id}') !== false ) $url[$key] = str_replace('{id}', $this->_row[$model][$field], $val);
			}
		
		// closure callback 
		} elseif ( is_callable($url) ) {
			$url = call_user_func( $url, $this->_row, $this );
			if ( empty($url) ) $url = array();
		
		// non valid value
		} else {
			$url = array();
			
		}
		
		// default button name
		if (empty($show)) {
			$show = $name;
		}
		
		// adds button classes
		$config['class'] = 'btn btn-small ' . $config['class'];
		
		return $this->link( $show, $url, $config );
		
	}

}