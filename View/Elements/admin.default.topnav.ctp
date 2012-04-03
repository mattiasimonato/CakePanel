<?php
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
			'controller' => 'users',
			'action' => 'logout',
			'admin' => false
		));
	 	?></li>
	 </ul>
</div>