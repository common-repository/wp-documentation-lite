<?php

/**
 * Install section of the plugin.
 *
 * Maintain a list of functions that are used for install purposes of the plugin
 *
 * @package    WP_Documentation
 * @subpackage Wp_Documentation/includes
 * @author     WEN Solutions <info@wensolutions.com>
 */
class WP_Documentation_Install {

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

	public function check_version(){
		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'wp_documentation_version' ) !== $this->version ) {
			$this->install();
		}
	}

	public function install(){

		$this->update_wd_version();

		// Flush rules after install
		flush_rewrite_rules();
	}

	/**
	 * Update WC version to current.
	 */
	private function update_wd_version() {
		delete_option( 'wp_documentation_version' );
		add_option( 'wp_documentation_version', $this->version );
	}
}