<?php 
/* wppa-setup.php
* Package: wp-photo-album-plus
*
* Contains all the setup stuff
* Version 4.3.6
*
*/

/* SETUP */
// It used to be: register_activation_hook(WPPA_FILE, 'wppa_setup');
// The activation hook is useless since wp does no longer call this hook after upgrade of the plugin
// this routine is now called at action admin_init, so also after initial install
// Additionally it can now output messages about success or failure
// Just for people that rely on the healing effect of de-activating and re-activating a plugin
// we still do a setup on activation by faking that we are not up yo rev, and so invoking
// the setup on the first admin_init event. This has the advantage that we can display messages
// instead of characters of unexpected output.
// register_activation_hook(WPPA_FILE, 'wppa_activate'); is in wppa.php
function wppa_activate() {
	$old_rev = get_option('wppa_revision', '100');
	$new_rev = $old_rev - '0.01';
	update_option('wppa_revision', $new_rev);
}
// Set force to true to re-run it even when on rev (happens in wppa-settings.php)
// Force will NOT redefine constants
function wppa_setup($force = false) {
global $silent;
	global $wpdb;
	global $wppa_revno;
	global $current_user;
	global $wppa;
	
	$old_rev = get_option('wppa_revision', '100');

	if ( $old_rev >= $wppa_revno && ! $force ) return; // Nothing to do here
		
	$create_albums = "CREATE TABLE " . WPPA_ALBUMS . " (
					id bigint(20) NOT NULL, 
					name text NOT NULL, 
					description text NOT NULL, 
					a_order smallint(5) unsigned NOT NULL, 
					main_photo bigint(20) NOT NULL, 
					a_parent bigint(20) NOT NULL,
					p_order_by int unsigned NOT NULL,
					cover_linktype tinytext NOT NULL,
					cover_linkpage bigint(20) NOT NULL,
					owner text NOT NULL,
					timestamp tinytext NOT NULL,
					PRIMARY KEY  (id) 
					) DEFAULT CHARACTER SET utf8;";
					
	$create_photos = "CREATE TABLE " . WPPA_PHOTOS . " (
					id bigint(20) NOT NULL, 
					album bigint(20) NOT NULL, 
					ext tinytext NOT NULL, 
					name text NOT NULL, 
					description longtext NOT NULL, 
					p_order smallint(5) unsigned NOT NULL,
					mean_rating tinytext NOT NULL,
					linkurl text NOT NULL,
					linktitle text NOT NULL,
					owner text NOT NULL,
					timestamp tinytext NOT NULL,
					PRIMARY KEY  (id) 
					) DEFAULT CHARACTER SET utf8;";

	$create_rating = "CREATE TABLE " . WPPA_RATING . " (
					id bigint(20) NOT NULL,
					photo bigint(20) NOT NULL,
					value smallint(5) NOT NULL,
					user text NOT NULL,
					PRIMARY KEY  (id)
					) DEFAULT CHARACTER SET utf8;";
					
	$create_comments = "CREATE TABLE " . WPPA_COMMENTS . " (
					id bigint(20) NOT NULL,
					timestamp tinytext NOT NULL,
					photo bigint(20) NOT NULL,
					user text NOT NULL,
					ip tinytext NOT NULL,
					email text NOT NULL,
					comment text NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id)	
					) DEFAULT CHARACTER SET utf8;";
					
	$create_iptc = "CREATE TABLE " . WPPA_IPTC . " (
					id bigint(20) NOT NULL,
					photo bigint(20) NOT NULL,
					tag tinytext NOT NULL,
					description text NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id)					
					) DEFAULT CHARACTER SET utf8;";

	$create_exif = "CREATE TABLE " . WPPA_EXIF . " (
					id bigint(20) NOT NULL,
					photo bigint(20) NOT NULL,
					tag tinytext NOT NULL,
					description text NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id)					
					) DEFAULT CHARACTER SET utf8;";
					
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	
	$tn = array( WPPA_ALBUMS, WPPA_PHOTOS, WPPA_RATING, WPPA_COMMENTS, WPPA_IPTC, WPPA_EXIF );
	$tc = array( $create_albums, $create_photos, $create_rating, $create_comments, $create_iptc, $create_exif );
	$idx = 0;
	while ($idx < 6) {
		$a0 = wppa_table_exists($tn[$idx]);
		dbDelta($tc[$idx]);
		$a1 = wppa_table_exists($tn[$idx]);
		if ( ! $a0 ) {
			if ( $a1 ) wppa_ok_message('Database table '.$tn[$idx].' created.');
			else wppa_error_message('Could not create database table '.$tn[$idx]);
		}
		else if ( isset($wppa['debug']) && $wppa['debug'] ) wppa_ok_message('Database table '.$tn[$idx].' updated.');
		$idx++;
	}
		
	// Coverphoto_left is obsolete per version 4.0.2 and changed to coverphoto_pos
	if (get_option('wppa_coverphoto_left', 'nil') == 'no') update_option('wppa_coverphoto_pos', 'right');
	if (get_option('wppa_coverphoto_left', 'nil') == 'yes') update_option('wppa_coverphoto_pos', 'left');
	if (get_option('wppa_coverphoto_left', 'nil') != 'nil') delete_option('wppa_coverphoto_left');
	// 2col and 3col tresholds are obsolete per version 4.0.2 and replaced by max_cover_width
	if (get_option('wppa_2col_treshold', 'nil') != 'nil') {
		update_option('wppa_max_cover_width', get_option('wppa_2col_treshold', '1024'));
		delete_option('wppa_2col_treshold');
		delete_option('wppa_3col_treshold');
	}

	if ( get_option('wppa_use_lightbox', 'no') == 'yes' && get_option('wppa_slideshow_linktype', 'nil') == 'nil' ) update_option('wppa_slideshow_linktype', 'lightbox');
	
	wppa_set_defaults();					// Will always work
	if ( ! wppa_check_dirs() ) return;		// Quit on error, messages are given in check_dirs

	// Copy factory supplied watermarks
	$frompath = WPPA_PATH . '/watermarks';
	$watermarks = glob($frompath . '/*.png');
	if ( is_array($watermarks) ) {
		foreach ($watermarks as $fromfile) {
			$tofile = WPPA_UPLOAD_PATH . '/watermarks/' . basename($fromfile);
			copy($fromfile, $tofile);
		}
	}
	
	$iret = true;

	$key = '0';
	if ( $old_rev < '400' ) {		// theme changed since...
		$usertheme_old 	= ABSPATH.'wp-content/themes/'.get_option('template').'/wppa_theme.php';
		$usertheme 		= ABSPATH.'wp-content/themes/'.get_option('template').'/wppa-theme.php';
		if ( is_file( $usertheme ) || is_file( $usertheme_old ) ) $key += '2';
	}
	if ( $old_rev < '420' ) {		// css changed since...
		$userstyle_old 	= ABSPATH.'wp-content/themes/'.get_option('template').'/wppa_style.css';
		$userstyle 		= ABSPATH.'wp-content/themes/'.get_option('template').'/wppa-style.css';
		if ( is_file( $userstyle ) || is_file( $userstyle_old ) ) $key += '1';
	}
	if ( $key ) {
		$msg = '<center>' . __('IMPORTANT UPGRADE NOTICE', 'wppa') . '</center><br/>';
		if ($key == '1' || $key == '3') $msg .= '<br/>' . __('Please CHECK your customized WPPA-STYLE.CSS file against the newly supplied one. You may wish to add or modify some attributes. Be aware of the fact that most settings can now be set in the admin settings page.', 'wppa');
		if ($key == '2' || $key == '3') $msg .= '<br/>' . __('Please REPLACE your customized WPPA-THEME.PHP file by the newly supplied one, or just remove it from your theme directory. You may modify it later if you wish. Your current customized version is NOT compatible with this version of the plugin software.', 'wppa');
		wppa_ok_message($msg);
	}
	
	if ( $old_rev < '436' ) {
		update_option('wppa_show_bbb_widget', get_option('wppa_show_bbb', 'no'));
	}
	if ( $old_rev <= '432' ) {
		if ( get_option('wppa_comment_use_gravatar', 'no') == 'yes' ) update_option('wppa_comment_gravatar', 'mm');
		if ( get_option('wppa_comment_use_gravatar', 'nil') != 'nil' ) delete_option('wppa_comment_use_gravatar');
	}
	
	if ( $old_rev <= '428' ) {
		$wppa_start_slide = get_option('wppa_start_slide');
		if ( $wppa_start_slide == 'yes' ) update_option('wppa_start_slide', 'run');
		if ( $wppa_start_slide == 'no' ) update_option('wppa_start_slide', 'still');
	}
		
	if ( $old_rev < '243' || $force ) {		// owner added in...
		get_currentuserinfo();
		$user = $current_user->user_login;
		$query = $wpdb->prepare( 'UPDATE `'.WPPA_ALBUMS.'` SET `owner` = %s WHERE `owner` = %s', $user, '' );
		$iret = $wpdb->query( $query );
	}
	
	if ( $iret !== false && ( $old_rev < '411' || $force ) ) {		// cover_linktype added in...
		$query = $wpdb->prepare( 'UPDATE `'.WPPA_ALBUMS.'`  SET `cover_linktype` = %s WHERE `cover_linktype` = %s', 'content', '' );
		$iret = $wpdb->query( $query );

		$query = $wpdb->prepare( 'UPDATE `'.WPPA_ALBUMS.'`  SET `cover_linktype` = %s WHERE `cover_linkpage` = %s', 'none', '-1' );
		$iret = $wpdb->query( $query );
	}
	
	if ($iret !== false) {
		update_option('wppa_revision', $wppa_revno);	
		if ( ! strstr($old_rev, '.') ) {	// NOT on activation
			if ( is_multisite() ) {
				if ( get_option('wppa_multisite', 'no') == 'yes' ) {
					wppa_ok_message(sprintf(__('WPPA+ successfully updated in multi site mode to db version %s.', 'wppa'), $wppa_revno));
				}
				else {
					wppa_error_message(sprintf(__('WPPA+ updated in single site mode to db version %s. Please visit <b>Photo Albums -> Settings</b> and follow the instructions.', 'wppa'), $wppa_revno));
				}
			}
			else {
				wppa_ok_message(sprintf(__('WPPA+ successfully updated in single site mode to db version %s.', 'wppa'), $wppa_revno));
			}
		}
	}
}

