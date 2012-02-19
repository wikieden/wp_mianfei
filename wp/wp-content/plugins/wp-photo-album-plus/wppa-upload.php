<?php 
/* wppa-upload.php
* Package: wp-photo-album-plus
*
* Contains all the upload/import pages and functions
* Version 4.3.9
*
*/

function _wppa_page_upload() {
global $target;
global $wppa_opt;

	// upload images admin page

    // sanitize system
	$user = wppa_get_user();
	wppa_cleanup_photos();
	wppa_sanitize_files();

	if ( $wppa_opt['wppa_watermark_on'] == 'yes' && $wppa_opt['wppa_watermark_user'] == 'yes') {
		if ( isset( $_POST['wppa-watermark-file'] ) ) update_option('wppa_watermark_file_'.$user, $_POST['wppa-watermark-file']);
		if ( isset( $_POST['wppa-watermark-pos'] ) ) update_option('wppa_watermark_pos_'.$user, $_POST['wppa-watermark-pos']);
	}
	
	// Do the upload if requested
	if ( isset( $_POST['wppa-upload'] ) ) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_upload_photos();
	} 
	if ( isset( $_POST['wppa-upload-zip'] ) ) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		$err = wppa_upload_zip();
		if ( isset( $_POST['wppa-go-import'] ) && $err == '0' ) { 
			wppa_ok_message(__('Connecting to your depot...', 'wppa'));
			update_option('wppa_import_source_'.$user, WPPA_DEPOT); ?>
			<script type="text/javascript">document.location = '<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_import_photos&zip='.$target, 'js')) ?>';</script>
		<?php }
	} 
	
	// sanitize system again
	wppa_cleanup_photos();
	wppa_sanitize_files();

	?>
	
	<div class="wrap">
		<?php $iconurl = WPPA_URL.'/images/camera32.png'; ?>
		<div id="icon-camera" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
		</div>
		<?php $iconurl = WPPA_URL.'/images/arrow32.png'; ?>
		<div id="icon-arrow" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
		</div>
		<?php $iconurl = WPPA_URL.'/images/album32.png'; ?>
		<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
		<br />
		</div>
		<h2><?php _e('Upload Photos', 'wppa'); ?></h2><br />

		<?php	
		$max_files = ini_get('max_file_uploads');
		$max_files_txt = $max_files;
		if ($max_files < '1') {
			$max_files_txt = __('unknown', 'wppa');
			$max_files = '15';
		}
		$max_size = ini_get('upload_max_filesize');
		$max_time = ini_get('max_input_time');	
		if ($max_time < '1') $max_time = __('unknown', 'wppa');
		// chek if albums exist before allowing upload
		if(wppa_has_albums()) { ?>
			<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; width: 600px; background-color:#fffbcc; border-color:#e6db55;">
			<?php echo(sprintf(__('<b>Notice:</b> your server allows you to upload <b>%s</b> files of maximum total <b>%s</b> bytes and allows <b>%s</b> seconds to complete.', 'wppa'), $max_files_txt, $max_size, $max_time)) ?>
			<?php _e('If your request exceeds these limitations, it will fail, probably without an errormessage.', 'wppa') ?>
			<?php _e('Additionally your hosting provider may have set other limitations on uploading files.', 'wppa') ?>
			</div>
			<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; width: 600px;">
				<h3 style="margin-top:0px;"><?php _e('Single Photos', 'wppa'); ?></h3><br />
				<?php //_e('You can select up to 15 photos one by one and upload them at once.', 'wppa'); ?>
				<form enctype="multipart/form-data" action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_upload_photos')) ?>" method="post">
				<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
					<input id="my_file_element" type="file" name="file_1" />
					<div id="files_list">
						<h3><?php _e('Selected Files:', 'wppa'); ?></h3>
						
					</div>
					<p>
						<label for="wppa-album"><?php _e('Album:', 'wppa'); ?> </label>
						<select name="wppa-album" id="wppa-album">
							<option value=""><?php _e('- select an album -', 'wppa') ?></option>
							<?php echo(wppa_album_select()); ?>
						</select>
		<?php if ( $wppa_opt['wppa_watermark_on'] == 'yes' && $wppa_opt['wppa_watermark_user'] == 'yes' ) { ?>		
</p>
<p>		
				<?php _e('Apply watermark file:', 'wppa') ?>
				<select name="wppa-watermark-file" id="wppa-watermark-file">
					<?php echo(wppa_watermark_file_select()) ?>
				</select>

				<?php _e('Position:', 'wppa') ?>
				<select name="wppa-watermark-pos" id="wppa-watermark-pos">
					<?php echo(wppa_watermark_pos_select()) ?>
				</select>
		<?php } ?>
						
					</p>
					<input type="submit" class="button-primary" name="wppa-upload" value="<?php _e('Upload Single Photos', 'wppa') ?>" />					
				</form>
				<script type="text/javascript">
				<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
					var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), <?php echo($max_files) ?> );
				<!-- Pass in the file element -->
					multi_selector.addElement( document.getElementById( 'my_file_element' ) );
				</script>
			</div>
			<?php if (PHP_VERSION_ID >= 50207) { ?>
				<div style="border:1px solid #ccc; padding:10px; width: 600px;">
					<h3 style="margin-top:0px;"><?php _e('Zipped Photos', 'wppa'); ?></h3><br />
					<?php _e('You can upload one zipfile at once. It will be placed in your personal wppa-depot.<br/>Once uploaded, use <b>Import Photos</b> to unzip the file and place the photos in any album.', 'wppa') ?>
					<form enctype="multipart/form-data" action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_upload_photos')) ?>" method="post">
					<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
						<input id="my_zipfile_element" type="file" name="file_zip" /><br/><br/>
						<input type="submit" class="button-primary" name="wppa-upload-zip" value="<?php _e('Upload Zipped Photos', 'wppa') ?>" />
						<input type="checkbox" name="wppa-go-import" checked="checked"><?php _e('After upload: Go to the <b>Import Photos</b> page.', 'wppa') ?></input>
					</form>
				</div>
			<?php }
			else { ?>
				<div style="border:1px solid #ccc; padding:10px; width: 600px;">
				<?php _e('<small>Ask your administrator to upgrade php to version 5.2.7 or later. This will enable you to upload zipped photos.</small>', 'wppa') ?>
				</div>
			<?php }
		}
	else { ?>
			<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu'); ?>
			<p><?php _e('No albums exist. You must', 'wppa'); ?> <a href="<?php echo($url) ?>"><?php _e('create one', 'wppa'); ?></a> <?php _e('beofre you can upload your photos.', 'wppa'); ?></p>
<?php } ?>
	</div>
