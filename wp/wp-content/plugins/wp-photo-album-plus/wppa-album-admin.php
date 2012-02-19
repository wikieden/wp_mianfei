<?php
/* wppa-albumadmin.php
* Package: wp-photo-album-plus
*
* create, edit and delete albums
* version 4.3.6
*
*/

function _wppa_admin() {
	global $wpdb;
	global $q_config;
	
	$sel = 'selected="selected"';

	// warn if the uploads directory is no writable
	if (!is_writable(WPPA_UPLOAD_PATH)) { 
		wppa_error_message(__('Warning:', 'wppa') . sprintf(__('The uploads directory does not exist or is not writable by the server. Please make sure that %s is writeable by the server.', 'wppa'), WPPA_UPLOAD_PATH));
	}

	if (isset($_GET['tab'])) {		
		// album edit page
		if ($_GET['tab'] == 'edit'){
			if ($_GET['edit_id'] == 'new') {
				$name = __('New Album', 'wppa');
				$id = wppa_nextkey(WPPA_ALBUMS);
				$query = $wpdb->prepare('INSERT INTO `' . WPPA_ALBUMS . '` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linktype`, `cover_linkpage`, `owner`, `timestamp`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $id, $name, '', '0', '0', '0', '0', 'content', '0', wppa_get_user(), time());
				$iret = $wpdb->query($query);
				if ($iret === FALSE) {
					wppa_error_message(__('Could not create album.', 'wppa').'<br/>Query = '.$query);
					wp_die('Sorry, cannot continue');
				}
				else {
					$edit_id = $id;
					wppa_set_last_album($edit_id);
					wppa_update_message(__('Album #', 'wppa') . ' ' . $edit_id . ' ' . __('Added.', 'wppa'));
				}
			}
			else {
				$edit_id = $_GET['edit_id'];
			}
		
			if (!wppa_have_access($edit_id)) wp_die('You do not have the rights to edit this album.');
		
			// updates the details
			if (isset($_POST['wppa-ea-submit'])) {
				wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
				wppa_edit_album();
			}
			
			// deletes the image
			if (isset($_GET['photo_del'])) {
				if ( ! wp_verify_nonce($_GET['wppa_nonce'], 'wppa_nonce') ) wp_die('Illegal attemp to delete a photo');

				$message = __('Photo Deleted.', 'wppa');
				
				$ext = $wpdb->get_var($wpdb->prepare('SELECT `ext` FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s', $_GET['photo_del'])); 
				
				$file = ABSPATH.'wp-content/uploads/wppa/'.$_GET['photo_del'].'.'.$ext;
				if (file_exists($file)) {
					unlink($file);
				}
				else {
					$message .= ' '.__('Fullsize image did not exist.', 'wppa');
				}
				
				$file = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$_GET['photo_del'].'.'.$ext;
				if (file_exists($file)) {
					unlink($file);
				}
				else {
					$message .= ' '.__('Thumbnail image did not exist.', 'wppa');
				}
				
				$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s LIMIT 1', $_GET['photo_del']));
				$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $_GET['photo_del']));
				$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_COMMENTS.'` WHERE `photo` = %s', $_GET['photo_del']));
				$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_IPTC.'` WHERE `photo` = %s', $_GET['photo_del']));
				$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_EXIF.'` WHERE `photo` = %s', $_GET['photo_del']));

				wppa_update_message($message, 'fixed');
			}
			
			// copies the image
			if (isset($_GET['photo_copy']) && isset($_GET['album_to'])) {
				if ( ! wp_verify_nonce($_GET['wppa_nonce'], 'wppa_nonce') ) wp_die('Illegal attemp to copy a photo');

				$err = wppa_copy_photo($_GET['photo_copy'], $_GET['album_to']);
				if (!$err) {
					wppa_update_message(__('Photo copied', 'wppa'), 'fixed');
				}
				else {
					wppa_error_message(__('Unable to copy photo, error:', 'wppa').' '.$err, 'fixed');
				}
			}
			
			// rotates the image
			if (isset($_GET['rotate'])) {
				if ( ! wp_verify_nonce($_GET['wppa_nonce'], 'wppa_nonce') ) wp_die('Illegal attemp to rotate a photo');

				if (isset($_GET['photo_rotate']) && isset($_GET['photo_angle'])) {
					$err = wppa_rotate($_GET['photo_rotate'], $_GET['photo_angle']);
					if (!$err) {
						wppa_update_message(__('Photo rotated', 'wppa'), 'fixed');
						clearstatcache();
					}
					else {
						wppa_error_message(__('Unable to rotate photo, error:', 'wppa').' '.$err);
					}
				}
				else {
					wppa_error_message(__('Internal fatal error while getting rotation data', 'wppa'));
				}
			}
			
			// Get the album information
			$albuminfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM `'.WPPA_ALBUMS.'` WHERE `id` = %s', $edit_id), 'ARRAY_A'); ?>	
			
			<div class="wrap">
				<h2><?php _e('Edit Album Information', 'wppa'); ?></h2>
				<p><?php _e('Album number:', 'wppa'); echo(' ' . $edit_id . '.'); ?></p>
				<form action="<?php echo wppa_ea_url($edit_id) ?>" method="post">
					<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE) ?>
					<table class="form-table albumtable">
						<tbody>
							<?php if (!wppa_qtrans_enabled()) { ?>
							<tr valign="top">
								<th scope="row">
									<label ><?php _e('Name:', 'wppa'); ?></label>
								</th>
								<td>
									<input type="text" name="wppa-name" id="wppa-name" style="width: 50%;" value="<?php echo(stripslashes($albuminfo['name'])) ?>" />
									<span class="description"><br/><?php _e('Type the name of the album. Do not leave this empty.', 'wppa'); ?></span>
								</td>
							</tr>
							<?php }
							else { 
								$first = true;
								$last = count($q_config['enabled_languages']) - 1;
								$idx = 0;
								foreach ($q_config['enabled_languages'] as $lcode) {
									$lname = $q_config['language_name'][$lcode]; ?>
									<tr valign="top">
										<th scope="row">
											<label ><?php if ($first) _e('Name:', 'wppa'); $first = false; ?></label>
										</th>
										<td>
											<b><?php echo($lname) ?></b><br/>
											<input type="text" name="wppa-name-<?php echo($lcode) ?>" id="wppa-name-<?php echo($lcode) ?>" style="width: 50%;" value="<?php echo(wppa_qtrans(stripslashes($albuminfo['name']), $lcode)) ?>" />
											<?php if ($idx == $last) { ?>
												<span class="description"><br/><?php _e('Type the name of the album. Do not leave this empty.', 'wppa'); ?></span>
											<?php } ?>
										</td>
									</tr>
									<?php $idx++; 
								} 
							} ?>

							<?php if (!wppa_qtrans_enabled()) { ?>
							<tr valign="top">
								<th>
									<label ><?php _e('Description:', 'wppa'); ?></label>
								</th>
								<td>
									<textarea style="width: 80%; height: 80px;" name="wppa-desc" id="wppa-desc"><?php echo(stripslashes($albuminfo['description'])) ?></textarea>
									<span class="description"><br/><?php _e('Enter / modify the description for this album.', 'wppa'); ?></span>
								</td>
							</tr>
							<?php } 
							else { 
								$first = true;
								$last = count($q_config['enabled_languages']) - 1;
								$idx = 0;
								foreach ($q_config['enabled_languages'] as $lcode) {
									$lname = $q_config['language_name'][$lcode]; ?>
									<tr valign="top">
										<th>
											<label ><?php if ($first) _e('Description:', 'wppa'); $first = false; ?></label>
										</th>
										<td>
											<b><?php echo($lname) ?></b><br/>
											<textarea style="width: 80%; height: 80px;" name="wppa-desc-<?php echo($lcode) ?>" id="wppa-desc-<?php echo($lcode) ?>"><?php echo(wppa_qtrans(stripslashes($albuminfo['description']), $lcode)) ?></textarea>
											<?php if ($idx == $last) { ?>
												<span class="description"><br/><?php _e('Enter / modify the description for this album.', 'wppa'); ?></span>
											<?php } ?>
										</td>
									</tr>
									<?php $idx++;
								}
							} ?>

							<?php if (get_option('wppa_owner_only', 'no') == 'yes') { ?>
								<tr valign="top">
									<th scope="row">
										<label ><?php _e('Owned by:', 'wppa'); ?></label>
									</th>
									<?php if ( $albuminfo['owner'] == '--- public ---' && !current_user_can('administrator') ) { ?>
										<td>
											<?php _e('--- public ---', 'wppa') ?>
										</td>
									<?php } else { ?>
										<td>
											<select name="wppa-owner"><?php wppa_user_select($albuminfo['owner']); ?></select>
											<?php if (!current_user_can('administrator')) { ?>
												<span class="description" style="color:orange;" ><br/><?php _e('WARNING If you change the owner, you will no longer be able to modify this album and upload or import photos to it!', 'wppa'); ?></span>
											<?php } ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>

							<tr valign="top">
								<th>
									<label ><?php _e('Sort order #:', 'wppa'); ?></label>
								</th>
								<td>
									<input type="text" name="wppa-order" id="wppa-order" value="<?php echo($albuminfo['a_order']) ?>" style="width: 50px;"/>
									<?php if (get_option('wppa_list_albums_by', '0') != '1' && $albuminfo['a_order'] != '0') { ?>
										<span class="description" style="color:red">
										<?php _e('Album order # has only effect if you set the album sort order method to <b>Order #</b> in the Photo Albums -> Settings screen.', 'wppa') ?>
										</span>
									<?php } ?>
									<span class="description"><br/><?php _e('If you want to sort the albums by order #, enter / modify the order number here.', 'wppa'); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th>
									<label ><?php _e('Parent album:', 'wppa'); ?> </label>
								</th>
								<td>
									<select name="wppa-parent"><?php echo(wppa_album_select($albuminfo['id'], $albuminfo['a_parent'], true, true, true)) /*$albuminfo["id"], $albuminfo["a_parent"], TRUE, TRUE, TRUE)) */?></select>
									<span class="description">
										<br/><?php _e('If this is a sub album, select the album in which this album will appear.', 'wppa'); ?>
									</span>					
								</td>
							</tr>
							
							<tr valign="top">
								<th>
									<?php $order = $albuminfo['p_order_by']; ?>
									<label ><?php _e('Photo order:', 'wppa'); ?></label>
								</th>
								<td>
									<select name="wppa-list-photos-by"><?php wppa_order_options($order, __('--- default ---', 'wppa'), __('Rating', 'wppa'), __('Timestamp', 'wppa')) ?></select>
									<span class="description">
										<br/><?php _e('Specify the way the photos should be ordered in this album.', 'wppa'); ?>
										<br/><?php _e('The default setting can be changed in the Options page.', 'wppa'); ?>
									</span>
								</td>
							</tr>
							
							<tr valign="top">
								<th>
									<label ><?php _e('Cover Photo:', 'wppa'); ?></label>
								</th>
								<td>
									<?php echo(wppa_main_photo($albuminfo['main_photo'])) ?>
									<span class="description"><br/><?php _e('Select the photo you want to appear on the cover of this album.', 'wppa'); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label ><?php _e('Link type:', 'wppa') ?></label>
								</th>
								<td>
									<?php $linktype = $albuminfo['cover_linktype']; ?>
									<?php /* if ( !$linktype ) $linktype = 'content'; /* Default */ ?>	
									<?php /* if ( $albuminfo['cover_linkpage'] == '-1' ) $linktype = 'none'; /* for backward compatibility */ ?>
									<select name="cover-linktype" id="cover-linktype" >
										<option value="content" <?php if ( $linktype == 'content' ) echo ($sel) ?>><?php _e('the sub-albums and thumbnails', 'wppa') ?></option>
										<option value="slide" <?php if ( $linktype == 'slide' ) echo ($sel) ?>><?php _e('the album photos as slideshow', 'wppa') ?></option>
										<option value="none" <?php if ( $linktype == 'none' ) echo($sel) ?>><?php _e('no link at all', 'wppa') ?></option>
									</select
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label ><?php _e('Link to:', 'wppa'); ?></label>
								</th>
								<td>
									<?php $query = $wpdb->prepare( 'SELECT `ID`, `post_title` FROM `'.$wpdb->posts.'` WHERE `post_type` = \'page\' AND `post_status` = \'publish\' ORDER BY `post_title` ASC');
									$pages = $wpdb->get_results($query, 'ARRAY_A');
									if (empty($pages)) {
										_e('There are no pages (yet) to link to.', 'wppa');
									} else {
										$linkpage = $albuminfo['cover_linkpage'];
										if (!is_numeric($linkpage)) $linkpage = '0'; ?>
										<select name="cover-linkpage" id="cover-linkpage" >
											<option value="0" <?php if ($linkpage == '0') echo($sel); ?>><?php _e('--- the same page or post ---', 'wppa'); ?></option>
											<?php foreach ($pages as $page) { ?>
												<option value="<?php echo($page['ID']); ?>" <?php if ($linkpage == $page['ID']) echo($sel); ?>><?php echo($page['post_title']); ?></option>
											<?php } ?>
										</select>
										<span class="description">
											<br/><?php _e('If you want, you can link the title to a WP page in stead of the album\'s content. If so, select the page the title links to.', 'wppa'); ?>
										</span>
									<?php }	?>
								</td>
							</tr>

							<?php if (get_option('wppa_rating_on', 'yes') == 'yes') { ?>
								<tr valign="top">
									<th scope="row">
										<label><?php _e('Reset ratings:', 'wppa') ?></label>
									</th>
									<td>
										<input type="checkbox" name="clear-rating" id="clear-rating" />
										<span style="color:red;"><?php _e('WARNING: If checked, this will clear all ratings in this album!', 'wppa') ?></span>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>

			
					<h2><?php _e('Manage Photos', 'wppa'); ?></h2>
					

					<?php wppa_album_photos($edit_id) ?>
					<p><input type="submit" class="button-primary" name="wppa-ea-submit" value="<?php _e('Save All Changes', 'wppa'); ?>" /></p><br />
			
			
				</form>
			</div>
		<?php } 
		// album delete confirm page
		else if ($_GET['tab'] == 'del'){ ?>
			
			<div class="wrap">
				<?php $iconurl = WPPA_URL.'/images/albumdel32.png'; ?>
				<div id="icon-albumdel" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
					<br />
				</div>

				<h2><?php _e('Delete Album', 'wppa'); ?></h2>
				
				<p><?php _e('Album:', 'wppa'); ?> <b><?php echo wppa_get_album_name($_GET['edit_id']); ?>.</b></p>
				<p><?php _e('Are you sure you want to delete this album?', 'wppa'); ?><br />
					<?php _e('Press Delete to continue, and Cancel to go back.', 'wppa'); ?>
				</p>
				<form name="wppa-del-form" action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu')) ?>" method="post">
					<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE) ?>
					<p>
						<?php _e('What would you like to do with photos currently in the album?', 'wppa'); ?><br />
						<input type="radio" name="wppa-del-photos" value="delete" checked="checked" /> <?php _e('Delete', 'wppa'); ?><br />
						<input type="radio" name="wppa-del-photos" value="move" /> <?php _e('Move to:', 'wppa'); ?> 
						<select name="wppa-move-album">
							<option value=""><?php _e('- select an album -', 'wppa') ?></option>
							<?php echo(wppa_album_select($_GET['edit_id'])) ?>
						</select>
					</p>
				
					<input type="hidden" name="wppa-del-id" value="<?php echo($_GET['edit_id']) ?>" />
					<input type="button" class="button-primary" value="<?php _e('Cancel', 'wppa'); ?>" onclick="parent.history.back()" />
					<input type="submit" class="button-primary" style="color: red" name="wppa-del-confirm" value="<?php _e('Delete', 'wppa'); ?>" />
				</form>
			</div>
<?php	
		}
	} 
	else {	//  'tab' not set. default, album manage page.
		
		// if add form has been submitted
		if (isset($_POST['wppa-na-submit'])) {
			wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );

			wppa_add_album();
		}
		
		// if album deleted
		if (isset($_POST['wppa-del-confirm'])) {
			wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );

			if ($_POST['wppa-del-photos'] == 'move') {
				$move = $_POST['wppa-move-album'];
				if ( wppa_have_access($move) ) {
					wppa_del_album($_POST['wppa-del-id'], $move);
				}
				else {
					wppa_error_message(__('Unable to move photos. Album not deleted.', 'wppa'));
				}
			} else {
				wppa_del_album($_POST['wppa-del-id'], '');
			}
		}
