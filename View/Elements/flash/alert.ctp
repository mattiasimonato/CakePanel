<?php
/**
 * AteUi - Warning Box
 * 
 */
echo $this->element('CakePanel.flash/_structure',array(
	'type' 		=> 'warning',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));