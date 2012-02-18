<!-- begin search box -->
		<div id="searchBox" class="clearfix">
			<form id="searchform" action="" method="get">
				<input id="s" type="text" name="s" value=""/>
				<input id="searchsubmit" type="submit" value="搜索"/>
			</form>
		</div>
		<!-- end search box -->
		<?php 
	/* Widgetized sidebar */
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?><?php endif; ?>
		<?php if(get_option('colorbold_flickr')=='yes'){?>
		
		<div id="flickr">
			<div class="flickr_tit"><img src="<?php bloginfo('template_url'); ?>/images/flickr_logo.jpg" alt="Flickr Photostream"  /></div>
			<?php get_flickrRSS(); ?> 
		</div>
		<?php }?>

