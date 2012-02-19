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
	 	<?php $postcnt = 1; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<!-- begin post -->
		<div class="blogPost clearfix">
			<div class="metaLeft">
				<div class="month"><?php the_time('M') ?>Êúà</div>
				<div class="day"><?php the_time('j') ?></div>
				<div class="comments"><?php comments_popup_link('0', '1', '%'); ?></div>
			</div>
			<div class="postRight">
				<div class="titBullet"></div>
				<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
				<div class="metaRight">
				<img src="<?php bloginfo('template_url'); ?>/images/ico_user.png" alt="Author" /> <?php the_author_link(); ?>&nbsp;&nbsp;&nbsp; <img src="<?php bloginfo('template_url'); ?>/images/ico_folder.png" alt="Category" /> <?php the_category(', ') ?>	&nbsp;&nbsp;&nbsp; <img src="<?php bloginfo('template_url'); ?>/images/ico_tag.png" alt="Tags" /> <?php the_tags(' ', ', ', ''); ?></div>
<!--<?php the_content(__('(more)')); ?>-->
<p><span style="font-weight:bold;color:#000;">精选限时免费应用，由</span> <a target="_blank" href="http://click.linksynergy.com/fs-bin/click?id=rZlxRrbLTZo&amp;subid=&amp;offerid=146261.1&amp;type=10&amp;tmpid=3909&amp;RD_PARM1=http%3A%2F%2Fitunes.apple.com%2Fcn%2Fapp%2Fapppusher%2Fid395501390%3Fmt%3D8" style="cursor: pointer;"><span style="font-weight:bold;color:#009ad9;">AppPusher</span></a> <span style="font-weight:bold;;color:#000;">为您送达！无限精彩，尽在</span> <a href="http://www.iapps.im"><span style="font-weight:bold;color:#009ad9;">爱</span><span style="font-weight:bold;color:#ff860a;">A</span><span style="font-weight:bold;color:#009ad9;">pps</span> <span style="color:#000;">- www.iapps.im</span></a><br><span style="font-weight:bold;color:red;">本站原创内容，转载时请务必注明出处，谢谢！</span></p>
<div>
<table class="app-icon-line" itemtype="http://schema.org/SoftwareApplication" itemscope="">
<tbody>
<tr>
<td colspan="2">
<div class="app-icon125"><span></span><img src="http://a2.mzstatic.com/us/r1000/041/Purple/10/02/8f/mzi.tsuhybma.175x175-75.jpg" itemprop="image"></div>
</td>
</tr>
<tr>
<td colspan="2"><meta content="Aqua Globs HD" itemprop="name"><a href="http://click.linksynergy.com/fs-bin/click?id=rZlxRrbLTZo&amp;subid=&amp;offerid=146261.1&amp;type=10&amp;tmpid=3909&amp;RD_PARM1=http%3A%2F%2Fitunes.apple.com%2Fcn%2Fapp%2Faqua-globs-hd%2Fid384357212%3Fmt%3D8%26uo%3D4" title="在 iTunes 中查看" itemprop="url" target="_blank" style="cursor: pointer;"><img width="127px" height="22px" alt="下载链接" src="/wp-includes/images/viewinitunes_cn@2x.png"></a></td>
</tr>
<tr>
<td id="app-icon-price-td" colspan="2" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers"><span id="app-icon-price-old">$1.99</span>&nbsp;&nbsp;<span id="app-icon-price-now" content="0" itemprop="price">限时免费</span></td>
</tr>
<tr>
<td class="app-icon-size">大小: <span itemprop="filesize">9.9 MB</span></td>
<td rowspan="2" class="app-icon-type" content="iPad" itemprop="operatingsystems"><span title="此应用程序仅适用于 iPad"><img width="31px" align="right" height="40px" alt="iPad" src="/wp-content/images/apptype_ipad@2x.png"></span></td>
</tr>
<tr>
<td colspan="2">系统: 3.2+</td>
</tr>

</tbody>
</table>
</div>
<?php the_content(); ?>


<!--<?php if ($postcnt == 1) : ?>
<?php the_content(); ?>
<?php else : ?>
<?php the_excerpt(); ?>
<?php endif; $postcnt++; ?>
			--></div>
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