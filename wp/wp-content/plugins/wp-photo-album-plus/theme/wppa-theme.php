<?php 
/* wppa_theme.php
* Package: wp-photo-album-plus
*
* display the albums/photos/slideshow in a page or post
* Version 4.0.0
*/
function wppa_theme() {

global $wppa_version; $wppa_version = '4-0-0';		// The version number of this file, please change if you modify this file
global $wppa;
global $wppa_opt;
global $wppa_show_statistics;						// Can be set to true by a custom page template

$curpage = wppa_get_curpage();						// Get the page # we are on when pagination is on, or 1
$didsome = false;									// Required initializations for pagination
$n_album_pages = '0';								// "
$n_thumb_pages = '0';								// "

wppa_container('open');																// Open container
	if ($wppa_show_statistics) wppa_statistics();									// Show statistics if set so by the page template
	wppa_breadcrumb('optional');													// Display breadcrumb navigation only if it is set in the settings page
	if (wppa_page('albums')) {														// Page 'Albums' requested
		$albums = wppa_get_albums();												// Get the albums
		if ($albums) {
			$counter_albums = '0';
			$n_album_pages = wppa_get_npages('albums', $albums);
			wppa_album_list('open');												// Open Albums sub-container
				foreach ($albums as $ta) :  global $album; $album = $ta;			// Loop the albums
					$counter_albums++;
					if (wppa_onpage('albums', $counter_albums, $curpage)) {
						$didsome = true;
						wppa_album_cover();										// Show the cover
					} // End if on page
				endforeach;
			wppa_album_list('close');												// Close Albums sub-container
		}	// If albums
 
		if ($wppa_opt['wppa_thumbtype'] != 'none') {
			$thumbs = wppa_get_thumbs();											// Get the Thumbs
		} else $thumbs = false;
		$n_thumb_pages = wppa_get_npages('thumbs', $thumbs);						// How many pages of thumbs will there be?
		if ($n_thumb_pages == '0') $thumbs = false;									// No pages: no thumbs. Maybe want covers only
		if ($didsome && wppa_is_pagination()) $thumbs = false;						// Pag on and didsome: pagebreak
		if (count($thumbs) <= wppa_get_mincount()) $thumbs = false;					// Less than treshold value
		
		if ($thumbs) {
			$counter_thumbs = '0';
			if (get_option('wppa_thumbtype', 'default') == 'ascovers') {			// Do the thumbs As covers
				wppa_thumb_list('open');											// Open Thumblist sub-container
					foreach ($thumbs as $tt) :  global $thumb; $thumb = $tt; 		// Loop the Thumbs
						$counter_thumbs++;
						if (wppa_onpage('thumbs', $counter_thumbs, $curpage - $n_album_pages)) {
							$didsome = true;
							wppa_thumb_ascover();									// Show Thumb as cover
						} // End if on page
					endforeach; 
				wppa_thumb_list('close');											// Close Thumblist sub-container
			}	// As covers
			else {																	// Do the thumbs As default
				wppa_thumb_area('open');											// Open Thumbarea sub-container
					foreach ($thumbs as $tt) :  global $thumb; $thumb = $tt; 		// Loop the Thumbs
						$counter_thumbs++;
						if (wppa_onpage('thumbs', $counter_thumbs, $curpage - $n_album_pages)) {
							$didsome = true;
							wppa_thumb_default();									// Show Thumb as default
						}	// End if on page
					endforeach; 
					wppa_popup();													// Prepare Popup box
				wppa_thumb_area('close');											// Close Thumbarea sub-container
			}	// As default
		}	// If thumbs
	
		if (!wppa_is_pagination()) $totpag = '1';									// If both pagination is off, there is only one page
		else $totpag = $n_album_pages + $n_thumb_pages;	

		wppa_page_links($totpag, $curpage);											// Show pages navigaion bar if needed
	
		if (!$didsome && $wppa['src']) {
			$wppa['out'] .= '<div class="center">'.__a('No albums or photos found matching your search criteria.', 'wppa_theme').'</div>';
		}
	} // wppa_page('albums')
	
	elseif (wppa_page('oneofone')) {												// Page 'Single image' requested
		wppa_slide_frame();															// Setup slideframe
		wppa_run_slidecontainer('single');											// Fill in the photo and display it
	} // wppa_page('oneofone')
	
	elseif (wppa_page('slide') || wppa_page('single')) {							// Page 'Slideshow' or 'Single' in browsemode requested
		wppa_the_slideshow();														// Producs all the html required for the slideshow
		wppa_run_slidecontainer('slideshow');										// Fill in the photo array and display it.
	} // wppa_page('slide')
wppa_container('close');
}
