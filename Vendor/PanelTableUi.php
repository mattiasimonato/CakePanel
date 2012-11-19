<?php
/**
 * PanelTableUI
 * ============
 * 
 * @author peg
 *
 */
class PanelTableUI extends PanelHtmlHelper {
	
	
	private $_settings = array(
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
	
	
	/**
	 * default values for instance settings
	 * decendat classes should pre-compile this
	 * @var array
	 */
	protected $settings = array();
	
	protected $data = array();
	
	/**
	 * stores an item dataset while stepping through body rendering
	 * @var array
	 */
	protected $_row = null;
	
	protected $_idx = null;
	
	/**
	 * stores actual column's config while stepping through body rendering
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_col = array();
	
	/**
	 * stores a cell data path
	 * @var unknown_type
	 */
	protected $_cell = null;
	
	protected $_even = false;
	
	protected $_odd = false;
	
	
// ------------------------------------------- //	
// ---[[   P U B L I C   M E T H O D S   ]]--- //
// ------------------------------------------- //	
	
	public function __construct( View $View, $settings = array() ) {
		
		$this->settings = $this->parseSettings($settings);
		
		parent::__construct( $View, $this->settings['helperConfig'] );
		
		$data = $this->settings['data'];
		unset($this->settings['data']);
		
		$this->set( $data );
		
	}
	
	/**
	 * set internal data array to render as table.
	 * 
	 * @param array $data
	 */
	public function set( $data = null ) {
		
		if ( empty($data) ) return;
			
		$this->data = PowerSet::def($data);
		
		$this->autoModel();
		
		$this->initColumns();
		
	}
	
	public function render( $data = array() ) {
		
		$this->set($data);
		
		$content = array(
			$this->renderCaption(),
			$this->renderThead(),
			$this->renderTbody(),
			$this->renderTfoot()
		);
		
		$settings = $this->settings['table'];
		
		return $this->tag( 'table', $content, $settings );
		
	}
	
	
	
	
	
	
	
// ------------------------------------------- //
// ---[[  B L O C K   R E N D E R I N G  ]]--- //
// ------------------------------------------- //	
	
	protected function renderCaption() {
		
		$content = array();
		
		$settings = $this->settings['caption'];
		
		return $this->tag( 'caption', $content, $settings );
	
	}
	
	protected function renderThead() {
		
		$content = array();
		
		#ddebug($this->settings['columns']);
		
		foreach ($this->settings['columns'] as $this->_col=>$this->_cell) {
			$content[] = $this->renderTheadCell();
		}
		
		$settings = $this->settings['thead'];
		unset($settings['th']);
		
		return $this->tag('thead', array('name'=>'tr', 'content'=>$content), $settings);
		
	}
	
	protected function renderTheadCell() {
		
		$tagName = $this->settings['thead']['tagName'];
		
		$label = $this->_cell['label'];
		unset($this->_cell['label']);
		
		
		// apply sort properties 
		if ($this->settings['sortable'] && isset($this->_View->Paginator)) {
			$label = $this->_View->Paginator->sort($this->_cell['order'],$label);
		}

		// apply defaults
		$settings = PowerSet::extend($this->settings['thead'][$tagName], $this->_cell, $this->_cell[$tagName]);
		
		// define callback name to search as class optional method or configuration closure
		$callback	= 'thead' . ucfirst($settings['model']) . ucFirst(Inflector::camelize($settings['field']));
		
		// clear unused properties
		unset($settings['th']);
		unset($settings['td']);
		unset($settings['model']);
		unset($settings['field']);
		unset($settings['order']);
		unset($settings['direction']);
		unset($settings['items']);
		
		// translate to a single array tag configuration
		$settings['name'] 		= $tagName;
		$settings['content'] 	= $label;
		
		// in-class callback
		if ( method_exists($this,$callback) ) {
			$r = call_user_method( $callback, $this, $settings['content'], $settings, $this );
			if ( is_array($r) ) $settings = $r; else $settings['content'] = $r;
		}
		
		// closure callback
		if ( isset($this->settings[$callback]) && is_callable($this->settings[$callback]) ) {
			$r = $this->settings[$callback]( $settings['content'], $settings, $this);
			if ( is_array($r) ) $settings = $r; else $settings['content'] = $r;	
		}
		
		return $this->tag( $settings );
		
	}
	
	protected function renderTbody() {
		
		$content = array();
		
		foreach ( $this->data as $this->_idx=>$this->_row ) {
			
			$this->_even = false;
			$this->_odd	= false;
			
			if ( (($this->_idx+1)%2) ) {
				$this->_odd = true;
				
			} else {
				$this->_even = true;
				
			}
			
			$content[] = $this->renderTbodyRow();
		
		}
		
		$settings = $this->settings['tbody'];
		
		return $this->tag( 'tbody', $content, $settings );
		
	}
	
	protected function renderTbodyRow() {
		
		$content = array();
		
		foreach ( $this->settings['columns'] as $this->_col=>$this->_cell ) {
				
			$content[] = $this->renderTbodyCell();
			
		}
		
		$settings = array(
			'name' => 'tr',
			'content' => $content
		);
		
		$callback = 'tbodyRow';
		
		// in-class callback
		if ( method_exists($this,$callback) ) {
			$r = call_user_method( $callback, $this, $this->_row, $settings );
			if ( is_array($r) ) $settings = $r; else $settings['class'] = $r;
		}
		
		// closure callback
		if ( isset($this->settings[$callback]) && is_callable($this->settings[$callback]) ) {
			$r = $this->settings[$callback]( $this->_row, $settings, $this );
			if ( is_array($r) ) $settings = $r; else $settings['class'] = $r;	
		}
		
		return $this->tag( $settings );
		
	}
	
	protected function renderTbodyCell() {
		
		$tagName = $this->settings['tbody']['tagName'];
		
		// apply defaults
		$settings = PowerSet::extend($this->settings['tbody'][$tagName], $this->_cell, $this->_cell[$tagName]);
		
		// define callback name to search as class optional method or configuration closure
		$callback	= 'tbody' . ucfirst($settings['model']) . ucFirst(Inflector::camelize($settings['field']));
		#debug($callback);
		
		// clear unused properties
		unset($settings['label']);
		unset($settings['order']);
		unset($settings['direction']);
		unset($settings['th']);
		unset($settings['td']);
		unset($settings['model']);
		unset($settings['field']);
		
		// try to fetch basic content from dataset with model.field notation
		if ( strpos($this->_col,'.') !== false ) {
			$content = PowerSet::extract( $this->_col, $this->_row );
		
		// search unnamed model inside the entire dataset
		} else {
			
			foreach ( array_keys($this->_row) as $model ) {
				
				if ( empty($content) ) {
					
					$content = PowerSet::extract( $model.'.'.$this->_col, $this->_row );
				
				}
				
			}
			
		}
		
		if ( isset($settings['items']) ) {
			$content.= $this->renderTbodyCellItems( $settings );
			unset($settings['items']);
		}
		
		// translate to a single array tag configuration
		$settings['name'] 		= $tagName;
		$settings['content'] 	= $content;
		
		// in-class callback
		if ( method_exists($this,$callback) ) {
			$r = call_user_method( $callback, $this, $settings['content'], $settings, $this );
			if ( is_array($r) ) $settings = $r; else $settings['content'] = $r;
		}
		
		// closure callback
		if ( isset($this->settings[$callback]) && is_callable($this->settings[$callback]) ) {
			$r = $this->settings[$callback]( $settings['content'], $settings, $this );
			if ( is_array($r) ) $settings = $r; else $settings['content'] = $r;	
		}
		
		return $this->tag( $settings );
		
	}
	
	
	protected function renderTbodyCellItems( $settings ) {
		
		$items = array();
		foreach ( $settings['items'] as $key=>$val ) {
			$items[] = $this->renderTbodyCellItem( $key, $val );
		}
		
		$config = array(
			'content' => $items,
			'class' 	=> 'btn-group'
		);
		
		return $this->tag($config);
		
	}
	
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
	
	
	protected function renderTfoot() {
		
		$content = array();
		
		$settings = $this->settings['tfoot'];
		
		return $this->tag( 'tfoot', $content, $settings );
		
	}
	
	
	
	
	
// -------------------------------------------- //
// ---[[   C O N F I G   F I L T E R S   ]] --- //
// -------------------------------------------- //
	
	/**
	 * parse a given array of settings to generate a full settings array
	 * for the table rendering process
	 * 
	 * "string" input will be translated to handled model name.
	 * 
	 * @param mixed $settings
	 */
	protected function parseSettings( $settings = array() ) {
		
		// import some public properties into class configuration object
		if ( !empty($this->model) ) 	$this->settings['model'] 	= $this->model;
		if ( !empty($this->columns) ) 	$this->settings['columns'] 	= $this->columns;
		
		$settings = PowerSet::def($settings, null, 'model');
		
		// parse given configuration properties
		$settings = $this->parseTableSettings($settings);
		$settings = $this->parseCaptionSettings($settings);
		$settings = $this->parseTheadSettings($settings);
		$settings = $this->parseTbodySettings($settings);
		$settings = $this->parseTfootSettings($settings);
		
		// imports settings from first level to proper places
		if ( isset($settings['bordered']) ) { 	$settings['table']['bordered'] 		= $settings['bordered']; 	unset($settings['bordered']); 	}
		if ( isset($settings['striped']) ) { 	$settings['table']['striped'] 		= $settings['striped']; 	unset($settings['striped']); 	}
		if ( isset($settings['hover']) ) { 		$settings['table']['hover'] 		= $settings['hover']; 		unset($settings['hover']); 		}
		if ( isset($settings['condensed']) ) { 	$settings['table']['condensed'] 	= $settings['condensed']; 	unset($settings['condensed']); 	}
		
		
		// apply default values
		$settings = PowerSet::extend( $this->_settings, $this->settings, $settings );
		
		// apply config transformations
		$settings = $this->applyTableSettings( $settings );
		$settings = $this->applyCaptionSettings( $settings );
		$settings = $this->applyTheadSettings( $settings );
		$settings = $this->applyTbodySettings( $settings );
		$settings = $this->applyTfootSettings( $settings );
		
		#ddebug($settings);
		return $settings;
		
	}
	
	protected function parseTableSettings( $settings = array() ) {
		
		if ( empty($settings['table']) ) return $settings;
		
		$settings['table'] = $this->tagOptions($settings['table']);
		
		return $settings;
		
	}
	
	protected function parseCaptionSettings( $settings = array() ) {
		
		if ( empty($settings['caption']) ) return $settings;
		
		return $settings;
	
	}
	
	protected function parseTheadSettings( $settings = array() ) {
		
		if ( empty($settings['thead']) ) return $settings;
		
		$settings['thead'] = $this->tagOptions($settings['thead']);
		
		if ( !empty($settings['thead']['tr']) ) $settings['thead']['tr'] = $this->tagOptions($settings['thead']['tr']);
		if ( !empty($settings['thead']['th']) ) $settings['thead']['th'] = $this->tagOptions($settings['thead']['th']); 
		
		return $settings;
		
	}
	
	protected function parseTbodySettings( $settings = array() ) {
		
		if ( empty($settings['tbody']) ) return $settings;
		
		return $settings;
	
	}
	
	protected function parseTfootSettings( $settings = array() ) {
		
		if ( empty($settings['tfoot']) ) return $settings;
		
		return $settings;
	
	}
	
	/**
	 * parses an actions array from a column property.
	 * it also accepts strings like "view,edit,delete"
	 * 
	 * @param mixed $input
	 */
	protected function parseActionItems( $input = array() ) {
		
		if ( is_string($input) ) $input = explode(',',$input);
		
		$items = array();
		foreach ( $input as $key=>$val ) {
			
			if ( is_numeric($key) ) {
				$key = $val;
				$val = array();
			}
			
			$val = PowerSet::def($val, array(
				'model' => $this->settings['model'],
				'field' => 'id' 
			), 'label');
			
			$items[$key] = $val;
		
		}
		
		return $items;
		
	}
	
	
	
	
	
	
// ------------------------------------------------------ //
// ---[[   C O N F I G   A P P L I C A T I O N S   ]] --- //
// ------------------------------------------------------ //
	
	
	/**
	 * hanldes specific configuration of the TABLE tag options array
	 * @param unknown_type $settings
	 */
	protected function applyTableSettings( $settings ) {
		
		// get the base-class to implement parametri class names
		$class = array_shift(explode(' ',$settings['table']['class']));
		
		// apply parametric classes
		foreach ( array('striped','bordered','hover','condensed') as $param ) {
			
			if ( isset($settings['table'][$param]) ) {
				
				if ( $settings['table'][$param] === true ) {
					$settings['table']['class'] .= ' ' . $class . '-' . $param;
				}
				
				unset($settings['table'][$param]);
				
			}
		
		}
		
		// apply other classes
		$settings['table']['class'] .= ' ' . $settings['table']['classes'];
		$settings['table']['class'] = trim($settings['table']['class']);
		unset($settings['table']['classes']);
		
		return $settings;
		
	}
	
	
	protected function applyCaptionSettings( $settings = array() ) {
		
		return $settings;
	
	}
	
	protected function applyTheadSettings( $settings = array() ) {
		
		return $settings;
		
	}
	
	protected function applyTbodySettings( $settings = array() ) {
		
		return $settings;
	
	}
	
	protected function applyTfootSettings( $settings = array() ) {
		
		return $settings;
	
	}
	
	
	protected function initColumns() {
		
		$old = PowerSet::def($this->settings['columns']);
		
		if ( empty($old) ) {
			$old = $this->autoColumns();
		}
		
		
		// fill a new row of colums configurations
		$columns = array();
		foreach ( $old as $key=>$val ) {
			
			// numeric key value to associative format
			if (is_numeric($key)) {
				$key = $val;
			}
			
			// compose data related properties
			list ($model, $field) = pluginSplit($key);
			
			// apply default values to the configuration array
			$val = PowerSet::def($val, array('model'=>$model, 'field'=>$field, 'label'=>$key), 'label');
			
			$val = PowerSet::extend(array(
				'label' 	=> '',
				'model' 	=> '',
				'field' 	=> '',
				'th' 		=> array(),
				'td' 		=> array(),
				'order'		=> ( !empty($model) ? $model : $this->settings['model'] ) . '.' . $field,
				'direction' => null
			),$val);
			
			// apply tag options to handle string or array configuration
			$val['th'] = PowerSet::filter($this->tagOptions($val['th']));
			$val['td'] = PowerSet::filter($this->tagOptions($val['td']));
			
			// apply translated labels
			if ( $this->settings['translate'] ) $val['label'] = __($val['label']);
			
			// parses column's actions items
			if ( isset($val['items']) ) {
				$val['items'] = $this->parseActionItems( $val['items'] );
				#ddebug($val);	
			}
			
			// apply new column configuration
			$columns[$key] = $val;
		
		}
		
		$this->settings['columns'] = $columns;
		
	}
	
	
	/**
	 * fetches the main model name from data
	 */
	protected function autoModel() {
		
		if ( empty($this->data) || empty($this->data[0]) ) return array();
		
		$models = array_keys($this->data[0]);
		
		// fetch general model name if missing
		if ( empty($this->settings['model']) ) {
			
			$this->settings['model'] = $models[0];
		
		}
		
	}
	
	
	/**
	 * fetches table columns from the first row of the data array.
	 * 
	 * column's key is composed by "Model.Field".
	 * 
	 */
	protected function autoColumns() {
		
		if ( empty($this->data) || empty($this->data[0]) ) return array();
		
		$columns = array();
		
		$row = $this->data[0];
		
		// collect columns
		foreach ( array_keys($row) as $model ) {
			
			if ( !is_array($row[$model]) ) continue;
			
			foreach ( array_keys($row[$model]) as $field ) {
				
				$key = $model . '.' . $field;
				
				$columns[$key] = $field;
				
			}
		
		}
		
		return $columns;
		
	}
	
	
	
	

}