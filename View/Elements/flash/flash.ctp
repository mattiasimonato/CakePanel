<?php
/**
 * AteUi - General Information Box
 * 
 */
echo $this->element('CakePanel.flash/_structure',array(
	'type' 		=> 'info',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));