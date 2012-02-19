<?php
/**
 * Plugin Name: CK and SyntaxHighlighter
 * Plugin URI: http://wordpress.org/extend/plugins/ck-and-syntaxhighlighter/
 * Version: 3.4.2
 * Author: 随意的风
 * Author URI: http://user.qzone.qq.com/448110054/blog/1291737353
 * Description: 著名的CKeditor文章编辑器,它可以将你文章中的代码渲染成彩色，它的灵活性更高,加载速度更快。
 */
if(!function_exists('file_get_contents')) {
    function file_get_contents($path) {
        if(($fp=@fopen($path,'r')) != false) {
            $content=@fread($fp, filesize($path));
            @fclose($fp);
            return ($content) ? $content : false;
        }
    }
}

if(!function_exists('file_put_contents')) {
    function file_put_contents($path,$data) {
        if(($fp=fopen($path,'w'))) {
			@fclose($fp);
			return fwrite($fp, $data) ? true : false;
        }
    }
}

@include_once(str_replace("\\" ,'/', dirname(__FILE__)) ."/ck.class.php");
if (class_exists("CkAndSyntaxHighlighter")){
	$ckeditor = new CkAndSyntaxHighlighter;
	add_action('activate_ck-and-syntaxhighlighter/main.php', array(&$ckeditor, 'ckeditor_install'));
	add_action('admin_menu', array(&$ckeditor, 'ckeditor_add_options_page'));

	add_action('admin_head', array(&$ckeditor, 'ckeditor_add_admin_css'));
	add_action('edit_form_advanced', array(&$ckeditor, 'ckeditor_load_admin_ckeditor'));
	add_action('edit_page_form', array(&$ckeditor, 'ckeditor_load_admin_ckeditor'));
	add_action('simple_edit_form', array(&$ckeditor, 'ckeditor_load_admin_ckeditor'));

	if($ckeditor->ckeditor_config['sh_load_mode'] == 'in_admin_edit')
	{
		add_filter('edit_post', array(&$ckeditor, 'ckeditor_syntax_in_admin_edit'));
		add_action('post_updated', array(&$ckeditor, 'ckeditor_syntax_in_admin_edit'));
		add_action('save_post', array(&$ckeditor, 'ckeditor_syntax_in_admin_edit'));
	}
	elseif($ckeditor->ckeditor_config['sh_load_mode'] == 'in_front_load')
	{
		add_action('the_content', array(&$ckeditor, 'ckeditor_syntax_in_fornt_load'));
	}

	if($ckeditor->ckeditor_config["ck_front_isstart"] == '1')
	{
		add_action('comment_form', array(&$ckeditor, 'ckeditor_load_front_ckeditor'));
	}
}