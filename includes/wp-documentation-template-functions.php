<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global.
 */

if ( ! function_exists( 'wp_documentation_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function wp_documentation_output_content_wrapper() {
		wp_documentation_get_template( 'global/wrapper-start.php' );
	}
}
if ( ! function_exists( 'wp_documentation_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function wp_documentation_output_content_wrapper_end() {
		wp_documentation_get_template( 'global/wrapper-end.php' );
	}
}

if( !function_exists( 'is_documentation_page' ) ){
	function is_documentation_page(){
		if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE )  || is_post_type_archive( WS_WP_DOCUMENTATION_POST_TYPE ) || is_tax(WS_WP_DOCUMENTATION_TAX)){
			return true;
		}
		return false;
	}
}

/**
 * Single header section template functions.
 */
if( !function_exists( 'wp_documentation_single_header_title' ) ){

	/**
	 * Add document title
	 * @param  int $post_id              Post ID.
	 * @param  array $document_settings  Document settings.
	 * @return void                    
	 */
	function wp_documentation_single_header_title( $post_id, $document_settings ){
		global $post;
		$show_title = ( !isset( $document_settings['doc_title'] ) ) ? true : false;
		if( apply_filters( 'wp_documentation_single_header_title_status', $show_title, $post_id ) ) {
			printf( '<div class="doc-title"><h1 class="ws-logo-title entry-title">%s</h1></div>', $post->post_title );
		}
	}
}

if( !function_exists( 'wp_documentation_single_tool_bar' ) ){

	/**
	 * Add toolbar buttons.
	 * @param  array  $hidden_buttons Buttons to hide.
	 * @param  int $post_id           ID of post.
	 * @return void                 
	 */
	function wp_documentation_single_tool_bar( $post_id, $document_settings ){
		global $post;
		$hidden_buttons = isset( $document_settings['buttons'] ) ? $document_settings['buttons'] : array();
		/**
		 * Tool buttons.
		 * @todo Find a way to purify this array.
		 */

		$buttons['copy_link'] = array(
									'title' => __( 'Copy Link', 'wp-documentation-lite' ),
									'class' => 'wp-doc-icon wp-doc-link-icon',
									'href' => '',
									'id' => 'copyButton'
								) ;

		$buttons['email'] = array(
									'title' => __( 'Email', 'wp-documentation-lite' ),
									'class' => 'wp-doc-icon wp-doc-email-icon',
									'href' => 'mailto:your-email@example.com?Subject=Documentation%20Link%20'. $post->post_title.'&amp;Body=' . get_permalink( $post_id ),
									'id' => ''
								) ;
		
		$buttons['print'] = array(
									'title' => __( 'Print', 'wp-documentation-lite' ),
									'class' => 'wp-doc-icon wp-doc-print-icon',
									'href' => '',
									'id' => 'wp-documentation-single-print'
								) ;
		$buttons = apply_filters( 'wp_documentation_single_tool_bar_buttons', $buttons, $post_id );

		if( empty( $buttons ) ){
			return;
		}

		echo '<div class="doc-click">';
		foreach ($buttons as $type => $value) {
			if( !in_array( $type, array_keys( $hidden_buttons ) ) ){
				printf( '<a class="%s" title="%s" href="%s" id="%s"></a>', $value['class'], $value['title'], $value['href'],  $value['id'] );			
			}
			
		}
		echo '</div>';
	}
}

/**
 * Single main content template functions.
 */

if( !function_exists( 'wp_documentation_single_toc' ) ){
	function wp_documentation_single_toc( $post_id ){
		$callback = new Wp_Documentation_Callback();
		$list_serialized    = get_post_meta( $post_id, 'list_serialized', true );
		$document_settings  = get_post_meta( $post_id, 'wp_documentation_settings', true );
		
		$toc_width = 25;
		echo '<div class="ws-wp-doc-items document-toc" style="width:'.$toc_width.'%" >';
		echo '<div id="document-toc">';
		do_action( 'wp_documentation_single_before_toc', $post_id, $list_serialized );

		echo '<div id="wp-doc-toc-list">';
		// Table of content.
		$listClass = 'ws-wp-docs-categories';
		echo $callback->frontend_toc( $list_serialized, '', $document_settings, $listClass );

		do_action( 'wp_documentation_single_after_toc', $post_id, $list_serialized );
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}

if( !function_exists( 'wp_documentation_single_toc_title' ) ){
	function wp_documentation_single_toc_title($post_id, $list_serialized){
		if(!empty($list_serialized))
		printf( '<h2>%s</h2>', __( 'Table of Contents', 'wp-documentation-lite' ) );
	}
}

if( !function_exists( 'wp_documentation_single_toc_mobile_navigation' ) ){
	function wp_documentation_single_toc_mobile_navigation( $post_id, $document_settings ){
		$document_settings['callback'] = new Wp_Documentation_Callback();
		$document_settings['list_serialized']    = get_post_meta( $post_id, 'list_serialized', true );
		$document_settings['document_settings']  = get_post_meta( $post_id, 'wp_documentation_settings', true );
		$document_settings['post_id']			 = $post_id;
		wp_documentation_get_template( 'single-wp_documentation/mobile-navigation.php', $document_settings );
	}
}

if( !function_exists( 'wp_documentation_single_main_documentation_content' ) ){
	function wp_documentation_single_main_documentation_content( $post_id, $document_settings ){		
		
		$content_width = 75;
		$document_settings['callback'] = new Wp_Documentation_Callback();
		$document_settings['list_serialized']	= get_post_meta( $post_id, 'list_serialized', true );
		$document_settings['document_settings']	= get_post_meta( $post_id, 'wp_documentation_settings', true );
		$document_settings['content_width']		= $content_width;
		wp_documentation_get_template( 'single-wp_documentation/main-documentation-content.php', $document_settings );
	}
}


if( !function_exists( 'wp_doc_active_theme_class_name' ) ){
	function wp_doc_active_theme_class_name($classes){
		if(is_documentation_page()):
			$theme_class = 'wp-documentation-'.str_replace(' ', '-', (strtolower(wp_get_theme()))) ;
			// add 'class-name' to the $classes array
			$classes[] = $theme_class;
		endif;
		// return the $classes array
		return $classes;
	}
}

if( !function_exists( 'wp_documentation_single_comment_section' ) ){
	function wp_documentation_single_comment_section(){
		echo '<section class="document-comments" style="padding:0 10px 10px;">';
	    	echo '<div class="ws-comment-section clearfix">';
	      		comments_template();
	    	echo '</div>';
	  	echo '</section>';
	}
}

if( !function_exists( 'wp_doc_add_wrapper_class' ) ){

	/**
	 * Add Class on wrapper div.
	 * @param  string $class Old class.
	 * @return string        Modified class.
	 */
	function wp_doc_add_wrapper_class( $class ){	
		$class .=  ' wp-doc-theme-default ';
		if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE ) ){
			global $post;
			$document_settings  = get_post_meta( $post->ID,'wp_documentation_settings', true ); 
			$class .= (isset($document_settings['custom_class']))?$document_settings['custom_class']:'';
		}

		return $class;
	}

}