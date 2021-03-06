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
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<title><?php echo ( $title_for_view = $this->fetch('title_for_view') ) ? $title_for_view : $title_for_layout ; ?></title>

	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="author" content="" />		
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	
	<?php
	echo $this->Html->css(array(
		'CakePanel.reset',
		'CakePanel.text',
		'CakePanel.buttons',
		'CakePanel.theme-default',
		'CakePanel.login',
		'CakePanel.notify',
	),null,array(
		'inline' => true,
		'debug' => true	
	));
	
	// Default Javascript
	echo $this->Html->script(array(
		'CakePanel.all'
	),array(
		'inline' => true
	));

	?>
	
</head>

<body>

<div id="login">
	<h1><?php echo ( $title_for_view = $this->fetch('title_for_view') ) ? $title_for_view : $title_for_layout ; ?></h1>
	<div id="login_panel">
		<?php
		/**
		 * Flash Messages
		 * CakePanel handles many kind of flashMessages:
		 *
		 * $this->Session->setFlash( 'common message' );
		 * $this->Session->setFlash( 'confirmation message', 'default', array(), 'ok' );
		 * $this->Session->setFlash( 'error message', 'default', array(), 'ko' );
		 * $this->Session->setFlash( 'warning message', 'default', array(), 'alert' );
		 *
		 * If you use CakePanel in combination of CakePower you can alias your Session component with "PowerSession" one
		 * so it became easy to use custom methods:
		 *
		 * Session::message()
		 * Session::confirm()
		 * Session::error()
		 * Session::alert()
		 */
		echo $this->Html->tag(array(
			'name'		=> 'div',
			'content'	=> $this->Session->flash(),
			'style'		=> 'margin:15px'
		));
		
		
		
		/**
		 * View's Content
		 */
		echo $this->fetch('content');
		
		
		?>
	</div>
</div>



</body>
</html>