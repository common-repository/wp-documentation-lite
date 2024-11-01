<?php
/**
 * The permalink settings of the plugin.
 *
 * @link       wensolutions.com
 * @since      0.0.1
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/admin
 */

/**
 * The permalink settings of the plugin.
 *
 * @package    Wp_Documentation
 * @subpackage Wp_Documentation/admin
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_Admin_Permalink_Settings {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {
		$this->settings_init();
		$this->settings_save();
	}

	/**
	 * Init settings.
	 */
	public function settings_init(){
		// Add a section to the permalinks page.
		add_settings_section( 'wp-documentation-permalink', __( 'WP Documentation Permalinks', 'wp-documentation-lite' ), array( $this, 'settings' ), 'permalink' );

		// Add settings fields.
		add_settings_field(
			'wp_documentation_slug',            // id
			__( 'Archive slug', 'wp-documentation-lite' ),   // setting title
			array( $this, 'documentation_slug' ),  // display callback
			'permalink',                                    // settings page
			'wp-documentation-permalink'                                      // settings section
		);
	}

	/**
	 * Show the settings.
	 */
	public function settings(){
		echo wpautop( __( 'These settings control the permalinks used specifically for WP Documentation.', 'wp-documentation-lite' ) );
	}

	/**
	 * Show a slug input box.
	 */
	public function documentation_slug() {
		$permalinks = get_option( 'wp_documentation_permalinks' );
		?>
		<input name="wp_documentation_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['archive_base'] ) ) echo esc_attr( $permalinks['archive_base'] ); ?>" placeholder="<?php echo esc_attr_x('wp-documentation', 'wp-documentation-lite') ?>" />
		<?php
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {

		if ( ! is_admin() ) {
			return;
		}

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) && isset( $_POST['wp_documentation_category_slug'] ) && isset( $_POST['wp_documentation_slug'] ) ) {

			$wp_documentation_slug  = wp_doc_clean( $_POST['wp_documentation_slug'] );
			$wp_documentation_category_slug  = wp_doc_clean( $_POST['wp_documentation_category_slug'] );
			$permalinks                         = get_option( 'wp_documentation_permalinks' );

			if ( ! $permalinks ) {
				$permalinks = array();
			}

			$permalinks['archive_base']    = untrailingslashit( $wp_documentation_slug );
			$permalinks['category_base']    = untrailingslashit( $wp_documentation_category_slug );

			update_option( 'wp_documentation_permalinks', $permalinks );
		}
	}
}

return new Wp_Documentation_Admin_Permalink_Settings();