<?php
}

// import images admin page
function _wppa_page_import() {
global $wppa_opt;

	// Sanitize system
    wppa_cleanup_photos('0');
	$user = wppa_get_user();
	$count = wppa_sanitize_files();
	if ($count) wppa_error_message($count.' '.__('illegal files deleted.', 'wppa'));

	if ( $wppa_opt['wppa_watermark_on'] == 'yes' && $wppa_opt['wppa_watermark_user'] == 'yes' ) {
		if ( isset( $_POST['wppa-watermark-file'] ) ) update_option('wppa_watermark_file_'.$user, $_POST['wppa-watermark-file']);
		if ( isset( $_POST['wppa-watermark-pos'] ) ) update_option('wppa_watermark_pos_'.$user, $_POST['wppa-watermark-pos']);
	}
	
	// Do the dirty work
	if (isset($_GET['zip'])) {
	//	wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		wppa_extract($_GET['zip'], true);
	}
	if (isset($_POST['wppa-import-set-source'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		update_option('wppa_import_source_'.$user, $_POST['wppa-source']);
	}
	elseif (isset($_POST['wppa-import-submit'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
        if (isset($_POST['del-after-p'])) $delp = true; else $delp = false;
		if (isset($_POST['del-after-a'])) $dela = true; else $dela = false;	
		if (isset($_POST['del-after-z'])) $delz = true; else $delz = false;
		wppa_import_photos($delp, $dela, $delz);
	} 
	// Sanitize again
	$count = wppa_sanitize_files();
	if ($count) wppa_error_message($count.' '.__('illegal files deleted.', 'wppa'));
?>
	
	<div class="wrap">
		<?php $iconurl = WPPA_URL.'/images/camera32.png'; ?>
		<div id="icon-camera" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"></div>
		<?php $iconurl = WPPA_URL.'/images/arrow32.png'; ?>
		<div id="icon-arrow" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"></div>
		<?php $iconurl = WPPA_URL.'/images/album32.png'; ?>
		<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat"><br /></div>
		
		<h2><?php _e('Import Photos', 'wppa'); ?></h2><br />
<?php		
		// Get this users current source directory setting
		$source      = get_option( 'wppa_import_source_'.$user, WPPA_DEPOT );
		$source_path = ABSPATH . $source;
		$source_url  = get_bloginfo('url') . '/' . $source;

		// See if the current source is the 'home' directory
		$is_depot 	= ( $source == WPPA_DEPOT );

		// See what's in there
		$paths 		= $source_path . '/*.*';
		$files 		= glob($paths);
		$zipcount 	= wppa_get_zipcount($files);
		$albumcount = wppa_get_albumcount($files);
		$photocount = wppa_get_photocount($files);
		
?>		
		<form action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_import_photos')) ?>" method="post">
		<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
		<?php _e('Import photos from:', 'wppa'); ?>
			<select name="wppa-source">
				<?php /* The source is eitrher the users depot: */ ?>
				<option value="<?php echo(WPPA_DEPOT) ?>" <?php if ($is_depot) echo('selected="selected"') ?>><?php _e('Your depot', 'wppa') ?></option>
				<?php /* Or anything in or in a subdirectory of WPPA_UPLOAD, except the wppa dir which contains all the wppa photos */ ?>
				<?php /* of which wppa_walktree() takes care of (it skips any (sub)dir named wppa) */ ?>
				<?php /* This is true for both singlesite as multisite installations. */ ?>
				<?php /* See wppa-common-functions.php for the definitions of WPPA_DEPOT and WPPA_UPLOAD */ ?>
				<?php wppa_walktree(WPPA_UPLOAD, $source) ?>	
			</select>
			<input type="submit" class="button-secundary" name="wppa-import-set-source" value="<?php _e('Set source directory', 'wppa'); ?>" />
		</form>
<?php
		
		// check if albums exist or will be made before allowing upload
		if(wppa_has_albums() || $albumcount > '0' || $zipcount >'0') { 
	
		if ($photocount > '0' || $albumcount > '0' || $zipcount >'0') { ?>
		
			<form action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_import_photos')) ?>" method="post">
			<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); 
			
			if (PHP_VERSION_ID >= 50207 && $zipcount > '0') { ?>		
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$zipcount.' '); _e('zipfiles in the depot.', 'wppa') ?><br/>
			</p>
			<table class="form-table albumtable" style="margin-bottom:0;" >
				<tr>
					<td>
						<input type="checkbox" id="all-zip" checked="checked" onchange="checkAll('all-zip', '.wppa-zip')" /><b>&nbsp;&nbsp;<?php _e('Check/uncheck all', 'wppa') ?></b>
					</td>
					<td>
					<?php if ($is_depot) { ?>
						<td>
							<input type="checkbox" name="del-after-z" checked="checked" /><b>&nbsp;&nbsp;<?php _e('Delete after successful extraction.', 'wppa'); ?></b>
						</td>
					<?php } ?>
				</tr>
			</table>
			<table class="form-table albumtable" style="margin-top:0;" >
				<tr>
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
			
						$ext = strtolower(substr(strrchr($file, "."), 1));
						if ($ext == 'zip') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx) ?>" class="wppa-zip" checked="checked" />&nbsp;&nbsp;<?php echo(basename($file)); ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php }
			if ($albumcount > '0') { ?>
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$albumcount.' '); _e('albumdefinitions in the depot.', 'wppa') ?><br/>
			</p>
			<table class="form-table albumtable" style="margin-bottom:0;" >
				<tr>
					<td>
						<input type="checkbox" id="all-amf" checked="checked" onchange="checkAll('all-amf', '.wppa-amf')" /><b>&nbsp;&nbsp;<?php _e('Check/uncheck all', 'wppa') ?></b>
					</td>
					<td>
					<?php if ($is_depot) { ?>
						<td>
							<input type="checkbox" name="del-after-a" checked="checked" /><b>&nbsp;&nbsp;<?php _e('Delete after successful import, or if the album already exits.', 'wppa'); ?></b>
						</td>
					<?php } ?>
				</tr>
			</table>
			<table class="form-table albumtable"  style="margin-top:0;" >
				<tr>
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
						$ext = strtolower(substr(strrchr($file, "."), 1));
						if ($ext == 'amf') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx) ?>" class="wppa-amf" checked="checked" />&nbsp;&nbsp;<?php echo(basename($file)); ?>&nbsp;<?php echo(stripslashes(wppa_get_meta_name($file, '('))) ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php }
			if ($photocount > '0') { ?>
			<p>
				<?php _e('There are', 'wppa'); echo(' '.$photocount.' '); _e('photos in the depot.', 'wppa'); if (get_option('wppa_resize_on_upload', 'no') == 'yes') { echo(' '); _e('Photos will be downsized during import.', 'wppa'); } ?><br/>
			</p>
			<p>
				<?php _e('Default album for import:', 'wppa') ?>
				<select name="wppa-album" id="wppa-album">
					<option value=""><?php _e('- select an album -', 'wppa') ?></option>
					<?php echo(wppa_album_select()) ?>
				</select>
				<?php _e('Photos that have (<em>name</em>)[<em>album</em>] will be imported by that <em>name</em> in that <em>album</em>.', 'wppa') ?>
			</p>
	<?php if ( $wppa_opt['wppa_watermark_on'] == 'yes' && $wppa_opt['wppa_watermark_user'] == 'yes' ) { ?>
			<p>
				<?php _e('Apply watermark file:', 'wppa') ?>
				<select name="wppa-watermark-file" id="wppa-watermark-file">
					<?php echo(wppa_watermark_file_select()) ?>
				</select>
				<?php _e('Position:', 'wppa') ?>
				<select name="wppa-watermark-pos" id="wppa-watermark-pos">
					<?php echo(wppa_watermark_pos_select()) ?>
				</select>
			</p>
	<?php } ?>
			<table class="form-table albumtable" style="margin-bottom:0;" >
				<tr>
					<td>
						<input type="checkbox" id="all-pho" checked="checked" onchange="checkAll('all-pho', '.wppa-pho')" /><b>&nbsp;&nbsp;<?php _e('Check/uncheck all', 'wppa') ?></b>
					</td>
					<?php if ($is_depot) { ?>
						<td>
							<input type="checkbox" name="del-after-p" checked="checked" /><b>&nbsp;&nbsp;<?php _e('Delete after successful import.', 'wppa'); ?></b>
						</td>
					<?php } ?>
					<td>
						<input type="checkbox" id="wppa-upd" onchange="impUpd(this, '#submit')" name="wppa-update"><b>&nbsp;&nbsp;<?php _e('Update existing photos', 'wppa') ?></b>
					</td>
				</tr>
			</table>				
			<table class="form-table albumtable" style="margin-top:0;" >
				<tr> 
					<?php
					$ct = 0;
					$idx = '0';
					foreach ($files as $file) {
						$ext = strtolower(substr(strrchr($file, "."), 1));
						$meta =	substr($file, 0, strlen($file)-3).'pmf';
						if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') { ?>
							<td>
								<input type="checkbox" name="file-<?php echo($idx) ?>" class= "wppa-pho" <?php if ($is_depot) echo('checked="checked"') ?> />&nbsp;&nbsp;<?php echo(basename($file)); ?>&nbsp;<?php echo(stripslashes(wppa_get_meta_name($meta, '('))) ?><?php echo(stripslashes(wppa_get_meta_album($meta, '['))) ?>
							</td>
							<?php if ($ct == 3) {
								echo('</tr><tr>'); 
								$ct = 0;
							}
							else {
								$ct++;
							}
						}
						$idx++;
					} ?>
				</tr>
			</table>
			<?php } ?>
			<p>
				<input type="submit" class="button-primary" id="submit" name="wppa-import-submit" value="<?php _e('Import', 'wppa'); ?>" />
			</p>
			</form>
		<?php }
		else {
			if (PHP_VERSION_ID >= 50207) {
				wppa_ok_message(__('There are no archives, albums or photos in directory:', 'wppa').' '.$source_url);
			}
			else {
				wppa_ok_message(__('There are no albums or photos in directory:', 'wppa').' '.$source_url);
			}
		}
	}
	else { ?>
		<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu'); ?>
		<p><?php _e('No albums exist. You must', 'wppa'); ?> <a href="<?php echo($url) ?>"><?php _e('create one', 'wppa'); ?></a> <?php _e('beofre you can upload your photos.', 'wppa'); ?></p>
<?php } ?>
	</div>
<?php
}

