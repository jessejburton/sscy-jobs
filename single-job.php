<?php
/**
 * @package SSCY
 *
*/
	
get_header();

?><main><div class="loop">

	<?php

	if (have_posts()) :
	   while (have_posts()) : the_post(); ?>
      		<section>
  				<article>
  					<p><a href="<?php echo get_post_type_archive_link( 'job' ); ?>"><<< Back to opportunities</a></p>
	      			<h1 class="the-title entry-title" id="post-<?php the_ID(); ?>" itemprop="headline"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</article>
			</section>

			<?php if( !empty( get_post_meta(get_the_ID(), 'responsibilities', true) ) ) { ?>
				<section class="bright-background">
					<article>
						<h2>Responsibilities & Qualifications</h2>
						<?php echo get_post_meta(get_the_ID(), 'responsibilities', true); ?>
					</article>
				</section>
			<?php } ?>

			<?php if( !empty( get_post_meta(get_the_ID(), 'conditions', true) ) ) { ?>
				<section>
					<article>
						<h2>Working Conditions</h2>
						<?php echo get_post_meta(get_the_ID(), 'conditions', true); ?>
					</article>
				</section>
			<?php } ?>

			<section>
				<article>
					<p><input type="button" value="Apply"></p>
				</article>
			</section>
	   <?php
	   endwhile;
	endif;

?></div></main><?php

get_footer(); 
?>