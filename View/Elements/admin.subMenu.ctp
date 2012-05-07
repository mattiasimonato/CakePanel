<?php

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