?>		
		<div class="wrap">
			<?php $iconurl = WPPA_URL.'/images/album32.png'; ?>
			<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
				<br />
			</div>

			<h2><?php _e('Manage Albums', 'wppa'); ?></h2>
			<?php wppa_admin_albums() ?>

			<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id=new'); ?>
			<?php $vfy = __('Are you sure you want to create a new album?', 'wppa') ?>
			
			<br />
			<input type="button" class="button-primary" onclick="if (confirm('<?php echo $vfy ?>')) document.location='<?php echo $url ?>';" value="<?php _e('Create New Empty Album', 'wppa') ?>" />
		</div>
<?php	
	}
}

/* get the albums */
function wppa_admin_albums() {
	global $wpdb;
	$albums = $wpdb->get_results($wpdb->prepare( "SELECT * FROM " . WPPA_ALBUMS . " " . wppa_get_album_order() ), 'ARRAY_A');
	
	if (!empty($albums)) {
?>	
	<div class="table_wrapper">	
		<table class="widefat">
			<thead>
			<tr>
				<th scope="col"><?php _e('Name', 'wppa'); ?></th>
				<th scope="col"><?php _e('Description', 'wppa'); ?></th>
				<th scope="col"><?php _e('ID', 'wppa'); ?></th>
				<?php if (current_user_can('administrator')) { ?>
					<th scope="col"><?php _e('Owner', 'wppa'); ?></th>
				<?php } ?>
                <th scope="col"><?php _e('Order', 'wppa'); ?></th>
                <th scope="col" style="width: 120px;"><?php _e('Parent', 'wppa'); ?></th>
				<th scope="col"><?php _e('Edit', 'wppa'); ?></th>
				<th scope="col"><?php _e('Delete', 'wppa'); ?></th>	
			</tr>
			</thead>
			
			<?php $alt = ' class="alternate" '; ?>
		
			<?php foreach ($albums as $album) if(wppa_have_access($album)) { ?>
				<tr <?php echo($alt) ?>>
					<td><?php echo(wppa_qtrans(stripslashes($album['name']))) ?></td>
					<td><small><?php echo(wppa_qtrans(stripslashes($album['description']))) ?></small></td>
					<td><?php echo($album['id']) ?></td>
					<?php if (current_user_can('administrator')) { ?>
						<td><?php echo($album['owner']); ?></td>
					<?php } ?>
					<td><?php echo($album['a_order']) ?></td>
					<td><?php echo(wppa_qtrans(wppa_get_album_name($album['a_parent']))) ?></td>
					<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id='.$album['id']); ?>
					
					<?php $url = wppa_ea_url($album['id']) ?>
					<td><a href="<?php echo($url) ?>" class="wppaedit"><?php _e('Edit', 'wppa'); ?></a></td>
					<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=del&amp;id='.$album['id']); ?>
					
					<?php $url = wppa_ea_url($album['id'], 'del') ?>
					<td><a href="<?php echo($url) ?>" class="wppadelete"><?php _e('Delete', 'wppa'); ?></a></td>
				</tr>		
				<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
			}
?>			
		</table>
	</div>
<?php	
	} else { 
?>
	<p><?php _e('No albums yet.', 'wppa'); ?></p>
<?php
	}
}

