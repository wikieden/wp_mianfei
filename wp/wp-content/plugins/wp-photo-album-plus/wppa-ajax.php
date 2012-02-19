<?php
/* wppa-ajax.php
*
* Functions used in ajax requests
* version 4.3.5
*
*/
add_action('wp_ajax_wppa', 'wppa_ajax_callback');
add_action('wp_ajax_nopriv_wppa', 'wppa_ajax_callback');

function wppa_ajax_callback() {
global $wpdb;
global $wppa_opt;
global $wppa;

	$wppa['ajax']  = true;
	$wppa['error'] = '0';
	$wppa['out']   = '';

	// ALTHOUGH WE ARE HERE AS FRONT END VISITOR, is_admin() is true. 
	// So, $wppa_opt switches are 'yes' or 'no' and not true or false.
	
	$wppa_action = $_REQUEST['wppa-action'];
	
	switch ($wppa_action) {
		case 'rate':
			// Get commandline args
			$photo  = $_REQUEST['wppa-rating-id'];
			$rating = $_REQUEST['wppa-rating'];
			$occur  = $_REQUEST['wppa-occur'];
			$index  = $_REQUEST['wppa-index'];
			$nonce  = $_REQUEST['wppa-nonce'];
			
			// Make errortext
			$errtxt = __('An error occurred while processing you rating request.', 'wppa');
			$errtxt .= "\n".__('You may refresh the page and try again.', 'wppa');
			$wartxt = __('Althoug an error occurred while processing your rating, your vote has been registered.', 'wppa');
			$wartxt .= "\n".__('However, this may not be reflected in the current pageview', 'wppa');
			
			// Check on validity
			if ( ! wp_verify_nonce($nonce, 'wppa-check') ) {
				echo '0||100||'.$errtxt;
				exit;																// Nonce check failed
			}
			if ( ! in_array($rating, array('1', '2', '3', '4', '5')) ) {
				echo '0||101||'.$errtxt;
				exit;																// Value out of range
			}
			
			// Get other data
			$user     = wppa_get_user();
			$mylast   = $wpdb->get_var($wpdb->prepare( 'SELECT * FROM `'.WPPA_RATING.'` WHERE `photo` = %s AND `user` = %s ORDER BY `id` DESC LIMIT 1', $photo, $user ) ); 
			$myavgrat = '0';														// Init
			
			// Case 0: Illegal second vote
			if ( $mylast && $wppa_opt['wppa_rating_change'] == 'no' && $wppa_opt['wppa_rating_multi'] == 'no' ) {
				echo '0||109||'.__('Illegal attempt to enter a second vote.', 'wppa');
				exit;
			}
			// Case 1: This is my first vote for this photo
			if ( ! $mylast ) {
				$key = wppa_nextkey(WPPA_RATING);
				$iret = $wpdb->query($wpdb->prepare('INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (%s, %s, %s, %s)', $key, $photo, $rating, $user));
				if ( $iret === false ) {
					echo '0||102||'.$errtxt;
					exit;															// Fail on storing vote
				}
				$myavgrat = $rating;
			}
			// Case 2: I will change my previously given vote
			elseif ( $wppa_opt['wppa_rating_change'] == 'yes' ) {					// Votechanging is allowed
				$query = $wpdb->prepare( 'UPDATE `'.WPPA_RATING.'` SET `value` = %s WHERE `photo` = %s AND `user` = %s LIMIT 1', $rating, $photo, $user );
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||103||'.$errtxt;
					exit;															// Fail on update
				}
				$myavgrat = $rating;
			}
			// Case 3: Add another vote from me
			elseif ( $wppa_opt['wppa_rating_multi'] == 'yes' ) {					// Rating multi is allowed
				$key = wppa_nextkey(WPPA_RATING);
				$query = $wpdb->prepare( 'INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (%s, %s, %s, %s)', $key, $photo, $rating, $user );
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||104||'.$errtxt;
					exit;															// Fail on storing vote
				}
				// Compute my avg rating
				$query = $wpdb->prepare( 'SELECT * FROM `'.WPPA_RATING.'`  WHERE `photo` = %s AND `user` = %s', $photo, $user );
				$myrats = $wpdb->get_results($query, 'ARRAY_A');
				if ( ! $myrats) {
					echo '0||105||'.$wartxt;
					exit;															// Fail on retrieve
				}
				$sum = 0;
				$cnt = 0;
				foreach ($myrats as $rt) {
					$sum += $rt['value'];
					$cnt ++;
				}
				if ($cnt > 0) $myavgrat = $sum/$cnt; else $myavgrat = '0';
			}
			else { 																	// Should never get here....
				echo '0||110||'.__('Unexpected error', 'wppa');
				exit;
			}

			// Find Olld avgrat
			$oldavgrat = $wpdb->get_var($wpdb->prepare('SELECT `mean_rating` FROM '.WPPA_PHOTOS.' WHERE `id` = %s', $photo));
			if ($oldavgrat === false) {
				echo '0||108||'.$wartxt;
				exit;																// Fail on read old avgrat
			}
			// Compute new allavgrat
			$ratings = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.WPPA_RATING.' WHERE `photo` = %s', $photo), 'ARRAY_A');
			if ($ratings) {
				$sum = 0;
				$cnt = 0;
				foreach ($ratings as $rt) {
					$sum += $rt['value'];
					$cnt ++;
				}
				if ($cnt > 0) $allavgrat = $sum/$cnt; else $allavgrat = '0';
			}
			else $allavgrat = '0';

			// Store it in the photo info if it has been changed
			if ( $oldavgrat != $allavgrat ) {
				$query = $wpdb->prepare('UPDATE `'.WPPA_PHOTOS. '` SET `mean_rating` = %s WHERE `id` = %s', $allavgrat, $photo);
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||106||'.$wartxt;
					exit;																// Fail on save
				}
			}

			echo $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.$allavgrat;
			break;
		
		case 'render':	
			// Correct the fact that this is a non-admin operation
			require_once 'wppa-non-admin.php';
			wppa_load_theme();
			foreach(array_keys($wppa_opt) as $s) {
				if ( $wppa_opt[$s] == 'no' ) $wppa_opt[$s] = false;
			}
			$wppa['ajax'] = true;
			// Do the dirty stuff
			echo wppa_albums();
			break;
			
		case 'delete-photo':
			$photo = $_REQUEST['photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '0||'.__('You do not have the rights to delete a photo', 'wppa').$nonce;
				exit;																// Nonce check failed
			}
			// Get file extension
			$ext = $wpdb->get_var($wpdb->prepare('SELECT `ext` FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s', $photo)); 
			// Delete fullsize image
			$file = ABSPATH.'wp-content/uploads/wppa/'.$photo.'.'.$ext;
			if (file_exists($file)) unlink($file);
			// Delete thumbnail image
			$file = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$photo.'.'.$ext;
			if (file_exists($file)) unlink($file);
			// Delete db entries
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s LIMIT 1', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_COMMENTS.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_IPTC.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_EXIF.'` WHERE `photo` = %s', $photo));
			echo '1||<span style="color:red" >'.sprintf(__('Photo %s has been deleted', 'wppa'), $photo).'</span>';
			break;

		case 'update-album':
			$album = $_REQUEST['album-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			$item  = $_REQUEST['item'];
			$value = $_REQUEST['value'];
			$value  = wppa_decode($value);
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$album) ) {
				echo '0||'.__('You do not have the rights to update album information', 'wppa').$nonce;
				exit;																// Nonce check failed
			}

			switch ($item) {
				case 'clear_ratings':
					$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $album), 'ARRAY_A');
					if ($photos) foreach ($photos as $photo) {
						$iret1 = $wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $photo['id']));
						$iret2 = $wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `mean_rating` = %s WHERE `id` = %s', '', $photo['id']));
					}
					if ($photos !== false && $iret1 !== false && $iret2 !== false) {
						echo '97||'.__('<b>Ratings cleared</b>', 'wppa').'||'.__('No ratings for this photo.', 'wppa');
					}
					else {
						echo '1||'.__('An error occurred while clearing ratings', 'wppa');
					}
					exit;
					break;
				case 'name':
					$itemname = __('Name', 'wppa');
					break;
				case 'description':
					$itemname = __('Description', 'wppa');
					break;
				case 'a_order':
					$itemname = __('Album order #', 'wppa');
					break;
				case 'main_photo':
					$itemname = __('Cover photo', 'wppa');
					break;
				case 'a_parent':
					$itemname = __('Parent album', 'wppa');
					break;
				case 'p_order_by':
					$itemname = __('Photo order', 'wppa');
					break;
				case 'cover_linktype':
					$itemname = __('Link type', 'wppa');
					break;
				case 'cover_linkpage':
					$itemname = __('Link to', 'wppa');
					break;
				case 'owner':
					$itemname = __('Owner', 'wppa');
					break;
				default:
					$itemname = $item;
			}
			
			$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_ALBUMS.' SET `'.$item.'` = %s WHERE `id` = %s', $value, $album));
			if ($iret !== false ) {
				echo '0||'.sprintf(__('<b>%s</b> of album %s updated', 'wppa'), $itemname, $album);
			}
			else {
				echo '2||'.sprintf(__('An error occurred while trying to update <b>%s</b> of album %s', 'wppa'), $itemname, $album);
				echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
			}
			exit;
			break;
			
		case 'update-photo':
			$photo = $_REQUEST['photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			$item  = $_REQUEST['item'];
			$value = $_REQUEST['value'];
			$value  = wppa_decode($value);
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '0||'.__('You do not have the rights to update photo information', 'wppa').$nonce;
				exit;																// Nonce check failed
			}
			
			switch ($item) {
				case 'rotright':
				case 'rotleft':
					$angle = $item == 'rotleft' ? '90' : '270';
					$wppa['error'] = wppa_rotate($photo, $angle);
					$leftorright = $item == 'rotleft' ? __('left', 'wppa') : __('right', 'wppa');
					if ( ! $wppa['error'] ) {
						echo '0||'.sprintf(__('Photo %s rotated %s', 'wppa'), $photo, $leftorright);
					}
					else {
						echo $wppa['error'].'||'.sprintf(__('An error occurred while trying to rotate photo %s', 'wppa'), $photo);
					}
					exit;
					break;
					
				case 'moveto':
					$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_PHOTOS.' SET `album` = %s WHERE `id` = %s', $value, $photo));
					if ($iret !== false ) {
						echo '99||'.sprintf(__('Photo %s has been moved to album %s (%s)', 'wppa'), $photo, wppa_qtrans(wppa_get_album_name($value)), $value);
					}
					else {
						echo '3||'.sprintf(__('An error occurred while trying to move photo %s', 'wppa'), $photo);
					}
					exit;
					break;
					
				case 'copyto':
					$wppa['error'] = wppa_copy_photo($photo, $value);
					if ( ! $wppa['error'] ) {
						echo '0||'.sprintf(__('Photo %s copied to album %s (%s)', 'wppa'), $photo, wppa_qtrans(wppa_get_album_name($value)), $value);
					}
					else {
						echo '4||'.sprintf(__('An error occurred while trying to copy photo %s', 'wppa'), $photo);
						echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
					}
					break;
					
				case 'name':
				case 'description':
				case 'p_order':
				case 'owner':
				case 'linkurl':
				case 'linktitle':
					switch ($item) {
						case 'name':
							$itemname = __('Name', 'wppa');
							break;
						case 'description':
							$itemname = __('Description', 'wppa');
							break;
						case 'p_order':
							$itemname = __('Photo order #', 'wppa');
							break;
						case 'owner':
							$itemname = __('Owner', 'wppa');
							break;
						case 'linkurl':
							$itemname = __('Link url', 'wppa');
							break;
						case 'linktitle':
							$itemname = __('Link title', 'wppa');
							break;
						default:
							$itemname = $item;
					}
					$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_PHOTOS.' SET `'.$item.'` = %s WHERE `id` = %s', $value, $photo));
					if ($iret !== false ) {
						echo '0||'.sprintf(__('<b>%s</b> of photo %s updated', 'wppa'), $itemname, $photo);
					}
					else {
						echo '2||'.sprintf(__('An error occurred while trying to update <b>%s</b> of photo %s', 'wppa'), $itemname, $photo);
						echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
					}
					exit;
					break;

					
				default:
					echo '98||This update action is not implemented yet('.$item.')';
					exit;
			}
			break;
			
		// The wppa-settings page calls ajax with $wppa_action == 'update-option';
		case 'update-option':
			// Verify that we are legally here
			$nonce  = $_REQUEST['wppa-nonce'];
			if ( ! wp_verify_nonce($nonce, 'wppa-nonce') ) {
				echo '1||'.__('You do not have the rights to update settings', 'wppa');
				exit;																// Nonce check failed
			}
			
			// Initialize
			$old_minisize = wppa_get_minisize();		// Remember for later, maybe we do something that requires regen
			$option = $_REQUEST['wppa-option'];			// The option to be processed
			$value  = isset($_REQUEST['value']) ? wppa_decode($_REQUEST['value']) : '';	// The new value, may also contain & # and +
			$value  = stripslashes($value);
			$alert  = '';			// Init the return string data
			$wppa['error']  = '0';	//
			$title  = '';			//
			
			$option = wppa_decode($option);
			// Dispatch on option
			if ( substr($option, 0, 16) == 'wppa_iptc_label_' ) {
				$tag = substr($option, 16);
				$q = $wpdb->prepare("UPDATE `".WPPA_IPTC."` SET `description`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '1||Failed to update '.$tag.'||';
				}
				echo $output;
				exit;
			}
			elseif ( substr($option, 0, 17) == 'wppa_iptc_status_' ) {
				$tag = substr($option, 17);
				$q = $wpdb->prepare("UPDATE `".WPPA_IPTC."` SET `status`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '1||Failed to update '.$tag.'||';
				}
				echo $output;			
				exit;
			}
			elseif ( substr($option, 0, 16) == 'wppa_exif_label_' ) {
				$tag = substr($option, 16);
				$q = $wpdb->prepare("UPDATE `".WPPA_EXIF."` SET `description`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '1||Failed to update '.$tag.'||';
				}
				echo $output;
				exit;
			}
			elseif ( substr($option, 0, 17) == 'wppa_exif_status_' ) {
				$tag = substr($option, 17);
				$q = $wpdb->prepare("UPDATE `".WPPA_EXIF."` SET `status`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '1||Failed to update '.$tag.'||';
				}
				echo $output;			
				exit;
			}
			else switch ($option) {
					
				case 'wppa_colwidth': //	 ??	  fixed   low	high	title
					wppa_ajax_check_range($value, 'auto', '100', false, __('Column width.', 'wppa'));
					break;
				case 'wppa_fullsize':
					wppa_ajax_check_range($value, false, '100', false, __('Full size.', 'wppa'));
					break;
				case 'wppa_maxheight':
					wppa_ajax_check_range($value, false, '100', false, __('Max height.', 'wppa'));
					break;
				case 'wppa_thumbsize':
					wppa_ajax_check_range($value, false, '50', false, __('Thumbnail size.', 'wppa'));
					break;
				case 'wppa_tf_width':
					wppa_ajax_check_range($value, false, '50', false, __('Thumbnail frame width', 'wppa'));
					break;
				case 'wppa_tf_height':
					wppa_ajax_check_range($value, false, '50',false,  __('Thumbnail frame height', 'wppa'));
					break;
				case 'wppa_tn_margin':
					wppa_ajax_check_range($value, false, '0', false, __('Thumbnail Spacing', 'wppa'));
					break;
				case 'wppa_min_thumbs':
					wppa_ajax_check_range($value, false, '0', false, __('Photocount treshold.', 'wppa'));
					break;
				case 'wppa_thumb_page_size':
					wppa_ajax_check_range($value, false, '0', false, __('Thumb page size.', 'wppa'));
					break;
				case 'wppa_smallsize':
					wppa_ajax_check_range($value, false, '50', false, __('Cover photo size.', 'wppa'));
					break;
				case 'wppa_album_page_size':
					wppa_ajax_check_range($value, false, '0', false, __('Album page size.', 'wppa'));
					break;
				case 'wppa_topten_count':
					wppa_ajax_check_range($value, false, '2', false, __('Number of TopTen photos', 'wppa'), '40');
					break;
				case 'wppa_topten_size':
					wppa_ajax_check_range($value, false, '32', false, __('Widget image thumbnail size', 'wppa'), wppa_get_minisize());
					break;
				case 'wppa_max_cover_width':
					wppa_ajax_check_range($value, false, '150', false, __('Max Cover width', 'wppa'));
					break;
				case 'wppa_text_frame_height':
					wppa_ajax_check_range($value, false, '0', false, __('Minimal Cover text frame height', 'wppa'));
					break;
				case 'wppa_bwidth':
					wppa_ajax_check_range($value, '', '0', false, __('Border width', 'wppa'));
					break;
				case 'wppa_bradius':
					wppa_ajax_check_range($value, '', '0', false, __('Border radius', 'wppa'));
					break;
				case 'wppa_popupsize':				
					$floor = get_option('wppa_thumbsize');
					$temp = get_option('wppa_smallsize');
					if ($temp > $floor) $floor = $temp;
					wppa_ajax_check_range($value, false, $floor, get_option('wppa_fullsize'), __('Popup size', 'wppa'));
					break;
				case 'wppa_fullimage_border_width':
					wppa_ajax_check_range($value, '', '0', false, __('Fullsize border width', 'wppa'));
					break;
				case 'wppa_lightbox_bordersize':
					wppa_ajax_check_range($value, false, '0', false, __('Lightbox Bordersize', 'wppa'));
					break;
				case 'wppa_comment_count':
					wppa_ajax_check_range($value, false, '2', '40', __('Number of Comment widget entries', 'wppa'));
					break;
				case 'wppa_comment_size':
					wppa_ajax_check_range($value, false, '32', wppa_get_minisize(), __('Comment Widget image thumbnail size', 'wppa'), wppa_get_minisize());
					break;
				case 'wppa_rerate':
					if ( wppa_recalculate_ratings() ) $title = __('Ratings recalculated', 'wppa');
					break;
				case 'wppa_lightbox_overlayopacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Lightbox opacity.', 'wppa'));
					break;
				case 'wppa_thumb_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Opacity.', 'wppa'));
					break;
				case 'wppa_cover_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Opacity.', 'wppa'));
					break;
				case 'wppa_star_opacity':
					wppa_ajax_check_range($value, false, '0', '50', __('Opacity.', 'wppa'));
					break;
				case 'wppa_filter_priority':
					wppa_ajax_check_range($value, false, '10', false, __('Filter priority', 'wppa'));
					break;
				case 'wppa_gravatar_size':
					wppa_ajax_check_range($value, false, '10', '256', __('Avatar size', 'wppa'));
					break;

		
			
				case 'wppa_chmod':
					if ( $value ) {						// CHMOD value given
						wppa_chmod($value);
						$wppa['error'] = $wppa['error'];
						if ( $wppa['error'] ) {
							$title = __('CHMOD failed', 'wppa');
							$alert = $wppa['out'];
						}
						else $title = $wppa['out'];
					}
					else {								// Zero value
						$title = __('CHMOD unchanged', 'wppa');
					}
					break;
					
				case 'wppa_charset':
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_ALBUMS . " MODIFY name text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_PHOTOS . " MODIFY name text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_ALBUMS . " MODIFY description text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_PHOTOS . " MODIFY description longtext CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_PHOTOS . " MODIFY linktitle text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_COMMENTS . " MODIFY comment text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_IPTC . " MODIFY description text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wpdb->query($wpdb->prepare( "ALTER TABLE " . WPPA_EXIF . " MODIFY description text CHARACTER SET utf8")) === false) $wppa['error'] = true;
					if ($wppa['error']) {
						$alert = __('Error converting to UTF_8', 'wppa');
					}
					else {
						$title = __('Conversion to UTF_8 completed', 'wppa');
					}
					break;

					
				case 'wppa_accesslevel':
				case 'wppa_accesslevel_upload':
				case 'wppa_accesslevel_sidebar':
					if (get_option('wppa_set_access_by', 'me') == 'me') {
						update_option($option, $value);	// set_caps needs to know
						wppa_set_caps();
						$title = __('Rights set', 'wppa');
					}
					else {
						$alert = __('Changes in accesslevels will not be made. It is set to be done by an other program.', 'wppa');
						$wppa['error'] = '1';
					}
					break;
					
				case 'wppa_rating_clear':
					$iret1 = $wpdb->query($wpdb->prepare( 'DELETE FROM '.WPPA_RATING.' WHERE id > -1' ) );
					$iret2 = $wpdb->query($wpdb->prepare( 'UPDATE '.WPPA_PHOTOS.' SET mean_rating="0" WHERE id > -1' ) );
					if ($iret1 !== false && $iret2 !== false) {
						delete_option('wppa_'.WPPA_RATING.'_lastkey');
						$title = __('Ratings cleared', 'wppa');
					}
					else {
						$title = __('Could not clear ratings', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;

				case 'wppa_iptc_clear':
					$iret = $wpdb->query($wpdb->prepare( 'TRUNCATE TABLE '.WPPA_IPTC ) );
					if ($iret !== false) {
						delete_option('wppa_'.WPPA_IPTC.'_lastkey');
						$title = __('IPTC data cleared', 'wppa');
						$alert = __('Refresh this page to clear table X', 'wppa');
					}
					else {
						$title = __('Could not clear IPTC data', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;

				case 'wppa_exif_clear':
					$iret = $wpdb->query($wpdb->prepare( 'TRUNCATE TABLE '.WPPA_EXIF ) );
					if ($iret !== false) {
						delete_option('wppa_'.WPPA_EXIF.'_lastkey');
						$title = __('EXIF data cleared', 'wppa');
						$alert = __('Refresh this page to clear table XI', 'wppa');
					}
					else {
						$title = __('Could not clear EXIF data', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;
					
				case 'wppa_recup':
					$result = wppa_recuperate_iptc_exif();
					echo '0||'.__('Recuperation performed', 'wppa').'||'.$result;
					exit;
					break;

				case 'wppa_regen':
				case 'wppa_thumb_aspect':
					if ( get_option('wppa_lastthumb', '-2') == '-2' ) {
						update_option('wppa_lastthumb', '-1');	// Trigger regen if not doing already
						$old_minisize--;
					}				
					break;

				
				
				default:
					// Do the update only
					update_option($option, $value);
					$wppa['error'] = '0';
					$alert = '';
			}
			if ( $wppa['error'] ) {
				if ( ! $title ) $title = sprintf(__('Failed to set %s to %s', 'wppa'), $option, $value);
				if ( ! $alert ) $alert .= $wppa['out'];
			}
			else {
				update_option($option, $value);
				if ( ! $title ) $title = sprintf(__('Setting %s updated to %s', 'wppa'), $option, $value);
			}
			
			// Did we do something that will require regen?
			$new_minisize = wppa_get_minisize();
			if ( $old_minisize != $new_minisize ) {
				update_option('wppa_lastthumb', '-1');	// Trigger regen
				$alert .= __('You just changed a setting that requires the regeneration of thumbnails.', 'wppa');
				$alert .= ' '.__('This process will start as soon as you refresh or re-enter the settings page.', 'wppa');
			}
			
			// Produce the response text
			$output = $wppa['error'].'||'.esc_attr($title).'||'.esc_js($alert);
			
			echo $output;
			exit;
			break;	// End update-option
			
		default:	// Unimplemented $wppa-action
		die('-1');
	}
	exit;
}

function wppa_decode($string) {
	$arr = explode('||HASH||', $string);
	$result = implode('#', $arr);
	$arr = explode('||AMP||', $result);
	$result = implode('&', $arr);
	$arr = explode('||PLUS||', $result);
	$result = implode('+', $arr);
	
	return $result;
}

function wppa_ajax_check_range($value, $fixed, $low, $high, $title) {
global $wppa;
	if ( $fixed !== false && $fixed == $value ) return;	// Ok
	if ( $low !== false && $value < $low ) {
		$wppa['error'] = true;
		$wppa['out'] .= __('Please supply a numeric value greater than or equal to', 'wppa') . ' ' . $low . ' ' . __('for', 'wppa') . ' ' . $title;
		if ( $fixed !== false ) {
			if ( $fixed ) $wppa['out'] .= '. ' . __('You may also enter:', 'wppa') . ' ' . $fixed;
			else $wppa['out'] .= '. ' . __('You may also leave/set this blank', 'wppa');
		}
	}
	if ( $high !== false && $value > $high ) {
		$wppa['error'] = true;
		$wppa['out'] .= __('Please supply a numeric value greater than or equal to', 'wppa') . ' ' . $low . ' ' . __('and less than or equal to', 'wppa') . ' ' . $high . ' ' . __('for', 'wppa') . ' ' . $title;
		if ( $fixed !== false ) {
			if ( $fixed ) $wppa['out'] .= '. ' . __('You may also enter:', 'wppa') . ' ' . $fixed;
			else $wppa['out'] .= '. ' . __('You may also leave/set this blank', 'wppa');
		}
	}
}
