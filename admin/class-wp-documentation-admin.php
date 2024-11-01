<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/admin
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->callback = new Wp_Documentation_Callback();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		$current_screen = get_current_screen();	
		$screens = array( WS_WP_DOCUMENTATION_POST_TYPE, 'wp_documentation_page_class-wp-documentation-admin', 'wp-doc-suggestion' );

		$prefix = "";
		if( !defined( 'WP_DOC_DEBUG' ) || true !== WP_DOC_DEBUG ){
			$prefix = '.min';
		}
		if( in_array( $current_screen->id, $screens ) ):
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/wp-documentation-admin' . $prefix . '.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name.'-tab-style', plugin_dir_url( __FILE__ ) . 'css/tab' . $prefix . '.css', array(), $this->version, 'all' );
			
		endif;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {
		
        $screen = get_current_screen();        
		$prefix = "";
		if( !defined( 'WP_DOC_DEBUG' ) || true !== WP_DOC_DEBUG ){
			$prefix = '.min';
		}

		if( $screen->id == WS_WP_DOCUMENTATION_POST_TYPE ){
		
		
			wp_enqueue_script( $this->plugin_name. "-admin", plugin_dir_url( __FILE__ ) . 'js/wp-documentation-admin' . $prefix . '.js', array( 'jquery' ), $this->version, false );				
			// Nested sortable
			wp_enqueue_script( $this->plugin_name.'-nestable', plugin_dir_url( __FILE__ ) . 'js/jquery.nestable' . $prefix . '.js', array( 'jquery'), $this->version, false );
			wp_enqueue_script( $this->plugin_name.'-nestable-custom', plugin_dir_url( __FILE__ ) . 'js/nestable-custom' . $prefix . '.js', array( 'jquery'), $this->version, false );
		}
		if( in_array( $screen->id, array( 'wp_documentation_page_class-wp-documentation-admin', WS_WP_DOCUMENTATION_POST_TYPE ) ) ){
			wp_enqueue_script( $this->plugin_name.'-settings-acc', plugin_dir_url( __FILE__ ) . 'js/settings-acc' . $prefix . '.js', array( 'jquery' ), $this->version, false );			
		}

	}
	
	/**
	* Registers settings page for wp documentation.
	*
	* @since 0.0.1
	*/

	public function wp_documentation_enable_pages() {
		add_submenu_page(
			'edit.php?post_type='.WS_WP_DOCUMENTATION_POST_TYPE, 
			'WP Documentation  Settings', 
			'Settings', 
			'manage_options', 
			basename(__FILE__), array($this,'wp_documentation_callback_function')
			);
	}

	/**
	* Registers settings for wp documentation.
	*
	* @since 0.0.1
	*/
	public function wp_documentation_register_settings(){
	//This will save the option in the wp_options table as 'wp_documentation_settings'.
	//The third parameter is a function that will validate input values.
		register_setting('wp_documentation_settings', 'wp_documentation_settings', '');
	}

	/**
	* Function displays three tabs, document title, content and settings options. 
	* Retrives saved settings from the database if settings are saved. Else, displays fresh forms for settings.
	*
	* @since 0.0.1
	*/
	function wp_documentation_callback_function() { 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-settings.php'; 
		$wp_documentation_Options = new WP_Documentation_Options();
		$wp_documentation_Options->wp_documentaion_settings();

	} 

	/**
	* Register a WP Documentation post type.
	*
	*/
	public static function register_wp_documentaion_init() {
		$labels = array(
			'name'               => _x( 'WP Documentations', 'post type general name', 'wp-documentation-lite' ),
			'singular_name'      => _x( 'WP Documentation', 'post type singular name', 'wp-documentation-lite' ),
			'menu_name'          => _x( 'Documentations', 'admin menu', 'wp-documentation-lite' ),
			'name_admin_bar'     => _x( 'WP Documentation', 'add new on admin bar', 'wp-documentation-lite' ),
			'add_new'            => _x( 'Add New', 'Documentation', 'wp-documentation-lite' ),
			'add_new_item'       => __( 'Add New Documentation', 'wp-documentation-lite' ),
			'new_item'           => __( 'New Documentation', 'wp-documentation-lite' ),
			'edit_item'          => __( 'Edit Documentation', 'wp-documentation-lite' ),
			'view_item'          => __( 'View Documentation', 'wp-documentation-lite' ),
			'all_items'          => __( 'All Documentations', 'wp-documentation-lite' ),
			'search_items'       => __( 'Search Documentations', 'wp-documentation-lite' ),
			'parent_item_colon'  => __( 'Parent Documentations:', 'wp-documentation-lite' ),
			'not_found'          => __( 'No Documentations found.', 'wp-documentation-lite' ),
			'not_found_in_trash' => __( 'No Documentations found in Trash.', 'wp-documentation-lite' )
			);

		$permalinks = get_option( 'wp_documentation_permalinks' );	
		$slug = (isset($permalinks['archive_base']) && '' != $permalinks['archive_base'] )?$permalinks['archive_base']:'wp-documentation';
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'wp-documentation-lite' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu' 		 => true,
			'menu_icon'			 => 'dashicons-media-document',
			'query_var'          => false,
			'rewrite'            => array( 'slug' => $slug  ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'revisions' )
			);

		register_post_type( WS_WP_DOCUMENTATION_POST_TYPE, $args );
	}

	function wp_doc_meta_boxes(){

		$screen = get_current_screen();
		if( in_array($screen->id, array( WS_WP_DOCUMENTATION_POST_TYPE ) ) ):
			
			if($screen->id === WS_WP_DOCUMENTATION_POST_TYPE):
			add_meta_box(
				'wp_doc_tab_metabox',
				__( 'Title / Contents / Settings', 'wp-documentation-lite' ),
				array($this->callback,'tab_meta_box_callback'),
				$screen,
				'normal',
				'high'
			);
			endif;
			add_meta_box(
				'wp_doc_upgrade',
				__( 'Upgrade Available', 'wp-documentation-lite' ),
				array($this->callback,'upgrade_box_callback'),
				$screen,
				'side',
				'low'
			);
			add_meta_box(
				'wp_doc_documentation',
				__( 'Documentation', 'wp-documentation-lite' ),
				array($this->callback,'documentation_box_callback'),
				$screen,
				'side',
				'low'
			);
			add_meta_box(
				'wp_doc_help',
				__( 'Help?', 'wp-documentation-lite' ),
				array($this->callback,'help_box_callback'),
				$screen,
				'side',
				'low'
			);
			// add_meta_box(
			// 	'wp_doc_review',
			// 	__( 'Review' ),
			// 	array($this->callback,'review_box_callback'),
			// 	$screen,
			// 	'side',
			// 	'low'
			// );
			if($screen->id === 'wp-doc-suggestion'):
			add_meta_box(
				'wp_doc_suggestion_metabox',
				__( 'Suggestions', 'wp-documentation-lite' ),
				array($this->callback,'suggestion_meta_box_callback'),
				$screen,
				'normal',
				'high'
			);
			endif;
			
		endif;
	}

	function save_wp_documents( $post_id ){
		
		if ( WS_WP_DOCUMENTATION_POST_TYPE != get_post_type( $post_id ) ) {
			return $post_id;
		}

		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if ( !isset( $_POST['wp_documentation_nonce_field'] ) || !wp_verify_nonce( $_POST['wp_documentation_nonce_field'], 'wp_documentation_nonce_action' ) )
		    return $post_id;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' , $post_id ) )
			return $post_id;

		
		// Saving Serialized List
		if(isset($_POST['list_serialized'])):
			$list_serialized = json_decode(str_replace('\"', '"', $_POST['list_serialized']));
			update_post_meta( $post_id, 'list_serialized', $list_serialized );
		endif;
		
		// Saving Documents
		if(isset($_POST['doc_title'])):			
			$document_list['title'] = array_map( 'sanitize_text_field', $_POST['doc_title'] );
			if(isset($_POST['doc_contents'])):
				$document_list['content'] =  $_POST['doc_contents'];
			endif;					
			update_post_meta( $post_id, '_wp_documentation',  $document_list);
		endif;

		// Saving Settings
		$refined_settings = array();
		if ( ! empty( $_POST['wp_documentation_settings'] ) ) {
			foreach ( $_POST['wp_documentation_settings'] as $key => $value) {
				$refined_settings[$key] = $value;				
			}
		}
		
		if ( ! empty( $refined_settings ) ) {			
			update_post_meta( $post_id, 'wp_documentation_settings', $refined_settings );
		}
	}

	function remove_sortable(){
		$screen = get_current_screen();
		if(WS_WP_DOCUMENTATION_POST_TYPE == $screen->id):
	?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#wp_doc_tab_metabox').sortable({
		      		disabled:true,
		      		sort: false,		      		
		      	}).children('h2').removeClass('hndle').css('border-bottom','1px solid #e5e5e5');
			});
		</script>
    <?php
    	endif;
	}


	/**
	 * Remove publish metabox from suggestion post type. [PRO_FEATURE].
	 * @return void 
	 */
	function remove_meta_boxes(){
		remove_meta_box( 'submitdiv', 'wp-doc-suggestion', 'side' );
	}

}
