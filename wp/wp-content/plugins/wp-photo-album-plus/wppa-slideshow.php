<?php 
/* wppa-slideshow.php
* Package: wp-photo-album-plus
*
* Contains all the slideshow high level functions
* Version 4.3.6
*
*/

function wppa_the_slideshow() {
global $wppa_opt;

	$indexes = explode(',', $wppa_opt['wppa_slide_order']);
	$i = '0';
	while ( $i < '10' ) {
		switch ( $indexes[$i] ) {
			case '0':
				wppaStartStop('optional');				// The 'Slower | start/stop | Faster' bar
				break;
			case '1':
				wppa_slide_frame();						// The photo / slide
				break;
			case '2':
				wppa_slide_name_desc('optional');		// Show name and description in a box. This replaces the old separate ones
				break;
			case '3':
				wppa_slide_custom('optional');			// Custom box			// Reserved for future use, does nothing as per version 3.1.0
				break;
			case '4':
				wppa_slide_rating('optional');			// Rating box
				break;
			case '5':
				wppa_slide_filmstrip('optional');		// Show Filmstrip
				break;
			case '6':
				wppa_browsebar('optional');				// The 'Previous photo | Photo n of m | Next photo' bar
				break;
			case '7':
				wppa_comments('optional');				// The Comments box
				break;
			case '8':
				wppa_iptc('optional');					// The IPTC box
				break;
			case '9':
				wppa_exif('optional');					// The EXIF box
				break;
			default:
				break;
		}
		$i++;
	}
}

function wppaStartStop($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
//		wppa_dummy_bar(__('- - - Start/stop slideshow navigation bar - - -', 'wppa_theme'));
		return;
	}
	
	// we always need this for the functionality (through filmstrip etc).
	// so if not wanted: hide it
	$hide = 'display:none; '; // assume hide
	if ($opt != 'optional') $hide = '';								// not optional: show
	if ($wppa_opt['wppa_show_startstop_navigation'] && !$wppa['is_slideonly']) $hide = '';	// we want it
	
	//if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	//$hide = $wppa_opt['wppa_enable_slideshow'] ? '' : ;
	
	if ( $wppa_opt['wppa_start_slide'] || $wppa['in_widget'] ) {
		$wppa['out'] .= "\n";
		$wppa['out'] .= '<script type="text/javascript">'."\n";
		$wppa['out'] .= '/* <![CDATA[ */'."\n";

		$wppa['out'] .= 'wppaSlideInitRunning['.$wppa['master_occur'].'] = true;';
		$wppa['out'] .= 'wppaMaxOccur = '.$wppa['master_occur'].';';
		
		$wppa['out'] .= "/* ]]> */\n";
		$wppa['out'] .= "</script>\n";
	}

	$wppa['out'] .= wppa_nltab('+').'<div id="prevnext1-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').$hide.'">';
		$wppa['out'] .= wppa_nltab().'<a id="speed0-'.$wppa['master_occur'].'" class="wppa-nav-text speed0" style="'.__wcs('wppa-nav-text').'" onclick="wppaSpeed('.$wppa['master_occur'].', false)">'.__a('Slower', 'wppa_theme').'</a> | ';
		$wppa['out'] .= wppa_nltab().'<a id="startstop-'.$wppa['master_occur'].'" class="wppa-nav-text startstop" style="'.__wcs('wppa-nav-text').'" onclick="wppaStartStop('.$wppa['master_occur'].', -1)">'.__a('Start', 'wppa_theme').'</a> | ';
		$wppa['out'] .= wppa_nltab().'<a id="speed1-'.$wppa['master_occur'].'" class="wppa-nav-text speed1" style="'.__wcs('wppa-nav-text').'" onclick="wppaSpeed('.$wppa['master_occur'].', true)">'.__a('Faster', 'wppa_theme').'</a>';
	$wppa['out'] .= wppa_nltab('-').'</div><!-- #prevnext1 -->';
}

