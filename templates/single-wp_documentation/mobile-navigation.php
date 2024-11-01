<div class="nav-inner-wrapper">
	<div class="ws-visible" style="<?php echo (isset($toc_position) && $toc_position ==='top')?'display:block;visibility:visible':'' ?>">
		<nav class="ws-collapse-navbar">
			<div class="navbar-header">
				<button type="button" class="ws-navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="ws-sr-only">Toggle navigation</span>
				<span class="ws-icon-bar"></span>
				<span class="ws-icon-bar"></span>
				<span class="ws-icon-bar"></span>
				</button>

				<?php do_action( 'wp_documentation_single_before_toc', $post_id, $list_serialized ); ?>
			</div>                         
		</nav>
		<div class="top-menu" style="display:none">
			<?php $listClass = 'ws-wp-docs-categories'; ?>
			<?php echo $callback->frontend_toc($list_serialized, '', $document_settings ,$listClass );  ?>
			<input type="hidden" name="js_offset_top" value="<?php echo (wp_is_mobile())?'yes':'no' ?>">
		</div> 
	</div>
</div>