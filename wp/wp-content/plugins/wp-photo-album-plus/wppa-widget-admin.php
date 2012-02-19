<?php
/* wppa_widgetadmin.php
* Pachkage: wp-photo-album-plus
*
* admin sidebar widget
* version 4.3.6
*
*/

function _wppa_sidebar_page_options() {
	global $wpdb;
	
	$options_error = false;
	
	if (isset($_GET['walbum'])) update_option('wppa_widget_album', wppa_walbum_sanitize($_GET['walbum']));
		
	if (isset($_POST['wppa-set-submit'])) {
		wppa_check_admin_referer( '$wppa_nonce', WPPA_NONCE );
		
		update_option('wppa_widgettitle', $_POST['wppa-widgettitle']);
		
		if (wppa_check_numeric($_POST['wppa-widget-width'], '100', __('Widget Photo Width.'))) {
			update_option('wppa_widget_width', $_POST['wppa-widget-width']);
		} else {
			$options_error = true;
		}
		
		if (isset($_POST['wppa-potd-align'])) update_option('wppa_potd_align', $_POST['wppa-potd-align']);
		if (isset($_POST['wppa-widget-albums'])) update_option('wppa_widget_album', wppa_walbum_sanitize($_POST['wppa-widget-albums']));
		if (isset($_POST['wppa-widget-photo'])) update_option('wppa_widget_photo', $_POST['wppa-widget-photo']);
		if (isset($_POST['wppa-widget-method'])) update_option('wppa_widget_method', $_POST['wppa-widget-method']);
		if (isset($_POST['wppa-widget-period'])) update_option('wppa_widget_period', $_POST['wppa-widget-period']);
		if (isset($_POST['wppa-widget-subtitle'])) update_option('wppa_widget_subtitle', $_POST['wppa-widget-subtitle']);
		if (isset($_POST['wppa-widget-linkpage'])) update_option('wppa_widget_linkpage', $_POST['wppa-widget-linkpage']);
		
	//		if ($_POST['wppa-widget-linkpage'] == '-1') {
				if (isset($_POST['wppa-widget-linkurl'])) update_option('wppa_widget_linkurl', $_POST['wppa-widget-linkurl']);
				if (isset($_POST['wppa-widget-linktitle'])) update_option('wppa_widget_linktitle', $_POST['wppa-widget-linktitle']);
	//		}
	//	}
		if (isset($_POST['wppa-widget-linktype'])) update_option('wppa_widget_linktype', $_POST['wppa-widget-linktype']);
		if (!$options_error) wppa_update_message(__('Changes Saved. Don\'t forget to activate the widget!', 'wppa')); 
	} ?>
	
	<div class="wrap">
		<?php $iconurl = WPPA_URL.'/images/settings32.png'; ?>
		<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
			<br />
		</div>
		<h2><?php _e('WP Photo Album Plus Sidebar Widget Settings', 'wppa'); ?></h2>
		
		<form action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_sidebar_options')) ?>" method="post">
			<?php wppa_nonce_field('$wppa_nonce', WPPA_NONCE); ?>

			<table class="form-table albumtable">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label ><?php _e('Widget Title:', 'wppa'); ?></label>
						</th>
						<td>
							<input type="text" name="wppa-widgettitle" id="wppa-widgettitle" value="<?php echo(get_option('wppa_widgettitle', __('Photo of the day', 'wppa'))); ?>" />
							<span class="description"><br/><?php _e('Enter/modify the title for the widget. This is a default and can be overriden at widget activation.', 'wppa'); ?></span>
						</td>
					</tr>				
					<tr valign="top">
						<th scope="row">
							<label ><?php _e('Widget Photo Width:', 'wppa'); ?></label>
						</th>
						<td>
							<input type="text" name="wppa-widget-width" id="wppa-widget-width" value="<?php echo(get_option('wppa_widget_width', '200')); ?>" style="width: 50px;" />
							<?php _e('pixels.', 'wppa'); echo(' '); _e('Horizontal alignment:', 'wppa'); ?>
							<select name="wppa-potd-align" id="wppa-potd-align">
								<?php $ali = get_option('wppa_potd_align') ?>
								<?php $sel = 'selected="selected"'; ?>
								<option value="none" <?php if ($ali == 'none') echo($sel) ?>><?php _e('--- none ---', 'wppa') ?></option>
								<option value="left" <?php if ($ali == 'left') echo($sel) ?>><?php _e('left', 'wppa') ?></option>
								<option value="center" <?php if ($ali == 'center') echo($sel) ?>><?php _e('center', 'wppa') ?></option>
								<option value="right" <?php if ($ali == 'right') echo($sel) ?>><?php _e('right', 'wppa') ?></option>
							</select>
							<span class="description"><br/><?php _e('Enter the desired display width and alignment of the photo in the sidebar.', 'wppa'); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label ><?php _e('Use album(s):', 'wppa'); ?></label>
						</th>
						<td>
							<script type="text/javascript">
							/* <![CDATA[ */
							function wppaCheckWa() {
								document.getElementById('wppa-spin').style.visibility = 'visible';
								document.getElementById('wppa-upd').style.visibility = 'hidden';
								var album = document.getElementById('wppa-wa').value;
								if ( album != 'all' && album != 'sep' && album != 'all-sep' && album != 'clr' )
									album = document.getElementById('wppa-was').value + ',' + album;
								var url = "<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_sidebar_options')) ?>&walbum=" + album;
								document.location.href = url;
							}
							/* ]]> */
							</script>
							<?php _e('Select:', 'wppa'); ?><select name="wppa-widget-album" id="wppa-wa" onchange="wppaCheckWa()" ><?php echo(wppa_walbum_select(get_option('wppa_widget_album', ''))) ?></select>
							<img id="wppa-spin" src="<?php echo(wppa_get_imgdir()); ?>wpspin.gif" style="visibility:hidden;"/>
							<?php _e('Or Edit:', 'wppa'); ?><input type="text" name="wppa-widget-albums" id="wppa-was" value="<?php echo(get_option('wppa_widget_album', '')); ?>" />
							<input class="button-primary" name="wppa-upd" id="wppa-upd" value="<?php _e('Update thumbnails', 'wppa'); ?>" onclick="wppaCheckWa()" />
							<span class="description"><br/><?php _e('Select or edit the album(s) you want to use the photos of for the widget.', 'wppa'); ?></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label ><?php _e('Display method:', 'wppa'); ?></label>
						</th>
						<td>
							<?php $sel = 'selected="selected"'; ?>
							<?php $method = get_option('wppa_widget_method', '1'); ?>
							<select name="wppa-widget-method" id="wppa-wm" onchange="wppaCheckWidgetMethod()" >
								<option value="1" <?php if ($method == '1') echo($sel); ?>><?php _e('Fixed photo', 'wppa'); ?></option> 
								<option value="2" <?php if ($method == '2') echo($sel); ?>><?php _e('Random', 'wppa'); ?></option>
								<option value="3" <?php if ($method == '3') echo($sel); ?>><?php _e('Last upload', 'wppa'); ?></option>
								<option value="4" <?php if ($method == '4') echo($sel); ?>><?php _e('Change every', 'wppa'); ?></option>
							</select>
							<?php $period = get_option('wppa_widget_period', '168'); ?>
							<select name="wppa-widget-period" id="wppa-wp" >
								<option value="0" <?php if ($period == '0') echo($sel); ?>><?php _e('pageview.', 'wppa'); ?></option>
								<option value="1" <?php if ($period == '1') echo($sel); ?>><?php _e('hour.', 'wppa'); ?></option>
								<option value="24" <?php if ($period == '24') echo($sel); ?>><?php _e('day.', 'wppa'); ?></option>
								<option value="168" <?php if ($period == '168') echo($sel); ?>><?php _e('week.', 'wppa'); ?></option>
							</select>
							<span class="description"><br/><?php _e('Select how the widget should display.', 'wppa'); ?></span>								
						</td>
					</tr>
<?php
					$linktype = get_option('wppa_widget_linktype', 'album');
					if ($linktype != 'custom') { ?>
						<tr>
							<th scope="row">
								<label ><?php _e('Link to:', 'wppa'); ?></label>
							</th>
							<td>
								<?php _e('Links are set on the <b>Photo Albums -> Settings</b> screen.', 'wppa'); ?>
							</td>
						</tr>
<?php				} 
					else { ?>
						<tr class="wppa-wlu" >
							<th scope="row">
								<label ><?php _e('Link to:', 'wppa'); ?></label>
							</th>
							<td>
								<?php _e('Title:', 'wppa') ?>
								<input type="text" name="wppa-widget-linktitle" id="wppa-widget-linktitle" value="<?php echo(get_option('wppa_widget_linktitle', __('Type the title here', 'wppa'))) ?>"style="width:20%" />
								<?php _e('Url:', 'wppa') ?>
								<input type="text"  name="wppa-widget-linkurl" id="wppa-widget-linkurl" value="<?php echo(get_option('wppa_widget_linkurl', __('Type your custom url here', 'wppa'))) ?>" style="width:50%" />
								<span class="description"><br/><?php _e('Enter the title and the url. Do\'nt forget the HTTP://', 'wppa') ?></span>
							</td>
						</tr>
<?php 				} ?>
					<!--<script type="text/javascript">wppaCheckWidgetLink()</script>-->
					<tr>
						<th scope="row">
							<label ><?php _e('Subtitle:', 'wppa'); ?></label>
						</th>
						<td>
							<?php $subtit = get_option('wppa_widget_subtitle', 'none'); ?>
							<select name="wppa-widget-subtitle" id="wppa-st" onchange="wppaCheckWidgetSubtitle()" >
								<option value="none" <?php if ($subtit == 'none') echo($sel); ?>><?php _e('--- none ---', 'wppa'); ?></option>
								<option value="name" <?php if ($subtit == 'name') echo($sel); ?>><?php _e('Photo Name', 'wppa'); ?></option>
								<option value="desc" <?php if ($subtit == 'desc') echo($sel); ?>><?php _e('Description', 'wppa'); ?></option>
							</select>
							<span class="description"><br/><?php _e('Select the content of the subtitle.', 'wppa'); ?></span>	
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<input type="submit" class="button-primary" name="wppa-set-submit" value="<?php _e('Save Changes', 'wppa'); ?>" />
			</p>
			<?php $alb = get_option('wppa_widget_album', '0');
			$photos = wppa_get_widgetphotos($alb);
			if (empty($photos)) { ?>
				<p><?php _e('No photos yet in this album.', 'wppa'); ?></p>
			<?php } 
			else {
				$id = get_option('wppa_widget_photo', '');
				$wi = wppa_get_minisize() + 24;
				$hi = $wi + 48;
				foreach ($photos as $photo) { ?>
					<div class="photoselect" style="width: <?php echo(get_option('wppa_widget_width', '200')+'12'); ?>px; height: <?php echo($hi); ?>px; overflow:hidden; " >
						<img src="<?php echo(WPPA_UPLOAD_URL.'/thumbs/' . $photo['id'] . '.' . $photo['ext']); ?>" alt="<?php echo($photo['name']); ?>"></img>
						<input type="radio" name="wppa-widget-photo" id="wppa-widget-photo<?php echo($photo['id']); ?>" value="<?php echo($photo['id']) ?>" <?php if ($photo['id'] == $id) echo('checked="checked"'); ?>/>
						<div class="clear"></div>
						<h4 style="position: absolute; top:<?php echo( $wi - 12 ); ?>px; font-size:11px; overflow:hidden;"><?php echo(wppa_qtrans(stripslashes($photo['name']))) ?></h4>
						<h6 style="position: absolute; top:<?php echo( $wi + 6); ?>px; font-size:9px; line-height:10px;"><?php echo(wppa_qtrans(stripslashes($photo['description']))); ?></h6>
					</div>
				<?php } ?>
				<div class="clear"></div>
			<?php } ?>
			<script type="text/javascript">wppaCheckWidgetMethod();</script>
			<script type="text/javascript">wppaCheckWidgetSubtitle();</script>
			<br />
			<p>
				<input type="submit" class="button-primary" name="wppa-set-submit" value="<?php _e('Save Changes', 'wppa'); ?>" />
			</p>
		</form>
	</div>
<?php
}

require_once ('wppa-widget-functions.php');