function wppa_slide_frame() {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
		if (wppa_page('oneofone')) {
//			wppa_dummy_bar(__('- - - Single photo - - -', 'wppa_theme'));
		}
		else {
//			wppa_dummy_bar(__('- - - Slideshow - - -', 'wppa_theme'));
		}
		return;
	}
	// There are still users who turn off javascript...
	$wppa['out'] .= wppa_nltab().'<noscript style="text-align:center; " ><span style="color:red; ">'.__a('To see the full size images, you need to enable javascript in your browser.', 'wppa').'</span></noscript>';
	$wppa['out'] .= wppa_nltab('+').'<div id="slide_frame-'.$wppa['master_occur'].'" class="slide-frame" style="'.wppa_get_slide_frame_style().'">';
		$wppa['out'] .= wppa_nltab().'<div id="theslide0-'.$wppa['master_occur'].'" class="theslide" style="width:'.$wppa['slideframewidth'].'px; " ></div>';
		$wppa['out'] .= wppa_nltab().'<div id="theslide1-'.$wppa['master_occur'].'" class="theslide" style="width:'.$wppa['slideframewidth'].'px; " ></div>';
		$wppa['out'] .= wppa_nltab().'<div id="spinner-'.$wppa['master_occur'].'" class="spinner"></div>';
		if ( ! wppa_page('oneofone') ) {	
			if (( $wppa_opt['wppa_show_bbb'] && ! $wppa['in_widget'] ) || ( $wppa_opt['wppa_show_bbb_widget'] && $wppa['in_widget'] )){	// big browsing buttons enabled
				$wppa['out'] .= wppa_nltab().'<img id="bbb-'.$wppa['master_occur'].'-l" class="bbb-'.$wppa['master_occur'].'" src="'.wppa_get_imgdir().'bbbl.png" style=" opacity:0; filter:alpha(opacity=0); border:none; z-index:903; position: absolute; left:0px; top: 0px; width: '.($wppa['slideframewidth']*0.5).'px; height: '.$wppa['slideframeheight'].'px; box-shadow: none; cursor:default;" onmouseover="wppaBbb('.$wppa['master_occur'].',\'l\',\'show\')" onmouseout="wppaBbb('.$wppa['master_occur'].',\'l\',\'hide\')" onclick="wppaBbb('.$wppa['master_occur'].',\'l\',\'click\')" />';
				$wppa['out'] .= wppa_nltab().'<img id="bbb-'.$wppa['master_occur'].'-r" class="bbb-'.$wppa['master_occur'].'" src="'.wppa_get_imgdir().'bbbr.png" style=" opacity:0; filter:alpha(opacity=0); border:none; z-index:903; position: absolute; left:'.($wppa['slideframewidth']*0.5).'px;top: 0px; width: '.($wppa['slideframewidth']*0.5).'px; height: '.$wppa['slideframeheight'].'px; box-shadow: none; cursor:default;" onmouseover="wppaBbb('.$wppa['master_occur'].',\'r\',\'show\')" onmouseout="wppaBbb('.$wppa['master_occur'].',\'r\',\'hide\')" onclick="wppaBbb('.$wppa['master_occur'].',\'r\',\'click\')" />';
			} /***/
		}
		wppa_numberbar();
		
	$wppa['out'] .= wppa_nltab('-').'</div>';
}

function wppa_slide_name_desc($key = 'optional') {
global $wppa;
global $wppa_opt;
	
	$do_it = false;
	if ($key != 'optional') $do_it = true;
	if ($wppa['is_slideonly']) {
		if ($wppa['name_on']) $do_it = true;
		if ($wppa['desc_on']) $do_it = true;
	}
	else {
		if ($wppa_opt['wppa_show_full_desc']) $do_it = true;
		if ($wppa_opt['wppa_show_full_name']) $do_it = true;
	}
	if ($do_it) { 
		$wppa['out'] .= wppa_nltab('+').'<div id="namedesc-'.$wppa['master_occur'].'" class="wppa-box wppa-name-desc" style="'.__wcs('wppa-box').__wcs('wppa-name-desc').'" >';
			if ($wppa_opt['wppa_swap_namedesc']) {
				wppa_slide_name($key);			// The name of the photo
				wppa_slide_description($key);		// The description of the photo
			}
			else {
				wppa_slide_description($key);		// The description of the photo
				wppa_slide_name($key);			// The name of the photo
			}
		$wppa['out'] .= wppa_nltab('-').'</div><!-- #namedesc -->';
	}
}

