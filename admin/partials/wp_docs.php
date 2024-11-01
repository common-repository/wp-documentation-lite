<section id="wp-documentation-section">	 
	<?php $list_serialized = get_post_meta( $post->ID, 'list_serialized', true ) ; ?>
	<?php $settings = wp_doc_get_options( $post->ID ) ; ?>
	<?php if ( ! empty( $list_serialized ) ): ?>
	<div class="documentation-list-warp">
		<div id="documentation-list_description"> <!-- wrapper div to delete list -->
		<!-- Other documentation editors -->
		<?php $this->loop_editor( $list_serialized ); ?>
	</div>	
	<?php endif; ?>	
</section>

