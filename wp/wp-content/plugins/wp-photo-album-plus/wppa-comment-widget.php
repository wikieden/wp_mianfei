<?php
/* wppa-comment-widget.php
* Package: wp-photo-album-plus
*
* display the recent commets on photos
* Version 4.3.5
*/

class wppaCommentWidget extends WP_Widget {
    /** constructor */
    function wppaCommentWidget() {
        parent::WP_Widget(false, $name = 'Comments on Photos');	
		$widget_ops = array('classname' => 'wppa_comment_widget', 'description' => __( 'WPPA+ Comments on Photos', 'wppa') );
		$this->WP_Widget('wppa_comment_widget', __('Comments on Photos', 'wppa'), $widget_ops);
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $wppa_opt;
		global $wppa;

        extract( $args );
		
 		$widget_title = apply_filters('widget_title', empty( $instance['title'] ) ? __a('Comments on Photos', 'wppa_theme') : $instance['title']);

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );

		$page = get_option('wppa_comment_widget_linkpage', '0');
		$max = get_option('wppa_comment_count', '10');
		
		$comments = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".WPPA_COMMENTS." WHERE status = 'approved' ORDER BY timestamp DESC LIMIT %d", $max ), "ARRAY_A");

		$widget_content = "\n".'<!-- WPPA+ Comment Widget start -->';
		$maxw = get_option('wppa_comment_size', '86');
		$maxh = $maxw + 18;

		if ($comments) foreach ($comments as $comment) {
		
			// Make the HTML for current comment
			$widget_content .= "\n".'<div class="wppa-widget" style="width:'.$maxw.'px; height:'.$maxh.'px; margin:4px; display:inline; text-align:center; float:left;">'; 
			$image = $wpdb->get_row($wpdb->prepare( "SELECT * FROM `".WPPA_PHOTOS."` WHERE `id` = %s", $comment['photo'] ), "ARRAY_A" );
			if ($image) {
				$imgurl = WPPA_UPLOAD_URL . '/' . $image['id'] . '.' . $image['ext'];
				$no_album = true;//!$album;
				$tit=esc_attr(wppa_qtrans(stripslashes($comment['comment'])));
				$link       = wppa_get_imglnk_a('comwidget', $image['id'], '', $tit, $no_album);
				$file       = wppa_get_thumb_path_by_id($image['id']);
				$imgstyle_a = wppa_get_imgstyle_a($file, $maxw, 'center', 'comthumb');
				$imgstyle   = $imgstyle_a['style'];
				$width      = $imgstyle_a['width'];
				$height     = $imgstyle_a['height'];

				$imgevents = wppa_get_imgevents('thumb', $image['id'], true);	

				if ($link) $title = esc_attr(stripslashes($link['title']));
				else $title = $comment['comment'];
				if ($link) {
					if ( $link['is_url'] ) {	// Is a href
						$widget_content .= "\n\t".'<a href="'.$link['url'].'" title="'.$title.'">';
							$widget_content .= "\n\t\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.'" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
						$widget_content .= "\n\t".'</a>';
					}
					elseif ( $link['is_lightbox'] ) {
						$widget_content .= "\n\t".'<a href="'.$link['url'].'" rel="'.$wppa_opt['wppa_lightbox_name'].'[comment]" title="'.$title.'">';
							$widget_content .= "\n\t\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.'" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
						$widget_content .= "\n\t".'</a>';
					}
					else { // Is an onclick unit
						$widget_content .= "\n\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.'" '.$imgevents.' onclick="'.$link['url'].'" alt="'.esc_attr(wppa_qtrans($image['name'])).'">';					
					}
				}
				else {
					$widget_content .= "\n\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="float:right; '.$imgstyle.'" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
				}
			}
			else {
				$widget_content .= __a('Photo not found.', 'wppa_theme');
			}
			$widget_content .= "\n\t".'<span style="font-size:9px; cursor:pointer;" title="'.esc_attr($comment['comment']).'" >'.$comment['user'].'</span>';
			$widget_content .= "\n".'</div>';
		}	
		else $widget_content .= 'There are no commented photos (yet).';
		
		$widget_content .= "\n".'<!-- WPPA+ comment Widget end -->';

		echo "\n".$before_widget.$before_title.$widget_title.$after_title.$widget_content.$after_widget;
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
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
 		$widget_title = apply_filters('widget_title', empty( $instance['title'] ) ? get_option('wppa_commentwidgettitle', __('Comments on Photos', 'wppa')) : $instance['title']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $widget_title; ?>" /></p>
			<p><?php _e('You can set the behaviour of this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wppa'); ?></p>
<?php
    }

} // class wppaCommentWidget

// register wppaCommentWidget widget only if comment system is enabled
if (get_option('wppa_show_comments', 'yes') == 'yes') add_action('widgets_init', create_function('', 'return register_widget("wppaCommentWidget");'));
