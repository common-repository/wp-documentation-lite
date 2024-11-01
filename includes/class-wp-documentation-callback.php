<?php

/**
 * Includes all the callback Functions of plugins
 *
 *
 * @link       http://author@wensolutions.com
 * @since      0.0.1
 *
 * @package    Wen_Cpt_Creator
 * @subpackage Wen_Cpt_Creator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.0.1
 * @package    Wen_Cpt_Creator
 * @subpackage Wen_Cpt_Creator/includes
 * @author     Wen Solutions <support@wensolutions.com>
 */
class Wp_Documentation_Callback {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.1
	 */
	
	public function tab_meta_box_callback(){
		?>

	    <div id="tabs-container" class="clearfix">
		    <ul class="tabs-menu clearfix">
		        <li class="current"><a href="#tab-1"><?php _e( 'Titles', 'wp-documentation-lite' ); ?></a></li>
		        <li><a href="#tab-2"><?php _e( 'Contents', 'wp-documentation-lite' ); ?></a></li>		        
		        <li><a href="#tab-4"><?php _e( 'Settings', 'wp-documentation-lite' ); ?></a></li>		        	        
		    </ul>
		    <div class="tab clearfix">
		        <div id="tab-1" class="tab-content">
	            	<?php include WS_WP_DOCUMENTATION_DIR.'/admin/partials/wp_doc_title.php'; ?>
		        </div>
		        <div id="tab-2" class="tab-content">
		        	<?php include WS_WP_DOCUMENTATION_DIR.'/admin/partials/wp_docs.php'; ?>
	            		            	
		        </div>
		        <div id="tab-4" class="tab-content">
	        		<?php include WS_WP_DOCUMENTATION_DIR.'/admin/partials/wp_doc_settings.php'; ?>         	
		        </div>		       	        
		    </div>
		</div>		
	<?php
	}

	// Backend New List Implementation to show nestable title
	function new_list($list_serialized,$list_class=null){
		$listClass = ($list_class)?$list_class:'';
		if($list_serialized):
			$return_list = '';
			foreach($list_serialized as $content){
				$return_list	.= '<li class="dd-item dd3-item" id="document_title_'.$content->id.'" data-id="'.$content->id .'">
										<div class="dd-handle dd3-handle"></div>
										<div class="dd3-content">
											<h3 class="itemTitle clearfix" data-id="'.$content->id .'">
											  	<span class="edit_title" id="title-'.$content->id .'" title="Click Here To Edit Title">'.$this->get_doc_title($content->id).'</span>
											  	<input type="text" data-id="title-'.$content->id .'" name="doc_title['.$content->id .']" value="'.(($this->get_doc_title($content->id) != "(no title)")?$this->get_doc_title($content->id):'').'" placeholder="(no title)" class="section_title" style="display:none"> 
											  	<a href="#documentation-content-'.$content->id .'" class="edit-doc-page" ><span class="dashicons dashicons-edit" title="Add/Edit Content"></span></a></span> 
											  	<i class="dashicons dashicons-no-alt delete-list delete-icon" data-id="'.$content->id .'"></i>
											</h3>
										</div>';

				if(isset($content->children))
					$return_list .= $this->new_list($content->children);
				$return_list 	.= '</li>';			
			}
			return ($return_list) ?  "\n<ol class='dd-list ".$listClass. "'>\n$return_list</ol>\n" : ''; 			
		endif;
		return false;
	}
	function get_doc_title($doc_id){
		global $post;
		$documentation_list = get_post_meta( $post->ID, '_wp_documentation', true ) ;
		return (isset($documentation_list['title'][$doc_id]) && !empty($documentation_list['title'][$doc_id]))?$documentation_list['title'][$doc_id]:"(no title)";
	}
	function get_doc_content($doc_id){
		global $post;
		$documentation_list = get_post_meta( $post->ID, '_wp_documentation', true ) ;
		return (isset($documentation_list['content'][$doc_id]))?$documentation_list['content'][$doc_id]:"";
	}

