<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style.css" type="text/css" media="screen" />
	<?php if(get_option('colorbold_style')!=''){?>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/<?php echo get_option('colorbold_style'); ?>" media="screen" />
	<?php }else{?>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/blue.css" media="screen" />
	<?php }?>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jquery.lightbox-0.5.css" media="screen" />
	 <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/superfish.css" media="screen" />	 
	 <!--[if gte IE 7]>
    <link rel="stylesheet" media="screen" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie7.css" />
    <![endif]-->
	<script language="JavaScript" type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.form.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.lightbox-0.5.min.js">
	</script>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
	
	<!-- lightbox initialize script -->
	<script type="text/javascript">
		$(function() {
		   $('a.lightbox').lightBox();
		});
	 </script>
	<!-- ajax contact form -->
	 <script type="text/javascript">
		 $(document).ready(function(){
			  $('#contact').ajaxForm(function(data) {
				 if (data==1){
					 $('#success').fadeIn("slow");
					 $('#bademail').fadeOut("slow");
					 $('#badserver').fadeOut("slow");
					 $('#contact').resetForm();
					 }
				 else if (data==2){
						 $('#badserver').fadeIn("slow");
					  }
				 else if (data==3)
					{
					 $('#bademail').fadeIn("slow");
					}
					});
				 });
		</script>
		<script type="text/javascript"> 
			$(document).ready(function(){ 
				$("ul.sf-menu").superfish({
					autoArrows:  false,
					delay:       200,                             // one second delay on mouseout 
					animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
					speed:       'fast',                          // faster animation speed 
					autoArrows:  true,                           // disable generation of arrow mark-up 
					dropShadows: true                            // disable drop shadows 			
					}); 
			});
		</script>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>

</head>
<body>

<!-- begin mainWrapper -->
<div id="mainWrapper">
	<!-- begin wrapper -->
	<div id="wrapper">
	<!-- begin header -->
	<div id="header">
	<a href="<?php bloginfo('rss2_url'); ?>" title="RSS" class="rssTag">RSS Feeds</a>
	
	<!-- logo -->
		<div id="logo"><a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_option('colorbold_logo_img'); ?>" alt="<?php echo get_option('colorbold_logo_alt'); ?>" /></a></div>
			<div style="float:right;padding-top:17px;">

			<a href="http://i-ebo.taobao.com/view_page-21036513.htm" target="_blank"><img src="http://www.iappstoday.com/wp-includes/images/adsimg/greenpeace/banner.jpg" /></a>
			
			</div>
		<!-- begin topmenu -->
		<div id="topMenu">
			<ul>
				<li><a href="<?php bloginfo('url'); ?>/">首页</a></li>
				<?php wp_list_pages('title_li=') ?>
			</ul>
		</div>
		<!-- end topmenu -->
		<!-- begin mainMenu -->
			<div id="mainMenu">
				<ul class="sf-menu">
				<?php wp_list_categories('hide_empty=1&exclude=1&title_li='); ?>
				</ul>
			</div>
		<!-- end mainMenu -->
	</div>
	<!-- end header -->
	<!-- begin content -->
	<div id="content" class="clearfix">