=== Plugin Name ===
Contributors: bitinn
Donate link: http://thanks.for.considering.a.donation/you.can.keep.it/
Tags: fast, quick, image, photo, insert, media, admin, edit
Requires at least: 2.6
Tested up to: 3.2
Stable tag: 2.2.0

Fully integrates media manager into editing interface, avoid reloading thickbox pop-up, with enhanced features like multi-insert & mass-editing.

== Description ==

Faster Image Insert aims to do **one thing right**:

Moves built-in Media Manager down in a meta-box, right next to main editing panel, so you have full control of the manager: opens it, makes it collapse or hidden from the interface completely.

Best of all, is now you can insert image(s) much **faster**, and **precisely** where you want them to be.

- **No thickbox**, using metabox with zero interface blocking, quite similar to the uploader in WordPress 1.5
- **No hacking**, default upload interface is not affected, only enhanced.
- **Insert multiple images** in gallery & library mode, without using shortcode; can also insert images in reversed order, and even control spacing between images.
- **Mass info editing**, change title/captions in one-shot.
- **Smart switches**: set default uploader, disable captions.

**This plugin is designed for**:

* Screenshot lover - movie, game or anime review etc.
* Howto guru - cooking guide, hardware DIY guide etc.
* Photo logger - author can comment below each photo.
* Blogger that has been shouting "run, thickbox, RUN!" to the loading screens.

== Installation ==

- Extract to folder /faster-image-insert
- Upload to WordPress plugins directory /wp-content/plugins
- Activate the plugin in WordPress plugin page
- (Optional) Change options in "Settings" menu
- Navigate to post/page editing panel, a Fast Insert metabox should appear

== Screenshots ==

1. Gallery tab
2. Upload tab
3. Settings

== Frequently Asked Questions ==

= Does the media manager behaves the same in meta-box ? =

Yes, except less annoying (I hope).

= Why my items' ordering are not saved ? =

Remember to press "save all changes" button after re-ordering images. "Insert selected images" only outputs in listing order, NOT by ordering numbers.

= Why reversed ordering starts with the number 500 ? =

It's just a number, I figure 500 should be enough for everyone; but you're free to change it. Note that ordering number can be negative, perform drag-and-drop will set them back to natural numbers.

**This function would not appear for WP2.8+ users, because sorting is now built-in.**

= Why are blank spaces/lines not saved ? =

WordPress' default editor TinyMCE strips consecutive blank spaces/lines; the action is performed when saving or switching edit mode. This plugin tries to work around it by sending

      <p>&nbsp;</p>


= Conflicts with Custom Field Template plugin ? =

One way to workaround this problem is to enable the multiple image insert option in CFT's settings page. If you don't feel like hacking wordpress core, media-manager popup **within** CPT meta-box will cease to load, but other function should work as usual.

**This is for 1.0 series**

= Conflicts with Admin Drop Down Menu plugin ? =

Schedule and Farbtastic libraries in wordpress can NOT live next to each other. Unfortunely, they met in the add new post section via these two plugins, which rendered the page as blank. One workaround is to uncomment the line:
 
     wp_deregister_script('farbtastic')


in faster-image-insert.php, it resolves the problem between two plugins, but also disable any color pickers.

**This is for 1.0 series**

== Changelog ==

= trunk =
* [unstable release](http://downloads.wordpress.org/plugin/faster-image-insert.zip)

= 2.2.0 =

* For WordPress 3.2+
* Fix various issues linked to jQuery 1.6.x on WP 3.2+
* Fix an issue where uninstallation is not successful

= 2.1.0 =

* Fixed CSRF issue
* Fixed support for HTTPS protocol
* Updated French translation

= 2.0.0 =

* For WordPress 3.0+
* Support custom post types
* Now able to insert multiple images at default upload page
* Remove unnecessary components

= 1.5.1 =
* Provides a new method for reloading the meta-box (click to toggle after autosave);
* New Option for global custom string (inserted between images on multiple insertions);
* New Translation and POT file.

= 1.5.0 =
* Updates for the wordpress 2.8+ user;
* Removes dependence on internal scripts to resolve plugin conflicts;
* Attempts to work around the TinyMCE whitespace stripping problem;
* Compatible mode is now auto-enabled (load quicktags.js if not 2.8);
* Updates FAQ.

= 1.3.7 =
* Resolves a plugin conflict, updates readme.

= 1.3.6 =
* Re-structured setting page layout, new translation, updated compatible version.

= 1.3.5 =
* Add compatibility mode to remove functions that are WP 2.8 built-in, debug mode for plugin conflict debugging, new translation.

= 1.3.4 =
* Ability to reverse image ordering & toggle all image items (remember to save it first). fix a problem regarding mass-edit.

= 1.3.3 =
* Negate selection now available, Danish translation by Georg, French translation by Li-An, users are now able to set separator between images, and disable caption if desired.

= 1.3.2 =
* new experimental mass-image edit (change title, caption, alignment & size; enable it in option page); added i18n support (pot file included), with chinese translation.

= 1.3.1 =
* removed due to wrong file, please use 1.3.2, we apologize for the trouble.

= 1.3.0 =
* provide options for customization & debug (under Settings menu); fix a bug associate with non-image insertion; load image upload form instead of media upload form (they have the identical functions, but latter has better From URL tab support); get ready for i18n; tested support for wordpress 2.6

= 1.2.0 =
* now comes with multiple images insertion feature; various improvements.

= 1.1.1 =
* optimized autosave detection. Final release for WordPress 2.7.1

= 1.1.0 =
* workaround for autosave breaking upload queue, better support for wordpress installation in sub-folder.

= 1.0.3 =
* updated readme, better support for un-saved post.

= 1.0.0 =
* initial release, basic function implemented.

== Upgrade Notice ==

= 2.2.0 =
For WordPress 3.2+ Only

= 2.1.0 =
Includes a security fix to avoid potential CSRF

= 2.0.0 =
For WordPress 3.0+ Only

= 1.5.0 =
For WordPress 2.8+ Only

== Notes ==

Translations:

* Chinese - by [DF](http://bitinn.net/)
* French - by [Li-An](http://www.li-an.fr/)
* Danish - by [Georg](http://wordpress.blogos.dk/)
* Japanese - by [Chibi](http://ilovechibi.net/)
* Russian - by [FatCow](http://www.fatcow.com/)

Plugin License:

* [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html)
