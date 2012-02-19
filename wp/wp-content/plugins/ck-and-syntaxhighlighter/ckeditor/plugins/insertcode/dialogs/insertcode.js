CKEDITOR.dialog.add('insertcode', function(editor){
	var escape = function(value){return value;};
	l = editor.lang;
	return {
		title: l.insertcode,
		resizable: CKEDITOR.DIALOG_RESIZE_BOTH,
		minWidth: 580,
		minHeight: 400,
		style: 'font-size:14px',
		contents: [{
			id: 'cb',
			name: 'cb',
			label: 'cb',
			title: 'cb',
			elements: [{
				type: 'select',
				label: l.selectLang,
				id: 'lang',
				required: true,
				'default': 'csharp',
				items: [['shell','shell'],['ActionScript3', 'as3'], ['Bash/shell', 'bash'], ['C#', 'csharp'], ['C++', 'cpp'], ['CSS', 'css'], ['Delphi', 'delphi'], ['Diff', 'diff'], ['Groovy', 'groovy'], ['Html', 'xhtml'], ['JavaScript', 'js'], ['Java', 'java'], ['JavaFX', 'jfx'], ['Perl', 'perl'], ['PHP', 'php'], ['Plain Text', 'plain'], ['PowerShell', 'ps'], ['Python', 'py'], ['Ruby', 'rails'], ['Scala', 'scala'], ['SQL', 'sql'], ['Visual Basic', 'vb'], ['XML', 'xml']] 
            },
			{
				type: 'textarea',
				style: 'width:100%;height:100%',
				rows:15,
				label: l.insertcode,
				id: 'code',
				'default': ''
			},
			{
				type: 'hbox',
				widths: ['15%', '15%', '25%', '45%'],
				style: 'padding:0;margin:0;',
				children:[
					{
						type: 'text',
						label: l.firstLine,
						id:'firstLine',
						style: 'width:35px;',
						'default':'1'
					},
					{
						type: 'text',
						label: l.highlighter,
						id:'highlight',
						style: 'width:120px;',
						'default':'null'
					},
					{
						type: 'radio',
						label: l.balancing,
						id:'padLineNumbers',
						'default':'true',
						items: [[l.balancing_Yes,'true'],[l.balancing_No, 'false']]
					},
					{
						type: 'radio',
						label: l.collapse,
						id:'collapse',
						'default':'false',
						items: [[l.collapse_Yes,'true'],[l.collapse_No, 'false']]
					},
					{
						type: 'text',
						label: l.codeTitle,
						id:'title',
						style: 'width:100px;',
						'default':''
					}
				]
			}]
		}],
		onOk: function(){
			code = this.getValueOf('cb', 'code');
			lang = this.getValueOf('cb', 'lang');
			
			title = this.getValueOf('cb', 'title');
			firstLine = this.getValueOf('cb', 'firstLine');
			padLineNumbers = this.getValueOf('cb', 'padLineNumbers');
			highlight = this.getValueOf('cb', 'highlight');
			collapse = this.getValueOf('cb', 'collapse');
			
			attr = '';
			if(title != '') {attr += 'title:\''+title+'\';';}
			if(firstLine != '') {attr += 'first-line:'+firstLine+';';}
			if(padLineNumbers != '') {attr += 'pad-line-numbers:'+padLineNumbers+';';}
			if(highlight != '') {attr += 'highlight:'+highlight+';';}
			if(collapse != ''){attr += 'collapse:'+collapse+';';}
			
			title = this.getValueOf('cb', 'lang');
			html = escape(code);
			replaceObj = /&/g;
			html = html.replace(replaceObj, '&amp;');
			replaceObj = /</g;
			html = html.replace(replaceObj, '&lt;');
			replaceObj = />/g;
			html = html.replace(replaceObj, '&gt;');
			editor.insertHtml("<pre class=\"brush:"+lang+";"+attr+"\">\n"+html+"\n</pre>");
		},
		onLoad: function(){} 
	};
});