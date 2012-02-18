<?php get_header(); ?>

<!-- begin colLeft -->
	<div id="colLeft" class="clearfix">
			<div class="searchQuery">Search results for <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); _e('<strong>'); echo $key; _e('</strong>'); wp_reset_query(); ?></div>
			
			
	<?php if (have_posts()) : while (have_posts()) : the_post();
		  ?>
		<!-- begin post -->
		<div class="blogPost clearfix">
			<div class="metaLeft">
				<div class="month"><?php the_time('M') ?>月</div>
				<div class="day"><?php the_time('j') ?></div>
				<div class="comments"><?php comments_popup_link('0', '1', '%'); ?></div>
			</div>
			<div class="postRight">
				<div class="titBullet"></div>
				<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
				<div class="metaRight">
				<img src="<?php bloginfo('template_url'); ?>/images/ico_user.png" alt="Author" /> <?php the_author_link(); ?>&nbsp;&nbsp;&nbsp; <img src="<?php bloginfo('template_url'); ?>/images/ico_folder.png" alt="Category" /> <?php the_category(', ') ?>	&nbsp;&nbsp;&nbsp; <img src="<?php bloginfo('template_url'); ?>/images/ico_tag.png" alt="Tags" /> <?php the_tags(' ', ', ', ''); ?></div>
				<?php the_excerpt(); ?> 
			</div>
		</div>
		<!-- end post -->
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('Older') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer') ?></div>
		</div>

	<?php else : ?>

		<p>抱歉，没有找到您想要的内容。</p>

	<?php endif; ?>

			
</div>
<!-- end colLeft -->

<!-- begin colRight -->
		<div id="colRight" class="clearfix">	
			<?php get_sidebar(); ?>	
			</div>
<!-- end colRight -->


<?php get_footer(); ?>
