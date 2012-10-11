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
 * PanelTableUI
 * 
 * Is a visual component do handle and display a data table with no HTML code.
 * 
 * This is a starting point to write your own PanelTableUI extension with callbacks to 
 * output cell data the way you like!
 * 
 * USAGE EXAMPLE:
 * 
 * $table = new PanelTableUI($this,array(  // $this is the View istance.
 *     'columns' => array( 'id', 'title', 'created' )
 * )); 
 * $table->show( $model_find_results );
 * @author peg
 *
 */



class PanelTableUI extends PanelHtmlHelper {
	
	public $modelName = '';
	
	
/**
 * List all columns to be displayed into the table.
 * Each item must define a model's fiels as key.
 * 
 * It is possible to add per-item configuration as an associative
 * values passed to the cells.
 */	
	public $columns = array(
		/*
		'id' => array(
			'show' 	=> 'ID',
			'th' => array(
				'id' 	=> 'th-id',
				'class' => 'a class',
				'style' => 'background:red
			),
			'td' => array(
				'class' => 'td-class'
			),
			'cellTag' => 'td' // the tag to apply to the body
		),
		'username',
		'created' => array(
			'show'	=> 'Created on:'
		)
		*/
	);

	
	
	
/**	
 * A set of html attributes to configure the table heading tags
 */
	public $thead = array(
		/*
		// These values are THEAD's tag html properties.
		// all optionals
		'class' => '',
		'id' 	=> '',
		
		// "tr" is uses as html properties for the THEAD > TR tag
		// optional.
		'tr' 	=> array(
			'class' => '',
			'is'	=> ''		
		),
		
		// "th" is used as default values for each header column
		// optional.
		'th' => array(
			'class'	=> '',
		),
		
		// Options for table headings
		'translate' => false,	// apply __() to the headers
		'sort' 		=> false	// apply View's Paginator->sort() to the headers
		*/
	);
	
	
/**	
 * A set of html attributes to be used to build the table body
 */
	public $tbody = array(
		/*
		'class' => array(),
		'tr' => array(),
		'th' => array(),
		'td' => array()
		 */		
	);
	
	
	public $table = array();
	
	
	
	public function __construct(View $View, $settings = array()) {
		
		// Parse internal settings and trigger the helper constructor.
		parent::__construct($View, $this->settings($settings) );
		
		// Initialize internal components
		$this->_initColumns();
		$this->_initBodyOptions();
	
	}
	
	
	
	
	
/**
 * Extract the table configuration from helper initialization config array.
 * This method may be overridden in custom table UIs to setup the instance.
 * @param array $settings
 */	
	protected function settings( $settings = array() ) {
		
		
		// Apply default values to the settings array
		$settings+= array( 'modelName'=>'', 'columns'=>array(), 'table'=>array(), 'thead'=>array(), 'tbody'=>array() );
		
		
		
		// Ports settings into internal attributes.
		$this->modelName = $settings['modelName'];
		unset($settings['modelName']);
		
		$this->columns = $settings['columns'];
		unset($settings['columns']);
		
		$this->table = $settings['table'];
		unset($settings['table']);
		
		$this->thead = $settings['thead'];
		unset($settings['thead']);
		
		$this->tbody = $settings['tbody'];
		unset($settings['tbody']);
		
		
		
		// Attributes normalization.
		if ( !is_array($this->table) ) $this->table = array( 'class'=>$this->table );
		if ( !is_array($this->thead) ) $this->thead = array( 'class'=>$this->thead );
		if ( !is_array($this->tbody) ) $this->tbody = array( 'class'=>$this->tbody );
		
		return $settings;
	
	}
	
	
	
	
	
	
/**
 * Render the HTML table from local configuration and passed data.
 * Return HTML table code.
 * 
 * @param array $rows
 */	
	public function show( $rows = array() ) {
		
		$table = $this->_tbHead();
		$table.= $this->_tbBody( $rows );
		
		$this->table += array( 'class'=>'table table-striped data-table' );
		
		return $this->tag( 'table', $table, $this->table );
		
	}
	
	protected function _tbHead() {
		
		// Thead options default values;
		$this->thead+= array( 'tr'=>array(), 'th'=>array(), 'translate'=>false, 'sort'=>false );
		
		
		
		// Compose the fields list using the "columns" property.
		ob_start();
		foreach ( $this->columns as $key=>$val ) {
			
			$show = $val['show'];
			unset($val['show']);
			
			// Tranlsate and add paginator links based on options.
			if ( $this->thead['translate'] ) $show = __($show);
			if ( $this->thead['sort'] && isset($this->_View->Paginator) ) $show = $this->_View->Paginator->sort($key,$show);
			
			echo $this->tag( 'th', $show, array_merge($this->thead['th'],$val['th']) );
			
		}
		
		$thead = $this->tag( 'tr', ob_get_clean(), $this->thead['tr'] );
		
		
		
		// Remove thead unuseful properties to pass html properties to the THEAD tag.
		unset($this->thead['tr']);
		unset($this->thead['th']);
		unset($this->thead['translate']);
		unset($this->thead['paginator']);
		
		return $this->tag( 'thead', $thead, $this->thead );
		
	}
	
