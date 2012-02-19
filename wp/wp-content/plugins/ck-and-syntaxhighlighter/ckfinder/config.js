/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckfinder.com/license
*/
CKFinder.customConfig = function(config)
{
	
	config.width = '100%';
	config.height = '100%';
	filebrowserBrowseUrl = 'ckfinder/ckfinder.html';
	filebrowserImageBrowseUrl = 'ckfinder/ckfinder.html?Type=Images';
	filebrowserFlashBrowseUrl = 'ckfinder/ckfinder.html?Type=Flash';
	filebrowserUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	filebrowserImageUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	filebrowserFlashUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
};
