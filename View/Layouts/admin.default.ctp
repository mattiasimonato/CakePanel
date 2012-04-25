
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
		
		<div id="wrapper">
			
			<?php
			echo $this->element('CakePanel.admin.default.header');
			echo $this->element('CakePanel.admin.default.search');
			echo $this->element('CakePanel.admin.default.sidebar');
			?>
			
			<div id="content">
				
				<div id="contentHeader">
					<h1><?php echo ( $title_for_view = $this->fetch('title_for_view') ) ? $title_for_view : $title_for_layout ; ?></h1>
					
					<?php echo $this->fetch('JumpMenu'); ?>
					
				</div>
				
				<div class="container">
					
					<?php
					
					#PowerMenu::debug('admin.sidebar');
					
					
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
					 * Session::msg()
					 * Session::ok()
					 * Session::ko()
					 * Session::alert()
					 */
					$tmp = $this->Session->flash('flash');
					$tmp.= $this->Session->flash('alert');
					$tmp.= $this->Session->flash('auth');
					$tmp.= $this->Session->flash('ok');
					$tmp.= $this->Session->flash('ko');					
					if ( $tmp ) echo $this->Html->tag( 'div', $tmp, array( 'style'=>'margin:15px;') );
					
					
					
					
					
					
					
					/**
					 * Main View Content
					 */
					echo $this->fetch('content');
					
					
					?>
					
					
				</div>
				
			</div>
			
			<?php
			echo $this->element('CakePanel.admin.default.topnav');
			#echo $this->element('CakePanel.admin.default.quicknav');
			?>
		
		</div>
		
		<!-- 
		<div id="footer">
			Copyright &copy; 2012, MadeByAmp Themes.
		</div>
		-->
		
	</body>
</html>