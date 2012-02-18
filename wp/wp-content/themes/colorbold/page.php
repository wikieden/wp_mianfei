<?php get_header(); ?>

<!-- begin colLeft -->
	<div id="colLeft">
		<h1><?php the_title(); ?></h1>	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php the_content(__('(more...)')); ?>
		
		
		<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?>
	</div>
	<!-- end colleft -->
	
	<!-- begin colRight -->
	<div id="colRight">
	<?php get_sidebar(); ?>	 
	</div>
	<!-- end colRight -->

<?php get_footer(); ?>