function wppa_slide_name($opt = '') {
global $wppa;
global $wppa_opt;

	if (($opt == 'optional') && !$wppa_opt['wppa_show_full_name']) return;
	if ($wppa['is_slideonly'] && !$wppa['name_on']) return;	/* Not when slideonly and not explicitly turned on in the widget */
	$wppa['out'] .= wppa_nltab().'<div id="imagetitle-'.$wppa['master_occur'].'" class="wppa-fulltitle imagetitle" style="'.__wcs('wppa-fulltitle').'padding:3px; width:100%"></div>';
}	

function wppa_slide_description($opt = '') {
global $wppa;
global $wppa_opt;

	if (($opt == 'optional') && !$wppa_opt['wppa_show_full_desc']) return;
	if ($wppa['is_slideonly'] && !$wppa['desc_on']) return;	/* Not when slideonly and not explicitly turned on in the widget */
	$wppa['out'] .= wppa_nltab().'<div id="imagedesc-'.$wppa['master_occur'].'" class="wppa-fulldesc imagedesc" style="'.__wcs('wppa-fulldesc').'padding:3px; width:100%; text-align:'.$wppa_opt['wppa_fulldesc_align'].'"></div>';
}

function wppa_slide_custom($opt = '') {
global $wppa;
global $wppa_opt;

	if ($opt == 'optional' && !$wppa_opt['wppa_custom_on']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	if (is_feed()) {
//		wppa_dummy_bar(_ _a('- - - Custom enabled - - -', 'wppa_theme'));
		return;
	}
	
	$wppa['out'] .= wppa_nltab('+').'<div id="wppa-custom-'.$wppa['master_occur'].'" class="wppa-box wppa-custom" style="'.__wcs('wppa-box').__wcs('wppa-custom').'">';
		$wppa['out'] .= wppa_nltab().stripslashes($wppa_opt['wppa_custom_content']);
	$wppa['out'] .= wppa_nltab('-').'</div>';
}

function wppa_slide_rating($opt = '') {
global $wppa;
global $wppa_opt;

	if ($opt == 'optional' && !$wppa_opt['wppa_rating_on']) return;
	if ($wppa['is_slideonly'] == '1') return;	/* Not when slideonly */
	if (is_feed()) {
		wppa_dummy_bar(__a('- - - Rating enabled - - -', 'wppa_theme'));
		return;
	}
	$fs = $wppa_opt['wppa_fontsize_nav'];	
	$dh = $fs + '6';
	$size = 'font-size:'.$fs.'px;';
	
	$wppa['out'] .= wppa_nltab('+').'<div id="wppa-rating-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="'.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').$size.' text-align:center;">';

	$r['1'] = __a('very low', 'wppa_theme');
	$r['2'] = __a('low', 'wppa_theme');
	$r['3'] = __a('average', 'wppa_theme');
	$r['4'] = __a('high', 'wppa_theme');
	$r['5'] = __a('very high', 'wppa_theme');

	if ($fs != '') $fs += 3; else $fs = '15';	// iconsize = fontsize+3, Default to 15
	$style = 'style="height:'.$fs.'px; margin:0 0 -3px 0; padding:0;"';
	$icon = 'star.png';

	if ( $wppa_opt['wppa_show_avg_rating'] ) {
		$wppa['out'] .= __a('Average&nbsp;rating', 'wppa_theme').'&nbsp;';
		
		$i = '1';
		while ($i < '6') {
			$wppa['out'] .= wppa_nltab().'<img id="wppa-avg-'.$wppa['master_occur'].'-'.$i.'" class="wppa-avg-'.$wppa['master_occur'].' no-shadow" '.$style.' src="'.wppa_get_imgdir().$icon.'" alt="'.$i.'" title="'.__a('Average&nbsp;rating', 'wppa_theme').': '.$r[$i].'" />';
			$i++;
		}
		
		$wppa['out'] .= '&nbsp;&nbsp;';
	}
	
	if (!$wppa_opt['wppa_rating_login'] || is_user_logged_in()) {
		if ( ! $wppa_opt['wppa_show_avg_rating'] ) $wppa['out'] .= __a('My&nbsp;rating', 'wppa_theme').':&nbsp;';
		
		$i = '1';
		while ($i < '6') {
			$wppa['out'] .= wppa_nltab().'<img id="wppa-rate-'.$wppa['master_occur'].'-'.$i.'" class="wppa-rate-'.$wppa['master_occur'].' no-shadow" '.$style.' src="'.wppa_get_imgdir().$icon.'" alt="'.$i.'" title="'.__a('My&nbsp;rating', 'wppa_theme').': '.$r[$i].'" onmouseover="wppaFollowMe('.$wppa['master_occur'].', '.$i.')" onmouseout="wppaLeaveMe('.$wppa['master_occur'].', '.$i.')" onclick="wppaRateIt('.$wppa['master_occur'].', '.$i.')" />';
			$i++;
		}
		
		if ( $wppa_opt['wppa_show_avg_rating'] ) $wppa['out'] .= '&nbsp;'.__a('My&nbsp;rating', 'wppa_theme');
	}
	else {
		$wppa['out'] .= __a('You must login to vote', 'wppa_theme');
	}
	
	$wppa['out'] .= wppa_nltab('-').'</div><!-- wppa-rating-'.$wppa['master_occur'].' -->';
}

function wppa_slide_filmstrip($opt = '') {
global $wppa;
global $wppa_opt;
global $thumb;

	$do_it = false;												// Init
	if (is_feed()) $do_it = true;								// feed -> do it to indicate that there is a slideshow
	else {														// Not a feed
		if ($opt != 'optional') $do_it = true;						// not optional -> do it
		else {														// optional
			if ($wppa_opt['wppa_filmstrip']) {							// optional and option on
				if (!$wppa['is_slideonly']) $do_it = true;					// always except slideonly
			}
			if ($wppa['film_on']) $do_it = true;						// explicitly turned on
		}
	}
	if (!$do_it) return;										// Don't do it
	
//	if (isset($_GET['album'])) $alb = $_GET['album'];
//	else $alb = '';	// Album id is in $wppa['start_album']
	$alb = wppa_get_get('album', '');
	
	$thumbs = wppa_get_thumbs($alb);
	if (!$thumbs || count($thumbs) < 1) return;
	
	$preambule = wppa_get_preambule();
		
	$width = ($wppa_opt['wppa_tf_width'] + $wppa_opt['wppa_tn_margin']) * (count($thumbs) + 2 * $preambule);
	$width += $wppa_opt['wppa_tn_margin'] + 2;
	$topmarg = $wppa_opt['wppa_thumbsize'] / 2 - 12 + 7;
	$height = $wppa_opt['wppa_thumbsize']+$wppa_opt['wppa_tn_margin'];
	$height1 = $wppa_opt['wppa_thumbsize'];
	$marg = '20';
	$fs = '24';
	if ($wppa['in_widget']) {
		$width /= 2;
		$topmarg /= 2;
		$height /= 2;
		$height1 /= 2;
		$marg = '10';
		$fs = '12';
	}

	$w = wppa_get_container_width() - ( 2*6 + 2*23 + 2*$wppa_opt['wppa_bwidth']); /* 2*padding + 2*arrow + 2*border */
	if ($wppa['in_widget']) $w = wppa_get_container_width() - ( 2*6 + 2*11 + 2*$wppa_opt['wppa_bwidth']); /* 2*padding + 2*arrow + 2*border */
	$IE6 = 'width: '.$w.'px;';
	
	if (is_feed()) {
		$wppa['out'] .= wppa_nltab().'<div style="'.__wcs('wppa-box').__wcs('wppa-nav').'">';
	} 
	else {

	$wppa['out'] .= wppa_nltab('+').'<div class="wppa-box wppa-nav" style="'.__wcs('wppa-box').__wcs('wppa-nav').'height:'.$height.'px;">';
		$wppa['out'] .= wppa_nltab().'<div style="float:left; text-align:left; cursor:pointer; margin-top:'.$topmarg.'px; width: '.($fs-'1').'px; font-size: '.$fs.'px;"><a class="wppa-prev-'.$wppa['master_occur'].' wppa-arrow" style="'.__wcs('wppa-arrow').'" id="prev-film-arrow-'.$wppa['master_occur'].'" onclick="wppaPrev('.$wppa['master_occur'].');" >&laquo;</a></div>';
		$wppa['out'] .= wppa_nltab().'<div style="float:right; text-align:right; cursor:pointer; margin-top:'.$topmarg.'px; width: '.($fs-'1').'px; font-size: '.$fs.'px;"><a class="wppa-next-'.$wppa['master_occur'].' wppa-arrow" style="'.__wcs('wppa-arrow').'" id="next-film-arrow-'.$wppa['master_occur'].'" onclick="wppaNext('.$wppa['master_occur'].');">&raquo;</a></div>';
		$wppa['out'] .= wppa_nltab().'<div id="filmwindow-'.$wppa['master_occur'].'" class="filmwindow" style="'.$IE6.' display: block; height:'.$height.'px; margin: 0 '.$marg.'px 0 '.$marg.'px; overflow:hidden;">';
			$wppa['out'] .= wppa_nltab('+').'<div id="wppa-filmstrip-'.$wppa['master_occur'].'" style="height:'.$height1.'px; width:'.$width.'px; margin-left: -100px;">';
	}
	
	$cnt = count($thumbs);
	$start = $cnt - $preambule;
	$end = $cnt;
	$idx = $start;
	while ($idx < $end) {
		$glue = $cnt == ($idx + 1) ? true : false;
		$ix = $idx;
		while ($ix < 0) $ix += $cnt;
		$thumb = $thumbs[$ix];
		wppa_do_filmthumb($ix, false, $glue);
		$idx++;
	}
	$idx = 0;
	foreach ($thumbs as $tt) : $thumb = $tt;
		$glue = $cnt == ($idx + 1) ? true : false;
		wppa_do_filmthumb($idx, true, $glue);
		$idx++;
	endforeach;
	$start = '0';
	$end = $preambule;
	$idx = $start;
	while ($idx < $end) {
		$ix = $idx;
		while ($ix >= $cnt) $ix -= $cnt;
		$thumb = $thumbs[$ix];
		wppa_do_filmthumb($ix, false);
		$idx++;
	}
	
	if (is_feed()) {
		$wppa['out'] .= wppa_nltab('-').'</div>';
	}
	else {
			$wppa['out'] .= wppa_nltab('-').'</div>';
		$wppa['out'] .= wppa_nltab('-').'</div>';
	$wppa['out'] .= wppa_nltab('-').'</div>';
	}
}

function wppa_numberbar($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) { 		//don't know if it works with feeds, so switch off
		return;
	}
	
    $do_it = false;
    if($wppa_opt['wppa_show_slideshownumbar'] && !$wppa['is_slideonly']) $do_it = true;
	if ($wppa['numbar_on']) $do_it = true;   
	if(!$do_it){
		return;
	}
	
	// get the data
	$thumbs = wppa_get_thumbs();
	if (!$thumbs || count($thumbs) < 1) return;
	
	// get the sizes
	$size_given = is_numeric($wppa_opt['wppa_fontsize_numbar']);
	if ($size_given) {
		$size = $wppa_opt['wppa_fontsize_numbar'];
		if ($wppa['in_widget']) $size /= 2;
	}
	else {
		$size = $wppa['in_widget'] ? '9' : '12';
	}
	if ($size < '9') $size = '9';
	$size_2 = floor($size / 2);
	$size_4 = floor($size_2 / 2);
	$size_32 = floor($size * 3 / 2);
	
	// make the numbar style
	$style = 'position:absolute; bottom:'.$size.'px; right:0; margin-right:'.$size_2.'px; ';
	
	// start the numbar
	$wppa['out'] .= wppa_nltab('+') . '<div class="wppa-numberbar" style="'.$style.'">';
		$numid = 0;
		
		// make the elementstyles
		$style = 'display:block; float:left; padding:0 '.$size_4.'px; margin-right:'.$size_2.'px; font-weight:'.$wppa_opt['wppa_fontweight_numbar'].'; ';
		if ($wppa_opt['wppa_fontfamily_numbar']) $style .= ' font-family:'.$wppa_opt['wppa_fontfamily_numbar'].';';
		if ($wppa_opt['wppa_fontcolor_numbar']) $style .= ' color:'.$wppa_opt['wppa_fontcolor_numbar'].';';
		if ($size_given) $style .= ' font-size:'.$size.'px; line-height:'.$size_32.'px;';
		
		$style_active = $style;
		if ($wppa_opt['wppa_bgcolor_numbar']) $style .= ' background-color:'.$wppa_opt['wppa_bgcolor_numbar'].';';
		if ($wppa_opt['wppa_bgcolor_numbar_active']) $style_active .= ' background-color:'.$wppa_opt['wppa_bgcolor_numbar_active'].';';
		if ($wppa_opt['wppa_bcolor_numbar']) $style .= ' border:1px solid '.$wppa_opt['wppa_bcolor_numbar'].';';
		if ($wppa_opt['wppa_bcolor_numbar_active']) $style_active .= 'border:1px solid '.$wppa_opt['wppa_bcolor_numbar_active'].';';

		// if the number of photos is larger than a certain number, only the active ph displays a number, other are dots
		$count = count($thumbs);
		$high = $wppa_opt['wppa_numbar_max'];
		
		// do the numbers
		foreach ($thumbs as $tt) :
			$title = sprintf(__a('Photo %s of %s', 'wppa_theme'), $numid + '1', $count);
			$wppa['out'] .= wppa_nltab('+') . '<a href="javascript://" id="wppa-numbar-'.$wppa['master_occur'].'-'.$numid.'" title="'.$title.'" ' . ($numid == 0 ? ' class="wppa-numbar-current" ' : '') . ' style="' . ($numid == 0 ? $style_active : $style) . '" onclick="wppaGotoKeepState('.$wppa['master_occur'].',' . $numid . ');return false;">';
			$wppa['out'] .= $count > $high ? wppa_nltab() . '.' : wppa_nltab() . $numid + 1;
			$wppa['out'] .= wppa_nltab('-') . '</a>';
			$numid++;
		endforeach;
	$wppa['out'] .= wppa_nltab('-') . '</div>';                        
}

