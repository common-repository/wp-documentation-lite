<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = get_option( 'template' );
$class = apply_filters( 'wp_documentation_wrapper_class', 'wp-doc-theme ' );

switch( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="wp-doc-twentyeleven ' .$class. '">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="wp-doc-twentytwelve ' .$class. '">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content wp-doc-twentythirteen ' .$class. '">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content wp-doc-twentyfourteen ' .$class. '"><div class="tfwd">';
		break;
	case 'twentyfifteen' :
		echo '<div id="primary" role="main" class="content-area wp-doc-twentyfifteen"><div id="main" class="site-main t15wd ' .$class. '">';
		break;
	case 'twentysixteen' :
		echo '<div id="primary" class="content-area wp-doc-twentysixteen"><main id="main" class="site-main ' .$class. '" role="main">';
		break;
	default :
		echo '<div class="content-area wp-doc-theme ' .$class. '"><div role="main">';
		break;
}