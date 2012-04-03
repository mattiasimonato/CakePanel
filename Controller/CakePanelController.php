<?php
/**
 * CakePANEL AppController
 *
 * This controller extends CakePower to customize dafault layout.
 */

class CakePanelController extends CakePowerController {
	
	public $layout 			= 'CakePanel.admin.default';
	
	// Customization for automagically sets the login layout: 
	public $loginLayout 	= 'CakePanel.login.default';
	public $loginActions 	= array( 'users/login' );
	
	
	
	
	
/**
 * CakePanel Info
 * store some static informations about CakePanel
 */
	protected $__cakePanel = array(
		'version' 			=> '1.0',
		'components' 		=> array(),
		'helpers' 			=> array( 'Html', 'Session', 'Form' ),
	);

	
	
	
	
	
	
	
/**
 * CakePanel Contructor
 *
 * It fill some configuration properties to use implemented CakePHP classes.
 */
	public function __construct($request = null, $response = null) {
		
		// Loads extended core classes setting an alias to use them with the normal app names.
		foreach( array('components','helpers') as $type ) {
			
			foreach ( $this->__cakePanel[$type] as $cmp ) {
				
				if ( empty($this->{$type}[$cmp]) ) $this->{$type}[$cmp] = array();
				if ( empty($this->{$type}[$cmp]['className']) ) $this->{$type}[$cmp]['className'] = 'CakePanel.Panel'.$cmp;
				
			}
			
		}
		
		parent::__construct( $request, $response );
	
	}
	
	
	
	
	public function beforeRender() {
		
		parent::beforeRender();
		
		// Auto set the login layout if a login action is identified with standard layout.
		if ( in_array($this->request->params['controller'].'/'.$this->request->params['action'],$this->loginActions) && $this->layout == 'CakePanel.admin.default' ) {
			$this->layout = $this->loginLayout;
		}
		
	}
	
}