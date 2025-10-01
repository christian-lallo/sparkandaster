<?php
	
	$vid = $_GET['vid'];
?>

<div class="modal modal--video">
	<div class="modal__container">
		<div class=" video__container">
			<iframe class="video__embed" src="https://player.vimeo.com/video/<?php echo $vid; ?>?autoplay=0&loop=0&background=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>
	</div>
</div>
