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
		
		<div class="container-fluid" style="margin-top:60px;">
			
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
					 * Display all CakePOWER state notifications
					 * http://movableapp.com/2012/07/cakepower-notification-system/ 
					 */
					echo $this->Html->tag(array(
						'tag'		=> 'div',
						'style'		=> 'margin-top:22px',
						$this->Session->flash()
					));
					
					
					
					
					/**
					 * View Title
					 */
					echo $this->Html->tag(array(
						'class' => 'page-header',
						array(
							'tag' => 'h4',
							$this->fetch('title_for_view')
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