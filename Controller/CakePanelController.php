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
 * CakePANEL AppController
 *
 * This controller extends CakePower to customize dafault layout.
 */

class CakePanelController extends CakePowerController {
	
	//public $layout 			= 'CakePanel.admin.default';
	
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
		
		// Alias CakePanel libraries
		$this->aliasLibs('components', $this->__cakePanel['components'], 'CakePanel.Panel');
		$this->aliasLibs('helpers', $this->__cakePanel['helpers'], 'CakePanel.Panel');
		
		// Add CakePanel helpers
		$this->addHelper( 'CakePanel.Panel' );
		
		parent::__construct( $request, $response );
	
	}
	
	
	
	public function beforeFilter() {
	
		// Handle JumpMenu
		if ( !empty($this->request->data['JumpMenu']) && !empty($this->request->data['JumpMenu']['JumpTo']) ) {
			$this->redirect(array( $this->request->data['JumpMenu']['JumpTo'] ));
		}
		
		parent::beforeFilter();
	
	}
	
	
	
	public function beforeRender() {
		
		parent::beforeRender();
		
		// Auto set the login layout if a login action is identified with standard layout.
		if ( in_array($this->request->params['controller'].'/'.$this->request->params['action'],$this->loginActions) && $this->layout == 'CakePanel.admin.default' ) {
			$this->layout = $this->loginLayout;
		}
		
	}
	
}