// Upload photos 
function wppa_upload_photos() {
	global $wpdb;
	global $warning_given;

	$warning_given = false;
	$uploaded_a_file = false;
	
	$count = '0';
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] != '') {
			if (wppa_insert_photo($file['tmp_name'], $_POST['wppa-album'], $file['name'])) {
				$uploaded_a_file = true;
				$count++;
			}
			else {
				wppa_error_message(__('Error inserting photo', 'wppa') . ' ' . basename($file['tmp_name']) . '.');
				return;
			}
		}
	}
	
	if ($uploaded_a_file) { 
		wppa_update_message($count.' '.__('Photos Uploaded in album nr', 'wppa') . ' ' . $_POST['wppa-album']);
		wppa_set_last_album($_POST['wppa-album']);
    }
}

function wppa_upload_zip() {
global $target;

	$file = $_FILES['file_zip'];
	$name = $file['name'];
	$type = $file['type'];
	$error = $file['error'];
	$size = $file['size'];
	$temp = $file['tmp_name'];
	
//	$user = wppa_get_user();
	
	$target = WPPA_DEPOT_PATH.'/'.$name;
	
	copy($temp, $target);
	
	if ($error == '0') wppa_ok_message(__('Zipfile', 'wppa').' '.$name.' '.__('sucessfully uploaded.', 'wppa'));
	else wppa_error_message(__('Error', 'wppa').' '.$error.' '.__('during upload.', 'wppa'));
	
	return $error;
}

