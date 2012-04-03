<?php
/**
 * CakePANEL - Sidebar
 * ------------------------------
 * 
 * It build a custom HTML structure based on "admin.sidebar" PoweMenu data tree.
 * It goes deep to 2 levels.
 */
?>
<div id="sidebar">	
		
	<ul id="mainNav">
		<?php
		foreach ( PowerMenu::getTree('admin.sidebar') as $item ) {
			
			$li = '';
			
			// Main Item Icon
			if ( !empty($item[PowerMenu::$displayModel]['icon']) ) {
				$li.= String::insert('<span class="icon-:icon"></span>',array(
					'icon' => $item[PowerMenu::$displayModel]['icon']
				));
			}
			
			// Main Item Link
			$li.= $this->Html->link( $item[PowerMenu::$displayModel]['show'], $item[PowerMenu::$displayModel]['url'] );
			
			// Main Item Options Array
			$li_opt = array(
				'class' => 'nav'
			);
			
			// Set active item.
			if ( $item[PowerMenu::$displayModel]['active'] ) $li_opt['class'].= ' active';
			
			
			// Sub Menu
			if ( !empty($item[PowerMenu::$children]) ) {
				
				ob_start();
				foreach ( $item[PowerMenu::$children] as $child ) {
					
					echo $this->Html->tag( 'li', array(
						$this->Html->link( $child[PowerMenu::$displayModel]['show'], $child[PowerMenu::$displayModel]['url'] )
					));
					
				}
				
				$li.= $this->Html->tag( 'ul', ob_get_clean(), array(
					'class' => 'subNav'
				));
				
			}
			
			// Output the Item.
			echo $this->Html->tag('li',$li,$li_opt);
			
		}
		
		
		
		/**
		<li class="nav">
			<span class="icon-denied"></span>
			<a href="javascript:;">Error Pages</a>
			
			<ul class="subNav">
				<li><a href="./error-401.html">401 Page</a></li>
				<li><a href="./error-403.html">403 Page</a></li>
				<li><a href="./error-404.html">404 Page</a></li>	
				<li><a href="./error-500.html">500 Page</a></li>	
				<li><a href="./error-503.html">503 Page</a></li>					
			</ul>	
		</li>
		*/
		?>
		
	</ul>
			
</div>