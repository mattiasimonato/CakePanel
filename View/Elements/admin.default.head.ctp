<?php
/**
 * CakePanel default layout dynamic HEAD tag generation.
 *
 * HEAD tag could be generated form a set of view's blocks for extreme customization.
 */


// Charset and page title
$this->start('title');
echo $this->Html->charset();
echo $this->Html->tag( 'title', $title_for_layout );
$this->end();

// Meta tags
$this->start('meta');
echo $this->Html->meta( 'description', 	'CakePanel v1.0' );
echo $this->Html->meta( 'keywords', 	'CakePanel, Admin Interface' );
echo $this->Html->meta( 'viewport', 	'width=device-width, initial-scale=1.0, maximum-scale=1' );
$this->end();






/**
 * Default Assets
 * Includes CakePanel default assets to the layout.
 * 
 * CakePanel uses both "css" and "script" block to allow views to queque assets into.
 */

// Default CSS
$this->Html->css(array(
	'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600',
	'CakePanel.all',
	'CakePanel.cake_panel',
	'CakePanel.ie9' 		=> 'gte IE 9',
	'CakePanel.ie8' 		=> 'gte IE 8',
),null,array(
	'inline' 	=> false,
	'debug' 	=> true
));



// Default Javascript
$this->Html->script(array(
	'CakePanel.all'
),array(
	'inline' => false
));










/**
 * Collecting inline scripting trunks and quequing to the inlineScript block.
 * Views can add inline javascript code to these blocks:
 *
 * - inlineScript: collect no-framework javascript code like "console.log" debug or alerts.
 *
 * - inlineJquery: collect jQuery based code. It will be wrabbed by an anonymous function 
 *             with jQuery set to the "$" var in safe mode.
 *             This blog is quequed to the inlineJs.
 *
 * - inlineJqueryOnReady: collect jQuery code who needs to run after the dom-ready event.
 *             It will be wrapped by ready() jQuery event listener and quequed to inlineJquery.
 */

$tmp = $this->fetch('inlineJqueryOnReady');
if ( !empty($tmp) ) {
	$this->start('inlineJquery');
	echo '$(document).ready(function(){' . $tmp . '});';
	$this->end();
}

$tmp = $this->fetch('inlineJquery');
if ( !empty($tmp) ) {
	$this->start('inlineScript');
	echo ';(function($) {';
	echo $tmp;
	echo '})(jQuery);';
	$this->end();
}

$tmp = $this->fetch('inlineScript');
if ( !empty($tmp) ) $this->assign( 'inlineScript', $this->Html->tag( 'script', $tmp, array(
	'type' => 'text/javascript'
)));



/**
 * Collecting inline CSS rules.
 * "inlineCSS" block will be filled up with plain CSS rules.
 * Here we wrap it with a valid STYLE tag.
 */
$tmp = $this->fetch('inlineCSS');
if ( !empty($tmp) ) $this->assign( 'inlineCSS', $this->Html->tag( 'style', $tmp, array(
	'type' => 'text/css'
)));




/**
 * Outputting headers blocks
 * "head" block can completely overwrite 
 */

echo $this->fetch('title');
echo $this->fetch('meta');


echo $this->fetch('beforeCss');
echo $this->fetch('css');
echo $this->fetch('afterCss');
echo $this->fetch('inlineCSS');





echo $this->fetch('beforeScript');
echo $this->fetch('script');
echo $this->fetch('afterScript');
echo $this->fetch('inlineScript');