function wppa_import_photos($delp = false, $dela = false, $delz = false) {
global $wpdb;
global $warning_given;

	$warning_given = false;
	
	// Get this users current source directory setting
	$user = wppa_get_user();
	$source = get_option('wppa_import_source_'.$user, WPPA_DEPOT); // removed /$user

	$depot = ABSPATH . $source;	// Filesystem
	$depoturl = get_bloginfo('url').'/'.$source;	// url

	// See what's in there
	$paths = $depot.'/*.*';
	$files = glob($paths);

	// First extract zips if our php version is ok
	$idx='0';
	$zcount = 0;
	if (PHP_VERSION_ID >= 50207) {
		foreach($files as $zipfile) {
			if (isset($_POST['file-'.$idx])) {
				$ext = strtolower(substr(strrchr($zipfile, "."), 1));
				
				if ($ext == 'zip') {
					$err = wppa_extract($zipfile, $delz);
					if ($err == '0') $zcount++;
				} // if ext = zip			
			} // if isset
			$idx++;
		} // foreach
	}
	
	// Now see if albums must be created
	$idx='0';
	$acount = 0;
	foreach($files as $album) {
		if (isset($_POST['file-'.$idx])) {
			$ext = strtolower(substr(strrchr($album, "."), 1));
			if ($ext == 'amf') {
				$name = '';
				$desc = '';
				$aord = '0';
				$parent = '0';
				$porder = '0';
				$owner = '';
				$handle = fopen($album, "r");
				if ($handle) {
					$buffer = fgets($handle, 4096);
					while (!feof($handle)) {
						$tag = substr($buffer, 0, 5);
						$len = strlen($buffer) - 6;	// substract 5 for label and one for eol
						$data = substr($buffer, 5, $len);
						switch($tag) {
							case 'name=':
								$name = $data;
								break;
							case 'desc=':
								$desc = $data;
								break;
							case 'aord=':
								if (is_numeric($data)) $aord = $data;
								break;
							case 'prnt=':
								if ($data == __('--- none ---', 'wppa')) $parent = '0';
								elseif ($data == __('--- separate ---', 'wppa')) $parent = '-1';
								else {
									$prnt = wppa_get_album_id($data);
									if ($prnt != '') {
										$parent = $prnt;
									}
									else {
										$parent = '0';
										wppa_warning_message(__('Unknown parent album:', 'wppa').' '.$data.' '.__('--- none --- used.', 'wppa'));
									}
								}
								break;
							case 'pord=':
								if (is_numeric($data)) $porder = $data;
								break;
							case 'ownr=':
								$owner = $data;
								break;
						}
						$buffer = fgets($handle, 4096);
					} // while !foef
					fclose($handle);
					if (wppa_get_album_id($name) != '') {
						wppa_warning_message('Album already exists '.stripslashes($name));
						if ($dela) unlink($album);
					}
					else {
						$id = basename($album);
						$id = substr($id, 0, strpos($id, '.'));
						if (!wppa_is_id_free('album', $id)) $id = wppa_nextkey(WPPA_ALBUMS);
						$query = $wpdb->prepare( 'INSERT INTO `' . WPPA_ALBUMS . '` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linktype`, `cover_linkpage`, `owner`, `timestamp`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $id, stripslashes($name), stripslashes($desc), $aord, $parent, $porder, '0', 'content', '0', $owner, time());
						$iret = $wpdb->query($query);

						if ($iret === FALSE) wppa_error_message(__('Could not create album.', 'wppa'));
						else {
							//$id = wppa_get_album_id($name);
							wppa_set_last_album($id);
							wppa_ok_message(__('Album #', 'wppa') . ' ' . $id . ': '.stripslashes($name).' ' . __('Added.', 'wppa'));
							if ($dela) unlink($album);
							$acount++;
						} // album added
					} // album did not exist
				} // if handle (file open)
			} // if its an album
		} // if isset
		$idx++;
	} // foreach file
	
	// Now the photos
	$idx='0';
	$pcount = '0';
	if (isset($_POST['wppa-album'])) $album = $_POST['wppa-album']; else $album = '0';

	wppa_ok_message(__('Processing files, please wait...', 'wppa').' '.__('If the line of dots stops growing or your browser reports Ready, your server has given up. In that case: try again', 'wppa').' <a href="'.wppa_dbg_url(get_admin_url().'admin.php?page=wppa_import_photos').'">'.__('here.', 'wppa').'</a>');
	foreach ($files as $file) {

		if (isset($_POST['file-'.$idx])) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') {
				// See if a metafile exists
				$meta = substr($file, 0, strlen($file) - 3).'pmf';
				// find all data: name, desc, porder form metafile
				if (is_file($meta)) {
					$alb = wppa_get_album_id(wppa_get_meta_album($meta));
					$name = wppa_get_meta_name($meta);
					$desc = wppa_get_meta_desc($meta);
					$porder = wppa_get_meta_porder($meta);
					$linkurl = wppa_get_meta_linkurl($meta);
					$linktitle = wppa_get_meta_linktitle($meta);
				}
				else {
					$alb = $album;	// default album
					$name = '';		// default name
					$desc = '';		// default description
					$porder = '0';	// default p_order
					$linkurl = '';
					$linktitle = '';
				}
				if (isset($_POST['wppa-update'])) { // Update the photo
					if (wppa_update_photo($file, $name)) {
						$pcount++;
						if ($delp) {
							unlink($file);
						}
					}
				} // Update
				else { // Insert the photo
					if (is_numeric($alb) && $alb != '0') {
						$id = basename($file);
						$id = substr($id, 0, strpos($id, '.'));
						if (!is_numeric($id) || !wppa_is_id_free('photo', $id)) $id = 0;
						if (wppa_insert_photo($file, $alb, stripslashes($name), stripslashes($desc), $porder, $id, stripslashes($linkurl), stripslashes($linktitle))) {

							$pcount++;
							if ($delp) {
								unlink($file);
								if (is_file($meta)) unlink($meta);
							}
						}
						else {
							wppa_error_message(__('Error inserting photo', 'wppa') . ' ' . basename($file) . '.');
//							return false;
						}
					}
					else {
						wppa_error_message(sprintf(__('Error inserting photo %s, unknown or non existent album.', 'wppa'), basename($file)));
//						return false;
					} 
				} // Insert
			}
		}
		$idx++;
	} // foreach $files
	wppa_ok_message(__('Done processing files.', 'wppa'));
	
	if ($pcount == '0' && $acount == '0' && $zcount == '0') {
		wppa_error_message(__('No files to import.', 'wppa'));
	}
	else {
		$msg = '';
		if ($zcount) $msg .= $zcount.' '.__('Zipfiles extracted.', 'wppa').' ';
		if ($acount) $msg .= $acount.' '.__('Albums created.', 'wppa').' ';
		if ($pcount) {
			if (isset($_POST['wppa-update'])) $msg .= $pcount.' '.__('Photos updated.', 'wppa').' ';
			else $msg .= $pcount.' '.__('Photos imported.', 'wppa').' '; 
		}
		wppa_ok_message($msg); 
		wppa_set_last_album($album);
	}
}

