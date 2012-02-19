<div class="wrap">
<h2>CKeditor and SyntaxHighlighter设置</h2>
<form action="" method="post">
  <style type="text/css">
  .set_ck_msg {color:#C3C3C3; display:block;}
  .ck_face{text-align:left;}
  .ck_face img.selective{border:1px solid #999;}
  .ck_face img{border:0;border:1px solid #F9F9F9;cursor:pointer;}
  span.BTNPrompt{margin-right:10px;word-wrap:normal; white-space:nowrap;}
  </style>
  <h3>CKeditor 编辑器配置</h3>
  小提示:<span style="color:red">用户->我的资料->撰写文章时禁用可视化编辑器才能正常使用这个插件</span>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">编辑器语言</th>
      <td>
      <?php
      $now_language = $this->ckeditor_config["ck_language"];
      $path = $this->plugins_path."/ckeditor/lang";
      if(is_dir($path)) {
          $resources = dir($path);
          $lang = array();
          while(($file = $resources->read())!=false) {
              $file = pathinfo($file);
              if($file["extension"] == 'js') {
                  $lang[] = str_replace(".js", '', $file["basename"]);
              }
          }
      }
      ?>
      <select name="ckeditor_config[ck_language]" style="width:70px;">
        <?php if(is_array($lang) and !empty($lang)):?>
              <?php foreach($lang as $langs):?>
                  <?php if($now_language == $langs):?>
                      <option value="<?=$langs;?>" selected><?=$langs;?></option>
                  <?php else:?>
                      <option value="<?=$langs;?>"><?=$langs;?></option>
                  <?php endif;?>
       		 <?php endforeach;?>
        <?php else:?>
            <option value="">语言文件不存在</option>
        <?php endif;?>
        </select>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">在写文章时按回车键</th>
      <td>
      <?php
      $ck_enter = $this->ckeditor_config["ck_enter"];
      $tag = array("&lt;br&gt"=>'CKEDITOR.ENTER_BR', "&lt;p&gt"=>'CKEDITOR.ENTER_P', "&lt;div&gt"=>'CKEDITOR.ENTER_DIV');
      ?>
      <select name="ckeditor_config[ck_enter]" style="width:120px;">
      <?php
      foreach($tag as $name=>$tags) {
          if($ck_enter == $tags) {
              echo "<option value=\"{$tags}\" selected>产生{$name}标签</option>";
          } else {
              echo "<option value=\"{$tags}\">产生{$name}标签</option>";
          }
      }
      ?>
      </select>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">编辑器高度</th>
      <td style="color:#A7A7A7">
        后台<input name="ckeditor_config[ck_admin_height]" type="text" value="<?php echo $this->ckeditor_config["ck_admin_height"];?>" style="width:40px;" />&nbsp;&nbsp;
        前台<input name="ckeditor_config[ck_front_height]" type="text" value="<?php echo $this->ckeditor_config["ck_front_height"];?>" style="width:40px;" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">编辑器皮肤</th>
      <td>
      <?php
      $now_skin = $this->ckeditor_config["ck_skin"];
      $path = $this->plugins_path."/ckeditor/skins";
      if(is_dir($path)) {
          $resources = dir($path);
          $skin = array();
          while(($file = $resources->read())!=false) {
              if($file != '.' and $file != '..') {
                  $skin[] = $file;
              }
          }
      }
      ?>
      <select name="ckeditor_config[ck_skin]" style="width:120px;">
        <?php
		if(is_array($skin) and !empty($skin)) {
			foreach($skin as $skins) {
				if($now_skin == $skins) {
					echo "<option value=\"{$skins}\" selected>{$skins}</option>";
   				} else {
					echo "<option value=\"{$skins}\">{$skins}</option>";
				}
			}
		} else {
            echo "<option value=\"\">皮肤文件不存在</option>";
        }
        ?>
      </select>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">前端评论是否启用编辑器</th>
      <td>
      <?php $is_start = $this->ckeditor_config['ck_front_isstart'];?>
      <?php if($is_start == '1'):?>
      <input name="ckeditor_config[ck_front_isstart]" type="radio" value="1" checked="checked" />是&nbsp;&nbsp;
      <input name="ckeditor_config[ck_front_isstart]" type="radio" value="0" />否
      <?php else:?>
      <input name="ckeditor_config[ck_front_isstart]" type="radio" value="1" />是&nbsp;&nbsp;
      <input name="ckeditor_config[ck_front_isstart]" type="radio" value="0" checked="checked" />否
      <?php endif;?>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">后台可用的编辑器功能按纽</th>
      <td></td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <p><textarea name="ckeditor_config[ck_admin_toolbar]" class="large-text code" rows="8" cols="50"><?php echo $this->ckeditor_config["ck_admin_toolbar"];?></textarea></p>
        <span class="set_ck_msg">新增了<span style="color:#F00">insertcode</span>(插入代码)功能，加入它就可以使用了。</span>
        <span class="BTNPrompt">[Source:源代码]</span><span class="BTNPrompt">[Save:保存]</span><span class="BTNPrompt">[NewPage:新建]</span><span class="BTNPrompt">[Preview:预览]</span><span class="BTNPrompt">[Templates:模板]</span><span class="BTNPrompt">[Cut:剪切]</span><span class="BTNPrompt">[Copy:复制]</span><span class="BTNPrompt">[Paste:粘贴]</span><span class="BTNPrompt">[PasteText:粘贴为无文本格式]</span> <span class="BTNPrompt">[PasteFromWord:从MS Word粘贴]</span><span class="BTNPrompt">[Print:打印]</span><span class="BTNPrompt">[SpellChecker:拼写检查]</span><span class="BTNPrompt">[Scayt:即时拼检查]</span><span class="BTNPrompt">[Undo:撤消]</span><span class="BTNPrompt">[Redo:重做]</span><span class="BTNPrompt">[Replace:替换]</span><span class="BTNPrompt">[SelectAll:全选]</span><span class="BTNPrompt">[Find:查找]</span> <span class="BTNPrompt">[PageBreak:插入分页符]</span><span class="BTNPrompt">[RemoveFormat:清除格式]</span><span class="BTNPrompt">[Form:插入表单]</span><span class="BTNPrompt">[Checkbox:插入复选框]</span><span class="BTNPrompt">[Radio:单选按纽]</span><span class="BTNPrompt">[TextField:文本框]</span><span class="BTNPrompt">[Textarea:多行文本框]</span><span class="BTNPrompt">[Select:下拉菜单]</span> <span class="BTNPrompt">[Button:按纽]</span><span class="BTNPrompt">[ImageButton:图片域]</span><span class="BTNPrompt">[HiddenField:隐藏域]</span><span class="BTNPrompt">[Bold:粗体]</span><span class="BTNPrompt">[Italic:斜体]</span><span class="BTNPrompt">[Underline:下划线字体]</span><span class="BTNPrompt">[Subscript:下标]</span><span class="BTNPrompt">[Superscript:上标]</span> <span class="BTNPrompt">[NumberedList:编号列表]</span><span class="BTNPrompt">[BulletedList:项目列表]</span><span class="BTNPrompt">[Strike:删除线]</span><span class="BTNPrompt">[Outdent:减少缩进]</span><span class="BTNPrompt">[Indent:增加缩进]</span><span class="BTNPrompt">[Blockquote:块引用]</span><span class="BTNPrompt">[CreateDiv:DIV容器]</span> <span class="BTNPrompt">[JustifyLeft:左对齐]</span><span class="BTNPrompt">[JustifyRight:右对齐]</span><span class="BTNPrompt">[JustifyBlock:两端对齐]</span><span class="BTNPrompt">[JustifyCenter:居中对齐]</span><span class="BTNPrompt">[Link:超链接]</span><span class="BTNPrompt">[Unlink:去除超链接]</span><span class="BTNPrompt">[Anchor:插入锚点]</span> <span class="BTNPrompt">[Image:插入图像]</span><span class="BTNPrompt">[Flash:插入Flash动画]</span><span class="BTNPrompt">[Table:插入表格]</span><span class="BTNPrompt">[HorizontalRule:插入水平线]</span><span class="BTNPrompt">[Smiley:表情]</span><span class="BTNPrompt">[SpecialChar:特殊字符]</span><span class="BTNPrompt">[TextColor:文本颜色]</span> <span class="BTNPrompt">[BGColor:背景颜色]</span><span class="BTNPrompt">[Styles:样式]</span><span class="BTNPrompt">[Format:格式]</span><span class="BTNPrompt">[Font:字体样式]</span><span class="BTNPrompt">[FontSize:字体大小]</span><span class="BTNPrompt">[Maximize:全屏]</span><span class="BTNPrompt">[ShowBlocks:显示区块]</span><span class="BTNPrompt">[About:关于CKeditor]</span><span class="BTNPrompt">[insertcode:插入代码]</span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">前台可用的编辑器功能按纽</th>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <p><textarea name="ckeditor_config[ck_front_toolbar]" class="large-text code" rows="2" cols="50"><?php echo $this->ckeditor_config["ck_front_toolbar"];?></textarea></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">选择可用表情图片</th>
      <td></td>
    </tr>
    <tr>
      <td align="right" class="ck_face" colspan="2">
        <input type="hidden"  id="ck_face" name="ckeditor_config[ck_face]" value="<?php $ck_face = $this->ckeditor_config["ck_face"];echo $ck_face;?>" />
        <div class="ck_face">
        <?php
        $now_face = (substr($ck_face, 0, 1) == "'") ? substr($ck_face, 1) : $ck_face;
        $now_face = (substr($now_face, -1) == "'") ? substr($now_face, 0, -1) : $now_face;
        $now_face = explode("','", $now_face);
        $path = $this->plugins_path."/ckeditor/plugins/smiley/images";
        if(is_dir($path)) {
            $resources = dir($path);
            while(($file = $resources->read())!=false) {
                if($file != '.' and $file != '..') {
                    $src =  $this->plugins_url ."ckeditor/plugins/smiley/images/". $file;
                    if(in_array($file, $now_face)) {?>
                        <img alt="1" title="<?php echo $file;?>" src="<?php echo $src;?>" class="selective" />
              <?php } else { ?>
                        <img alt="0" title="<?php echo $file;?>" src="<?php echo $src;?>" class="noSelective" />
              <?php }
                }
            }
        }
        ?>
        </div>
        <span class="set_ck_msg">wp-content/plugins/ck_and_syntax/ckeditor/plugins/smiley/images</span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">手动更改编辑器配置文件</th>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <p><textarea id="edit_ck_cfg" class="large-text code" rows="15" cols="50"><?php echo file_get_contents($this->plugins_path."/ckeditor/config.js");?></textarea></p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">编辑器载入的CSS[<span style="color:red">分号分割</span>]</th>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <p><textarea class="large-text code" rows="1" cols="50" name="ckeditor_config[ck_loadCss]"><?php echo $this->ckeditor_config["ck_loadCss"];?></textarea>
         <span class="set_ck_msg">将你你模板目录下的.css结尾的文件名全部帖在这里，这将有助于你在编辑文章时编辑界面和发布后的页面的布局一致。</span></p>
      </td>
    </tr>
  </table>

  <h3>SyntaxHighlighter 语法高亮配置</h3>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">亮皮肤设置</th>
      <td>
      <?php
      $syntax_color_style = array('默认风格'=>'shCoreDefault.css',
						   'CK and SyntaxHighlighter'=>'shCoreCk.css',
						   'Django'		=>'shCoreDjango.css',
						   'Eclipse'	=>'shCoreEclipse.css',
						   'Emacs'		=>'shCoreEmacs.css',
						   'FadeToGrey'	=>'shCoreFadeToGrey.css',
						   'MDUltra'	=>'shCoreMDUltra.css',
						   'Midnight'	=>'shCoreMidnight.css',
						   'RDark'		=>'shCoreRDark.css');
	  
	  $syntax_skin_style = array('默认风格'=>'shThemeDefault.css',
						   'CK and SyntaxHighlighter'=>'shThemeCk.css',
						   'Django'		=>'shThemeDjango.css',
						   'Eclipse'	=>'shThemeEclipse.css',
						   'Emacs'		=>'shThemeEmacs.css',
						   'FadeToGrey'	=>'shThemeFadeToGrey.css',
						   'MDUltra'	=>'shThemeMDUltra.css',
						   'Midnight'	=>'shThemeMidnight.css',
						   'RDark'		=>'shThemeRDark.css');
	  ?>
      <span style="color:#A7A7A7">着色风格:</span>
      <select name="ckeditor_config[sh_color_style]">
        <?php
        foreach($syntax_color_style as $name=>$css) {
			if($css == $this->ckeditor_config['sh_color_style']) {
				echo "<option value=\"{$css}\" selected=\"selected\">{$name}</option>";
			} else {
				echo "<option value=\"{$css}\">{$name}</option>";
			}
		}
		?>
      </select>
      <br />
      <span style="color:#A7A7A7">皮肤样式:</span>
      <select name="ckeditor_config[sh_skin_style]">
        <?php
		unset($name, $css);
        foreach($syntax_skin_style as $name=>$css) {
			if($css == $this->ckeditor_config['sh_skin_style']) {
				echo "<option value=\"{$css}\" selected=\"selected\">{$name}</option>";
			} else {
				echo "<option value=\"{$css}\">{$name}</option>";
			}
		}
		?>
      </select>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">自定义css</th>
      <td><input name="ckeditor_config[sh_class_name]" type="text" style="width:450px;" value="<?php echo $this->ckeditor_config['sh_class_name'];?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row">其它设置</th>
      <td>
      缩进<input name="ckeditor_config[sh_tab_size]" type="text" value="<?php echo $this->ckeditor_config['sh_tab_size'];?>" style="width:20px;" />个空格<br />
      <input name="ckeditor_config[sh_smart_tabs]" type="checkbox" value="true"<?php if($this->ckeditor_config['sh_smart_tabs'] == 'true'){echo ' checked="checked"';}?> />
      &nbsp;智能标签<br />
      <input name="ckeditor_config[sh_gutter]" type="checkbox" value="true"<?php if($this->ckeditor_config['sh_gutter'] == 'true'){echo ' checked="checked"';}?> />&nbsp;显示行号<br />
      <input name="ckeditor_config[sh_quick_code]" type="checkbox" value="true"<?php if($this->ckeditor_config['sh_quick_code'] == 'true'){echo ' checked="checked"';}?> />&nbsp;允许双击快速复制代码<br />
      <input name="ckeditor_config[sh_collapse]" type="checkbox" value="true"<?php if($this->ckeditor_config['sh_collapse'] == 'true'){echo ' checked="checked"';}?> />&nbsp;收缩代码框<br />
      <input name="ckeditor_config[sh_auto_links]'" type="checkbox" value="true"<?php if($this->ckeditor_config['sh_auto_links'] == 'true'){echo ' checked="checked"';}?> />&nbsp;自动转换代码中的URL为链接<br />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><?php _e('调用方式')?></th>
      <td>
      <input name="ckeditor_config[sh_load_mode]" type="radio" value="in_admin_edit"<?php if($this->ckeditor_config['sh_load_mode'] == 'in_admin_edit'){echo ' checked="checked"';}?> />&nbsp;后台发布文章时调用<br />
      <input name="ckeditor_config[sh_load_mode]" type="radio" value="in_front_load"<?php if($this->ckeditor_config['sh_load_mode'] == 'in_front_load'){echo ' checked="checked"';}?> />&nbsp;前端输出文章时调用<br />
      <input name="ckeditor_config[sh_load_mode]" type="radio" value="not_use"<?php if($this->ckeditor_config['sh_load_mode'] == 'not_use'){echo ' checked="checked"';}?> />&nbsp;不使用语法高亮功能
      </td>
    </tr>
  </table>
  
  <table class="form-table">
    <tr valign="top">
      <th scope="row"></th>
      <td><input class="button-primary" type="submit" value="<?php _e('保存设置 &raquo;') ?>" name="update_message" /> &nbsp;&nbsp;E-mail:sss60@qq.com</td>
    </tr>
  </table>
  <script type="text/javascript">
  function imgMover() {
      jQuery(this).css('border', '1px solid #F00')
  }
  function imgMout() {
      jQuery(this).css('border', '1px solid #F9F9F9')
  }
  
  jQuery(document).ready(function (){
	  //选择可用表情
      var o = jQuery("#ck_face");
	  //绑定鼠标事件
      jQuery(".ck_face img.noSelective").bind('mouseover', imgMover);
      jQuery(".ck_face img.noSelective").bind('mouseout', imgMout);
      jQuery(".ck_face img")
      .click(function (){
          fName = jQuery(this).attr("title");
          fName = '\''+ fName +'\'';
		  //alt属性表明对象本身是否被选中
          isStart = jQuery(this).attr("alt");
          oValue = o.val();
		  //如果被选中...否则...
          if(isStart == '1') {
              jQuery(this).attr("alt", '0');
              jQuery(this).css('border', '1px solid #F9F9F9');
              
              jQuery(this).bind('mouseover', imgMover);
              jQuery(this).bind('mouseout', imgMout);
              
              re = RegExp("\,?"+fName,"im");
              nowValue = oValue.replace(re, '');
              if(nowValue.substr(0, 1) == ',') {
                  nowValue = nowValue.substr(1);	
              }
              o.val(nowValue);
          } else if(isStart == '0') {
              if(oValue.match(fName) == null) {
                  jQuery(this).unbind('mouseover', imgMover);
                  jQuery(this).unbind('mouseout', imgMout);
                  
                  jQuery(this).attr("alt", '1');
                  jQuery(this).css('border', '1px solid #999');
                  if(o.val() == '') {
                      o.val(fName);	
                  } else {
                      o.val(oValue+','+fName);
                  }
              }
          }
      });
	  //判断ckeditor/config.js文件有没有被修改
	  jQuery("#edit_ck_cfg")
	  .change(function(){
	      jQuery(this).attr('name', 'edit_ck_cfg');
	  });
  })
  </script>
</form>
</div>
