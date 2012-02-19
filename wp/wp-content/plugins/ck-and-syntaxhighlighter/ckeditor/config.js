/*
配置参考:http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
*/
CKEDITOR.editorConfig = function( config )
{
	config.extraPlugins = "insertcode,more";
	config.tabSpaces = 4;
	config.resize_enabled = true;
	config.autoUpdateElement = true;
	config.entities = true;
	config.disableNativeSpellChecker = false;
	config.scayt_autoStartup = false;
	config.format_tags = 'div;p;h1;h2;h3;h4;h5;h6;pre;address';
	config.format_div = {element : 'div', attributes :{'class' : 'Code'}};
    config.fontSize_sizes ='8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px'
};