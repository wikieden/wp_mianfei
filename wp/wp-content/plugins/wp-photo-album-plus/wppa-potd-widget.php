<?php
/* wppa-potd-widget.php
* Package: wp-photo-album-plus
*
* display the widget
* Version 4.3.6
*/

class PhotoOfTheDay extends WP_Widget {
    /** constructor */
    function PhotoOfTheDay() {
        parent::WP_Widget(false, $name = 'Photo Of The Day');	
		$widget_ops = array('classname' => 'wppa_widget', 'description' => __( 'WPPA+ Photo Of The Day', 'wppa') );	//
		$this->WP_Widget('wppa_widget', __('Photo Of The Day', 'wppa'), $widget_ops);															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $wppa_opt;

        extract( $args );

		$widget_title = $instance['title'];

		// get the photo  ($image)
		$image = wppa_get_potd();
		
		// Make the HTML for current picture
		$widget_content = "\n".'<!-- WPPA+ Photo of the day Widget start -->';

		$ali = $wppa_opt['wppa_potd_align'];
		if ($ali != 'none') {
			$align = 'text-align:'.$ali.';';
		}
		else $align = '';
		$widget_content .= "\n".'<div class="wppa-widget-photo" style="'.$align.' padding-top:2px; ">';
		if ($image) {
			// make image url
			$imgurl = WPPA_UPLOAD_URL . '/' . $image['id'] . '.' . $image['ext'];
		
			$name = wppa_qtrans($image['name']);
			$link = wppa_get_imglnk_a('potdwidget', $image['id']);
			
			if ($link) $widget_content .= "\n\t".'<a href = "'.$link['url'].'" rel="'.$wppa_opt['wppa_lightbox_name'].'" title="'.$link['title'].'">';
			
				$widget_content .= "\n\t\t".'<img src="'.$imgurl.'" style="width: '.get_option('wppa_widget_width', '190').'px;" alt="'.$name.'" />';

			if ($link) $widget_content .= "\n\t".'</a>';
		} 
		else {	// No image
			$widget_content .= __a('Photo not found.', 'wppa_theme');
		}
		$widget_content .= "\n".'</div>';
		// Add subtitle, if any		
		switch (get_option('wppa_widget_subtitle', 'none'))
		{
			case 'none': 
				break;
			case 'name': 
				if ($image && $image['name'] != '') {
					$widget_content .= "\n".'<div class="wppa-widget-text" style="'.$align.'">' . wppa_qtrans(wppa_html(stripslashes($image['name']))) . '</div>';
				}
				break;
			case 'desc': 
				if ($image && $image['description'] != '') {
					$widget_content .= "\n".'<div class="wppa-widget-text" style="'.$align.'">' . wppa_qtrans(wppa_html(stripslashes($image['description']))) . '</div>'; 
				}
				break;
		}

		$widget_content .= "\n".'<!-- WPPA+ Photo of the day Widget end -->';

		echo "\n" . $before_widget . $before_title . $widget_title . $after_title . $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		//Defaults
		$instance = wp_parse_args( (array) $instance, array(  'title' => get_option('wppa_widgettitle', __( 'Photo Of The Day', 'wppa' ))) );
		$widget_title = $instance['title']; 
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $widget_title; ?>" /></p>
			<p><?php _e('You can set the content and the behaviour of this widget in the <b>Photo Albums -> Sidebar Widget</b> admin page.', 'wppa'); ?></p>
		<?php
    }

} // class PhotoOfTheDay

require_once ('wppa-widget-functions.php');

// register PhotoOfTheDay widget
add_action('widgets_init', create_function('', 'return register_widget("PhotoOfTheDay");'));
