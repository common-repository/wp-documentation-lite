<?php

/**
 * Fired during plugin activation
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.1
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/includes
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.0.1
	 */
	public static function activate() {
		Wp_Documentation_Admin::register_wp_documentaion_init();
		flush_rewrite_rules();
	}

}
