<?php global $post; ?>
<?php

	$default_settings = new WP_Documentation_Options();
	
	$wen_settings = array();
	$wen_settings = get_post_meta( $post->ID, 'wp_documentation_settings', true );
	$wen_global_settings = get_option('wp_documentation_settings');

	if(empty($wen_global_settings))
		$wen_global_settings = array();

	$defaults = $default_settings->get_default_documentation_settings();
	$defaults = array_merge( $defaults, $wen_global_settings );

	if(!empty($wen_settings)):
		$settings_args = $wen_settings;
	else:
		$settings_args = array_merge( $defaults, $wen_global_settings );
	endif;

?>

<div class="ws_settings">		
	<?php  $this->settings_html_callback($settings_args); ?>
</div>