// get photo edit list for albums
function wppa_album_photos($id) {
	global $wpdb;
	global $q_config;
	
	$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s '.wppa_get_photo_order($id, 'norandom'), $id), 'ARRAY_A');

	if (empty($photos)) { ?>
		<p><?php _e('No photos yet in this album.', 'wppa'); ?></p>
	<?php } 
	else { 
		$prev = '';
		foreach ($photos as $photo) { ?>
			<a name="p_<?php echo $photo['id'] ?>"></a>
		
			<p><input type="submit" class="button-primary" name="wppa-ea-submit" value="<?php _e('Save All Changes', 'wppa'); ?>" /></p>

			<div class="photoitem" style="width:100%;" >
			<div style="width:49.5%; float:left; border-right:1px solid #ccc; margin-right:0;">
				<table class="form-table phototable"  ><!--325-->
					<tbody>
					
						<tr valign="top">
							<th scope="row">
								<label ><?php echo 'ID = '.$photo['id'].' '.__('Preview:', 'wppa'); ?></label>
								<br/>
								<?php $href = wppa_ea_url($id).'&amp;rotate&amp;photo_rotate='.$photo['id'].'&amp;photo_angle=90#p_'.$photo['id']; ?>
								<input type="button" name="rotate" class="button-secondary" style="font-weight:bold; width:90%" onclick="if (confirm('<?php _e('Are you sure you want to rotate this photo?', 'wppa') ?>')) document.location='<?php echo $href ?>'; else return false;" value="<?php _e('Rotate left', 'wppa'); ?>" />
								<br/>
								<?php $href = wppa_ea_url($id).'&amp;rotate&amp;photo_rotate='.$photo['id'].'&amp;photo_angle=270#p_'.$photo['id']; ?>
								<input type="button" name="rotate" class="button-secondary" style="font-weight:bold; width:90%" onclick="if (confirm('<?php _e('Are you sure you want to rotate this photo?', 'wppa') ?>')) document.location='<?php echo $href ?>'; else return false;" value="<?php _e('Rotate right', 'wppa'); ?>" />
								<br/><span style="font-size: 9px; line-height: 10px; color:#666;"><?php _e('If it says \'Photo rotated\', the photo is rotated. If you do not see it happen here, clear your browser cache.', 'wppa') ?></span>
							</th>
							<td style="text-align:center;">
								<?php $src = WPPA_UPLOAD_URL.'/thumbs/' . $photo['id'] . '.' . $photo['ext']; ?> 
								<img src="<?php echo($src) ?>" alt="<?php echo($photo['name']) ?>" style="max-width: 160px;" />
							</td>	
						</tr>
						
						<tr valign="top">
							<th scope="row">
								<label ><?php _e('Upload:', 'wppa'); ?></label>
							</th>
							<td>
								<?php $timestamp = $photo['timestamp'] ? $photo['timestamp'] : '0'; ?>
								<?php if ($timestamp) echo( __('On:', 'wppa').' '.date("F j, Y, g:i a", $timestamp).' '); if ($photo['owner']) echo( __('By:', 'wppa').$photo['owner']) ?>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo('photos[' . $photo['id'] . '][album]') ?>"><?php _e('Album:', 'wppa'); ?></label>
							</th>
							<td>							
								<select name="<?php echo('photos[' . $photo['id'] . '][album]') ?>" style="width:100%;"><?php echo(wppa_album_select('', $id)) ?></select>
							</td>
						</tr>
							
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo('photos[' . $photo['id'] . '][p_order]') ?>"><?php _e('Photo order #:', 'wppa'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo('photos[' . $photo['id'] . '][p_order]') ?>" value="<?php echo($photo['p_order']) ?>" style="width: 50px"/>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row">
								<a href="#" id="copy-photo-<?php echo($photo['id']) ?>"></a>
								<input type="button" class="button-secondary" style="font-weight:bold; color:blue; width:90%" onclick="if (document.getElementById('albsel-<?php echo($photo['id']) ?>').value != 0) { if (confirm('<?php _e('Are you sure you want to copy this photo?', 'wppa') ?>')) document.location = document.getElementById('copy-photo-<?php echo($photo['id']) ?>').href; } else { alert('<?php _e('Please select an album to copy the photo to first.', 'wppa') ?>'); return false;}" value="<?php _e('Copy photo to', 'wppa') ?>" />
								<?php $url = wppa_ea_url($_GET['edit_id']).'&amp;photo_del='.$photo['id'].'#p_'.$prev; ?>
								<br/><a href="<?php echo($url) ?>" id="del-photo-<?php echo($photo['id']) ?>"></a>
								<input type="button" class="button-secondary" style="font-weight:bold; color:red; width:90%" onclick="if (confirm('<?php _e('Are you sure you want to delete this photo?', 'wppa') ?>')) document.location = document.getElementById('del-photo-<?php echo($photo['id']) ?>').href;" value="<?php _e('Delete photo', 'wppa'); ?>" />
								
								<br/><input type="button" class="button-secondary" style="font-weight:bold; width:90%" onclick="prompt('<?php _e('Insert code for single image in Page or Post:\nYou may change the size if you like.', 'wppa') ?>', '%%wppa%% %%photo=<?php echo($photo['id']); ?>%% %%size=<?php echo(get_option('wppa_fullsize')); ?>%%')" value="<?php _e('Insertion Code', 'wppa'); ?>" />
							</th>
							<td>
								<select id="albsel-<?php echo($photo['id']) ?>" style="width:100%;" name="copy-photo" onchange="document.getElementById('copy-photo-<?php echo($photo['id']) ?>').href='<?php echo wppa_ea_url($_GET['edit_id']).'&amp;photo_copy='.$photo['id'] ?>&amp;album_to='+this.value+'#p_<?php echo $photo['id'] ?>'; "><?php echo(wppa_album_select($id, '0', true)) ?></select>

								<br/><br/><?php _e('Rating:', 'wppa') ?><?php _e('Entries:', 'wppa'); echo(' '); echo wppa_get_rating_count_by_id($photo['id']); echo('. '); _e('Mean value:', 'wppa'); echo(' '.wppa_get_rating_by_id($photo['id'], 'nolabel').'.'); ?>
							
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" style="padding-top:0; padding-bottom:0;">
								<label><?php _e('Link url:', 'wppa') ?></label>
							</th>
							<td style="padding-top:0; padding-bottom:0;">
								<input type="text" name="<?php echo('photos[' . $photo['id'] . '][linkurl]') ?>" style="width:100%;" value="<?php echo(stripslashes($photo['linkurl'])) ?>" style="width: 100%"/>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" style="padding-top:0; padding-bottom:0;">
								<label><?php _e('Link title:', 'wppa') ?></label>
							</th>
							<td style="padding-top:0; padding-bottom:0;">
								<input type="text" name="<?php echo('photos[' . $photo['id'] . '][linktitle]') ?>" style="width:100%;" value="<?php echo(stripslashes($photo['linktitle'])) ?>" style="width: 100%"/>
							</td>
						</tr>

					</tbody>
				</table>
				
				<p style="padding-left:7px; font-size:9px; line-height:10px; color:#666;" >
					<?php _e('If you want this link to be used, check \'PS Overrule\' checkbox in table VI of the Photo Albums -> Settings admin page.', 'wppa') ?>
				</p>

			</div>
			<div style="width:50%; float:left; border-left:1px solid #ccc; margin-left:-1px;">
				
				<table class="form-table phototable" >
					<tbody>
						<?php if (!wppa_qtrans_enabled()) { ?>					
							<tr valign="top">
								<th scope="row" >
									<label for="<?php echo('photos[' . $photo['id'] . '][name]') ?>"><?php _e('Name:', 'wppa'); ?></label>
								</th>
								<td>
									<input type="text" style="width:100%;"name="<?php echo('photos[' . $photo['id'] . '][name]') ?>" value="<?php echo(stripslashes($photo['name'])) ?>" />
									<span class="description"><br/><?php _e('Type/alter the name of the photo. It is NOT a filename and needs no file extension like .jpg.', 'wppa'); ?></span>
								</td>
							</tr>
						<?php }
						else { 
							$first = true;
							$last = count($q_config['enabled_languages']) - 1;
							$idx = 0;
							foreach ($q_config['enabled_languages'] as $lcode) {
								$lname = $q_config['language_name'][$lcode]; ?>
								<tr valign="top">
									<th scope="row" >
										<label ><?php if ($first) _e('Name:', 'wppa'); $first = false; ?></label>
									</th>
									<td>
										<b><?php echo($lname) ?></b><br/>
										<input type="text" style="width:100%;" name="<?php echo('photos['.$photo['id'].'][name]['.$lcode.']') ?>" value="<?php echo(wppa_qtrans(stripslashes($photo['name']), $lcode)) ?>" />
										<?php if ($idx == $last) { ?>
											<span style="color:#666;font-size:9px;line-height:10px;"><?php _e('Type/alter the name of the photo. It is NOT a filename and needs no file extension like .jpg.', 'wppa'); ?></span>
										<?php } ?>
									</td>
								</tr>
								<?php $idx++;
							}
						} ?>

						<?php if (!wppa_qtrans_enabled()) { ?>
							<tr valign="top">
								<th scope="row" >
									<label><?php _e('Description:', 'wppa'); ?></label>
								</th>
								<td>
									<textarea style="width: 100%; height:160px;" name="photos[<?php echo($photo['id']) ?>][description]"><?php echo(stripslashes($photo['description'])) ?></textarea>
								</td>
							</tr>
						<?php }
						else { 
							$first = true;
							$last = count($q_config['enabled_languages']) - 1;
							$idx = 0;
							foreach ($q_config['enabled_languages'] as $lcode) {
								$lname = $q_config['language_name'][$lcode]; ?>
								<tr valign="top">
									<th scope="row" >
										<label><?php if ($first) _e('Description:', 'wppa'); $first = false; ?></label>
									</th>
									<td>
										<b><?php echo($lname) ?></b><br/>
										<textarea style="width: 100%; height: 80px;" name="photos[<?php echo($photo['id']) ?>][description][<?php echo($lcode) ?>]"><?php echo(wppa_qtrans(stripslashes($photo['description']), $lcode)) ?></textarea>
									</td>
								</tr>
							<?php }
						} ?>

					</tbody>
				</table>

			</div>
			
				<input type="hidden" name="<?php echo('photos[' . $photo['id'] . '][id]') ?>" value="<?php echo($photo['id']) ?>" />
				<input type="hidden" name="<?php echo('photos[' . $photo['id'] . '][mean_rating]') ?>" value="<?php echo($photo['mean_rating']) ?>" />
				<div class="clear"></div>
			</div>
		<?php $prev = $photo['id'];
		} /* foreach photo */
	} /* photos not empty */
} /* function */


