<?php
/**
 * JumpMenu
 * UI component to easy switch from an item to another
 */

if ( empty($options) ) 	return;
if ( empty($url) )		$url 		= array( 'admin'=>$this->request->params['admin'], 'plugin'=>$this->request->params['plugin'], 'controller'=>$this->request->params['controller'], 'action'=>$this->request->params['action']);
if ( empty($selected) )	$selected 	= null;

$this->start('JumpMenu');

echo $this->Form->create( 'JumpMenu', array(
	'url'		=> $url,
	'class'		=> 'navbar-form pull-right jump-menu'
));

echo $this->Form->input( 'JumpTo', array(
	'type' 		=> 'select',
	'options' 	=> $options,
	'selected'	=> $selected,
	'label'		=> false,
	'div'		=> false,
));

echo ' ';

echo $this->Html->tag(array(
	'name' => 'button',
	'content' => 'Go!',
	'type' => 'submit',
	'class' => 'btn btn-primary'
));

echo $this->Form->end();

$this->end('JumpMenu');