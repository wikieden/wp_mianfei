<?php get_header(); ?>
	

		
		<!-- begin colLeft -->
		<div id="colLeft">
		<!-- archive-title -->				
						<?php if(is_month()) { ?>
						<div id="archive-title">
						Browsing all articles from <strong><?php the_time('F, Y') ?></strong>
						</div>
						<?php } ?>
						<?php if(is_category()) { ?>
						<div id="archive-title">
						Browsing all articles in <strong><?php $current_category = single_cat_title("", true); ?></strong>
						</div>
						<?php } ?>
						<?php if(is_tag()) { ?>
						<div id="archive-title">
						Browsing all articles tagged with <strong><?php wp_title('',true,''); ?></strong>
						</div>
						<?php } ?>
						<?php if(is_author()) { ?>
						<div id="archive-title">
						Browsing all articles by <strong><?php wp_title('',true,''); ?></strong>
						</div>
						<?php } ?>
					<!-- /archive-title -->
	 	< ?php $postcnt = 1; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
<?php the_content(__('(more)')); ?>

			</div>
		</div>
		<!-- end post -->
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('Older') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer') ?></div>
		</div>

	<?php else : ?>

		<p>Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>
		</div>
		<!-- end colLeft -->

<!-- begin colRight -->
		<div id="colRight">	
			<?php get_sidebar(); ?>	
			</div>
<!-- end colRight -->

<?php get_footer(); ?>