function wppa_insert_photo ($file = '', $album = '', $name = '', $desc = '', $porder = '0', $id = '0', $linkurl = '', $linktitle = '') {
	global $wpdb;
	global $warning_given_small;
	global $warning_given_big;
	global $wppa_opt;
	
	if ($file != '' && $album != '' ) {
		// Get the name if not given
		if ($name == '') $name = basename($file);
		// Get and verify the size
		$img_size = getimagesize($file);
		
		if ($img_size) { 
			if (!$warning_given_big && ($img_size['0'] > 1280 || $img_size['1'] > 1280)) {
				if (get_option('wppa_resize_on_upload', 'no') == 'yes') {
					wppa_ok_message(__('Although the photos are resized during the upload/import process, you may encounter \'Out of memory\'errors.', 'wppa') . '<br/>' . __('In that case: make sure you set the memory limit to 64M and make sure your hosting provider allows you the use of 64 Mb.', 'wppa'));
				}
				else {
					wppa_warning_message(__('WARNING: You are uploading very large photos, this may result in server problems and excessive download times for your website visitors.', 'wppa') . '<br/>' . __('Check the \'Resize on upload\' checkbox, and/or resize the photos before uploading. The recommended size is: not larger than 1024 x 768 pixels (up to approx. 250 kB).', 'wppa'));
				}
				$warning_given_big = true;
			}
			if (!$warning_given_small && ($img_size['0'] < wppa_get_minisize() && $img_size['1'] < wppa_get_minisize())) {
				wppa_warning_message(__('WARNING: You are uploading photos that are too small. Photos must be larger than the thumbnail size and larger than the coverphotosize.', 'wppa'));
				$warning_given_small = true;
			}
		}
		else {
			wppa_error_message(__('ERROR: Unable to retrieve immage size of', 'wppa').' '.$name.' '.__('Are you sure it is a photo?', 'wppa'));
			return false;
		}
		// Get ext based on mimetype, regardless of ext
		switch($img_size[2]) { 	// mime type
			case 1: $ext = 'gif'; break;
			case 2: $ext = 'jpg'; break;
			case 3: $ext = 'png'; break;
			default:
				wppa_error_message(__('Unsupported mime type encountered:', 'wppa').' '.$img_size[2].'.');
				return false;
		}
		// Get an id if not yet there
		if ($id == '0') {
			$id = wppa_nextkey(WPPA_PHOTOS);
		}
		// Get opt deflt desc if empty
		if ( $desc == '' && $wppa_opt['wppa_apply_newphoto_desc'] == 'yes' ) {
			$desc = stripslashes($wppa_opt['wppa_newphoto_description']);
		}
		// Reset rating
		$mrat = '0';
		// Find (new) owner
		$owner = wppa_get_user();
		// Validate album
		if ( !is_numeric($album) || $album < '1' ) {
			wppa_error_message(__('Album not known while trying to add a photo', 'wppa'));
			return false;
		}
		if ( !wppa_have_access($album) ) {
			wppa_error_message(sprintf(__('Album %s does not exist or is not accessable while trying to add a photo', 'wppa'), $album));
			return false;
		}
		// Add photo to db
		$query = $wpdb->prepare('INSERT INTO `' . WPPA_PHOTOS . '` (`id`, `album`, `ext`, `name`, `p_order`, `description`, `mean_rating`, `linkurl`, `linktitle`, `timestamp`, `owner`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $id, $album, $ext, $name, $porder, $desc, $mrat, $linkurl, $linktitle, time(), $owner);
		if ($wpdb->query($query) === false) {
			wppa_error_message(__('Could not insert photo. query=', 'wppa').$query);
		}
		// Make the photo files		
		if ( wppa_make_the_photo_files($file, $id, $ext) ) return true;
	}
	else {
		wppa_error_message(__('ERROR: Unknown file or album.', 'wppa'));
		return false;
	}
}

function wppa_get_zipcount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'zip') $result++;
		}
	}
	return $result;
}

