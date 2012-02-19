<?php
if(!class_exists('CkAndSyntaxHighlighter'))
{
	class CkAndSyntaxHighlighter
	{
		var $_admin_name = "编辑器设置";		// 后台管理功能名称
		var $plugins_url = '';				// 本插件的根URL
		var $plugins_path = '';				// 本插件的根目录
		var $ckeditor_config = array();		// ckeditor配置
		var $syntax = array(
			'as3'	=>'shBrushAS3.js',
			'bash'	=>'shBrushBash.js',
			'csharp'=>'shBrushCSharp.js',
			'cpp'	=>'shBrushCpp.js',
			'css'	=>'shBrushCss.js',
			'delphi'=>'shBrushDelphi.js',
			'diff'	=>'shBrushDiff.js',
			'groovy'=>'shBrushGroovy.js',
			'xhtml'	=>'shBrushXml.js',
			'js'	=>'shBrushJScript.js',
			'java'	=>'shBrushJava.js',
			'jfx'	=>'shBrushJavaFX.js',
			'perl'	=>'shBrushPerl.js',
			'plain'	=>'shBrushPlain.js',
			'ps'	=>'shBrushPowerShell.js',
			'py'	=>'shBrushPython.js',
			'rails'	=>'shBrushRuby.js',
			'scala'	=>'shBrushScala.js',
			'sql'	=>'shBrushSql.js',
			'vb'	=>'shBrushVb.js',
			'xml'	=>'shBrushXml.js',
			'php'	=>'shBrushPhp.js',
			'shell'	=>'shBrushShell.js',
		);
		
		function __construct()
		{
			$siteurl = trailingslashit(get_option('siteurl'));
			$this->plugins_url	= $siteurl .'wp-content/plugins/'. basename(dirname(__FILE__)) .'/';
			$this->plugins_path	= str_replace("\\", '/', dirname(__FILE__));
			
			/* 更新配置 */
			if(isset($_POST['ckeditor_config']))
			{
				$this->_ckeditor_update_option($_POST['ckeditor_config']);
			}

			/* 写入配置文件 */
			if(isset($_POST['edit_ck_cfg']))
			{
				$this->_write_config_file($this->plugins_path .'/ckeditor/config.js', $_POST['edit_ck_cfg']);	
			}

			$this->ckeditor_config = maybe_unserialize(get_option('ckeditor_config'));
		}
		
		function CkAndSyntaxHighlighter()
		{
			$this->__construct();
		}
		
		function _options_page_content()
		{
			include_once($this->plugins_path ."/option.php");
		}
		
		/**调用ckeditor
		 * @$textarea_id	element ID
		 */
		function _create_ckeditor_script($textarea_id)
		{
			$front_toolbar = $this->ckeditor_config["ck_front_toolbar"];
			$admin_toolbar = $this->ckeditor_config["ck_admin_toolbar"];
			
			$front_height = $this->ckeditor_config['ck_front_height'];
			$admin_height = $this->ckeditor_config['ck_admin_height'];
			
			switch($textarea_id)
			{
				case 'content': $toolbar= $admin_toolbar; $height = $admin_height; break;
				case 'comment': $toolbar= $front_toolbar; $height = $front_height; break;
			}
			
			$load_css = explode(';', $this->ckeditor_config['ck_loadCss']);
			?>
			<script type="text/javascript" src="<?php echo $this->plugins_url;?>ckeditor/ckeditor.js"></script>
			<script type="text/javascript" src="<?php echo $this->plugins_url;?>ckfinder/ckfinder.js"></script>
			<script type="text/javascript">
			//<![CDATA[
			function _load_ckeditor() {
				CK_editor = CKEDITOR.replace('<?php echo $textarea_id;?>',{
					skin : '<?php echo $this->ckeditor_config['ck_skin'];?>',
					language : '<?php echo $this->ckeditor_config['ck_language'];?>',
					height : '<?php echo $height;?>',
					enterMode : <?php echo $this->ckeditor_config['ck_enter'];?>,
					smiley_images : [<?php echo $this->ckeditor_config['ck_face'];?>],
					toolbar_MyToolBar : [<?php echo $toolbar;?>],
					toolbar : 'MyToolBar',
					contentsCss : [
						<?php foreach($load_css as $temp):?>'<?php bloginfo('stylesheet_directory');?>/<?=$temp;?>',<?php endforeach;?>
						'<?php echo $this->plugins_url;?>ckeditor/contents.css'
					]
				});
				CKFinder.setupCKEditor(CK_editor, '<?php echo $this->plugins_url;?>ckfinder/');
			}
			_load_ckeditor();
			//]]>
			</script>
			<?php
		}
	
		/* 搜索文章存在语法高亮标签 */
		function _in_content_seek_syntax($content)
		{
			$content = preg_replace("/<!--\[syntaxhighlighter\]-->(.*)<!--\[\/syntaxhighlighter\]-->/is", '', $content);
			preg_match_all('/<pre\s+class="brush:([\w]+);[^"].*?">/is', $content, $result);

			$match = array_unique($result[1]);

			$lang = '';
			foreach($match as $type)
			{
				$lang .= "<script type=\"text/javascript\" src=\"". $this->plugins_url . "syntaxhighlighter/scripts/". $this->syntax[$type]. "\"></script>\n";
			}

			if($lang == ''){ return ''; }

			return "
			<!--[syntaxhighlighter]-->
			<!--代码高亮，请勿编辑-->
			<script type=\"text/javascript\" src=\"".  $this->plugins_url ."syntaxhighlighter/scripts/shCore.js\"></script>". $lang ."
			<link type=\"text/css\" rel=\"stylesheet\" href=\"". $this->plugins_url ."syntaxhighlighter/styles/". $this->ckeditor_config['sh_color_style'] ."\" />
			<link type=\"text/css\" rel=\"stylesheet\" href=\"". $this->plugins_url ."syntaxhighlighter/styles/". $this->ckeditor_config['sh_skin_style'] ."\" />
			<script type=\"text/javascript\">
			SyntaxHighlighter.defaults['class-name']	= '".$this->ckeditor_config['sh_class_name']."';
			SyntaxHighlighter.defaults['smart-tabs']	= ".$this->ckeditor_config['sh_smart_tabs'].";
			SyntaxHighlighter.defaults['tab-size']		= ".$this->ckeditor_config['sh_tab_size'].";
			SyntaxHighlighter.defaults['gutter']		= ".$this->ckeditor_config['sh_gutter'].";
			SyntaxHighlighter.defaults['quick-code']	= ".$this->ckeditor_config['sh_quick_code'].";
			SyntaxHighlighter.defaults['collapse'] 		= ".$this->ckeditor_config['sh_collapse'].";
			SyntaxHighlighter.defaults['auto-links']	= ".$this->ckeditor_config['sh_auto_links'].";
			SyntaxHighlighter.defaults['toolbar']		= true;
			SyntaxHighlighter.all();
			</script>
			<!--[/syntaxhighlighter]-->";
		}
	
		/* 前台文章载入文章时调用语法高亮 */
		function ckeditor_syntax_in_fornt_load($content)
		{
			$syntax_script = $this->_in_content_seek_syntax($content);
			$content = $content.$syntax_script;
			return $content;
		}
	
		/* 在文章编辑之后载入语法高亮 */
		function ckeditor_syntax_in_admin_edit($post)
		{
			global $wpdb;
			$get_content = $wpdb->get_row("SELECT post_content FROM {$wpdb->posts} WHERE id='{$post}'", ARRAY_A);
			$content = $get_content['post_content'];
			
			$lang = $this->_in_content_seek_syntax($content);
			if(!empty($lang))
			{
				$syntax_script = $this->_syntaxhighlighter_script($lang);
				$new_content = addslashes($content.$syntax_script);
				$wpdb->query("UPDATE {$wpdb->posts} SET `post_content`='{$new_content}' WHERE `id`='{$post}'");
			}
			return $post;
		}
	
		/* 第一次安装时初始化数据 */
		function ckeditor_install()
		{
			/* ckeditor */
			$default_ckeditor_config['ck_loadCss']		= '';
			$default_ckeditor_config['ck_language']		= 'zh-cn';
			$default_ckeditor_config['ck_enter']		= 'CKEDITOR.ENTER_BR';
			$default_ckeditor_config['ck_admin_height']	= '400';
			$default_ckeditor_config['ck_front_height']	= '100';
			$default_ckeditor_config['ck_skin']			= 'v2';
			$default_ckeditor_config['ck_admin_toolbar']= "['Source','Save','NewPage','Preview','Templates','Cut','Copy','Paste','PasteText','PasteFromWord','Print','SpellChecker','Scayt','Undo','Redo','Replace','SelectAll','Find'],['PageBreak','RemoveFormat','Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField','Bold','Italic','Underline','Subscript','Superscript','NumberedList','BulletedList','Strike'],['Outdent','Indent','Blockquote','CreateDiv','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Link','Unlink','Anchor','Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','TextColor','BGColor'],['Styles','Format','Font','FontSize','Maximize','ShowBlocks','About','insertcode','more']";
			$default_ckeditor_config['ck_front_toolbar']= "['Source','insertcode']";
			$default_ckeditor_config['ck_front_isstart']= '1';
			for($i=1; $i<=88; $i++)
			{
				$smiley .= '\'' .$i.'.gif\',';
			}
			$smiley = substr($smiley, 0, -1);
			$default_ckeditor_config['ck_face']			= $smiley;
	
			/* SyntaxHighlighter */
			$default_ckeditor_config['sh_class_name']	= "";
			$default_ckeditor_config['sh_color_style']	= "shCoreCk.css";	
			$default_ckeditor_config['sh_skin_style']	= "shThemeCk.css";
			$default_ckeditor_config['sh_tab_size']		= "4";
			$default_ckeditor_config['sh_smart_tabs']	= "true";
			$default_ckeditor_config['sh_gutter']		= "true";
			$default_ckeditor_config['sh_quick_code']	= "true";
			$default_ckeditor_config['sh_collapse']		= "false";
			$default_ckeditor_config['sh_auto_links']	= "true";
			$default_ckeditor_config['sh_load_mode']	= "in_front_load";
			
			if(!get_option('ckeditor_config'))
			{
				add_option("ckeditor_config", maybe_serialize($default_ckeditor_config));
			}
		}
	
		/* 给后台增加一个设置功能 */
		function ckeditor_add_options_page()
		{
			add_options_page('ckeditor', $this->_admin_name, 8, 'index', array(&$this, '_options_page_content'));
		}
		
		/* Function:stripslashes */
		function _stripslashes($value)
		{
			if(empty($value))
			{
				return $value;
			}
			elseif(is_array($value))
			{
				return array_map(array(&$this, "_stripslashes"), $value);
			}
			else
			{
				return stripslashes($value);
			}
		}

		function _ckeditor_update_option($ck_config)
		{
			$ck_config = $this->_stripslashes($ck_config);
			$tab_size = intval($ck_config['sh_tab_size']);
			
			$ck_config['sh_tab_size']		= empty($tab_size) ? 4 : $tab_size;
			$ck_config['sh_smart_tabs']		= (!isset($ck_config['sh_smart_tabs'])) ? 'false' : 'true';
			$ck_config['sh_gutter']			= (!isset($ck_config['sh_gutter'])) ? 'false' : 'true';
			$ck_config['sh_quick_code']		= (!isset($ck_config['sh_quick_code'])) ? 'false' : 'true';
			$ck_config['sh_collapse']		= (!isset($ck_config['sh_collapse'])) ? 'false' : 'true';
			$ck_config['sh_auto_links']		= (!isset($ck_config['sh_auto_links'])) ? 'false' : 'true';
			
			$ck_config = maybe_serialize($ck_config);
			update_option('ckeditor_config', $ck_config);
		}
		
		function _write_config_file($file, $content)
		{
			$content = $this->_stripslashes($content);
			file_put_contents($file, $content);
		}
		
		/* 后台载入CSS */
		function ckeditor_add_admin_css()
		{
			?>
			<style type="text/css">
			#quicktags,
			#editor-toolbar,
			#media-buttons,
			#content_parent{display:none;}
			</style>
			<?php
		}
	
		/* 载入前台Ckeditor */
		function ckeditor_load_front_ckeditor()
		{
			if (is_page() || is_single())
			{
				$this->_create_ckeditor_script('comment');
			}
		}
	
		/* 载入后台Ckeditor */
		function ckeditor_load_admin_ckeditor()
		{
			$this->_create_ckeditor_script('content');
		}
	}
}