// Set default option values if the option does not exist.
// With $force = true, all options will be set to their default value.
function wppa_set_defaults($force = false) {
global $wppa_defaults;

	$wppa_npd = 'The brief photo description';
	$wppa_npd .= "\n".'<a href="javascript://" onClick="jQuery(\'.wppa-detail\').css(\'display\', \'block\'); jQuery(\'.wppa-more\').css(\'display\', \'none\');">';
	$wppa_npd .= "\n".'<div class="wppa-more">'."\n".'More -->'."\n".'</div>';
	$wppa_npd .= "\n".'</a>';
	$wppa_npd .= "\n".'<a href="javascript://" onClick="jQuery(\'.wppa-detail\').css(\'display\', \'none\'); jQuery(\'.wppa-more\').css(\'display\', \'block\');">';
	$wppa_npd .= "\n".'<div class="wppa-detail" style="display:none;" >'."\n".'<-- Less'."\n".'</div>';
	$wppa_npd .= "\n".'</a>';
	$wppa_npd .= "\n".'<div class="wppa-detail" style="display:none;">';
	$wppa_npd .= "\n".'<table style="margin:0;" >';
	$wppa_npd .= "\n".'<tr><td>Date Shot:</td><td>Feb 30 2011 17:37:39</td></tr>';
	$wppa_npd .= "\n".'<tr><td>Artist:</td><td>Demos Examplos</td></tr>';
	$wppa_npd .= "\n".'<tr><td>Copyright:</td><td>Lorem Ipse Inc. Ltd.</td></tr>';
	$wppa_npd .= "\n".'</table>';
	$wppa_npd .= "\n".'</div>';
		
	$wppa_defaults = array ( 'wppa_revision' 		=> '100',
						'wppa_fullsize' 			=> '640',
						'wppa_colwidth' 			=> '640',
						'wppa_maxheight' 			=> '640',
						'wppa_enlarge' 				=> 'no',
						'wppa_resize_on_upload' 	=> 'no',
						'wppa_resize_to'			=> '0',
						'wppa_fullvalign' 			=> 'fit',
						'wppa_fullhalign' 			=> 'center',
						'wppa_min_thumbs' 			=> '1',
						'wppa_thumbtype' 			=> 'default',
						'wppa_valign' 				=> 'center',
						'wppa_thumbsize' 			=> '100',
						'wppa_tf_width' 			=> '100',
						'wppa_tf_height' 			=> '130',
						'wppa_tn_margin' 			=> '4',
						'wppa_smallsize' 			=> '150',
						'wppa_show_bread' 			=> 'yes',
						'wppa_show_home' 			=> 'yes',
						'wppa_bc_separator' 		=> 'raquo',
						'wppa_use_thumb_opacity' 	=> 'yes',
						'wppa_thumb_opacity' 		=> '85',
						'wppa_use_thumb_popup' 		=> 'yes',
						'wppa_use_cover_opacity' 	=> 'yes',
						'wppa_cover_opacity' 		=> '85',
						'wppa_animation_speed' 		=> '800',
						'wppa_slideshow_timeout'	=> '2500',
						'wppa_bgcolor_even' 		=> '#eeeeee',
						'wppa_bgcolor_alt' 			=> '#dddddd',
						'wppa_bgcolor_nav' 			=> '#dddddd',
						'wppa_bgcolor_img'			=> '#eeeeee',
						'wppa_bgcolor_namedesc' 	=> '#dddddd',
						'wppa_bgcolor_com' 			=> '#dddddd',
						'wppa_bgcolor_iptc'			=> '#dddddd',
						'wppa_bgcolor_exif'			=> '#dddddd',
						'wppa_bgcolor_cus'			=> '#dddddd',
						'wppa_bgcolor_numbar'		=> '#cccccc',
						'wppa_bgcolor_numbar_active'=> '#333333',
						'wppa_bcolor_even' 			=> '#cccccc',
						'wppa_bcolor_alt' 			=> '#bbbbbb',
						'wppa_bcolor_nav' 			=> '#bbbbbb',
						'wppa_bcolor_img'			=> '',
						'wppa_bcolor_namedesc' 		=> '#bbbbbb',
						'wppa_bcolor_com' 			=> '#bbbbbb',
						'wppa_bcolor_iptc' 			=> '#bbbbbb',
						'wppa_bcolor_exif' 			=> '#bbbbbb',
						'wppa_bcolor_cus'			=> '#bbbbbb',
						'wppa_bcolor_numbar'		=> '#cccccc',
						'wppa_bcolor_numbar_active' => '#333333',
						'wppa_bwidth' 				=> '1',
						'wppa_bradius' 				=> '6',
						'wppa_fontfamily_thumb' 	=> '',
						'wppa_fontsize_thumb' 		=> '',
						'wppa_fontcolor_thumb' 		=> '',
						'wppa_fontweight_thumb'		=> 'normal',
						'wppa_fontfamily_box' 		=> '',
						'wppa_fontsize_box' 		=> '',
						'wppa_fontcolor_box' 		=> '',
						'wppa_fontweight_box'		=> 'normal',
						'wppa_fontfamily_nav' 		=> '',
						'wppa_fontsize_nav' 		=> '',
						'wppa_fontcolor_nav' 		=> '',
						'wppa_fontweight_nav'		=> 'normal',
						'wppa_fontfamily_title' 	=> '',
						'wppa_fontsize_title' 		=> '',
						'wppa_fontcolor_title' 		=> '',
						'wppa_fontweight_title'		=> 'bold',
						'wppa_fontfamily_fulldesc' 	=> '',
						'wppa_fontsize_fulldesc' 	=> '',
						'wppa_fontcolor_fulldesc' 	=> '',
						'wppa_fontweight_fulldesc'	=> 'normal',
						'wppa_fontfamily_fulltitle' => '',
						'wppa_fontsize_fulltitle' 	=> '',
						'wppa_fontcolor_fulltitle' 	=> '',
						'wppa_fontweight_fulltitle'	=> 'normal',
						'wppa_fontfamily_numbar' 	=> '',
						'wppa_fontsize_numbar' 		=> '',
						'wppa_fontcolor_numbar' 	=> '#777777',
						'wppa_fontweight_numbar'	=> 'bold',
						'wppa_arrow_color' 			=> 'black',
						'wppa_max_cover_width'		=> '1024',
						'wppa_text_frame_height'	=> '54',
						'wppa_film_show_glue' 		=> 'yes',
						'wppa_album_page_size' 		=> '0',
						'wppa_thumb_page_size' 		=> '0',
						'wppa_thumb_auto' 			=> 'yes',
						'wppa_coverphoto_pos'		=> 'right',
						'wppa_thumbphoto_left' 		=> 'no',
						'wppa_enable_slideshow' 	=> 'yes',
						'wppa_thumb_text_name' 		=> 'yes',
						'wppa_thumb_text_desc' 		=> 'yes',
						'wppa_thumb_text_rating' 			=> 'yes',
						'wppa_show_startstop_navigation' 	=> 'yes',
						'wppa_show_browse_navigation' 		=> 'yes',
						'wppa_show_full_desc' 				=> 'yes',
						'wppa_show_full_name' 		=> 'yes',
						'wppa_show_comments' 		=> 'no',
						'wppa_show_cover_text' 		=> 'yes',
						'wppa_start_slide' 			=> 'run',
						'wppa_hide_slideshow' 		=> 'no',
						'wppa_filmstrip' 			=> 'yes',
						'wppa_bc_url' 				=> wppa_get_imgdir().'arrow.gif',
						'wppa_bc_txt' 				=> htmlspecialchars('<span style="color:red; font_size:24px;">&bull;</span>'),
						'wppa_topten_count' 		=> '10',
						'wppa_topten_size' 			=> '86',
						'wppa_excl_sep' 			=> 'no',
						'wppa_rating_on' 			=> 'yes',
						'wppa_rating_login' 		=> 'yes',
						'wppa_rating_change' 		=> 'yes',
						'wppa_rating_multi' 		=> 'no',
						'wppa_comment_login' 		=> 'no',
						'wppa_list_albums_by' 		=> '0',
						'wppa_list_albums_desc' 	=> 'no',
						'wppa_list_photos_by' 		=> '0',
						'wppa_list_photos_desc' 	=> 'no',
						'wppa_html' 				=> 'no',
						'wppa_thumb_linkpage' 		=> '0',
						'wppa_thumb_linktype' 		=> 'photo',
						'wppa_mphoto_linkpage' 		=> '0',
						'wppa_mphoto_linktype' 		=> 'photo',
						'wppa_fadein_after_fadeout' => 'no',
						'wppa_widget_linkpage' 		=> '0',
						'wppa_widget_linktype' 		=> 'album',
						'wppa_widget_linkurl'		=> '',
						'wppa_widget_linktitle' 	=> '',
						'wppa_topten_widget_linkpage' 		=> '0',
						'wppa_topten_widget_linktype' 		=> 'photo',
						'wppa_slideonly_widget_linkpage' 	=> '0',
						'wppa_slideonly_widget_linktype' 	=> 'widget',
						'wppa_coverimg_linkpage' 	=> '0',
						'wppa_coverimg_linktype' 	=> 'same',
						'wppa_mphoto_overrule'		=> 'no',
						'wppa_thumb_overrule'		=> 'no',
						'wppa_topten_overrule'		=> 'no',
						'wppa_sswidget_overrule'	=> 'no',
						'wppa_potdwidget_overrule'	=> 'no',
						'wppa_coverimg_overrule'	=> 'no',
						'wppa_slideshow_overrule'	=> 'no',
						'wppa_search_linkpage' 		=> '0',
						'wppa_rating_clear' 		=> 'no',
						'wppa_chmod' 				=> '0',
						'wppa_owner_only' 			=> 'no',
						'wppa_set_access_by' 		=> 'me',
						'wppa_accesslevel' 			=> 'administrator',
						'wppa_accesslevel_upload' 	=> 'administrator',
						'wppa_accesslevel_sidebar' 	=> 'administrator',
						'wppa_charset' 				=> '',
						'wppa_setup' 				=> '',
						'wppa_backup' 				=> '',
						'wppa_restore' 				=> '',
						'wppa_defaults' 			=> '',
						'wppa_regen' 				=> '',
						'wppa_rerate'				=> '',
						'wppa_allow_debug' 			=> 'no',
						'wppa_potd_align' 			=> 'center',
						'wppa_comadmin_show' 		=> 'all',
						'wppa_popupsize' 			=> get_option('wppa_smallsize', '150'),
						'wppa_comadmin_order' 		=> 'timestamp',
						'wppa_slide_order'			=> '0,1,2,3,4,5,6,7,8,9',
						'wppa_show_bbb'				=> 'no',
						'wppa_show_bbb_widget'		=> 'no',
						'wppa_show_slideshowbrowselink' => 'yes',
						'wppa_fullimage_border_width' 	=> '',
						'wppa_bgcolor_fullimg' 			=> '#ccc',
						'wppa_bcolor_fullimg' 			=> '#777',
						'wppa_max_photo_newtime'		=> '0',
						'wppa_max_album_newtime'		=> '0',
						'wppa_load_skin' 				=> '',
						'wppa_skinfile' 				=> 'default',
						'wppa_use_lightbox'				=> 'no',
						'wppa_lightbox_bordersize'		=> '10',
						'wppa_lightbox_animationspeed'	=> '5',
						'wppa_lightbox_backgroundcolor' => '#fff',
						'wppa_lightbox_bordercolor' 	=> '#fff',
						'wppa_lightbox_overlaycolor' 	=> '#000',
						'wppa_lightbox_overlayopacity'	=> '80',
						'wppa_swap_namedesc' 			=> 'no',
						'wppa_fontfamily_lightbox'		=> 'Verdana, Helvetica, sans-serif',
						'wppa_fontsize_lightbox'		=> '10',
						'wppa_fontcolor_lightbox'		=> '#666',
						'wppa_fontweight_lightbox'		=> 'normal',
						'wppa_filter_priority'			=> '1001',
						'wppa_widget_width'				=> '200',
						'wppa_custom_on' 				=> 'no',
						'wppa_custom_content' 			=> '<div style="color:red; font-size:24px; font-weight:bold; text-align:center;">Hello world!</div>',
						'wppa_apply_newphoto_desc'		=> 'no',
						'wppa_newphoto_description'		=> $wppa_npd,
						'wppa_comments_desc'			=> 'no',
						'wppa_user_upload_on'			=> 'no',
						'wppa_show_slideshownumbar'  	=> 'no',
						'wppa_autoclean'				=> 'yes',
						'wppa_numbar_max'				=> '10',
						'wppa_watermark_on'				=> 'no',
						'wppa_watermark_user'			=> 'no',
						'wppa_watermark_file'			=> 'specimen.png',
						'wppa_watermark_pos'			=> 'cencen',
						'wppa_watermark_upload'			=> '',
						'wppa_comment_widget_linkpage'	=> '0',
						'wppa_comment_widget_linktype'	=> 'photo',
						'wppa_comment_count'			=> '10',
						'wppa_comment_size'				=> '86',
						'wppa_comment_overrule'			=> 'no',
						'wppa_next_on_callback'			=> 'no',
						'wppa_show_avg_rating'			=> 'yes',
						'wppa_rating_use_ajax'			=> 'no',
						'wppa_star_opacity'				=> '20',
						'wppa_album_admin_autosave'		=> 'yes',
						'wppa_settings_autosave'		=> 'yes',
						'wppa_slide_wrap'				=> 'yes',
						'wppa_comment_login_approved'	=> 'yes',
						'wppa_lightbox_name'			=> 'lightbox',
						'wppa_slideshow_linktype'		=> 'none',
						'wppa_popup_text_name' 			=> 'yes',
						'wppa_popup_text_desc' 			=> 'yes',
						'wppa_popup_text_rating' 		=> 'yes',
						'wppa_thumb_aspect'				=> '0:0:none',
						'wppa_show_iptc'				=> 'no',
						'wppa_show_exif'				=> 'no',
						'wppa_comment_gravatar'			=> 'none',
						'wppa_comment_gravatar_url'		=> 'http://',
						'wppa_gravatar_size'			=> '40',
						'wppa_comment_moderation'		=> 'logout',
						'wppa_comment_email_required'	=> 'yes',
						'wppa_copyright_on'				=> 'no',
						'wppa_copyright_notice'			=> __('<span style="color:red" >Warning: Do not upload copyrighted material!</span>', 'wppa'),
						'wppa_fulldesc_align'			=> 'center',
						'wppa_allow_ajax'				=> 'no'

						);

	array_walk($wppa_defaults, 'wppa_set_default', $force);

	return true;
}
function wppa_set_default($value, $key, $force) {
	if ($force) {
		// Skip the autosave version settings when force, so this is set only once at upgrade or new instal and not when restoring to defaults
		if ( $key != 'wppa_album_admin_autosave' && $key != 'wppa_settings_autosave' ) {
			update_option($key, $value);
		}
	}
	else {
		if (get_option($key, 'nil') == 'nil') update_option($key, $value);
	}
}

