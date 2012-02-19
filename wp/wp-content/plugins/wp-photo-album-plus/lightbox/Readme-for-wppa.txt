On upgrade of lightbox check the paths to loading.gif and closelabel.gif. See example below

lightbox.js line 45:

//
//  Configurationl
//
LightboxOptions = Object.extend({
    fileLoadingImage:        'wp-content/plugins/wp-photo-album-plus/lightbox/images/loading.gif',     
    fileBottomNavCloseImage: 'wp-content/plugins/wp-photo-album-plus/lightbox/images/closelabel.gif',

	
as of version 4.0.8:

The css file lightbox.css and the config part of lightbox.js are integrated in wppa-non-admin.php
This enables configurability.

The original files are kept for reference