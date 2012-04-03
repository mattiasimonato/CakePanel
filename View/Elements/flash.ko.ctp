<div class="notify notify-error">
	<a href="javascript:;" class="close">&times;</a>
	<?php
	if ( !empty($title) ) echo $this->Html->tag( 'h3', $title );
	echo $this->Html->tag( 'p', $message );
	?>
</div>