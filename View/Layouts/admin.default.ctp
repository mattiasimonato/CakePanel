
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	
		
	<?php
	/**
	 * Extension Point
	 * Apps and othe plugins can register callbacks or listeners to interact with the HEAD generation
	 * of the document.
	 *
	 * With this event it is possibile for plugins to inject assets, meta tags or, even, a fully custom HEAD code.
	 */
	$this->getEventManager()->dispatch(new CakeEvent('CakePanel.Layout.Default.beforeHead',$this));
	
	
	
	/**
	 * Document Head Tag
	 * It can be strictly defined into the "head" block from views or other places.
	 * Elsewere it will be generated mixing a lot of blocks inside "default.head" element.
	 * Look at this element's source for documentation.
	 */
	echo $this->Html->tag( 'head', ( $head = $this->fetch('head') ) ? $head : $this->element('CakePanel.admin.default.head') );
	
	
	
	?>
	
	<body>
		
		<?php echo $this->element( 'CakePanel.admin.default.header' ) ?>
		
		<div class="container-fluid">
			
			<div class="row-fluid">
			
				<div class="span3">
					
					<div id="sidebar" class="span3 affix">
						<?php echo $this->element('CakePanel.admin.default.sidebar'); ?>
					</div>
					
					
				</div>
				
				<div class="span9">
					
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
						'style'		=> 'margin-top:22px'
					));
					
					
					
					
					/**
					 * View Title
					 */
					echo $this->Html->tag(array(
						'class'		=> 'page-header',
						'content'	=> array(
							'name' 		=> 'h4',
							'content'	=> $this->fetch( 'title_for_view' )
						)
					));
					
					
					/**
					 * Main View Content
					 */
					echo $this->fetch('content');
					
					
					?>
				</div>
				
			</div>
			
		</div>
		
		
		
	</body>
</html>