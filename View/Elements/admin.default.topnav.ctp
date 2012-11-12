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



/**
 * CakePanel
 * Login related menu
 */
?>
<div id="topNav">
	 <ul>
	 	<li>
	 		<a href="#menuProfile" class="menu"><?php echo AuthComponent::user('username') ?></a>
	 		<!--
	 		<div id="menuProfile" class="menu-container menu-dropdown">
				<div class="menu-content">
					<ul class="">
						<li><a href="javascript:;">Edit Profile</a></li>
						<li><a href="javascript:;">Edit Settings</a></li>
						<li><a href="javascript:;">Suspend Account</a></li>
					</ul>
				</div>
			</div>
			-->
 		</li>
	 	<li><?php
	 	echo $this->Html->link( 'logout', array(
	 		'plugin'		=> 'cake_auth',
			'controller' 	=> 'cake_auth_users',
			'action' 		=> 'logout',
			'admin' 		=> false
		));
	 	?></li>
	 </ul>
</div>