	protected function _tbBody( $rows ) {
		
		// Compose the rows
		ob_start();
		foreach ( $rows as $idx=>$row ) {
			
			echo $this->_tbRow( $row, $idx );
			
		}
		
		// Remove unuseful properties
		$tbody_options = $this->tbody;
		unset($tbody_options['td']);
		unset($tbody_options['th']);
		unset($tbody_options['tr']);
		
		return $this->tag( 'tbody', ob_get_clean(), $tbody_options );
		
	}
	
	protected function _tbRow( $row, $idx ) {
		
		if ( (($idx+1)%2) ) $type = 'odd'; else $type = 'even';
		
		ob_start();
		foreach ( $this->columns as $key=>$val ) {
			
			
			/**
			 * custom table UIs may define a function for each fiel named:
			 * 
			 * function fld{FieldName}( $row, $idx, $type ) { return "foo"; }
			 * 
			 * These methods returns the content for the cell.
			 * If no custom method are found "cell()" method is used.
			 */
			
			$methodName = 'fld' . Inflector::camelize($key);
			
			if ( method_exists($this,$methodName) ) {
				$show = call_user_method( $methodName, $this, $row, $idx, $type );
				
			} else {
				$show = $this->cell( $row, $key, $idx, $type );
					
			}
			
			$cellOptions = array_merge(
				$val[$val['cellTag']],							// Columns attributes 
				$this->_tbody[$val['cellTag'].'_'.$type] 		// body cell attributes
			);
			
			echo $this->tag( $val['cellTag'], $show, $cellOptions );
			
		}
		
		return $this->tag( 'tr', ob_get_clean(), $this->_tbody['tr_'.$type] );
		
	}
	

/**	
 * Compose the content of a cell.
 * Custom table UIs may implement this method the way they needs! 
 * 
 * @param array $data
 * @param string $key
 * @param int $idx
 * @param string $type
 */
	public function cell( $data, $key, $idx, $type ) {
		
		if ( empty($data[$this->modelName][$key]) ) return;
		
		return $data[$this->modelName][$key];
	
	}
	
	
	
	
	
	
	
	
	
/**	
 * Read the $columns property and applies some default values.
 */
	protected function _initColumns() {
		
		$columns = array();
		
		foreach ( $this->columns as $key=>$val ) {
		
			if ( is_numeric($key) ) {
				
				$num = $key;
				$key = $val;
				$val = array();
				
			} else {
				
				if ( !is_array($val) ) $val = array(
					'show' => $val				
				);
				
			}
		
			$val+= array( 'show'=>$key, 'th'=>array(), 'td'=>array(), 'cellTag'=>'td' );
			
			
			// Arttributes applied to the column are traslated to the cell type containers:
			if ( isset($val['style']) ) {
				$val['th']['style'] = $val['style'];
				$val['td']['style'] = $val['style'];
				unset($val['style']);
			}
			
			if ( isset($val['class']) ) {
				$val['th']['class'] = $val['class'];
				$val['td']['class'] = $val['class'];
				unset($val['style']);
			}
		
			// Add the complete columns information.
			$columns[$key] = $val;
		
		}

		$this->columns = $columns;
		
	}
	
	
/**	
 * Compose the body options to apply attributes to tbody, rows and cells
 */
	protected function _initBodyOptions() {
		
		$this->tbody+= array( 'tr'=>array(), 'td'=>array(), 'th'=>array() );
		$this->tbody['tr']+= array( 'odd'=>array(), 'even'=>array() );
		$this->tbody['td']+= array( 'odd'=>array(), 'even'=>array() );
		$this->tbody['th']+= array( 'odd'=>array(), 'even'=>array() );
		
		$tr			= $this->tbody['tr'];
		$tr_odd 	= $tr['odd'];
		$tr_even	= $tr['even'];
		unset($tr['odd']);
		unset($tr['even']);
		
		$td			= $this->tbody['td'];
		$td_odd 	= $td['odd'];
		$td_even	= $td['even'];
		unset($td['odd']);
		unset($td['even']);
		
		$th			= $this->tbody['th'];
		$th_odd 	= $th['odd'];
		$th_even	= $th['even'];
		unset($th['odd']);
		unset($th['even']);
		
		
		$this->_tbody = array(
			'tr'		=> $tr,
			'tr_odd'	=> array_merge( $tr, $tr_odd ),
			'tr_even'	=> array_merge( $tr, $tr_even ),
			'td'		=> $td,
			'td_odd'	=> array_merge( $td, $td_odd ),
			'td_even'	=> array_merge( $td, $td_even ),
			'th'		=> $th,
			'th_odd'	=> array_merge( $th, $th_odd ),
			'th_even'	=> array_merge( $th, $th_even ),
		);
		
	}
	
	
	
	
	
	
	
	

/**	
 * Standard Actions List
 * this method offer an "edit" and "delete" actions for the row.
 * 
 * you can override this method to customize row actions.
 */
	protected function fldActions($data) {
		
		return $this->tag('div',array(
			$this->editAction(array( 'action'=>'edit', $data[$this->modelName]['id'] )),
			' | ',
			$this->deleteAction(array( 'action'=>'delete', $data[$this->modelName]['id'] ))
		));
		
	}
	
}