	// Backend Content Tab list
	function loop_editor( $list_serialized, $parent_prefix = '' ){
		$prefix = 1;
		foreach($list_serialized as $k => $v): 
			//$depth 		= 0;
			$own_id		= $v->id;
		?>
		<div id="documentation-list_<?php echo $own_id; ?>"> <!-- wrapper div to delete list -->
		<div class="documentation-list" >
			<div class="documentation-title">
				<h3 class="clearfix">
					<?php $new_prefix = (''!=$parent_prefix)? $parent_prefix.'.'.$prefix: $prefix; ?>
					 <i class="dashicons dashicons-arrow-down"></i> <span class="num-counter"> <?php echo $new_prefix; ?> </span> <?php echo $this->get_doc_title($own_id)  ?>
				</h3>

			</div>

			<div class="documentation-content" id="documentation-content-<?php echo $own_id; ?>">
				<?php

					$content = $this->get_doc_content($own_id);
					$editor_id	= 'doc_contents_'.($own_id);
					$textarea_name = 'doc_contents['.($own_id).']';
					
				?>
				<?php wp_editor($content,$editor_id, array( 'textarea_name' => $textarea_name ,'editor_height'=>300) ); ?>
			</div>
		</div>
		<?php
		if( isset( $v->children ) ){
			$this->loop_editor( $v->children, $new_prefix );
		}?>
		</div> <!-- ends wrapper div to delete list -->
		<?php

		$prefix++;
		endforeach;
	}