// add an album 
function wppa_add_album() {
	global $wpdb;
	global $q_config;
	
	if (!wppa_qtrans_enabled()) {
		$name = $_POST['wppa-name'];
		$desc = $_POST['wppa-desc'];
	}
	else {
		$name = '';
		$desc = '';
		foreach ($q_config['enabled_languages'] as $lcode) {
			$n = $_POST['wppa-name-'.$lcode];
			$d = $_POST['wppa-desc-'.$lcode];
			if ($n != '') $name .= '[:'.$lcode.']'.$n;
			if ($d != '') $desc .= '[:'.$lcode.']'.$d;
		}
	}
	$name = esc_attr($name);
	$desc = esc_attr($desc);

	$order = (is_numeric($_POST['wppa-order']) ? $_POST['wppa-order'] : 0);
	$parent = (is_numeric($_POST['wppa-parent']) ? $_POST['wppa-parent'] : 0);
	$porder = (is_numeric($_POST['wppa-photo-order-by']) ? $_POST['wppa-photo-order-by'] : 0);
	
	$owner = wppa_get_user();

	if (!empty($name)) {
		error_reporting(E_ALL);
		$query = $wpdb->prepare('INSERT INTO `' . WPPA_ALBUMS . '` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linktype`, `cover_linkpage`, `owner`, `timestamp`) VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $name, $desc, $order, $parent, $porder, '0', 'content', '0', $owner, time());
		$iret = $wpdb->query($query);
        if ($iret === FALSE) wppa_error_message(__('Could not create album.', 'wppa').'<br/>Query = '.$query);
		else {
            $id = wppa_get_album_id($name);
            wppa_set_last_album($id);
			wppa_update_message(__('Album #', 'wppa') . ' ' . $id . ' ' . __('Added.', 'wppa'));
        }
	} 
    else wppa_error_message(__('Album Name cannot be empty.', 'wppa'));
}

// edit an album 
function wppa_edit_album() {
	global $wpdb;
	global $q_config;
	
    $first = TRUE;
	
	if (!wppa_qtrans_enabled()) {
		$name = $_POST['wppa-name'];
		$desc = $_POST['wppa-desc'];
	}
	else {
		$name = '';
		$desc = '';
		foreach ($q_config['enabled_languages'] as $lcode) {
			$n = $_POST['wppa-name-'.$lcode];
			$d = $_POST['wppa-desc-'.$lcode];
			if ($n != '') $name .= '[:'.$lcode.']'.$n;
			if ($d != '') $desc .= '[:'.$lcode.']'.$d;
		}
	}
	$name = esc_attr($name);
	$desc = esc_attr($desc);

	if (isset($_POST['wppa-main'])) $main = $_POST['wppa-main'];
	else $main = '0';
	
    $order = (is_numeric($_POST['wppa-order']) ? $_POST['wppa-order'] : 0);
	
	$parent = (isset($_POST['wppa-parent']) ? $_POST['wppa-parent'] : 0);
	if ($parent == -3) $parent = 0;	// selected an unselectable item (IE < 8 ?)
	
    $orderphotos = (is_numeric($_POST['wppa-list-photos-by']) ? $_POST['wppa-list-photos-by'] : 0);
	
	$linktype = $_POST['cover-linktype'];
	$link = $_POST['cover-linkpage'];
	
	$owner = (isset($_POST['wppa-owner']) ? $_POST['wppa-owner'] : '');
	
    // update the photo information
    if (isset($_POST['photos']))
	foreach ($_POST['photos'] as $photo) {

		$mean_rating = $photo['mean_rating'];
		
		if (isset($_POST['clear-rating'])) {
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $photo['id']));
			$mean_rating = '0';
		}
		
		if (!wppa_qtrans_enabled()) {
			$photo_name = $photo['name'];
			$photo_desc = $photo['description'];
		}
		else {
			$photo_name = '';
			$photo_desc = '';
			foreach ($q_config['enabled_languages'] as $lcode) {
				$n = $photo['name'][$lcode];
				$d = $photo['description'][$lcode];
				if ($n != '') $photo_name .= '[:'.$lcode.']'.$n;
				if ($d != '') $photo_desc .= '[:'.$lcode.']'.$d;
			}
		}
		$photo_name = esc_attr($photo_name);
		$photo_desc = esc_attr($photo_desc);
	
        if (!is_numeric($photo['p_order'])) $photo['p_order'] = 0;
		
		$linkurl = $photo['linkurl'];
		$linktitle = $photo['linktitle'];
		
		$query = $wpdb->prepare('UPDATE `' . WPPA_PHOTOS . '` SET `name` = %s, `album` = %s, `description` = %s, `p_order` = %s, `mean_rating` = %s, `linkurl` = %s, `linktitle` = %s WHERE `id` = %s LIMIT 1', $photo_name, $photo['album'], $photo_desc, $photo['p_order'], $mean_rating, $linkurl, $linktitle, $photo['id']);
		$iret = $wpdb->query($query);

        if ($iret === FALSE) {
            if ($first) { 
				wppa_error_message(__('Could not update photo.', 'wppa'));
				$first = FALSE;
			}
        }
	}
	
	// update the album information
	if (!empty($name)) {
		if ($owner == '') $query = $wpdb->prepare('UPDATE `' . WPPA_ALBUMS . '` SET `name` = %s, `description` = %s, `main_photo` = %s, `a_order` = %s, `a_parent` = %s, `p_order_by` = %s, `cover_linktype` = %s, `cover_linkpage` = %s WHERE `id` = %s', $name, $desc, $main, $order, $parent, $orderphotos, $linktype, $link, $_GET['edit_id']);
		else $query = $wpdb->prepare('UPDATE `' . WPPA_ALBUMS . '` SET `name` = %s, `description` = %s, `main_photo` = %s, `a_order` = %s, `a_parent` = %s, `p_order_by` = %s, `cover_linktype` = %s, `cover_linkpage` = %s, `owner` = %s WHERE `id` = %s', $name, $desc, $main, $order, $parent, $orderphotos, $linktype, $link, $owner, $_GET['edit_id']);
		$iret = $wpdb->query($query);
		
        if ($iret === FALSE) {
			wppa_error_message(__('Album could not be updated.', 'wppa'));
		}
		else {
			wppa_update_message(__('Album information edited.', 'wppa') . ' ' . '<a href="'.wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu').'">' . __('Back to album management.', 'wppa') . '</a>');
		}
				
		wppa_set_last_album($_GET['edit_id']);
	} else { 
		wppa_error_message(__('Album Name cannot be empty.', 'wppa'));
	}
}

