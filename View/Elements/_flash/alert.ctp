<div class="alert alert-block alert-alert">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<?php
	if ( !empty($title) ) echo $this->Html->tag( 'h4', $title );
	echo $this->Html->tag( 'p', $message );
	?>
</div>