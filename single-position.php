<?php get_header(); ?>
	<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
		<div <?php post_class('clearfix'); ?> itemscope itemtype="http://schema.org/CreativeWork">
			<div class="content-first">
				<div class="content-second">
					<h1 class="the-title entry-title" id="post-<?php the_ID(); ?>" itemprop="headline"><?php the_title(); ?></h1>
					<?php
						if(strlen(get_post_meta( $post->ID, 'special_notice_text', true )) > 0){	
							?><div class="special_notice_text_display"><?php
								echo sanitize_text_field(get_post_meta( $post->ID, 'special_notice_text', true ));
							?></div><?php
						} 
					?>

					<div class="content-third" itemprop="text">						
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
					</div>

					<?php
						$file1url = get_post_meta(get_the_ID(), 'league_file1', true);
					    $file2url = get_post_meta(get_the_ID(), 'league_file2', true);
					    $file3url = get_post_meta(get_the_ID(), 'league_file3', true);
					    $file4url = get_post_meta(get_the_ID(), 'league_file4', true);
					    $file5url = get_post_meta(get_the_ID(), 'league_file5', true);
					    $file6url = get_post_meta(get_the_ID(), 'league_file6', true);

					    if(sizeof($file1url) == 4 || sizeof($file2url) == 4 || sizeof($file3url) == 4 || sizeof($file4url) == 4 || sizeof($file5url) == 4 || sizeof($file6url) == 4){
					    	?> <h2>Downloads</h2> <ul class="league_document_list" style="list-style: none;"><?php					    		
					    }

					    if(sizeof($file1url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file1_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file1url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file1_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    	// If there is a space and then another item add a blank li
				    		if(sizeof($file3url) == 4){
				    			?><li>&nbsp;</li><?php
				    		}
					    }
					    if(sizeof($file2url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file2_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file2url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file2_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    	// If there is a space and then another item add a blank li
				    		if(sizeof($file4url) == 4){
				    			?><li>&nbsp;</li><?php
				    		}
					    }
					    if(sizeof($file3url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file3_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file3url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file3_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    	// If there is a space and then another item add a blank li
				    		if(sizeof($file5url) == 4){
				    			?><li>&nbsp;</li><?php
				    		}
					    }
					    if(sizeof($file4url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file4_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file4url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file4_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    	// If there is a space and then another item add a blank li
				    		if(sizeof($file6url) == 4){
				    			?><li>&nbsp;</li><?php
				    		}
					    }
					    if(sizeof($file5url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file5_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file5url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file5_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    }
					    if(sizeof($file6url) == 4 && strlen(get_post_meta(get_the_ID(), 'league_file6_text', true)) > 0){
					    	?> 
					    		<li>
					    			<a target="_blank" href=<?php echo $file6url['url'] ?>>
					    				<span class="dashicons dashicons-media-default league-icon"></span>
					    				<?php echo get_post_meta(get_the_ID(), 'league_file6_text', true); ?>
					    			</a>
					    		</li>
					    	<?php
					    }

					    if(sizeof($file1url) == 4 || sizeof($file2url) == 4 || sizeof($file3url) == 4 || sizeof($file4url) == 4 || sizeof($file5url) == 4 || sizeof($file6url) == 4){
					    	?> </ul><?php					    		
					    }
					?>
				</div>
			</div>
		</div>		
	<?php endwhile; endif;  ?>

<?php get_sidebar( 'page' ); ?>
<?php get_footer(); ?>