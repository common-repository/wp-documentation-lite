<?php

		$option = get_option( 'wp_documentation_settings' );
			$archive_view = $option['archive_view'];
			if(isset($archive_view) && !empty($archive_view)):
				if($archive_view === 'list'){ 
					// Start the Loop.
					while ( have_posts() ) : the_post();
					?>					
					<article id="post-<?php echo get_the_ID() ?>">
						<header class="entry-header">
							<div class="doc-post-thumbnail">
								<?php 
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(array(100,100));
								} 
								?>
							</div>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						</header>
					</article>
					
					<?php	
					// End the loop.
					endwhile;					
				}
				else{
					// Start the Loop.
					$option_val = '';
					while ( have_posts() ) : the_post();
						$dufault_title = (!empty(get_the_title()))?get_the_title():"No Title";
						$option_val .= '<option value="'.esc_url( get_permalink() ).'">'.$dufault_title.'</option>'."\n";
					// End the loop.
					endwhile;

					if(!empty($option_val)): ?>

					<select name="documentation_list" id="documentation_list">
						<option value="">Select </opton>
						<?php echo $option_val ?>
					</select>

					<?php
					endif;
				}
			endif;