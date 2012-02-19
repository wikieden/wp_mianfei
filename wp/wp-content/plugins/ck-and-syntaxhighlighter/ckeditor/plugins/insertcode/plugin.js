CKEDITOR.plugins.add('insertcode', {
	requires: ['dialog'],
	lang:['zh-cn','en'],
	init: function(a){
		var b = a.addCommand('insertcode', new CKEDITOR.dialogCommand('insertcode'));
		a.ui.addButton('insertcode', {
			label: a.lang.insertcode,
			command: 'insertcode',
			icon: this.path + 'images/code.gif'
	});
	CKEDITOR.dialog.add('insertcode', this.path + 'dialogs/insertcode.js');
}});