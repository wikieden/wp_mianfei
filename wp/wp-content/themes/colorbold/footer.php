	</div>
	<!-- end content -->
	</div>
	<!-- end wrapper -->
	<!-- begin footer -->
	<div id="footer">
	&copy; 2010 <strong>爱Apps - iappstoday.com</strong>， 版权所有。主题支持: Site 5: ColorBold 
	<ul id="footerMenu">
		<li><a href="<?php bloginfo('url'); ?>/">主页</a></li>
				<?php wp_list_pages('title_li=') ?>
	</ul>
	
	</div>
	<!-- end footer -->
</div>
<!-- end mainWrapper -->
<?php if (get_option('colorbold_analytics') <> "") { 
		echo stripslashes(stripslashes(get_option('colorbold_analytics'))); 
	} ?>
</body>
</html>

