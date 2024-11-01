<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      Wp_Documentation_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {

		$this->plugin_name = 'wp-documentation';
		$this->version = '0.0.2';

		$this->load_dependencies();
		$this->set_locale();
		$this->defin_general_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Documentation_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Documentation_i18n. Defines internationalization functionality.
	 * - Wp_Documentation_Admin. Defines all hooks for the admin area.
	 * - Wp_Documentation_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for installation
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-install.php';

		/**
		 * The core functions
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-documentation-core-functions.php';

		/**
		 * The class responsible for defining callback functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-callback.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-documentation-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-documentation-public.php';


		/**
		 * The template functions
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-documentation-template-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-settings.php';

		/**
		 * The class responsible for defining Library required to generate PDF
		 * side of the site.
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendors/tcpdf/tcpdf_include.php';

		$this->loader = new Wp_Documentation_Loader();
		

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Documentation_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Documentation_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function defin_general_hooks(){
		$plugin_install = new Wp_Documentation_Install( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_install, 'check_version' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Documentation_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('admin_menu' , $plugin_admin, 'wp_documentation_enable_pages');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_documentation_register_settings');

		$this->loader->add_action( 'init', '', 'Wp_Documentation_Admin::register_wp_documentaion_init', 1 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wp_doc_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_wp_documents' );

		// removing sortable content meta box
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'remove_sortable' );

		$this->loader->add_action( 'current_screen', $this, 'conditional_includes' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_meta_boxes' );
		
	}

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		switch ( $screen->id ) {
			case 'options-permalink' :
				/**
				 * The class responsible for adding permalink settings.
				 */
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-documentation-admin-permalink-settings.php';
			break;
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Documentation_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//template
		$this->loader->add_filter( 'template_include', $plugin_public, 'wp_documentation_custom_archive_template' );	
		
		// Comment Template
		$this->loader->add_filter( "comments_template", $plugin_public, "wp_documentation_comment_template" );

		$this->loader->add_action('wp_head', $plugin_public, 'wp_documentation_header_style');
		
		$this->loader->add_action('wp_documentation_before_main_content', '', 'wp_documentation_output_content_wrapper', 10, 2 );
		$this->loader->add_action('wp_documentation_after_main_content', '', 'wp_documentation_output_content_wrapper_end', 10, 2 );
		$this->loader->add_action('wp_documentation_single_header', '', 'wp_documentation_single_header_title', 10, 2 );
		$this->loader->add_action('wp_documentation_single_header', '', 'wp_documentation_single_tool_bar', 10, 2 );
		$this->loader->add_action('wp_documentation_after_single_main_content', '', 'wp_documentation_single_comment_section', 10, 2 );

		$this->loader->add_action('wp_documentation_single_main_content', '', 'wp_documentation_single_toc', 10, 2 );
		$this->loader->add_action('wp_documentation_single_main_content', '', 'wp_documentation_single_toc_mobile_navigation', 10, 2 );
		$this->loader->add_action('wp_documentation_single_before_toc', '', 'wp_documentation_single_toc_title', 10, 2 );
		$this->loader->add_action('wp_documentation_single_main_content', '', 'wp_documentation_single_main_documentation_content', 10, 2 );

		$this->loader->add_filter( 'body_class','', 'wp_doc_active_theme_class_name' );

		$this->loader->add_filter( 'wp_documentation_wrapper_class', "", 'wp_doc_add_wrapper_class' );

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_settings_hooks() {

		$plugin_setting = new WP_Documentation_Options( $this->get_plugin_name(), $this->get_version() );

		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.1
	 * @return    Wp_Documentation_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function init_shortcodes(){

		$plugin_shortcode = new Wp_Documentation_Shortcode();
		$plugin_shortcode->init();
	}
}
