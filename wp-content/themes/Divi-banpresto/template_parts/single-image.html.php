<?php
	$me = get_queried_object_id();
?>
<div class="bp-image-container">
	<div class="bp-image-content">
		<h3><?php do_action('acme_bp_tagname', $me);?></h3>
		<?php do_action('acme_bp_featured_image', $me); ?>
	</div>
    <div class="bp-image-back"></div>
</div>