// Check if the required directories exist, if not, try to create them and report it
function wppa_check_dirs() {

	if ( ! is_multisite() ) {
		// check if uploads dir exists
		$dir = ABSPATH . 'wp-content/uploads';
		if (!is_dir($dir)) {
			mkdir($dir);
			if (!is_dir($dir)) {
				wppa_error_message(__('The uploads directory does not exist, please do a regular WP upload first.', 'wppa').'<br/>'.$dir);
				return false;
			}
			else {
				wppa_ok_message(__('Successfully created uploads directory.', 'wppa').'<br/>'.$dir);
			}
		}	
	}

	// check if wppa dir exists
	$dir = WPPA_UPLOAD_PATH;
	if (!is_dir($dir)) {
		mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Could not create the wppa directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
			wppa_ok_message(__('Successfully created wppa directory.', 'wppa').'<br/>'.$dir);
		}
	}
	
	// check if thumbs dir exists 
	$dir = WPPA_UPLOAD_PATH.'/thumbs';
	if (!is_dir($dir)) {
		mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Could not create the wppa thumbs directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
			wppa_ok_message(__('Successfully created wppa thumbs directory.', 'wppa').'<br/>'.$dir);
		}
	}

	// check if watermarks dir exists 
	$dir = WPPA_UPLOAD_PATH.'/watermarks';
	if (!is_dir($dir)) {
		mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Could not create the wppa watermarks directory.', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
			wppa_ok_message(__('Successfully created wppa watermarks directory.', 'wppa').'<br/>'.$dir);
		}
	}
	
	// check if depot dir exists
	if ( ! is_multisite() ) {
		// check if users depot dir exists
		$dir = ABSPATH.'wp-content/wppa-depot';
		if (!is_dir($dir)) {
			mkdir($dir);
			if (!is_dir($dir)) {
				wppa_error_message(__('Unable to create depot directory.', 'wppa').wppa_credirmsg($dir));
				return false;
			}
			else {
				wppa_ok_message(__('Successfully created wppa depot directory.', 'wppa').'<br/>'.$dir);
			}
		}
	}
	
	// check the user depot directory
	$dir = WPPA_DEPOT_PATH;
	if (!is_dir($dir)) {
		mkdir($dir);
		if (!is_dir($dir)) {
			wppa_error_message(__('Unable to create user depot directory', 'wppa').wppa_credirmsg($dir));
			return false;
		}
		else {
			wppa_ok_message(__('Successfully created wppa user depot directory.', 'wppa').'<br/>'.$dir);
		}
	}
	
	return true;
}
function wppa_credirmsg($dir) {
	$msg = ' '.sprintf(__('Ask your administrator to give you more rights, try CHMOD from table VII item 1 of the Photo Albums -> Settings admin page or create <b>%s</b> manually using an FTP program.', 'wppa'), $dir);
	return $msg;
}

