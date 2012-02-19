/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.plugins.add('more', {
	requires: ['dialog'],
	lang:['zh-cn','en'],
	init: function(a){
		a.addCommand( 'more', {
			exec:function(editor){
				editor.insertHtml("<!--more-->");
			}
		});
		a.ui.addButton('more', {
			label: a.lang.more,
			command: 'more',
			icon: this.path + 'images/more.gif'
		});
	}
});