function wppa_get_albumcount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'amf') $result++;
		}
	}
	return $result;
}

function wppa_get_photocount($files) {
	$result = 0;
	if ($files) {
		foreach ($files as $file) {
			$ext = strtolower(substr(strrchr($file, "."), 1));
			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif') $result++;
		}
	}
	return $result;
}

function wppa_get_meta_name($file, $opt = '') {
	return wppa_get_meta_data($file, 'name', $opt);
}
function wppa_get_meta_album($file, $opt = '') {
	return wppa_get_meta_data($file, 'albm', $opt);
}
function wppa_get_meta_desc($file, $opt = '') {
	return wppa_get_meta_data($file, 'desc', $opt);
}
function wppa_get_meta_porder($file, $opt = '') {
	return wppa_get_meta_data($file, 'pord', $opt);
}
function wppa_get_meta_linkurl($file, $opt = '') {
	return wppa_get_meta_data($file, 'lnku', $opt);
}
function wppa_get_meta_linktitle($file, $opt = '') {
	return wppa_get_meta_data($file, 'lnkt', $opt);
}

function wppa_get_meta_data($file, $item, $opt) {
	$result = '';
	$opt2 = '';
	if ($opt == '(') $opt2 = ')';
	if ($opt == '{') $opt2 = '}';
	if ($opt == '[') $opt2 = ']';
	if (is_file($file)) {
		$handle = fopen($file, "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				if (substr($buffer, 0, 5) == $item.'=') {
					if ($opt == '') $result = substr($buffer, 5, strlen($buffer)-6);
					else $result = $opt.wppa_qtrans(substr($buffer, 5, strlen($buffer)-6)).$opt2;		// Translate for display purposes only
				}
			}
			if (!feof($handle)) {
				_e('Error: unexpected fgets() fail in wppa_get_meta_data().', 'wppa');
			}
			fclose($handle);
		}
	}
	return $result;
}


