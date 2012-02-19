<?php 
/* wppa-non-admin.php
* Package: wp-photo-album-plus
*
* Contains all the non admin stuff
* Version 4.3.6
*
*/

/* API FILTER and FUNCTIONS */
require_once 'wppa-filter.php';
require_once 'wppa-slideshow.php';
require_once 'wppa-functions.php';
	
/* LOAD STYLESHEET */
add_action('wp_print_styles', 'wppa_add_style');

function wppa_add_style() {
	$userstyle = ABSPATH . 'wp-content/themes/' . get_option('template') . '/wppa-style.css';
	if ( is_file($userstyle) ) {
		wp_register_style('wppa_style', '/wp-content/themes/' . get_option('template')  . '/wppa-style.css');
		wp_enqueue_style('wppa_style');
	} else {
		wp_register_style('wppa_style', WPPA_URL.'/theme/wppa-style.css');
		wp_enqueue_style('wppa_style');
	}
}

/* LOAD SLIDESHOW and THEME JS */
add_action('init', 'wppa_add_javascripts');
	
function wppa_add_javascripts() {
	wp_register_script('wppa-slideshow', WPPA_URL.'/wppa-slideshow.js');
	wp_register_script('wppa-theme', WPPA_URL.'/wppa-theme.js');
	wp_register_script('wppa-ajax', WPPA_URL.'/wppa-ajax.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('wppa-slideshow');
	wp_enqueue_script('wppa-theme');
	wp_enqueue_script('wppa-ajax');
	if ( get_option('wppa_use_lightbox', 'yes') == 'yes' ) {
		wp_enqueue_script('prototype');
		wp_enqueue_script('scriptaculous-effects');
		wp_enqueue_script('scriptaculous-builder');
	}
}
	
/* LOAD WPPA+ THEME */
add_action('init', 'wppa_load_theme');
	
function wppa_load_theme() {
	$usertheme = ABSPATH . 'wp-content/themes/' . get_option('template') . '/wppa-theme.php';
	if ( is_file($usertheme) ) {
		require_once $usertheme;
	} else {
		require_once 'theme/wppa-theme.php';
	}
}
	
/* LOAD LIGHTBOX */
add_action('wp_head', 'wppa_lightbox', '99');

function wppa_lightbox() {
global $wppa_opt;

	if ( get_option('wppa_use_lightbox', 'yes') == 'yes' ) {
		$bw = $wppa_opt['wppa_lightbox_bordersize'];
		$bw1 = $bw == '' ? '0' : $bw + '1';
		if ( $bw == '' ) $bw = '0';
		$as = $wppa_opt['wppa_lightbox_animationspeed'];
		$bac = $wppa_opt['wppa_lightbox_backgroundcolor'];
		$boc = $wppa_opt['wppa_lightbox_bordercolor'];
		$obac = $wppa_opt['wppa_lightbox_overlaycolor'];
		$opac = $wppa_opt['wppa_lightbox_overlayopacity'] / '100';
		$fontfam = $wppa_opt['wppa_fontfamily_lightbox'];
		$fontsiz = $wppa_opt['wppa_fontsize_lightbox'];
		$fontcol = $wppa_opt['wppa_fontcolor_lightbox'];
		echo "\n<!-- Start WPPA+ inserted lightbox -->\n";
	//	echo "\n".'<script type="text/javascript" src="'.WPPA_URL.'/lightbox/js/prototype.js"></script>';
	//	echo "\n".'<script type="text/javascript" src="'.WPPA_URL.'/lightbox/js/scriptaculous.js?load=effects,builder"></script>';
		echo "\n".'<script type="text/javascript">';
		echo "\n".'/* <![CDATA[ */';
			echo "\n".'LightboxOptions = Object.extend({';
			echo "\n"."fileLoadingImage:        '".WPPA_URL."/lightbox/images/loading.gif',   ";  
			echo "\n"."fileBottomNavCloseImage: '".WPPA_URL."/lightbox/images/cross.png',"; //closelabel.gif',";

			echo "\n".'overlayOpacity: '.$opac.',   // controls transparency of shadow overlay';

			echo "\n".'animate: '; if ($as) echo 'true,'; else echo 'false,         // toggles resizing animations';
			echo "\n".'resizeSpeed: '.$as.',        // controls the speed of the image resizing animations (1=slowest and 10=fastest)';

			echo "\n".'borderSize: '.$bw1.',         //if you adjust the padding in the CSS, you will need to update this variable';

			echo "\n".'// When grouping images this is used to write: Image # of #.';
			echo "\n".'// Change it for non-english localization';
			echo "\n".'labelImage: "'.__a('Image', 'wppa_theme').'",';
			echo "\n".'labelOf: "'.__a('of', 'wppa_theme').'"';
			echo "\n".'}, window.LightboxOptions || {});';
		echo "\n".'/* ]]> */';
		echo "\n".'</script>';
		echo "\n".'<script type="text/javascript" src="'.WPPA_URL.'/lightbox/js/lightbox.js"></script>';
		echo "\n".'<style type="text/css" media="screen">';
			echo "\n".'#lightbox{	position: absolute;	left: 0; width: 100%; z-index: 100; text-align: center; line-height: 0;}';
			echo "\n".'#lightbox img{ width: auto; height: auto;}';
			echo "\n".'#lightbox a img{ border: none; }';
			echo "\n".'#outerImageContainer{ position: relative; background-color: '.$bac.'; width: 250px; height: 250px; margin: 0 auto; }';
			echo "\n".'#imageContainer{ padding: '.$bw.'px; '; if ($bw != '') echo 'border: 1px solid '.$boc.';'; echo ' }';
			echo "\n".'#loading{ position: absolute; top: 40%; left: 0%; height: 25%; width: 100%; text-align: center; line-height: 0; }';
			echo "\n".'#hoverNav{ position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 10; }';
			echo "\n".'#imageContainer>#hoverNav{ left: 0;}';
			echo "\n".'#hoverNav a{ outline: none;}';
			echo "\n".'#prevLink, #nextLink{ width: 49%; height: 100%; background-image: url(data:image/gif;base64,AAAA); /* Trick IE into showing hover */ display: block; }';
			echo "\n".'#prevLink { left: 0; float: left;}';
			echo "\n".'#nextLink { right: 0; float: right;}';
			echo "\n".'#prevLink:hover, #prevLink:visited:hover { background: url('.WPPA_URL.'/lightbox/images/prevlabel.gif) left 15% no-repeat; }';
			echo "\n".'#nextLink:hover, #nextLink:visited:hover { background: url('.WPPA_URL.'/lightbox/images/nextlabel.gif) right 15% no-repeat; }';
			echo "\n".'#imageDataContainer{ font: '.$fontsiz.'px '.$fontfam.'; background-color: '.$bac.'; margin: 0 auto; line-height: 1.4em; overflow: auto; width: 100%	; }';
			echo "\n".'#imageData{	padding:0 '.$bw.'px; color: '.$fontcol.'; }';
			echo "\n".'#imageData #imageDetails{ width: 70%; float: left; text-align: left; }';
			echo "\n".'#imageData #caption{ font-weight: '.$wppa_opt['wppa_fontweight_lightbox'].';	}';
			echo "\n".'#imageData #numberDisplay{ display: block; clear: left; padding-bottom: 1.0em;	}';
			echo "\n".'#imageData #bottomNavClose{ width: 32px; float: right;  padding-bottom: 0.7em; outline: none;}';
			echo "\n".'#overlay{ position: absolute; top: 0; left: 0; z-index: 90; width: 100%; height: 500px; background-color: '.$obac.'; }';
		echo "\n".'</style>';
	//	echo "\n".'<link rel="stylesheet" href="'.WPPA_URL.'/lightbox/css/lightbox.css" type="text/css" media="screen" />';
		echo "\n<!-- End WPPA+ inserted lightbox -->\n";		
	}
}
/* LOAD JS VARS AND ENABLE RENDERING */
add_action('wp_head', 'wppa_kickoff', '100');

function wppa_kickoff() {
global $wppa;
global $wppa_opt;

	echo("\n<!-- WPPA+ Runtime parameters -->\n");
	
	echo('<script type="text/javascript">'."\n");
	echo('/* <![CDATA[ */'."\n");
	
		/* This goes into wppa_theme.js */ 
		echo("\t".'wppaBackgroundColorImage = "'.$wppa_opt['wppa_bgcolor_img'].'";'."\n");
		echo("\t".'wppaPopupLinkType = "'.$wppa_opt['wppa_thumb_linktype'].'";'."\n"); 
		//echo("\t".'wppa_popup_size = "'.$wppa_opt['wppa_popupsize'].'";'."\n");

		/* This goes into wppa_slideshow.js */
		if ($wppa_opt['wppa_fadein_after_fadeout']) echo("\t".'wppaFadeInAfterFadeOut = true;'."\n");
		else echo("\t".'wppaFadeInAfterFadeOut = false;'."\n");
		echo("\t".'wppaAnimationSpeed = '.$wppa_opt['wppa_animation_speed'].';'."\n");
		echo("\t".'wppaImageDirectory = "'.wppa_get_imgdir().'";'."\n");
		if ($wppa['auto_colwidth']) echo("\t".'wppaAutoColumnWidth = true;'."\n");
		else echo("\t".'wppaAutoCoumnWidth = false;'."\n");
		echo("\t".'wppaThumbnailAreaDelta = '.wppa_get_thumbnail_area_delta().';'."\n");
		echo("\t".'wppaTextFrameDelta = '.wppa_get_textframe_delta().';'."\n");
		echo("\t".'wppaBoxDelta = '.wppa_get_box_delta().';'."\n");
		echo("\t".'wppaSlideShowTimeOut = '.$wppa_opt['wppa_slideshow_timeout'].';'."\n");		
		echo("\t".'wppaPreambule = '.wppa_get_preambule().';'."\n");
		if ($wppa_opt['wppa_film_show_glue'] == 'yes') echo("\t".'wppaFilmShowGlue = true;'."\n");
		else echo("\t".'wppaFilmShowGlue = false;'."\n");
		echo("\t".'wppaSlideShow = "'.__a('Slideshow', 'wppa_theme').'";'."\n");
		echo("\t".'wppaStart = "'.__a('Start', 'wppa_theme').'";'."\n");
		echo("\t".'wppaStop = "'.__a('Stop', 'wppa_theme').'";'."\n");
		echo("\t".'wppaPhoto = "'.__a('Photo', 'wppa_theme').'";'."\n");
		echo("\t".'wppaOf = "'.__a('of', 'wppa_theme').'";'."\n");
		echo("\t".'wppaPreviousPhoto = "'.__a('Previous photo', 'wppa_theme').'";'."\n");
		echo("\t".'wppaNextPhoto = "'.__a('Next photo', 'wppa_theme').'";'."\n");
		echo("\t".'wppaPrevP = "'.__a('Prev.', 'wppa_theme').'";'."\n");
		echo("\t".'wppaNextP = "'.__a('Next', 'wppa_theme').'";'."\n");
		echo("\t".'wppaUserName = "'.wppa_get_user().'";'."\n");
		if ($wppa_opt['wppa_rating_change'] || $wppa_opt['wppa_rating_multi']) echo("\t".'wppaRatingOnce = false;'."\n");
		else echo("\t".'wppaRatingOnce = true;'."\n");
		echo("\t".'wppaPleaseName = "'.__a('Please enter your name', 'wppa_theme').'";'."\n");
		echo("\t".'wppaPleaseEmail = "'.__a('Please enter a valid email address', 'wppa_theme').'";'."\n");
		echo("\t".'wppaPleaseComment = "'.__a('Please enter a comment', 'wppa_theme').'";'."\n");
		
		echo("\t".'wppaBGcolorNumbar = "'.$wppa_opt['wppa_bgcolor_numbar'].'";'."\n");
		echo("\t".'wppaBcolorNumbar = "'.$wppa_opt['wppa_bcolor_numbar'].'";'."\n");
		echo("\t".'wppaBGcolorNumbarActive = "'.$wppa_opt['wppa_bgcolor_numbar_active'].'";'."\n");
		echo("\t".'wppaBcolorNumbarActive = "'.$wppa_opt['wppa_bcolor_numbar_active'].'";'."\n");
		echo("\t".'wppaNumbarMax = "'.$wppa_opt['wppa_numbar_max'].'";'."\n");
		echo("\t".'wppaAjaxUrl = "'.admin_url('admin-ajax.php').'";'."\n");
		if ($wppa_opt['wppa_next_on_callback']) echo("\t".'wppaNextOnCallback = true;'."\n");
		else echo("\t".'wppaNextOnCallback = false;'."\n");
		if ($wppa_opt['wppa_rating_use_ajax']) echo("\t".'wppaRatingUseAjax = true;'."\n");
		else if ($wppa_opt['wppa_rating_use_ajax']) echo("\t".'wppaRatingUseAjax = false;'."\n");
		echo("\t".'wppaStarOpacity = '.($wppa_opt['wppa_star_opacity']/'100').';'."\n");
		// Preload checkmark and clock images
		echo("\t".'wppaTickImg.src = "'.wppa_get_imgdir().'tick.png";'."\n");
		echo("\t".'wppaClockImg.src = "'.wppa_get_imgdir().'clock.png";'."\n");
		if ($wppa_opt['wppa_slide_wrap'] == 'yes') echo("\t".'wppaSlideWrap = true;'."\n");
		else echo("\t".'wppaSlideWrap = false;'."\n");
		switch ($wppa_opt['wppa_slideshow_linktype']) {
			case 'none':
				echo("\t".'wppaLightBox = "";'."\n");		// results in omitting the anchor tag
				break;
			case 'file':
				echo("\t".'wppaLightBox = "file";'."\n");	// gives anchor tag with rel="file"
				break;
			case 'lightbox':
				echo("\t".'wppaLightBox = "'.$wppa_opt['wppa_lightbox_name'].'";'."\n");	// gives anchor tag with rel="lightbox" or the like
				break;
		}
		if ( $wppa_opt['wppa_comment_email_required'] ) echo("\t".'wppaEmailRequired = true;'."\n");
		else echo("\t".'wppaEmailRequired = false;'."\n");
		if ( is_numeric($wppa_opt['wppa_fullimage_border_width']) ) $temp = $wppa_opt['wppa_fullimage_border_width'] + '1'; else $temp = '0';
		echo("\t".'wppaSlideBorderWidth = '.$temp.';'."\n");
		if ( $wppa_opt['wppa_allow_ajax'] ) echo("\t".'wppaAllowAjax = true;'."\n"); 
		else echo("\t".'wppaAllowAjax = false;'."\n");

	echo("/* ]]> */\n");
	echo("</script>\n");
	
	$wppa['rendering_enabled'] = true;
	echo("\n<!-- WPPA+ Rendering enabled -->\n");
	if ($wppa['debug']) {
		error_reporting($wppa['debug']);
		add_action('wp_footer', 'wppa_phpinfo');
		
		//	global $wp_filter; if (is_array($wp_filter)) print_r($wp_filter);
	}
}

/* ADD ADMIN BAR */
require_once 'wppa-adminbar.php';
