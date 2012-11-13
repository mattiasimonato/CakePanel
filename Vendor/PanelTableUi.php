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
PanelTableUI
============
 
Is a visual component do handle and display a data table with no HTML code.
 
This is a starting point to write your own PanelTableUI extension with callbacks to 
output cell data the way you like!
 
## Example:

You can run PanelTableUI as instance of the main object

	App::uses( 'Vendor', 'CakePanel.PanelTableUI' );
	$tb = new PanelTableUI($this);
	echo $tb->show($list);

Code above will display the entire data set using first model it can find inside.  
If dataset contains more than one model you may need to use some options!

## _modelName_ and _columns_

 You can define what model to use and what columns to display with a little help
 of a configuration array:
	
	$tb = new PanelTableUI($this,array(
	  'modelName' => 'CakeAuthUser',
	  'columns' => array('id','username','created')
	));
	echo $tb->show($list);
	
Code above will filter dataset to a single model and will define what columns to display





## Columns Options

You can also define some options for each column:

	array(
	  'id' => array(
	    'show' => 'ID',
	    'cellTag' => 'th',
	    'th' => 'text-align:center;background:#eee'
	  ),
	  'username' => array(
	    'show' 	=> 'User',
	    'class' => 'uname',
	    'style' => 'background:#f4f4aa',
	    'th'	=> 'background:#eaea44',
	  ),
	  'created' => 'Creation Date'
	)

If you set a string value as column's content this value will be applied as "show" value for that column

### show

Column's label

### class

Column's class, applied to both header's and body's cells

if you define something like:

	'id' => array(
	  'class' => 'aaa',
	  'th' => 'bbb',
	  'td' => 'ccc'
	)

the resulting classes for TD and TH tags will be:

	TH: "aaa bbb"
	TD: "aaa ccc"
	
because cell's class extends column's class

### style

Column's inline CSS, applied to both header's and body's cells

if you define something like:

	'id' => array(
	  'style' => 'background:red',
	  'th' => 'background:yellow',
	  'td' => 'background:green'
	)

the resulting styles for TD and TH tags will be:

	TH: "background:yellow"
	TD: "background:green"
	
because cell's style overrides column's style

### th

Options for TH cells in array format.

	array(
	  'class' => 'foo',
	  'style' => 'background:yellow'
	)

You an also pass a string value, it should be a class name or a style rule.

### td

Options for TD cells in array format.

You an also pass a string value, it should be a class name or a style rule.

### cellTag

You can describe what kind of cell tag to be used into the table body.

By default is set to "td" but you can change it to implement "th" inside body too!





## Table Options

You an give some options to drive table's rendering rules.

Some of these options are based on Twitter Bootstrap table class options

### class

[string]  
apply a custom class name to the table. default is "table"

### style

[string]  
apply custom inline CSS to the table

### striped

[bool]  
add stripes to the table

### bordered

[bool]  
add borders

### hover

[bool]  
add hovering higlightion

### condensed

[bool]  
render a condensed table






## Option: "table" 

Array of HTML properties to be applied to the TABLE tag



## Option: "thead" 

With "thead" config key you can set some default values for thead tags styles, activate label translation and column sorting:

	array(
	  'sort' => true,
	  'translate' => true,
	  'tr' => array(
	    'class'=> 'tr_class',
	    'style'=> 'color:red'
	  ),
	  'th' => 'background:yellow,
	  'class' => 'aa',
	  'style' => '...'
	)

All stiling options defined here are overridden by deep configuration such column's th options!

"class" and "style" options are applied to the THEAD tag.

**NOTE:** Tranlsation option is available only if Paginator was used controller side.


## Option: "tbody" 

Array of HTML properties to be applied to the TBODY tag.

You an also implement the "th" and "th" keys to set defaults for tbody's cells.  
Each configuration is overridden by column's configuration!

You can also define an "odd" and "even" sub-keys for tr, td, th to detail cell configuration
in even/odd cases.


*/



class PanelTableUI extends PanelHtmlHelper {
	