function wppa_extract($path, $delz) {
// There are two reasons that we do not allow the directory structure from the zipfile to be restored.
// 1. we may have no create dir access rights.
// 2. we can not reach the pictures as we only glob the users depot and not lower.
// We extract all files to the users depot. 
// The illegal files will be deleted there by the wppa_sanitize_files routine, 
// so there is no chance a depot/subdir/destroy.php or the like will get a chance to be created.
// dus...

	$err = '0';
	if (!class_exists('ZipArchive')) {
		$err = '3';
		wppa_error_message(__('Class ZipArchive does not exist! Check your php configuration', 'wppa'));
	}
	else {
		$ext = strtolower(substr(strrchr($path, "."), 1));
		if ($ext == 'zip') {
			$zip = new ZipArchive;
			if ($zip->open($path) === true) {
				for($i = 0; $i < $zip->numFiles; $i++) {
					$filename = $zip->getNameIndex($i);
					$fileinfo = pathinfo($filename);
					copy("zip://".$path."#".$filename, WPPA_DEPOT_PATH."/".$fileinfo['basename']);
				}                  
				$zip->close();
				wppa_ok_message(__('Zipfile', 'wppa').' '.basename($path).' '.__('extracted.', 'wppa'));
				if ($delz) unlink($path);
			} else {
				wppa_error_message(__('Failed to extract', 'wppa').' '.$path);
				$err = '1';
			}
		}
		else $err = '2';
	}
	return $err;
}

