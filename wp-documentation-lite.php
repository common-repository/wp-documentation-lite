<?php
/**
 *
 * @link              wensolutions.com
 * @since             0.0.1
 * @package           Wp_Documentation
 *
 * Plugin Name:       WP Documentation Lite
 * Plugin URI:        http://wensolutions.com/plugins/wp-documentation-lite
 * Description:       Creating online documentation has never been this easy!
 * Version:           0.0.6
 * Author:            WEN Solutions
 * Author URI:        http://wensolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-documentation-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class Wp_Documentation_Lite{

	function __construct(){
		// Define constants
		$this->define_constants();

		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-wp-documentation.php';


		register_activation_hook( __FILE__, array( $this, 'activate_wp_documentation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate_wp_documentation' ) );

		$this->run_wp_documentation();
	}

	function define_constants(){
		// Define
		define( 'WS_WP_DOCUMENTATION_VERSION', '0.0.6' );
		define( 'WS_WP_DOCUMENTATION_POST_TYPE', 'wp_documentation' );
		define( 'WS_WP_DOCUMENTATION_TAX', 'wp_documentation_cat' );
		define( 'WS_WP_DOCUMENTATION_DIR_BASE', __FILE__ );
		define( 'WS_WP_DOCUMENTATION_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
		define( 'WS_WP_DOCUMENTATION_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );		
	}
	
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-wp-documentation-activator.php
	 */
	function activate_wp_documentation() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-documentation-activator.php';
		Wp_Documentation_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-wp-documentation-deactivator.php
	 */
	function deactivate_wp_documentation() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-documentation-deactivator.php';
		Wp_Documentation_Deactivator::deactivate();
	}

	/**
	 * Plugin execution.
	 *
	 * @since    0.0.1
	 */
	function run_wp_documentation() {

		$plugin = new Wp_Documentation();
		$plugin->run();

	}
}

new Wp_Documentation_Lite();