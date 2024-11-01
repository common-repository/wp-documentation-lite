<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function wp_documentation_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = wp_documentation_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'wp_documentation_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'wp_documentation_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'wp_documentation_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function wp_documentation_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = 'wp-documentation/';
	}

	if ( ! $default_path ) {
		$default_path = WS_WP_DOCUMENTATION_DIR . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template/
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'wp_documentation_locate_template', $template, $template_name, $template_path );
}


/**
 * Get template part (for templates like the shop-loop).
 *
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function wp_documentation_get_template_part( $slug, $name = '' ) {
	$template = '';
	$template_path = 'wp-documentation/';
	// Look in yourtheme/slug-name.php and yourtheme/wp-documentation/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", $template_path . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( WS_WP_DOCUMENTATION_DIR . "/templates/{$slug}-{$name}.php" ) ) {
		$template = WS_WP_DOCUMENTATION_DIR . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/wp-documentation/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", $template_path . "{$slug}.php" ) );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'wp_documentation_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @param string|array $var
 * @return string|array
 */
function wp_doc_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'wp_doc_clean', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}

/**
 * Get documentation post options.
 * @param  int 		$post_id Documentation ID.
 * @return array          	 Documentation options.
 */
function wp_doc_get_options( $post_id ){
	$default_settings = new WP_Documentation_Options();
	$defaults = $default_settings->get_default_documentation_settings();
	$wen_settings = get_post_meta( $post_id, 'wp_documentation_settings', true );
	$settings = wp_parse_args( $wen_settings, $defaults );
	return $settings;
}


function wp_doc_get_themes( $theme = NULL ){
	$themes['default'] = array(
		'title' => __( 'Default', 'wp-documentation-lite' ),
		'primary_color' => '#000000',
		'screenshot_url' => WS_WP_DOCUMENTATION_URL . '/admin/images/default-theme.jpg'
	);

	if( !is_null( $theme ) ) {
		return isset( $themes[ $theme ] ) ? $themes[ $theme ] : false;
	}
	return $themes;
}