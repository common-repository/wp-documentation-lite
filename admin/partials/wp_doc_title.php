<?php global $post; ?>
<?php wp_nonce_field( 'wp_documentation_nonce_action', 'wp_documentation_nonce_field' ); ?>

<textarea name="list_serialized" id="list_serialized" style="display:none"></textarea>
<section id="wp-documentation-section">		
	<div class="dd" id="nestable-document">	

		<?php $list_serialized = get_post_meta( $post->ID, 'list_serialized', true ) ; ?>
		<?php
		
		if( ! empty($list_serialized)):
			$list_class = 'wp-doc-nested-sortable';
			echo @$this->new_list($list_serialized,$list_class);
		else:
		?>
		<ol class="dd-list wp-doc-nested-sortable"></ol>
		<?php 
		endif;
		?>

	</div>
	<?php if (empty($list_serialized)): ?>
	<span class="message-add-new"><?php _e('Click &lsquo;Add New&rsquo; to begin.', 'wp-documentation-lite') ?> </span>
	<?php endif; ?>
	<span class="save-notice"></span>
	<div class="doc-button clearfix">		
		<input type="button" value="<?php  esc_attr( _e( 'Add New ', 'wp-documentation-lite' ) ); ?>" class="button button-primary wp-doc-add-new" />		
	</div>
	<br>
	<hr>
	<span class="alert-warning"><small><?php printf( __( 'WP Documentation Lite support only one level nesting. For more nesting level %splease upgrade to pro.%s', 'wp-documentation-lite' ), '<a href="http://themepalace.com/downloads/wp-documentation-pro/" target="_blank">', '</a>' ); ?></small></span>
</section>


<?php