	function frontend_toc($list_serialized, $parent_prefix = '', $doc_settings, $list_class=null){
		$listClass = ($list_class)?$list_class:'';
		if($list_serialized):
			$return_list = '';
			$prefix = 1;
			
			foreach($list_serialized as $content){
				$title = $this->get_doc_title($content->id);
				$new_prefix = (''!=$parent_prefix)? $parent_prefix.$prefix.'.': $prefix.'.';	
				$selector  	= trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($title.'-'.$content->id))))));

				$toc_span_class = "toc-has-no-child";
				if(isset($content->children)){
					$toc_span_class = 'toc-has-child';
				}

				$return_list	.= '<li>';
				$return_list	.= '<span class="doc-list-item-wrapper">';
				$return_list	.= '<a href="#doc-'.str_replace(" ", "-", strtolower($selector)).'">';
				if(!isset($doc_settings['hide_counter']['toc'])):
		          $return_list .= '<span class="num-counter">'.$new_prefix.'</span>';
		        endif;
		        $return_list 	.= $title;
		        $return_list 	.= '</a>';
		        
		        if(!isset($content->children)){
		        	$return_list	.= '</span>';
		        }	    

				if(isset($content->children)){
					$return_list	.=  '<span class="'.$toc_span_class.' dashicons dashicons-arrow-right "></span>';
					$return_list	.=  '</span>';
					$return_list .= $this->frontend_toc($content->children, $new_prefix, $doc_settings);				
				}
				$return_list 	.= '</li>';
				$prefix++;			
			}
			$ulClass = "";
			if($listClass)
				$ulClass = ' class="'.$listClass.'"';
			return ($return_list) ?  "\n<ul ".$ulClass. ">\n$return_list</ul>\n" : ''; 
			
		endif;
		return false;
	}

	function frontend_document_list($list_serialized, $parent_prefix = '', $doc_settings, $nesting_level= 1, $echo = true ){		
		$contents = "";
		if($list_serialized):

			global $post;			

			$prefix = 1;

			foreach($list_serialized as $content){
				$title 			= $this->get_doc_title($content->id);
				$doc_content 	= $this->get_doc_content($content->id);
				$new_prefix 	= (''!=$parent_prefix)? $parent_prefix.$prefix.'.': $prefix.'.';			
				$selector  		= trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($title.'-'.$content->id))))));
				$id_val 		= 'doc-'. str_replace(' ', '-', strtolower($selector));

				$heading_tag = ( $nesting_level < 6 ) ? 'h' . ( $nesting_level + 1 ) : 'h6';

				$contents .= sprintf('<div class="wp-doc-section wp-doc-%d-%d">',$post->ID,$content->id);

					$contents .= '<div class="wp-doc-head-wrap">';
					$contents .= sprintf( '<%s class="doc-list-title clearfix" id="%s">', $heading_tag, $id_val );
					if(!isset($doc_settings['hide_counter']['title'])):
			        	$contents .= sprintf( '<span class="num-counter">%s</span>', $new_prefix );
			        endif;
			        $contents .= $title;
			        $contents .= sprintf( '</%s>', $heading_tag );
			        $contents .= '<div class="ws-wp-tool-wrapper">';			        
			        $contents .= '<div class="ws-content-icon-wrapper">';

			        
			        if(!isset($doc_settings['buttons']['copy_link']))
			        $contents .= '<a class="wp-doc-icon wp-doc-link-icon copy-title-link"></a>';

			        // email content section
			        if(!isset($doc_settings['buttons']['email']))
					$contents .= '<a href="mailto:your-email@example.com?Subject='.urldecode($title).'&amp;Body='.urlencode(esc_url( get_permalink() ).'#'.$id_val).'" class="wp-doc-icon wp-doc-email-icon" ></a>';
			        
			        $edit_link = "";
					if(is_user_logged_in()){
						// Edit Link Secton
						$current_usr_details	= wp_get_current_user();
						$usr_cap 			= $current_usr_details->allcaps;
					
						$current_usr_cap = "";
						if ((!empty($usr_cap))  &&  $usr_cap['edit_posts']){
							$edit_link = admin_url().'post.php?post='.$post->ID.'&action=edit&doc_fhl=wp_documentation&doc_fhl_id=documentation-content-'.$content->id .'';	
							$contents .= '<a class="edit-doc-page" href="'.$edit_link.'">Edit</a>';
						}

					}

					$contents .= sprintf( '<input type="hidden" class="doc-hash-link" value="%s">', esc_url( get_permalink() ).'#'.$id_val );
					$contents .= '</div>';
					$contents .= '</div>';
			    
				$contents .= '</div>';
				
		        $contents .= $doc_content;
				$contents .= sprintf('</div>');				
				if(isset($content->children)){
					$contents .= $this->frontend_document_list($content->children, $new_prefix, $doc_settings, ( $nesting_level + 1 ) , false );				
				}
				$prefix++;			
			}			
		endif;
		if( !$echo )
			return $contents;

		echo apply_filters( 'the_content', $contents );
		return false;
	}

	public function documentation_box_callback(){
		?>

       <div class="thumbnail">
            <img src="<?php echo WS_WP_DOCUMENTATION_URL ?>/admin/images/docico.png" style="max-width:100%">
             <p class="text-justify"><?php _e( 'Click below for our full documentation about WP Documentation Lite.', 'wp-documentation-lite' ); ?> </p>
             <p class="text-center"><a href="<?php echo esc_url('http://wensolutions.com/documentation/wp-documentation-lite/') ?>" target="_blank" class="button button-primary"><?php _e( 'Get Documentation Here', 'wp-documentation-lite') ?></a></p>
       </div>             

		<?php
	}
	
	public function help_box_callback(){
		?>

       <div class="thumbnail">
            <img src="<?php echo WS_WP_DOCUMENTATION_URL ?>/admin/images/help.png" style="max-width:100%;">
             <p class="text-justify"><?php _e('If you need further assistance, Please feel free to visit our support team.', 'wp-documentation-lite'); ?></p>
             <p class="text-center"><a href="<?php echo esc_url('http://themepalace.com/forum/wp-documentation-lite/') ?>" target="_blank" class="button button-primary"><?php _e('Get Support Here', 'wp-documentation-lite'); ?></a></p>
       </div>             

		<?php
	}

	public function upgrade_box_callback(){
		?>

       <div class="thumbnail">
            <img src="<?php echo WS_WP_DOCUMENTATION_URL ?>/admin/images/update.png" style="max-width:100%">
             <p class="text-justify"><?php _e('For more features and settings, Please upgrade to pro. Need more info? click link below', 'wp-documentation-lite') ?></p>
             <p class="text-center"><a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" target="_blank" class="button button-primary"><?php _e('Upgrade Now', 'wp-documentation-lite'); ?></a></p>
       </div>
             

		<?php
	}
	public function review_box_callback(){
		?>		
		<div class="thumbnail">
			<p class="text-center">  
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>					
			</p>
			<h5><?php _e('"After testing a lot of plugin. I was hopeless o get a free logo slider but luckily I found this one and it saved the day :D "', 'wp-documentation-lite'); ?></h5>
			<span class="by"><strong> <a href="https://wordpress.org/" target="_blank">Suleman Muqeed</a></strong></span>

		</div>
		<div class="thumbnail">
			<p class="text-center"> 
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>			
			</p>
			<h5><?php _e('"The best solution: Light and easy to use! "', 'wp-documentation-lite'); ?></h5>
			<span class="by"><strong><a href="https://wordpress.org/" target="_blank">ntorga</a></strong></span>
		</div>
		<div class="thumbnail">
			
			<p class="text-center"> 
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
				<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>			 
			</p>
			<h5><?php _e('"The best slider i found after testing a lot of them!! Very clean and very easy to install and setup!"', 'wp-documentation-lite'); ?></h5>
			<span class="by"><strong><a href="https://wordpress.org/" target="_blank">sandrobatista</a> </strong></span>
		</div>
		<div class="thumbnail last">
			<h5><?php _e('"Please fill free to leave us a review, if you found this plugin helpful."', 'wp-documentation-lite'); ?></h5>
			<p  class="text-center"><a href="https://wordpress.org/" target="_blank" class="button button-primary"><?php _e('Leave a Review ', 'wp-documentation-lite')?></a></p>
		</div>
     
		<?php
	}

	function settings_html_callback($option){

		ob_start();
	?>
		<?php $s = get_current_screen(); ?>
		<div class="setting-options-wrap">
			<h3 class="option-title"><a href="#" class="showing"><?php _e('General Settings', 'wp-documentation-lite') ?><i class="dashicons dashicons-arrow-up"></i></a></h3>
			<div class="setting-options general-options">
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Documentation title', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>											
							<input type="checkbox" id="" name="wp_documentation_settings[doc_title]" value="1" <?php (isset($option['doc_title']))?checked( $option['doc_title'], 1, true):""; ?> />
							<span class="small"><?php _e( 'Hide', 'wp-documentation-lite' ); ?></span>
						</label>
					</div>
				</div>				

				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Scroll top offset', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>
							<input type="number" min="1" id="wp_documentation_settings[top_offset]" name="wp_documentation_settings[top_offset]" value="<?php echo esc_attr((isset($option['top_offset']) && $option['top_offset'] != '')) ? $option['top_offset'] : '50'; ?>" />
						</label>
						<div class="note">
							<p class="description"><?php _e( 'Please enter the top offset without px.', 'wp-documentation-lite' ); ?></p>
						</div>
					</div>				
				</div>
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Go to Top', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>							
							<input type="checkbox" id="" name="wp_documentation_settings[go_to_top]" value="1" <?php (isset($option['go_to_top']))?checked( $option['go_to_top'], 1, true):""; ?> />
							<span class="small"><?php _e( 'Hide', 'wp-documentation-lite' ); ?></span>
						</label>
					</div>
				</div>


				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Suggestion form', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>
							<a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" target="_blank"><?php _e('Upgrade to Pro', 'wp-documentation-lite') ?></a>
						</label>
						
					</div>
				</div>


				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Like / Dislike', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label><a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" target="_blank"><?php _e('Upgrade to Pro', 'wp-documentation-lite') ?></a></label>
					</div>
				</div>				

			</div>
		</div>

		<div class="setting-options-wrap">
			<h3 class="option-title"><a href="#" ><?php _e('Custom CSS', 'wp-documentation-lite') ?><i class="dashicons dashicons-arrow-down"></i></a></h3>
			<div class="setting-options color-options">
							
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Custom Class', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<input type="text" name="wp_documentation_settings[custom_class]" placeholder="eg: wp-documentation" value="<?php echo (isset($option['custom_class']))?$option['custom_class']:''; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="setting-options-wrap">
			<h3 class="option-title"><a href="#"><?php _e('Advance Settings', 'wp-documentation-lite')?> <i class="dashicons dashicons-arrow-down"></i></a></h3>
			<div class="setting-options advance-options">
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Smooth scroll effect', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>							
							<input type="checkbox" id="wp_documentation_settings-ss_effect" name="wp_documentation_settings[ss_effect]" value="1" <?php (isset($option['ss_effect']))?checked( $option['ss_effect'], 1, true):""; ?> />
							<span class="small"><?php _e( 'Disable', 'wp-documentation-lite' ); ?></span>
						</label>
					</div>
				</div>
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Table of Contents (TOC) Position', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>
							<a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" target="_blank"><?php _e('Upgrade to Pro', 'wp-documentation-lite') ?></a>
						</label>
					</div>
				</div>
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Hide index', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">
						<label>
							<a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" target="_blank"><?php _e('Upgrade to Pro', 'wp-documentation-lite') ?></a>
						</label>												
					</div>
				</div>
				
				<div class="wpdp-form-row clearfix">
					<p class="label-main"><?php _e( 'Hide Buttons:', 'wp-documentation-lite' ); ?></p>
					<div class="form-values">

						<label class="checkbox_list">
																	
							<input type="checkbox" id="wp_documentation_settings_buttons-print" name="wp_documentation_settings[buttons][print]" value="1" <?php (isset($option['buttons']['print']))?checked( $option['buttons']['print'], 1, true):''; ?> />	
							<span class="small"><?php _e( 'Print', 'wp-documentation-lite' ); ?></span>						
						</label>
						<label class="checkbox_list">
																		
							<input type="checkbox" id="wp_documentation_settings_buttons-Email" name="wp_documentation_settings[buttons][email]" value="1" <?php (isset($option['buttons']['email']))?checked( $option['buttons']['email'], 1, true):''; ?> />		
							<span class="small"><?php _e( 'Email', 'wp-documentation-lite' ); ?></span>					
						</label>
						<label class="checkbox_list">
															
							<input type="checkbox" id="wp_documentation_settings_buttons-copy-link" name="wp_documentation_settings[buttons][copy_link]" value="1" <?php (isset($option['buttons']['copy_link']))?checked( $option['buttons']['copy_link'], 1, true):''; ?> />	
							<span class="small"><?php _e( 'Copy Link', 'wp-documentation-lite' ); ?></span>						
						</label>						
					</div>
				</div>
				
			</div>
		</div>

	<?php
		$output = ob_get_contents();
		ob_end_clean();
		echo  $output;
	}


	function global_settings_html_callback($option){
		ob_start();
	?>		
		
		<div class="setting-options-wrap " >
			<h3 class="option-title"><a href="#" class="showing"><?php _e('Themes', 'wp-documentation-lite') ?><i class="dashicons dashicons-arrow-up"></i></a></h3>
			
			<div class="setting-options general-options wp-doc-theme-options clearfix">
				
				<div class="wp-doc-themes">		
					<ul>
						
						<li class="selected">
							<label for="default-theme">
								<img src="<?php echo WS_WP_DOCUMENTATION_URL.'/admin/images/default-theme.jpg' ?>" width="200">
								<span style="background: #000">Default</span>
							</label>
						</li>
						<li class="">
							<label for="upgrade-theme">
								<a href="<?php echo esc_url('http://themepalace.com/downloads/wp-documentation-pro/') ?>" style="text-decoration:none"  target="_blank">
								<img src="<?php echo WS_WP_DOCUMENTATION_URL.'/admin/images/coffee-theme.jpg' ?>" width="200" height="200">
								<span style="background: #905726"><?php _e('Upgrade to Pro for more themes', 'wp-documentation-lite') ?></span></a>
							</label>
						</li>						
					</ul>
				</div>						
			</div>
		</div>

	<?php
		$output = ob_get_contents();
		ob_end_clean();
		echo  $output;
	}
	
}
