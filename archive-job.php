<?php
get_header();

?><main><div class="loop">
	<?php

	if (have_posts()) :
	   ?>
	   	<section>
			<article>
				<h1>Current Opportunities</h1>
			</article>
		</section>
	<?php

	   while (have_posts()) :
	      the_post();
	      	?>
			<section>
				<article>
					<h2><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h2>
			    	<?php the_excerpt(); ?>
				</article>
			</section>
			<?php
	   endwhile;
	endif;

?></div></main><?php

get_footer(); 
?>