	protected $modelName = '';
	
/**
 * List all columns to be displayed into the table.
 * Each item must define a model's fiels as key.
 * 
 * It is possible to add per-item configuration as an associative
 * values passed to the cells.
 */	
	protected $columns = array(
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
 * a set of html attributes to be applied to TABLE tag.
 * a string value is uses as "class" attribute.
 */	
	protected $table = array();
	
	
/**
 * a set of preoperties to define a default class name for the table
 * reflects TwitterBootstrap table options
 */	
	protected $class		= 'table';
	protected $striped 		= false;
	protected $bordered 	= true;
	protected $hover 		= true;
	protected $condensed 	= false;
	
/**	
 * default "style" option for inline css
 */
	protected $style		= '';
	
	
	
	
	
	
/**	
 * A set of html attributes to configure the table heading tags
 */
	protected $thead = array(
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
 * Sortable columns default value
 * @var bool
 */
	protected $sort = true;
	
	
/**	
 * Column's label translation default value
 * @var bool
 */
	protected $translate = true;
	
	
/**	
 * A set of html attributes to be used to build the table body
 */
	protected $tbody = array(
		/*
		'class' => array(),
		'tr' => array(),
		'th' => array(),
		'td' => array()
		 */		
	);
	

	
	
	
	
	
	
	
	public function __construct(View $View, $settings = array()) {
		
		// Adds some always useful helpers
		$this->helpers[] = 'CakePanel.Panel';
		
		// Parse internal settings and trigger the helper constructor.
		parent::__construct($View, $this->settings($settings) );
		
		// Initialize internal components
		$this->_initThead();
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
		if ( !empty($settings['modelName']) ) $this->modelName = $settings['modelName'];
		unset($settings['modelName']);
		
		if ( !empty($settings['columns']) ) $this->columns = $settings['columns'];
		unset($settings['columns']);
		
		if ( !empty($settings['table']) ) $this->table = $settings['table'];
		unset($settings['table']);
		
		if ( !empty($settings['thead']) ) $this->thead = $settings['thead'];
		unset($settings['thead']);
		
		if ( !empty($settings['tbody']) ) $this->tbody = $settings['tbody'];
		unset($settings['tbody']);
		
		
		/**
		 * Table attributes configuration
		 */
		
		if ( isset($settings['class']) ) {
			$this->class = $settings['class'];
			unset($settings['class']);
		}
		
		if ( isset($settings['striped']) ) {
			$this->striped = $settings['striped'];
			unset($settings['striped']);
		}
		
		if ( isset($settings['bordered']) ) {
			$this->bordered = $settings['bordered'];
			unset($settings['bordered']);
		}
		
		if ( isset($settings['hover']) ) {
			$this->hover = $settings['hover'];
			unset($settings['hover']);
		}
		
		if ( isset($settings['condensed']) ) {
			$this->condensed = $settings['condensed'];
			unset($settings['condensed']);
		}
		
		if ( isset($settings['style']) ) {
			$this->style = $settings['style'];
			unset($settings['style']);
		}
		
		
		
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
		
		// implicit model name
		if ( empty($this->modelName) && !empty($rows) ) {
			$this->modelName = array_shift(array_keys($rows[0]));
		}
		
		// implicit columns listing
		if ( empty($this->columns) && !empty($this->modelName) ) {
			$this->columns = array_keys($rows[0][$this->modelName]);
			$this->_initColumns();
		}
		
		// builds internal blocks
		$table = $this->_tbHead();
		$table.= $this->_tbBody( $rows );
		
		// creates table's base class
		$class = $this->class;
		if ( $this->striped ) 	$class.= ' ' . $this->class . '-striped';
		if ( $this->bordered ) 	$class.= ' ' . $this->class . '-bordered';
		if ( $this->hover ) 	$class.= ' ' . $this->class . '-hover';
		if ( $this->condensed ) $class.= ' ' . $this->class . '-condensed';
		
		// apply default values to the class configuration objects
		$this->table += array( 'class'=>'', 'style'=>'' );
		
		// apply "class" attribute
		$this->table['class'] = $class .= ' ' . $this->table['class'];
		
		// apply "style" attribute
		if ( empty($this->table['style']) ) $this->table['style'] = $this->style;
		
		// apply overrides
		$this->table = PowerSet::configOverride($this->table);
		
		return $this->tag( 'table', $table, $this->table );
		
	}
	
	protected function _tbHead() {
		
		// Compose the fields list using the "columns" property.
		ob_start();
		foreach ( $this->columns as $key=>$val ) {
			
			// Assign display valye
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
		
		// evaluate row's type
		if ( (($idx+1)%2) ) $type = 'odd'; else $type = 'even';
		
		// builds row's fields
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
			
			/**
			 * build configuration for the cell tag
			 * 
			 * function configFld{FieldName}( $row, $idx, $type ) { return "foo"; }
			 */
			
			$cellOptions = array_merge(
				$this->_tbody[$val['cellTag'].'_'.$type], 		// body cell attributes
				$val[$val['cellTag']]							// Columns attributes
			);
			
			$methodName = 'config' . ucfirst($methodName);
			
			if ( method_exists($this,$methodName) ) {
				$cellOptions = call_user_method( $methodName, $this, $cellOptions, $row, $idx, $type );
				
			} else {
				$cellOptions = $this->cellConfig( $cellOptions, $row, $key, $idx, $type );
					
			}
			
			echo $this->tag( $val['cellTag'], $show, PowerSet::configOverride($cellOptions) );
			
		}
		
		// builds the row's config by apply callback config method
		$rowConfig = $this->rowConfig($this->_tbody['tr_'.$type],$row,$idx,$type);
		$rowCOnfig = PowerSet::configOverride($rowConfig);
		
		// builds the row's tag
		return $this->tag( 'tr', ob_get_clean(), $rowConfig );
		
	}
	
/**
 * Callback: Custom table UIs may alter row's properties to set classes or custom attributes
 * 
 * @param array $cfg
 * @param array $row
 * @param int $idx
 * @param type $type [odd|even]
 */	
	protected function rowConfig( $cfg, $row, $idx, $type ) { return $cfg; }
	
	protected function cellConfig( $cfg, $row, $key, $idx, $type ) { return $cfg; }
	

/**	
 * Compose the content of a cell.
 * Custom table UIs may implement this method the way they needs! 
 * 
 * @param array $data
 * @param string $key
 * @param int $idx
 * @param string $type
 */
	protected function cell( $data, $key, $idx, $type ) {
		
		if ( empty($data[$this->modelName][$key]) ) return;
		
		return $data[$this->modelName][$key];
	
	}
	
	
	
	
	
/**
 * Thead config normalization
 */	
	protected function _initThead() {
		
		// boolean value to set up columns sorting
		if ( $this->thead === true ) $this->thead = array( 'sort'=>true );
		
		
		// Thead options default values;
		if ( empty($this->thead) || !is_array($this->thead) ) $this->thead = array();
		$this->thead+= array( 'tr'=>array(), 'th'=>array(), 'translate'=>$this->translate, 'sort'=>$this->sort );
		
		
		// accepts string TR configuration
		// "style" overrides parent's configurations, class extends it!
		if ( is_string($this->thead['tr']) ) {
			if ( strpos($this->thead['tr'],':') !== false ) {
				$this->thead['tr'] = array( 'style'=>$this->thead['tr'] );
			} else {
				$this->thead['tr'] = array( 'class'=>$this->thead['tr'] );
			}
		}
		
		if ( empty($this->thead['tr']) || !is_array($this->thead['tr']) ) $this->thead['tr'] = array();
		$this->thead['tr'] += array( 'class'=>'', 'style'=>'' );
		
		
		// accepts string TH configuration
		// "style" overrides parent's configurations, class extends it!
		if ( is_string($this->thead['th']) ) {
			if ( strpos($this->thead['th'],':') !== false ) {
				$this->thead['th'] = array( 'style'=>$this->thead['th'] );
			} else {
				$this->thead['th'] = array( 'class'=>$this->thead['th'] );
			}
		}
		
		if ( empty($this->thead['th']) || !is_array($this->thead['th']) ) $this->thead['th'] = array();
		$this->thead['th'] += array( 'class'=>'', 'style'=>'' );
	
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
			
			// accepts string TH configuration
			// "style" overrides parent's configurations, class extends it!
			if ( is_string($val['th']) ) {
				if ( strpos($val['th'],':') !== false ) {
					$val['th'] = array( 'style'=>$val['th'] );
				} else {
					$val['th'] = array( 'class'=>$val['th'] );
				}
			}
			
			// accepts string TD configuration
			// "style" overrides parent's configurations, class extends it!
			if ( is_string($val[$val['cellTag']]) ) {
				if ( strpos($val[$val['cellTag']],':') !== false ) {
					$val[$val['cellTag']] = array( 'style'=>$val[$val['cellTag']] );
				} else {
					$val[$val['cellTag']] = array( 'class'=>$val[$val['cellTag']] );
				}
			}
			
			
			
			// Attributes applied to the column are traslated to the cell type containers:
			// column's style is overridden by column's property.
			if ( isset($val['style']) ) {
				if ( empty($val['th']['style']) ) $val['th']['style'] = $val['style'];
				if ( empty($val['td']['style']) ) $val['td']['style'] = $val['style'];
				unset($val['style']);
			}
			
			// class cell attribute extends column's one!
			if ( isset($val['class']) ) {
				
				$val['th'] += array( 'class'=>'' );
				$val['th']['class'] = $val['class'] . ' ' . $val['th']['class'];
				
				$val['td'] += array( 'class'=>'' );
				$val['td']['class'] = $val['class'] . ' ' . $val['td']['class'];
				
				unset($val['style']);
			}
			
			// Add the complete columns information.
			$columns[$key] = PowerSet::configOverride($val);
		
		}

		$this->columns = $columns;
		
	}
	
	
/**	
 * Compose the body options to apply attributes to tbody, rows and cells
 */
	protected function _initBodyOptions() {
		
		if ( empty($this->tbody) || !is_array($this->tbody) ) $this->tbody = array();
		$this->tbody+= array( 'tr'=>array(), 'td'=>array(), 'th'=>array() );
		
		// accepts string TR configuration
		if ( is_string($this->tbody['tr']) ) {
			if ( strpos($this->tbody['tr'],':') !== false ) {
				$this->tbody['tr'] = array( 'style'=>$this->tbody['tr'] );
			} else {
				$this->tbody['tr'] = array( 'class'=>$this->tbody['tr'] );
			}
		}
		
		if ( empty($this->tbody['tr']) || !is_array($this->tbody['tr']) ) $this->tbody['tr'] = array();
		$this->tbody['tr']+= array( 'odd'=>array(), 'even'=>array() );
		
		
		// accepts string TD configuration
		if ( is_string($this->tbody['td']) ) {
			if ( strpos($this->tbody['td'],':') !== false ) {
				$this->tbody['td'] = array( 'style'=>$this->tbody['td'] );
			} else {
				$this->tbody['td'] = array( 'class'=>$this->tbody['td'] );
			}
		}
		
		if ( empty($this->tbody['td']) || !is_array($this->tbody['td']) ) $this->tbody['td'] = array();
		$this->tbody['td']+= array( 'odd'=>array(), 'even'=>array() );
		
		
		// accepts string TH configuration
		if ( is_string($this->tbody['th']) ) {
			if ( strpos($this->tbody['th'],':') !== false ) {
				$this->tbody['th'] = array( 'style'=>$this->tbody['th'] );
			} else {
				$this->tbody['th'] = array( 'class'=>$this->tbody['th'] );
			}
		}
		
		if ( empty($this->tbody['th']) || !is_array($this->tbody['th']) ) $this->tbody['th'] = array();
		$this->tbody['th']+= array( 'odd'=>array(), 'even'=>array() );
		
		
		
		// Initializie even/odd details
		
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
		
		
		
		// accepts string TR-ODD-EVEN configuration
		if ( is_string($tr_odd) ) {
			if ( strpos($tr_odd,':') !== false ) {
				$tr_odd = array( 'style'=>$tr_odd );
			} else {
				$tr_odd = array( 'class'=>$tr_odd );
			}
		} elseif ( empty($tr_odd) || !is_array($tr_odd) ) $tr_odd = array();
		
		if ( is_string($tr_even) ) {
			if ( strpos($tr_even,':') !== false ) {
				$tr_even = array( 'style'=>$tr_even );
			} else {
				$tr_even = array( 'class'=>$tr_even );
			}
		} elseif ( empty($tr_even) || !is_array($tr_even) ) $tr_even = array();
		
		// accepts string TH-ODD-EVEN configuration
		if ( is_string($th_odd) ) {
			if ( strpos($th_odd,':') !== false ) {
				$th_odd = array( 'style'=>$th_odd );
			} else {
				$th_odd = array( 'class'=>$th_odd );
			}
		} elseif ( empty($th_odd) || !is_array($th_odd) ) $th_odd = array();
		
		if ( is_string($th_even) ) {
			if ( strpos($td_even,':') !== false ) {
				$th_even = array( 'style'=>$th_even );
			} else {
				$th_even = array( 'class'=>$th_even );
			}
		} elseif ( empty($th_even) || !is_array($th_even) ) $th_even = array();
		
		// accepts string TD-ODD-EVEN configuration
		if ( is_string($td_odd) ) {
			if ( strpos($td_odd,':') !== false ) {
				$td_odd = array( 'style'=>$td_odd );
			} else {
				$td_odd = array( 'class'=>$td_odd );
			}
		} elseif ( empty($td_odd) || !is_array($td_odd) ) $td_odd = array();
		
		if ( is_string($td_even) ) {
			if ( strpos($td_even,':') !== false ) {
				$td_even = array( 'style'=>$td_even );
			} else {
				$td_even = array( 'class'=>$td_even );
			}
		} elseif ( empty($td_even) || !is_array($td_even) ) $td_even = array();
		
		
		
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