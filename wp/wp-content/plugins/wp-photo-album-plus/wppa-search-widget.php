<?php
/* wppa-searchwidget.php
* Package: wp-photo-album-plus
*
* display the search widget
* Version 4.1.0
*
*/

class SearchPhotos extends WP_Widget {
    /** constructor */
    function SearchPhotos() {
        parent::WP_Widget(false, $name = 'Search Photos');	
		$widget_ops = array('classname' => 'wppa_search_photos', 'description' => __( 'WPPA+ Search Photos', 'wppa') );	//
		$this->WP_Widget('wppa_search_photos', __('Search Photos', 'wppa'), $widget_ops);															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $widget_content;
		global $wppa;

        extract( $args );
        
 		$widget_title = apply_filters('widget_title', empty( $instance['title'] ) ? get_option('wppa_searchwidgettitle', __('Search photos', 'wppa')) : $instance['title']);

		// Display the widget
		echo $before_widget . $before_title . $widget_title . $after_title;
		
		$page = get_option('wppa_search_linkpage', '0');
		if ($page == '0') {
			_e('Warning. No page defined for search results!', 'wppa');
		}
		else {
			$pagelink = wppa_dbg_url(get_page_link($page));
?>
			<form id="wppa_searchform" action="<?php echo($pagelink) ?>" method="post" class="widget_search">
				<div>
					<input type="text" name="wppa-searchstring" id="wppa_s" value="<?php echo $wppa['searchstring'] ?>" />
					<input id = "wppa_searchsubmit" type="submit" value="<?php _e('Search', 'wppa'); ?>" />
				</div>
			</form>
<?php
		}
		
		echo $after_widget;
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
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
<?php
    }

} // class SearchPhotos

// register SearchPhotos widget
add_action('widgets_init', create_function('', 'return register_widget("SearchPhotos");'));

