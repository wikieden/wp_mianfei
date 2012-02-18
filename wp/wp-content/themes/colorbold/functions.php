<?php if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));


//custom comments

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
  	

     <div id="comment-<?php comment_ID(); ?>" >
	 
	 <div class="comment-metaLeft">
			<?php echo get_avatar($comment,$size='40',$default='http://www.gravatar.com/avatar/61a58ec1c1fba116f8424035089b7c71' ); ?>
			</div>
			<div class="commentRight">
			<div class="commentBullet"></div>
				 <span><?php printf(__('<strong>%s</strong>'), get_comment_author_link()) ?> </span><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?> <?php edit_comment_link(__('(Edit)'),'  ','') ?>
				
				  <div class="text"><?php comment_text() ?> </div>
				   <?php if ($comment->comment_approved == '0') : ?>
					 <em><?php _e('Your comment is awaiting moderation.') ?></em>
				  <?php endif; ?>
			<div class="reply">
					 <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				  </div>
			</div>
	 
      
     </div>
<?php }
add_action('admin_menu', 'colorbold_theme_page');
function colorbold_theme_page ()
{
	if ( count($_POST) > 0 && isset($_POST['colorbold_settings']) )
	{
		$options = array ( 'style','logo_img','logo_alt','contact_email','ads', 'twitter_link', 'twitter_txt','flickr', 'analytics');
		
		foreach ( $options as $opt )
		{
			delete_option ( 'colorbold_'.$opt, $_POST[$opt] );
			add_option ( 'colorbold_'.$opt, $_POST[$opt] );	
		}	
		header("Location: admin.php?page=functions.php&saved=true");								
			
		die;		
	}
	add_theme_page(__('Colorbold Options'), __('Colorbold Options'), 'edit_themes', basename(__FILE__), 'colorbold_settings');	
}
function colorbold_settings ()
{?>
<div class="wrap">
	<h2>Colorbold Options Panel</h2>
	
<form method="post" action="">
	<table class="form-table">
		<!-- General settings -->	
		<tr valign="top">
			<th scope="row"><label for="style">Theme Color Scheme</label></th>
			<td>
				<select name="style" id="style">
					<option value="blue.css" <?php if(get_option('colorbold_style') == 'blue.css'){?>selected="selected"<?php }?>>blue.css</option>
					<option value="magenta.css" <?php if(get_option('colorbold_style') == 'magenta.css'){?>selected="selected"<?php }?>>magenta.css</option>
					<option value="green.css" <?php if(get_option('colorbold_style') == 'green.css'){?>selected="selected"<?php }?>>green.css</option>
					<option value="red.css" <?php if(get_option('colorbold_style') == 'red.css'){?>selected="selected"<?php }?>>red.css</option>
					
				</select> 
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_img">Change logo (full path to image)</label></th>
			<td>
				<input name="logo_img" type="text" id="logo_img" value="<?php echo get_option('colorbold_logo_img'); ?>" class="regular-text" /><br />
				<em>current logo:</em> <br /> <img src="<?php echo get_option('colorbold_logo_img'); ?>" alt="<?php bloginfo('name'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_alt">Logo image ALT text</label></th>
			<td>
				<input name="logo_alt" type="text" id="logo_alt" value="<?php echo get_option('colorbold_logo_alt'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="contact_email">Email Address for Contact Form</label></th>
			<td>
				<input name="contact_email" type="text" id="contact_email" value="<?php echo get_option('colorbold_contact_email'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="twitter_link">Twitter link</label></th>
			<td>
				<input name="twitter_link" type="text" id="twitter_link" value="<?php echo get_option('colorbold_twitter_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="twitter_txt">Twitter box text</label></th>
			<td>
				<input name="twitter_txt" type="text" id="twitter_txt" value="<?php echo get_option('colorbold_twitter_txt'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="flickr">Flickr Photostream</label></th>
			<td>
				<select name="flickr" id="flickr">
					<option value="yes" <?php if(get_option('colorbold_flickr') == 'yes'){?>selected="selected"<?php }?>>Yes</option>
					<option value="no" <?php if(get_option('colorbold_flickr') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select> 
				<br /><em>Make sure you have FlickrRSS plugin activated if you choose to enable Flickr Photostream</em>
			</td>
		</tr>
		<!-- Ads Box Settings -->
		<tr>
			<th><label for="ads">Ads Section Enabled:</label></th>
			<td>
				<select name="ads" id="ads">
					<option value="yes" <?php if(get_option('colorbold_ads') == 'yes'){?>selected="selected"<?php }?>>Yes</option>
					<option value="no" <?php if(get_option('colorbold_ads') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select> 
				<br /><em>Make sure you have AdMinister plugin activated and have the position "Sidebar" created within the plugin.</em>
			</td>
		</tr>
		<!-- Google Analytics -->
		<tr>
			<th><label for="ads">Google Analytics code:</label></th>
			<td>
				<textarea name="analytics" id="analytics" rows="7" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('colorbold_analytics')); ?></textarea>
			</td>
		</tr>
		
		
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="colorbold_settings" value="save" style="display:none;" />
	</p>
</form>

</div>
<? }?>
<?php function get_first_image() {
global $post, $posts;
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches [1] [0];
if(empty($first_img)){ //Defines a default image
$first_img = "/images/default.jpg";
}
return $first_img;
} ?>