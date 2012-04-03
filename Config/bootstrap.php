<?php
/**
 * CakePanel - bootstrap.php
 */



/**
 * Import the CakePanel AppXxxx classes
 * Use these classes in our AppXxxxx.php files to let your App extends CakePanel
 * using it's behaviors:
 * 
 * // App/Controller/AppController.php
 * class AppController extends CakePanelController {
 * 
 * NOTICE: CakePanel extends CakePower!
 */
App::uses( 'CakePanelController', 'CakePanel.Controller' );




/**
 * Import static libraries
 */







/**
 * Build Sidebar Menu
 * NOTICE: this code need to be shifted in a view's related code.
 */

PowerMenu::appendTo('admin.sidebar', 'users', array(
	'show' 	=> 'Users',
	'url'	=> array( 'controller'=>'users' ),
	'icon'	=> 'user',
	'children' => array(
		'users_index' => array(
			'show' 	=> 'List Users',
			'url'	=> array( 'controller'=>'users', 'action'=>'index' )
		),
		'users_add' => array(
			'show' 	=> 'New User',
			'url'	=> array( 'controller'=>'users', 'action'=>'add' )
		),
	)
));


PowerMenu::appendTo('admin.sidebar', 'posts', array(
	'show' 	=> 'Posts',
	'url'	=> array( 'controller'=>'posts' ),
	'icon'	=> 'article',
	'children' => array(
		'posts_index' => array(
			'show' 	=> 'List Posts',
			'url'	=> array( 'controller'=>'posts', 'action'=>'index' )
		),
		'posts_add' => array(
			'show' 	=> 'New Post',
			'url'	=> array( 'controller'=>'posts', 'action'=>'add' )
		),
	)
));