function wppa_browsebar($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
//		wppa_dummy_bar(__('- - - Browse navigation bar - - -', 'wppa_theme'));
		return;
	}
	$do_it = false;
	if ($opt != 'optional') $do_it = true;
	if (!$wppa['is_slideonly'] && $wppa_opt['wppa_show_browse_navigation']) $do_it = true;
	if ($wppa['is_slideonly'] && $wppa['browse_on']) $do_it = true;

	if ($do_it) {
		$wppa['out'] .= wppa_nltab('+').'<div id="prevnext2-'.$wppa['master_occur'].'" class="wppa-box wppa-nav wppa-nav-text" style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-nav').__wcs('wppa-nav-text').'">';
			$wppa['out'] .= wppa_nltab().'<span id="p-a-'.$wppa['master_occur'].'" class="wppa-prev-'.$wppa['master_occur'].' wppa-nav-text wppa-arrow" style="float:left; text-align:left; '.__wcs('wppa-nav-text').__wcs('wppa-arrow').'">&laquo;&nbsp;</span>';
			$wppa['out'] .= wppa_nltab().'<a id="prev-arrow-'.$wppa['master_occur'].'" class="wppa-prev-'.$wppa['master_occur'].' wppa-nav-text arrow-'.$wppa['master_occur'].'" style="float:left; text-align:left; cursor:pointer; '.__wcs('wppa-nav-text').'" onclick="wppaPrev('.$wppa['master_occur'].')" ></a>';
			$wppa['out'] .= wppa_nltab().'<span id="n-a-'.$wppa['master_occur'].'" class="wppa-next-'.$wppa['master_occur'].' wppa-nav-text wppa-arrow" style="float:right; text-align:right; '.__wcs('wppa-nav-text').__wcs('wppa-arrow').'">&nbsp;&raquo;</span>';
			$wppa['out'] .= wppa_nltab().'<a id="next-arrow-'.$wppa['master_occur'].'" class="wppa-next-'.$wppa['master_occur'].' wppa-nav-text arrow-'.$wppa['master_occur'].'" style="float:right; text-align:right; cursor:pointer; '.__wcs('wppa-nav-text').'" onclick="wppaNext('.$wppa['master_occur'].')"></a>';
			$wppa['out'] .= wppa_nltab().'<span id="counter-'.$wppa['master_occur'].'" class="wppa-nav-text wppa-black" style="text-align:center; '.__wcs('wppa-nav-text').'; cursor:pointer;" onclick="wppaStartStop('.$wppa['master_occur'].', -1);" title="'.__a('Click to start/stop', 'wppa').'"></span>';
		$wppa['out'] .= wppa_nltab('-').'</div><!-- #prevnext2 -->';
	}
}


