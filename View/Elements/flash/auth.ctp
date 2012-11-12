<?php
/**
 * AteUi - Authentication/Authorization Wargning Box
 * 
 */
echo $this->element('CakePanel.flash/alert',array(
	'type' 		=> 'warning',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));