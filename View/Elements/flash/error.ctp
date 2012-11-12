<?php
/**
 * AteUi - Error Box
 * 
 */
echo $this->element('CakePanel.flash/_structure',array(
	'type' 		=> 'error',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));