// Comments box
function wppa_comments($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
		if ( $wppa_opt['wppa_show_comments'] ) wppa_dummy_bar(__a('- - - Comments box activated - - -', 'wppa_theme'));
		return;
	}
	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( !$wppa['is_slideonly'] && $wppa_opt['wppa_show_comments'] && !$wppa['in_widget'] ) $do_it = true;

	if ($do_it) {
		$wppa['out'] .= wppa_nltab('+').'<div id="comments-'.$wppa['master_occur'].'" class="wppa-box wppa-comments " style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-comments').'">';

		$wppa['out'] .= wppa_nltab('-').'</div><!-- #comments -->';
	}

}

// The IPTC box
function wppa_iptc($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
		if ( $wppa_opt['wppa_show_iptc'] ) wppa_dummy_bar(__a('- - - IPTC box activated - - -', 'wppa_theme'));
		return;
	}
	$do_it = false;
	if ($opt != 'optional') $do_it = true;
	if (!$wppa['is_slideonly'] && $wppa_opt['wppa_show_iptc']) $do_it = true;

	if ($do_it) {
		$wppa['out'] .= wppa_nltab('+').'<div id="iptc-'.$wppa['master_occur'].'" class="wppa-box wppa-iptc " style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-iptc').'">';

		$wppa['out'] .= wppa_nltab('-').'</div><!-- #iptc -->';
	}

}

// The EXIF box
function wppa_exif($opt = '') {
global $wppa;
global $wppa_opt;

	if (is_feed()) {
		if ( $wppa_opt['wppa_show_exif'] ) wppa_dummy_bar(__a('- - - EXIF box activated - - -', 'wppa_theme'));
		return;
	}
	$do_it = false;
	if ($opt != 'optional') $do_it = true;
	if (!$wppa['is_slideonly'] && $wppa_opt['wppa_show_exif']) $do_it = true;

	if ($do_it) {
		$wppa['out'] .= wppa_nltab('+').'<div id="exif-'.$wppa['master_occur'].'" class="wppa-box wppa-exif " style="text-align: center; '.__wcs('wppa-box').__wcs('wppa-exif').'">';

		$wppa['out'] .= wppa_nltab('-').'</div><!-- #exif -->';
	}

}