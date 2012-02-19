<?php
/* wppa-slideshow-widget.php
* Package: wp-photo-album-plus
*
* display a slideshow in the sidebar
* Version 4.3.3
*/

/**
 * SlideshowWidget Class
 */
class SlideshowWidget extends WP_Widget {
    /** constructor */
    function SlideshowWidget() {
        parent::WP_Widget(false, $name = 'Sidebar Slideshow');	
		$widget_ops = array('classname' => 'slideshow_widget', 'description' => __( 'WPPA+ Sidebar Slideshow', 'wppa') );	//
		$this->WP_Widget('slideshow_widget', __('Sidebar Slideshow', 'wppa'), $widget_ops);															
		
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $wppa; 
		global $wppa_opt;

        extract( $args );

		$instance = wp_parse_args( (array) $instance, 
									array( 	'title' 	=> '', 
											'album' 	=> '', 
											'width' 	=> $wppa_opt['wppa_widget_width'], 
											'height' 	=> round( $wppa_opt['wppa_widget_width'] * $wppa_opt['wppa_maxheight'] / $wppa_opt['wppa_fullsize'] ),
											'ponly' 	=> 'no', 
											'linkurl' 	=> '', 
											'linktitle' => '', 
											'subtext' 	=> '', 
											'supertext' => '', 
											'valign' 	=> 'center', 
											'timeout' 	=> '4', 
											'film' 		=> 'no', 
											'browse' 	=> 'no', 
											'name' 		=> 'no', 
											'numbar'	=> 'no',
											'desc' 		=> 'no' 
											) );
		$title 		= $instance['title'];
		$album 		= $instance['album'];
		$width 		= $instance['width'];
		$height		= $instance['height'];
		$ponly 		= $instance['ponly'];
		$linkurl 	= $instance['linkurl'];
		$linktitle 	= $instance['linktitle'];
		$supertext 	= wppa_qtrans($instance['supertext']);
		$subtext 	= wppa_qtrans($instance['subtext']);
		$valign 	= $instance['valign'];
		$timeout	= $instance['timeout'] * 1000;
		$film 		= $instance['film'];
		$browse 	= $instance['browse'];
		$name 		= $instance['name'];
		$numbar		= $instance['numbar'];		
		$desc 		= $instance['desc'];
		
		if (is_numeric($album)) {
			echo $before_widget . $before_title . $title . $after_title;
				if ($linkurl != '' && get_option('wppa_slideonly_widget_linktype') == 'widget') {
					$wppa['in_widget_linkurl'] = $linkurl;
					$wppa['in_widget_linktitle'] = wppa_qtrans($linktitle);
				}
				if ($supertext != '') {
					echo '<div style="padding-top:2px; padding-bottom:4px; text-align:center">'.$supertext.'</div>';
				}
				echo '<div style="padding-top:2px; padding-bottom:4px;" >';
					$wppa['in_widget'] 			= 'ss';
					$wppa['in_widget_frame_height'] = $height;
					$wppa['in_widget_timeout'] 	= $timeout;
					$wppa['portrait_only'] = ($ponly == 'yes');
					$wppa['ss_widget_valign'] = $valign;
					$wppa['film_on'] = ($film == 'yes');
					$wppa['browse_on'] = ($browse == 'yes');
					$wppa['name_on'] = ($name == 'yes');
					$wppa['numbar_on'] = ($numbar == 'yes');
					$wppa['desc_on'] = ($desc == 'yes');
						echo wppa_albums($album, 'slideonly', $width, 'center');
					$wppa['desc_on'] = false;
					$wppa['numbar_on'] = false;
					$wppa['name_on'] = false;
					$wppa['browse_on'] = false;
					$wppa['film_on'] = false;
					$wppa['ss_widget_valign'] = '';
					$wppa['portrait_only'] = false;
					$wppa['in_widget_timeout'] = '0';
					$wppa['in_widget_frame_height'] = '';
					$wppa['in_widget'] = false;
					
					$wppa['fullsize'] = '';	// Reset to prevent inheritage of wrong size in case widget is rendered before main column
				echo '</div>';
				if ($linkurl != '') {
					$wppa['in_widget_linkurl'] = '';
					$wppa['in_widget_linktitle'] = '';
				}
				if ($subtext != '') {
					echo '<div style="padding-top:2px; padding-bottom:0px; text-align:center">'.$subtext.'</div>';
				}
			echo $after_widget;
		}
		else {
			echo $before_widget . $before_title . $title . $after_title;
			echo __a('No album defined yet.', 'wppa_theme');
			echo $after_widget;
		}

		//echo $before_widget . $before_title . $title . $after_title . $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['album'] = $new_instance['album'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['ponly'] = $new_instance['ponly'];
		$instance['linkurl'] = $new_instance['linkurl'];
		$instance['linktitle'] = $new_instance['linktitle'];
		$instance['supertext'] = $new_instance['supertext'];
		$instance['subtext'] = $new_instance['subtext'];
		if ($instance['ponly'] == 'yes') {
			$instance['valign'] = 'fit';
		}
		else {
			$instance['valign'] = $new_instance['valign'];
		}
		$instance['timeout'] = $new_instance['timeout'];
		$instance['film'] = $new_instance['film'];
		$instance['browse'] = $new_instance['browse'];
		$instance['name'] = $new_instance['name'];
		$instance['numbar'] = $new_instance['numbar'];
		$instance['desc'] = $new_instance['desc'];
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	
		global $wppa_opt;
		//Defaults
		$instance = wp_parse_args( (array) $instance,
									array( 	'title' 	=> apply_filters('widget_title', __( 'Sidebar Slideshow', 'wppa' )),
											'album' 	=> '', 
											'width' 	=> $wppa_opt['wppa_widget_width'], 
											'height' 	=> round( $wppa_opt['wppa_widget_width'] * $wppa_opt['wppa_maxheight'] / $wppa_opt['wppa_fullsize'] ),
											'ponly' 	=> 'no', 
											'linkurl' 	=> '', 
											'linktitle' => '', 
											'subtext' 	=> '', 
											'supertext' => '', 
											'valign' 	=> 'center', 
											'timeout' 	=> '4', 
											'film' 		=> 'no', 
											'browse' 	=> 'no', 
											'name' 		=> 'no', 
											'numbar'	=> 'no',
											'desc' 		=> 'no' 
											) );
											
		$title = esc_attr( $instance['title'] );
		$album = $instance['album'];
		$width = $instance['width'];
		$height = $instance['height'];
		$ponly = $instance['ponly'];
		$linkurl = $instance['linkurl'];
		$linktitle = $instance['linktitle'];
		$supertext = $instance['supertext'];
		$subtext = $instance['subtext'];
		$valign = $instance['valign'];
		$timeout = $instance['timeout'];
		$film = $instance['film'];
		$browse = $instance['browse'];
		$name = $instance['name'];
		$numbar = $instance['numbar'];
		$desc = $instance['desc'];
		
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('album'); ?>"><?php _e('Album:', 'wppa'); ?></label> <select id="<?php echo $this->get_field_id('album'); ?>" name="<?php echo $this->get_field_name('album'); ?>"><?php echo(wppa_album_select('', $album)) ?></select></p>
		<p><?php _e('Enter the width and optionally the height of the area wherein the slides will appear. If you specify a 0 for the height, it will be calculated. The value for the height will be ignored if you set the vertical alignment to \'fit\'.', 'wppa') ?></p>
		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'wppa'); ?></label> <input class="widefat" style="width:15%;" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />&nbsp;<?php _e('pixels.', 'wppa') ?>
		<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'wppa'); ?></label> <input class="widefat" style="width:15%;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />&nbsp;<?php _e('pixels.', 'wppa') ?></p>
		<p>
			<?php _e('Portrait only:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('ponly'); ?>" name="<?php echo $this->get_field_name('ponly'); ?>">
				<option value="no" <?php if ($ponly == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($ponly == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>&nbsp;<?php _e('Set to \'yes\' if there are only portrait images in the album and you want the photos to fill the full width of the widget.<br/>Set to \'no\' otherwise.', 'wppa') ?>
			&nbsp;<?php _e('If set to \'yes\', Vertical alignment will be forced to \'fit\'.', 'wppa') ?>
		</p>
		<p>
			<?php _e('Vertical alignment:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('valign'); ?>" name="<?php echo $this->get_field_name('valign'); ?>">
				<option value="top" <?php if ($valign == 'top') echo(' selected '); ?>><?php _e('top', 'wppa'); ?></option>
				<option value="center" <?php if ($valign == 'center') echo(' selected '); ?>><?php _e('center', 'wppa'); ?></option>
				<option value="bottom" <?php if ($valign == 'bottom') echo(' selected '); ?>><?php _e('bottom', 'wppa'); ?></option>
				<option value="fit" <?php if ($valign == 'fit') echo(' selected '); ?>><?php _e('fit', 'wppa'); ?></option>	
			</select><br/><?php _e('Set the desired vertical alignment method.', 'wppa'); ?>
		</p>
		<p><label for="<?php echo $this->get_field_id('timeout'); ?>"><?php _e('Slideshow timeout:', 'wppa'); ?></label> <input class="widefat" style="width:15%;" id="<?php echo $this->get_field_id('timeout'); ?>" name="<?php echo $this->get_field_name('timeout'); ?>" type="text" value="<?php echo $timeout; ?>" />&nbsp;<?php _e('sec.', 'wppa'); ?></p>
		<p><label for="<?php echo $this->get_field_id('linkurl'); ?>"><?php _e('Link to:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('linkurl'); ?>" name="<?php echo $this->get_field_name('linkurl'); ?>" type="text" value="<?php echo $linkurl; ?>" /></p>

		<p>
			<?php _e('Show name:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>">
				<option value="no" <?php if ($name == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($name == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>
		<p>
			<?php _e('Show description:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>">
				<option value="no" <?php if ($desc == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($desc == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>
		<p>
			<?php _e('Show filmstrip:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('film'); ?>" name="<?php echo $this->get_field_name('film'); ?>">
				<option value="no" <?php if ($film == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($film == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>
		<p>
			<?php _e('Show browsebar:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('browse'); ?>" name="<?php echo $this->get_field_name('browse'); ?>">
				<option value="no" <?php if ($browse == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($browse == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>
		<p>
			<?php _e('Show numbar:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('numbar'); ?>" name="<?php echo $this->get_field_name('numbar'); ?>">
				<option value="no" <?php if ($numbar == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($numbar == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>

		<p><span style="color:blue"><small><?php _e('The following text fields support qTranslate', 'wppa') ?></small></span></p>
		<p><label for="<?php echo $this->get_field_id('linktitle'); ?>"><?php _e('Tooltip text:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('linktitle'); ?>" name="<?php echo $this->get_field_name('linktitle'); ?>" type="text" value="<?php echo $linktitle; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('supertext'); ?>"><?php _e('Text above photos:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('supertext'); ?>" name="<?php echo $this->get_field_name('supertext'); ?>" type="text" value="<?php echo $supertext; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('subtext'); ?>"><?php _e('Text below photos:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('subtext'); ?>" name="<?php echo $this->get_field_name('subtext'); ?>" type="text" value="<?php echo $subtext; ?>" /></p>
		
<?php
    }

} // class SlideshowWidget

// register SlideshowWidget widget
add_action('widgets_init', create_function('', 'return register_widget("SlideshowWidget");'));
?>