function wppa_update_photo($file, $xname) {
global $wpdb;
global $allphotos;

	if ($xname == '') $name = basename($file);
	else $name = wppa_qtrans($xname);
	
	wppa_dbg_msg('Trying to update '.$name);
	// Fill the names array
	if ( ! $allphotos ) {
	wppa_dbg_msg('Filling');
		$allphotos = $wpdb->get_results($wpdb->prepare( "SELECT id, name, ext FROM ".WPPA_PHOTOS) , "ARRAY_A" );
		if ( is_array($allphotos) ) {
			$index = '0';
			$count = count($allphotos);
			while ( $index < $count ) {
				$allphotos[$index]['name'] = wppa_qtrans($allphotos[$index]['name']);
				$index++;
			}
		}
	}
	// Search
	if ( is_array($allphotos) ) {
		$index = '0';
		$count = count($allphotos);
		$hits = '0';
		$lasthit = '0';
		$ext = '';
		while ( $index < $count ) {
			if ($name == $allphotos[$index]['name']) {
				$hits++;
				$lasthit = $allphotos[$index]['id'];
				$ext = $allphotos[$index]['ext'];
			}
			$index++;
		}
	wppa_dbg_msg('Found '.$hits.' times photo '.$name.' id='.$lasthit.', ext='.$ext);
	}
	// If one, proceed
	if ( $hits == '1' ) {
		wppa_make_the_photo_files($file, $lasthit, $ext);
	}
	elseif ( $hits ) {
		wppa_error_message('Found '.$hits.' copies of photo '.$name.', update skipped');
		return false;
	}
	else {
		wppa_error_message('Photo '.$name.' not found, update skipped');
		return false;
	}
	return true;
}

