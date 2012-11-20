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
 * CakePANEL - Sidebar
 * ------------------------------
 * 
 * It build a custom HTML structure based on "admin.sidebar" PoweMenu data tree.
 * It goes deep to 2 levels.
 */





foreach ( PowerMenu::getTree('admin.sidebar') as $item ) {
	
	
	/**
	 * Check menu permissions
	 */
	
	if ( $this->Html->allowUrl($item[PowerMenu::$displayModel]['url']) !== true ) continue;
	
	
	
	
	/**
	 * Startup the block's cache var
	 */
	$block 			= '';
	$blockDomId 	= str_replace( array('.','_'), array('_','-'), 'admin.sidebar.' . $item[PowerMenu::$displayModel]['_name'] );
	$blockIsActive	= ( $item[PowerMenu::$displayModel]['active'] == 'active' ) ? true : false ;
	
	
	/**
	 * Menu Block Title
	 */
	$title = '';
	
	// Main Item Icon
	if ( !empty($item[PowerMenu::$displayModel]['icon']) ) {
		$title.= String::insert('<i class="icon-:icon"></i>',array(
			'icon' => $item[PowerMenu::$displayModel]['icon']
		));
	}
	
	$title.= $item[PowerMenu::$displayModel]['show']; 
	
	$block.= $this->Html->tag(array(
		'tag' 			=> 'h5',
		'text' 			=> $title,
		'class'			=> !$item[PowerMenu::$displayModel]['active'] ? 'collapsed' : '',
		'data-toggle' 	=> 'collapse',
		'data-target' 	=> '#'.$blockDomId
	));
	
	
	
	
	
	
	
	/**
	 * Block Properties
	 */
	
	// Main Item Options Array
	$block_opt = array(
		'class' => 'nav'
	);
	
	// Set active item.
	if ( $item[PowerMenu::$displayModel]['active'] ) $block_opt['class'].= ' active';
	
	
	
	
	
	
	
	/**
	 * Block Extension - View Block
	 * admin.sidebar.{blockName}.before
	 */
	
	$block.= $this->fetch('admin.sidebar.'.$item[PowerMenu::$displayModel]['_name'].'.before');
	
	
	
	
	/**
	 * Block Actions
	 * generates the nav menu for this block
	 */
	if ( !empty($item[PowerMenu::$children]) ) {
		
		ob_start();
		foreach ( $item[PowerMenu::$children] as $child ) {
			
			// Investigate to determine if item is related to the actual action
			$itemIsActive = false;
			
			if ( $child[PowerMenu::$displayModel]['active'] == 'active' ) {
				$itemIsActive 	= true;
				$blockIsActive 	= true;
			}
			
			// Check link permissions
			if ( $this->Html->allowUrl($child[PowerMenu::$displayModel]['url']) !== true ) continue;
			
			// create the action link
			echo $this->Html->tag(array(
				'tag' 		=> 'li',
				'text' 		=> $this->Html->link( $child[PowerMenu::$displayModel]['show'], $child[PowerMenu::$displayModel]['url'] ),
				'class' 	=> ( $itemIsActive ) ? 'active' : ''
			));
			
		}
		
		
		
		
		/**
		 * Block Extension - View Block
		 * admin.sidebar.{blockName}.actions
		 */
		
		echo $this->fetch('admin.sidebar.'.$item[PowerMenu::$displayModel]['_name'].'.actions');
		
		
		
		/**
		 * Creates the menu list
		 * we are producing an accordion block so it's visible status ( class "in" )
		 * depend by the $blockIsVisible truth value!
		 */
		$block.= $this->Html->tag( 'ul', ob_get_clean(), array(
			'id' 	=> $blockDomId,
			'class'	=> 'nav nav-list nav-stacked collapse ' . ( $blockIsActive ? 'in' : '' )
		));
		
	}
	
	
	
	
	
	
	/**
	 * Block Extension - View Block
	 * admin.sidebar.{blockName}.after
	 */
	
	$block.= $this->fetch('admin.sidebar.'.$item[PowerMenu::$displayModel]['_name'].'.after');
	
	
	
	
	
	
	/**
	 * Block Output
	 */
	
	echo $this->Html->tag( 'div', $block, $block_opt );
	
	
}

