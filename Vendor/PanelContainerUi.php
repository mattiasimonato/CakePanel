<?php
class PanelContainerUI extends PanelHtmlHelper {
	
	private $_settings = array(
		'helperConfig' 	=> array(),
		'class' 		=> 'box',
		'classes'		=> '',
		'style'		=> '',
		'collapsible'	=> false,
		'title' => array(
			'class' 	=> 'box-head',
			'classes' 	=> '',
			'style'		=> '',
			'content' 	=> array()
		),
		'body' => array(
			'class' 	=> 'box-body',
			'classes' 	=> '',
			'style'		=> '',
			'content' 	=> array()
		),
		'titleWrapper' 	=> array(
			'name'		=> 'h3'
		),
		'bodyWrapper' 	=> array(
			'class' 	=> 'wrap'
		),
	);
	
	private $_collapsible = array(
		'collapsed'			=> false,
		'applyTo' 			=> 'title',
		'wrapperClass' 		=> 'collapsible',
		'collapsedClass' 	=> 'collapsed',
		'group'				=> false
	);
	
	protected $settings = array();
	protected $collapsible = array();
	
	
	
// ------------------------------------------- //	
// ---[[   P U B L I C   M E T H O D S   ]]--- //
// ------------------------------------------- //	
	
	public function __construct( View $View, $settings = array() ) {
		
		// apply default ids to the interna configuration
		$this->_settings['id'] = uniqid('panel-container-ui');
		$this->_settings['title']['id'] = $this->_settings['id'] . '-head';
		$this->_settings['body']['id'] = $this->_settings['id'] . '-body';
		
		$this->settings = $this->parseSettings($settings);
		
		parent::__construct( $View, $this->settings['helperConfig'] );
		unset($this->settings['helperConfig']);
		
	}
	
	public function render() {
		
		$settings = PowerSet::extend($this->settings,array(
			'content' => array(
				$this->renderHead(),
				$this->renderBody()
			)
		));
		
		// apply collapsible options
		$settings = $this->renderCollapsible($settings);
		
		// Apply additional classes to the container wrapper
		$settings['class'] .= ' ' . $settings['classes'];
		unset($settings['classes']);
		
		unset($settings['title']);
		unset($settings['body']);
		
		return $this->tag($settings);
		
	}
	
	
	
	
	
	
	
// ------------------------------------------- //
// ---[[  B L O C K   R E N D E R I N G  ]]--- //
// ------------------------------------------- //

	protected function renderCollapsible($settings) {
		
		// no collapsible behavior required
		if ( !is_array($this->settings['collapsible']) ) return $settings;
		
		// add collapsible enabling class
		$settings['classes'].= ' ' . $this->settings['collapsible']['wrapperClass'];
		
		// add collapsed status class
		if ( $this->settings['collapsible']['collapsed'] ) {
			$settings['classes'].= ' ' . $this->settings['collapsible']['collapsedClass'];
		}
		
		if ( is_string($this->settings['collapsible']['group']) ) {
			$settings['data-collapsible-group'] = $this->settings['collapsible']['group'];
		}
		
		return $settings;
		
	}
	
	protected function renderHead() {
		
		$settings = $this->settings['title'];
		
		// apply wrapper
		if ( isset($this->settings['titleWrapper']) ) {
			$wrapper = $this->tagOptions($this->settings['titleWrapper'], 'class', array('content'=>array()));
			$wrapper['content'] 	= $settings['content'];
			$settings['content'] 	= $wrapper;
		}
		
		// apply collapsible settings
		$settings = $this->renderHeadCollapsible($settings);
		
		// apply additional classes to the container wrapper
		$settings['class'] .= ' ' . $settings['classes'];
		unset($settings['classes']);
		
		return $this->tag($settings);
		
	}
	
	protected function renderHeadCollapsible( $settings ) {
		
		// no collapsible behavior required
		if ( !is_array($this->settings['collapsible']) ) return $settings;
			
		switch ( $this->settings['collapsible']['applyTo'] ) {
		
		}
		
		return $settings;
		
	}
	
	protected function renderBody() {
		
		$settings = $this->settings['body'];
		
		// apply wrapper
		if ( isset($this->settings['bodyWrapper']) ) {
			$wrapper = $this->tagOptions($this->settings['bodyWrapper'], 'class', array('content'=>array()));
			$wrapper['content'] 	= $settings['content'];
			$settings['content'] 	= $wrapper;
		}
		
		// apply collapsible settings
		$settings = $this->renderBodyCollapsible($settings);
		
		// Apply additional classes to the container wrapper
		$settings['class'] .= ' ' . $settings['classes'];
		unset($settings['classes']);
		
		return $this->tag($settings);
	
	}
	
	protected function renderBodyCollapsible( $settings ) {
		
		// no collapsible behavior required
		if ( !is_array($this->settings['collapsible']) ) return $settings;
			
		if ( $this->settings['collapsible']['collapsed'] ) $settings['style'] = 'display:none;' . $settings['style'];
		
		return $settings;
		
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
		
		$settings = PowerSet::todef($settings, 'content', array(
			'title' 		=> array(),
			'body'		=> array(),
			'content' 	=> array()
		));
		
		$settings = $this->parseHeadSettings($settings);
		$settings = $this->parseBodySettings($settings);
		
		$settings = PowerSet::extend($this->settings,$settings);
		$settings = PowerSet::extend($this->_settings,$settings);
		
		$settings = $this->parseCollapsibleSettings($settings);
		
		return $settings;
		
	}
	
	protected function parseHeadSettings($settings = array()) {
		
		if ( is_array($settings['title']) ) $settings['title'] = array( 'content'=>$settings['title'] );
		
		$settings['title'] = PowerSet::todef( $settings['title'], 'content', array('content'=>'') );
		
		return $settings;
	}
	
	protected function parseBodySettings($settings = array()) {
		
		$settings['body'] = $this->tagOptions( $settings['body'], 'class', array(
			'content' => $settings['content']
		));
		
		unset($settings['content']);
		
		return $settings;
	}
	
	protected function parseCollapsibleSettings( $settings = array() ) {
		
		if ( $settings['collapsible'] !== false ) {
			
			// collapsed shortcut
			if ( $settings['collapsible'] === 'collapsed' ) {
				$settings['collapsible'] = array( 'collapsed'=>true );
			
			// accordion group shortcut
			} elseif ( is_string($settings['collapsible']) ) {
				$settings['collapsible'] = array( 'group'=>$settings['collapsible'] );
				
			}
			
			$settings['collapsible'] = PowerSet::todef($settings['collapsible']);
			$settings['collapsible'] = PowerSet::extend($this->collapsible,$settings['collapsible']);
			$settings['collapsible'] = PowerSet::extend($this->_collapsible,$settings['collapsible']);
			
		}
		
		return $settings;
	
	}
	
}