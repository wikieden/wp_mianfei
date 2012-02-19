/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/SyntaxHighlighter
 *
 * SyntaxHighlighter is donationware. If you are using it, please donate.
 * http://alexgorbatchev.com/SyntaxHighlighter/donate.html
 *
 * @version
 * 3.0.83 (July 02 2010)
 * 
 * @copyright
 * Copyright (C) 2004-2010 Alex Gorbatchev.
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 */
;(function()
{
	// CommonJS
	typeof(require) != 'undefined' ? SyntaxHighlighter = require('shCore').SyntaxHighlighter : null;

	function Brush()
	{
		this.regexList = [
			//{ regex: /\n[^-][\w-]+/g,									css: 'keyword' },			// 命令:make
			//{ regex: /^\w[\w-]+/g,										css: 'keyword' },			// 命令:make
			//{ regex: /(&|&amp;){2}\s*[^-][\w-]+/ig,						css: 'keyword' },			// 命令:make&&make install
			//{ regex: /;\s*(^\w[\w-]+)/g,								css: 'keyword' },			// 命令:make;make install
			{ regex: /\s+[-]+\w+/g,										css: 'functions'}			// 参数
		];

		this.forHtmlScript(SyntaxHighlighter.regexLib.phpScriptTags);
	};

	Brush.prototype	= new SyntaxHighlighter.Highlighter();
	Brush.aliases	= ['shell'];

	SyntaxHighlighter.brushes.Php = Brush;

	// CommonJS
	typeof(exports) != 'undefined' ? exports.Brush = Brush : null;
})();