// delete an album 
function wppa_del_album($id, $move = '') {
	global $wpdb;

	if ( $move && !wppa_have_access($move) ) {
		wppa_error_message(__('Unable to move photos to album %s. Album not deleted.', 'wppa'));
		return false;
	}
	
	$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_ALBUMS . '` WHERE `id` = %s LIMIT 1', $id));

	if (empty($move)) { // will delete all the album's photos
		$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `' . WPPA_PHOTOS . '` WHERE `album` = %s', $id), 'ARRAY_A');

		if (is_array($photos)) {
			foreach ($photos as $photo) {
				// remove the photos and thumbs
				$file = ABSPATH . 'wp-content/uploads/wppa/' . $photo['id'] . '.' . $photo['ext'];
				if (file_exists($file)) {
					unlink($file);
				}
				/* else: silence */
				$file = ABSPATH . 'wp-content/uploads/wppa/thumbs/' . $photo['id'] . '.' . $photo['ext'];
				if (file_exists($file)) {
					unlink($file);
				}
				/* else: silence */
				// remove the photo's ratings
				$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_RATING . '` WHERE `photo` = %s', $photo['id']));
				// remove the photo's comments
				$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_COMMENTS . '` WHERE `photo` = %s', $photo['id']));
			} 
		}
		
		// remove the database entries
		$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $id));
	} else {
		$wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `album` = %s WHERE `album` = %s', $move, $id));
	}
	
	wppa_update_message(__('Album Deleted.', 'wppa'));
}

// select main photo
function wppa_main_photo($cur = '') {
	global $wpdb;
	
    $a_id = $_GET['edit_id'];
	$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s '.wppa_get_photo_order($a_id), $a_id), 'ARRAY_A');
	
	$output = '';
	if (!empty($photos)) {
		$output .= '<select name="wppa-main">';
		$output .= '<option value="0">'.__('--- random ---', 'wppa').'</option>';

		foreach($photos as $photo) {
			if ($cur == $photo['id']) { 
				$selected = 'selected="selected"'; 
			} 
			else { 
				$selected = ''; 
			}
			$output .= '<option value="'.$photo['id'].'" '.$selected.'>'.wppa_qtrans($photo['name']).'</option>';
		}
		
		$output .= '</select>';
	} else {
		$output = '<p>'.__('No photos yet', 'wppa').'</p>';
	}
	return $output;
}

function wppa_ea_url($edit_id, $tab = 'edit') {

	$nonce = wp_create_nonce('wppa_nonce');
//	$referrer = $_SERVER["REQUEST_URI"];
	return wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab='.$tab.'&amp;edit_id='.$edit_id.'&amp;wppa_nonce='.$nonce);
}
