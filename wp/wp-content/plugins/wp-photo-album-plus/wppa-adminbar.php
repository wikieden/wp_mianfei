<?php
/* wppa-adminbar.php
* Package: wp-photo-album-plus
*
* enhances the admin bar with wppa+ menu
* version 4.2.8
*
*/

add_action( 'admin_bar_menu', 'wppa_admin_bar_menu', 97 );

function wppa_admin_bar_menu() {
	global $wp_admin_bar;
	global $wpdb;
		
	$wppaplus = 'wppa-admin-bar';

	$menu_items = false;
	
	$pend = $wpdb->get_results($wpdb->prepare( "SELECT id FROM ".WPPA_COMMENTS." WHERE status='pending'"), "ARRAY_A" );
	if ( $pend ) $pending = '&nbsp;<span id="ab-awaiting-mod" class="pending-count">'.count($pend).'</span>';
	else $pending = '';
	
	if ( current_user_can( 'wppa_admin' ) ) {
		$menu_items['admin'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Photo Albums', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_admin_menu' )
		);
	}
	if ( current_user_can( 'wppa_upload' ) ) {
		$menu_items['upload'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Upload Photos', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_upload_photos' )
		);
	}
	if ( current_user_can( 'wppa_upload' ) ) {
		$menu_items['import'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Import Photos', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_import_photos' )
		);
	}
	if ( current_user_can( 'administrator' ) ) {
		$menu_items['export'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Export Photos', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_export_photos' )
		);
	}
	if ( current_user_can( 'administrator' ) ) {
		$menu_items['settings'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Settings', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_options' )
		);
	}
	if ( current_user_can( 'wppa_sidebar_admin' ) ) {
		$menu_items['sidebar'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Photo of the day', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_sidebar_options' )
		);
	}
	if ( current_user_can( 'administrator' ) ) {
		$menu_items['comments'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Comments', 'wppa_theme' ).$pending,
			'href'   => admin_url( 'admin.php?page=wppa_manage_comments' )
		);
	}
	if ( current_user_can( 'edit_posts' ) ) {
		$menu_items['help'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Help & Info', 'wppa_theme' ),
			'href'   => admin_url( 'admin.php?page=wppa_help' )
		);
	}
	if ( current_user_can( 'edit_posts' ) ) {
		$menu_items['opajaap'] = array(
			'parent' => $wppaplus,
			'title'  => __( 'Docs & Demos', 'wppa_theme' ),
			'href'   => 'http://wppa.opajaap.nl'
		);
	}
	
		
	// Add top-level item
	$wp_admin_bar->add_menu( array(
		'id'    => $wppaplus,
		'title' => __( 'Photo Albums', 'wppa_theme' ).$pending,
		'href'  => ''
	) );

	// Loop through menu items
	if ( $menu_items ) foreach ( $menu_items as $id => $menu_item ) {
		
		// Add in item ID
		$menu_item['id'] = 'wppa-' . $id;

		// Add meta target to each item where it's not already set, so links open in new tab
		if ( ! isset( $menu_item['meta']['target'] ) )		
			$menu_item['meta']['target'] = '_blank';

		// Add class to links that open up in a new tab
		if ( '_blank' === $menu_item['meta']['target'] ) {
			if ( ! isset( $menu_item['meta']['class'] ) )
				$menu_item['meta']['class'] = '';
			$menu_item['meta']['class'] .= 'wppa-' . 'new-tab';
		}

		// Add item
		$wp_admin_bar->add_menu( $menu_item );
	}		
}