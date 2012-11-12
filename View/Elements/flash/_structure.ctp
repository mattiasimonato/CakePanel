<?php
/**
 * AteUi - Notification Box Structure
 * 
 */
echo $this->Html->tag(array(
	'class' 	=> 'alert alert-'.$type,
	'content' 	=> array(
		array(
			'name' 			=> 'button',
			'class' 		=> 'close',
			'data-dismiss' 	=> 'alert',
			'content' 		=> '×'
		),
		array(
			'name' 			=> 'h4',
			'content' 		=> $title
		),
		$message
	)
));