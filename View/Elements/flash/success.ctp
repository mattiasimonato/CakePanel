<?php
/**
 * AteUi - Confirmation Box
 * 
 */
echo $this->element('CakePanel.flash/_structure',array(
	'type' 		=> 'success',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));