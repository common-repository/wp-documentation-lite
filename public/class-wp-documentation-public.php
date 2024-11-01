<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/public
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Documentation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Documentation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(is_documentation_page()){
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( $this->plugin_name."-style", plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-documentation-public.css', array(), $this->version, 'all' );
			if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE ) ){
				$selected_theme = 'default';
				wp_enqueue_style( $this->plugin_name . '-theme', plugin_dir_url( __FILE__ ) . 'css/wp-documentation-'.$selected_theme.'-theme.css', array(), $this->version, 'all' );
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Documentation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Documentation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */	
	 	if( is_documentation_page() ){
			wp_enqueue_script( $this->plugin_name.'-scroll', plugin_dir_url( __FILE__ ) . 'js/scroll.js', array( 'jquery','jquery-ui-core','jquery-ui-widget' ), $this->version, false );		
			wp_enqueue_script( $this->plugin_name.'-highlighter', plugin_dir_url( __FILE__ ) . 'js/jquery.syntaxhighlighter.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name.'-selectToPrint', plugin_dir_url( __FILE__ ) . 'js/selectToPrint.js', array( 'jquery' ), $this->version, false );

			// Add scroll bar js	
			wp_enqueue_script( $this->plugin_name.'-scroll-bar', plugin_dir_url( __FILE__ ) . 'js/jquery.slimscroll.min.js', array( 'jquery' ), $this->version, false );	

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-documentation-public.js', array( 'jquery', $this->plugin_name.'-scroll-bar' ), $this->version, false );

			$selected_theme = 'default';
			wp_localize_script( $this->plugin_name, "wp_documentation", array( 'theme' => wp_doc_get_themes( $selected_theme ) ) ); 
		}
	}



	// Template Override for archive page
	function wp_documentation_custom_archive_template( $template )
	{ 	

        if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE ) ){
            $template = '/wp-documentation/single-wp-documentation.php';
            if( '' == ( $template = locate_template( $template ) ) ){
                $template = WS_WP_DOCUMENTATION_DIR . '/templates/single-wp-documentation.php';
            }
        }

        return $template;
    }

    // Comment template override
	function wp_documentation_comment_template($comment_template){		 

		if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE ) ){

			$template = '/wp-documentation/comments-wp-documentation.php';
			if( '' == ( $template = locate_template( $template ) ) ){							
			    $comment_template = WS_WP_DOCUMENTATION_DIR . '/templates/comments-wp-documentation.php';
			}
		}
		return $comment_template;
	}

	function wp_documentation_header_style(){
		if( is_singular( WS_WP_DOCUMENTATION_POST_TYPE ) ):
		global $post;
		$document_settings  = get_post_meta($post->ID,'wp_documentation_settings',true); 	
		?>
	    <style type="text/css">
	    <?php if(isset($document_settings['custom_css'])): ?>
	    /*Custom Styles Here*/
	    <?php echo (isset($document_settings['apply_custom_css']))?$document_settings['apply_custom_css']:''; ?>

	    <?php endif; ?>
	    </style>

	    <script type="text/javascript">
		    jQuery(document).ready(function($){
		    /*<![CDATA[*/
		    $.SyntaxHighlighter.init({
		      wrapLines   : false,      
		    });
		  /*]]>*/      
		      $(document).wen_scroll({
		        <?php echo (isset($document_settings['top_offset']) && $document_settings['top_offset'] > 0)?'offsetTop:'.$document_settings['top_offset'].',':''  ?>
		        <?php echo (isset($document_settings['ss_effect']) && $document_settings['ss_effect'] === '1')?'smoothScroll:false,':''  ?>
		        <?php echo (isset($document_settings['go_to_top']) && $document_settings['go_to_top'] === '1')?'backToTop:false,':''  ?>

		      });		      
		    });
		    </script>
    	<?php
    	endif;
	}
}
