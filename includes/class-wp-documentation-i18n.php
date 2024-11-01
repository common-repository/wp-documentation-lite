<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.1
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-documentation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
