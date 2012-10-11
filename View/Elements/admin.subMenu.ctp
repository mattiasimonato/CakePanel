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
 * Bisogna capire cos'è e come si usa!!!
 */


if ( empty($links) ) return;

$this->start('SubMenu');

ob_start();
foreach ( $links as $link ) {
	
	echo $this->Html->tag( 'li', $link );
	
}

echo $this->Html->tag( 'ul', ob_get_clean(), array(
	'class' => 'sub-menu'
));


$this->end('SubMenu');