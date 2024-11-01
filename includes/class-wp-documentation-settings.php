<?php

/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    WP_Documentation
 * @subpackage Wp_Documentation/includes
 * @author     WEN Solutions <info@wensolutions.com>
 */
class WP_Documentation_Options {

	 /**
	   * Get skill type options.
	   *
	   * @since    0.0.1
	   */
	  public function get_table_position_options() {

	    $option = array(
	      'top'    => __( 'Top', 'wp-documentation-lite' ),
	      'left' => __( 'Left', 'wp-documentation-lite' ),
	    );
	    $option = apply_filters( 'wp_documentation_table_position_type_options', $option );
	    return $option;

	  }


/**
 * Get documentation default settings.
 *
 * @since    0.0.1
 */
public function get_default_documentation_settings() {

    $default_settings = array(
		'doc_title'		=> '0',		
		'top_offset'    => '30',
		'ss_effect'     => '0',				
		'toc_position'	=> 'left',
		'hide_counter'	=> array('toc'=>'0','title'=>'0'),
		'buttons'		=> array('download'=>'0','print'=>'0','email'=>'0','copy_link'=>'0'),		
		'archive_slug'	=> 'wp-documentation',
		'archive_disp'	=> 'tiles',
		'archive_view'	=> 'list',		
		'custom_class'	=> '',
		'nesting_level'	=> '2',		
		'item_per_row'	=> '5',		
    );
    $default_settings = apply_filters( 'wp-documentation-deafult-settings', $default_settings );
    return $default_settings;

  }


/**
 * Settings panel of the plugin.
 *
 * @since    0.0.1
 */
function wp_documentaion_settings(){ ?>
	<div class="wrap clearfix" >
   		<!--  Settings save Message  -->
   		<?php if( isset($_GET['settings-updated']) ) { ?>
		<div id="message" class="updated">
		<p><strong><?php _e('Settings saved.', 'wp-documentation-lite') ?></strong></p>
		</div>
		<?php } ?>
		<!--  /Settings save Message  -->
		<h2><?php _e('WP Documentation Lite Settings', 'wp-documentation-lite'); ?></h2>
		<div class="ws_settings" style="float:left; width:73%">
			<form method="post" action="options.php">

				<?php 

				settings_fields( 'wp_documentation_settings' );
				do_settings_sections( __FILE__ );
				$option = get_option( 'wp_documentation_settings' );

				$default_options['bg_opt'] = ''; 

				$defaults = $this->get_default_documentation_settings();
	
				$option = wp_parse_args( $option, $defaults );

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-documentation-callback.php'; 
				$callback = new Wp_Documentation_Callback();
				$callback->global_settings_html_callback($option);
				?>
				
				<div class="submit"><?php submit_button();?></div>
			</form>
		</div>
		<div class="ws-right-section" style="width:25%; float:right;">
			<div class="ws-metabox upgrade">
				<h4><?php _e('Upgrade Available', 'wp-documentation-lite'); ?></h4>
				<?php $callback->upgrade_box_callback(); ?>
			</div>
			<div class="ws-metabox help">
				<h4><?php _e('Documentation', 'wp-documentation-lite'); ?></h4>
				<?php $callback->documentation_box_callback(); ?>
			</div>
			<div class="ws-metabox help">
				<h4><?php _e('Help?', 'wp-documentation-lite'); ?></h4>
				<?php $callback->help_box_callback(); ?>
			</div>
					
		</div>					
	</div>
	<?php 

	}
}