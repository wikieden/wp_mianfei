=== WP Photo Album Plus ===
Contributors: opajaap
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=USD&lc=US
Tags: photo, album, photoalbum, gallery, slideshow, sidebar widget, photowidget, photoblog, widget, qtranslate, multisite, network, lightbox, comment, watermark, iptc, exif
Version: 4.3.10
Stable tag: trunk
Author: J.N. Breetvelt
Author URI: http://www.opajaap.nl/
Requires at least: 3.0
Tested up to: 3.3.1

This plugin is designed to easily manage and display your photo albums and slideshows in a single as well as in a network WordPress site. 
Additionally there are five widgets: Photo of the day, a Search Photos widget, a Top Ten Rated photo widget, a Recent comments widget and a Mini slideshow widget.
Visitors can leave comments on individual photos. Uploads can be provided with a watermark. IPTC and EXIF data can be displayed and used in descriptions.

== Description ==

This plugin is designed to easily manage and display your photo albums and slideshows within your WordPress site. 

* You can create various albums that contain photos as well as sub albums at the same time.
* There is no limitation to the number of albums and photos.
* There is no limitation to the nesting depth of sub-albums.
* You have full control over the display sizes of the photos.
* You can specify the way the albums are ordered.
* You can specify the way the photos are ordered within the albums, both on a system-wide as well as an per album basis.
* The visitor of your site can run a slideshow from the photos in an album by a single mouseclick.
* The visitor can see an overview of thumbnail images of the photos in album.
* The visitor can browse through the photos in each album you decide to publish.
* You can add a Photo of the day Sidebar Widget that displays a photo which can be changed every hour, day or week.
* You can add a Search Sidebar Widget which enables the visitors to search albums and photos for certain words in names and descriptions.
* You can enable a rating system and a supporting Top Ten Photos Sidebar Widget that can hold a configurable number of high rated photos.
* You can enable a comment system that allows visitors to enter comments on individual photos.
* You can add a recent comments on photos Widget.
* Apart from the full-size slideshows you can add a Sidebar Widget that displays a mini slideshow.
* There is a General Purpose widget that is a text widget wherein you can use wppa+ script commands.
* Almost all appearance settings can be done in the settings admin page. No php, html or css knowledge is required to customize the appearence of the photo display.
* International language support for static text: Currently included foreign languages files: Dutch, Japanese, French(outdated), Spanish, German.
* Inrernational language support for dynamic text: Album and photo names and descriptions fully support the qTranslate multilanguage rules and have separate edit fields for all qTranslate activated languages.
* Suports lightbox.
* You can add watermarks to the photos.
* The plugin supports IPTC and EXIF data.

Plugin Admin Features:

You can find the plugin admin section under Menu Photo Albums on the admin screen.

* Photo Albums: Create and manage Albums.
* Upload photos: To upload photos to an album you created.
* Import photos: To bulk import photos to an album that are previously been ftp'd.
* Settings: To control the various settings to customize your needs.
* Sidebar Widget: To specify the behaviour for an optional sidebar photo of the day widget.
* Help & Info: Much information about how to...

== Installation ==

= Upgrade notice =
This version is: Major rev# 4, Minor rev# 3, Fix rev# 5, Hotfix rev# 000.
If you are upgrading from a previous Major or Minor version, note that:
* If you modified wppa_theme.php and/or wppa_style.css, you will have to use the newly supplied versions. The previous versions are NOT compatible.
* If you set the userlevel to anything else than 'administrator' you may have to set it again. Note that changing the userlevel can be done by the administrator only!
* You may have to activate the sidebar widget again.

= Standard installation when not from the wp plugins page =
* Unzip and upload the wppa plugin folder to wp-content/plugins/
* Make sure that the folder wp-content/uploads/ exists and is writable by the server (CHMOD 755)
* Activate the plugin in WP Admin -> Plugins.
* If, after installation, you are unable to upload photos, check the existance and rights (CHMOD 755) of: 
for the single site mode installation: the folders .../wp-content/uploads/wppa/ and .../wp-content/uploads/wppa/thumbs/, 
and for the multisite mode installation (example for blog id 92): the folders path: .../wp-content/blogs.dir/92/wppa/ and .../wp-content/blogs.dir/92/wppa/thumbs/.
In rare cases you will need to create them manually. You can see the actual pathnames and urls in the lowest table of the Photo Albums -> Settings page.
* If you upgraded from WP Photo Album (without plus) and you had copied wppa_theme.php and/or wppa_style.css 
to your theme directory, you must remove them or replace them with the newly supplied versions.

== Frequently Asked Questions ==

= How does the search widget work? =

* A space between words means AND, a comma between words means OR.
Example: search for 'one two, three four, five' gives a result when either 'one' AND 'two' appears in the same (combination of) name and description. 
If it matches the name and description of an album, you get the album, and photo vice versa.
OR this might apply for ('three' AND 'four') OR 'five'. Albums and photos are returned on one page, regardless of pagination settings, if any. 
That's the way it is designed.

= How can i translate the plugin into my language? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=1349

= How do i install a hotfix? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=823

= What to do if i get errors during upload or import photos? =

* It is always the best to downsize your photos to the Full Size before uploading. It is the fastest and safest way to add photos tou your photo albums.
Photos that are way too large take unnessesary long time to download, so your visitors will expierience a slow website. 
Therefor the photos should not be larger (in terms of pixelsizes) than the largest size you are going to display them on the screen.
WP-photo-album-plus is capable to downsize the photos for you, but very often this fails because of configuration problems. 
Here is explained why:
Modern cameras produce photos of 7 megapixels or even more. To downsize the photos to either an automaticly downsized photo or
even a thumbnail image, the server has to create internally a fullsize fullcolor image of the photo you are uploading/importing.
This will require one byte of memory for each color (Red, Green, Blue) and for every pixel. 
So, apart form the memory required for the server's program and the resized image, you will need 21 MB (or even more) of memory just for the intermediate image.
As most hosting providers do not allow you more than 32 MB, you will get 'Out of memory' errormessages when you try to upload large pictures.
You can configure WP to use 64 MB (That would be enough in most cases) by specifying *define('WP_MEMORY_LIMIT', '64M');* in wp-config.php, 
but, as explained earlier, this does not help when your hosting provider does not allows the use of that much memory.
If you have control over the server yourself: configure it to allow the use of enough memory.
Oh, just Google on 'picture resizer' and you will find a bunch of free programs that will easily perform the resizing task for you.


== Changelog ==

See for additional information: http://wppa.opajaap.nl/?page_id=1459

= 4.3.10 =

= Other changes =

* Errors during upload caused by unwilling exif or iptc extraction are now suppressed when they are not fatal in standard mode (non-debug).

= 4.3.9 =

= Bug fixes =

* Fixed a erroneous link to a different given page.

= Other changes =

* Language files update (French)
* Various cosmetic fixes

= 4.3.8 =

= Bug fixes =

* Fixed link from covertitle/coverimage to slideshow. (Stopped working as per 4.3.6).
The computation of all links in the cover have been throughly revised and should function properly now
in both cases either Table IV-33 is checked or not (Ajax).

= Other changes =

* The use of Big Browse Buttons will now also change the url when ajax is enabled in Table IV-33.
* The urls created during browsing a slideshow are now equal to the respective single photo urls under the same conditions.
This means that after browsing a slideshow, the content of the browser addressline can be saved 
and used later to enter the slideshow at the specified point.

= 4.3.7 =

= Bug fixes =

* Fixed error in wppa-common-functions.php causing a fatal error during update.
* Fixed problem in wppa_get_permalink() causing many links to point to the homepage.

= 4.3.6 =

= Bug fixes =

* Photo of the day admin and Album admin: display of thumbs: link errors in multisite environment fixed.
* In a widget there will no longer be an empty box at the location of the comment box.

= New features =

* You can now switch BBB's separately for widgets (Table II-19).

= Other changes =

* Improved userfriendlyness of the selection of albums in the potd widget admin
* Increased ajaxification. You are strongly recommended to test your site with Table IV-33 checked.
* IPTC and EXIF tages can now be set to Optional: display when the content is not empty.
* The photo of the day will link to lightbox if lightbox is activated and if the link is set to: the plain photo(file).
* Simplified qTranslate interface.
* Importing photos that were previously exported will now properly import into albums with quotes in the name.
* Importing photos will no longer stop at an error, but will attempt to continue.

= 4.3.5 =

= Bug fixes =

* The name of the commenter will be properly displayed in the comment widget, even when the comment contains html.

= New Features =

* An editable copyright warning message can be added on the user upload section. Table II-31,32.
* The fullsize photo description can be aligned left, center or right. Table IV-32.
* New installations will have default chracter set utf8 and do not need to run Table VIII-2.

= Other changes =

* The tooltip on fullsize images now shows the photo name rather than the description.
* The Avatar can now be local.

= 4.3.4 =

= Bug fixes =

* Added a few forgotten translations

= New features =

* You can now recuperate IPTC and EXIF data from photo files that are already in wppa+ without updating them.
This will only work on photos not resized during the original upload/import. Table VIII item 12.

= Other changes =

* Built in a safety check and removal of linebreaks that will prevent many causes of broken slideshows.

= 4.3.3 =

= Bug fixes =

* Fixed a hangup on 16 bit servers when uploading/importing photo.
* The spinner image is now approx in the center of the fullsize image.
* A lot of issues when size=auto are fixed. Still no 100% guarantee for all (old) browsers that it works as desired.

= Other changes =

* All new features and improvements will only be implemented in the Auto Save versions of the Settings page and the Edit Album pages.
The old versions will get phased out. If you can not run the autosave versions please report that on the Forum: http://wordpress.org/extend/plugins/wp-photo-album-plus/
* Edit Album Autosave: The table is now sortable by clicking on the caption items.
A subsequent click on the same caption toggles up/down sort.
It also displays the number of subalbums and photos each album contains.
* Increased configurability for moderation of comments on photos. Table IV-30.
* Email address can be set to not required for comments on photos. Table IV-31.
* Titles in widgets display photo names rather than descriptions for fullsized photos. This is neater while descriptions often contain html code that can not be rendered in a tooltip.
* Captions of slidewidget and photo of the day widget now behave as expected during widget activation. 
You do no longer need to enter a html special space to have no title.
* Lightbox fullsize images (slideshow) will display the photo descriptions as subtitle.

= 4.3.2 =

= New features =

* Avatar default is now configurable (Table II-30,30a)
* Avatar size is now configurable (Table I-27)

= Bug fixes =

* Fixed errors in avatar code

= 4.3.1 =

= New features =

* IPTC and EXIF support has been added. This is configurable in Table II-28..29 and Table X and Table XI of the Settings page Auto save version only.
* You can set the display of avatars at the comments in Table II-30 on the Autosave settings page.
* The display of name, desc, rating in the thumbnail popup are now switcheable (Table II-25..27).
* Font weights are now settable in Table V.
* You can now optionally force the aspect ratio of thumbnails to a fixed value. either by clipping or by padding.

= Other changes =

* If you upgrade from a version prior to 4.2.11 and you used the wppa+ supplied lightbox, the configurable fullsize linktype (Table VI-8) will now be initialized to lightbox.

= Bug fixes =

* Fixed a fatal error in the potd widget when album selection was all-sep.

= 4.2.11 =

= Bug Fixes =

* Clear ratings on the edit album page now reports correct.
* Fixed a possible hangup during ajax rating auto next at end of slide cycle.

= New Features =

* If you do not use the wppa+ embedded lightbox but want to use a different lightbox(plugin) the required keyword used in 'rel="lightbox"' can now be set when it differs from 'lightbox'. (Table IX-9a).

= Other changes =

* The thumbnail popups will now popdown at mouseout.
* The inconsistency checks in the Settings Autosave are now dynamic and will change the moment you change the settings involved.
* You can select the link from fullsize to be one of: no link, plain file and lightbox. If yoy used lightbox on fullsize images, you will have to reset the setting in Table VI-8. 

= 4.2.10 =

= Bug Fixes =

* Clicking an item that was a result of a search operation caused the search creteria to be lost. Fixed.

= Other changes =

* This version is to make sure you will have hotfix 4.2.9 001: Fixed a slideshow problem when My Rating was not shown.
* The search results may now be retrieved in a direct link using &wppa-searchstring=.
* Tested on WP 3.3

= 4.2.9 =

= New Features =

* You can now decide if comments entered by logged in visitors are immediately approved (as before) or they need moderation like comments entered by 
not logged in visitors.

= Other changes =

* If you selected -first at no rated- for the slideshow start (Table IV - 3) and next after vote (Table IV - 26),
the show will indeed start at the first unrated slide as well as the already voted slides will successively 
be skipped as long as there are unrated photos. This works only with ajax voting on (Table IV - 27).
* You will get a confirmation box on actions in the auto-save settings screen.

= Hot Fixes =

001: Fixed a slideshow problem when My Rating was not shown.

= 4.2.8 =

= Bug Fixes =

* You can now enter the special characters & # and + in album and photo descriptions in the autosave version of album admin.
* Fixed a warning in the admin bar when logged in user has no rights on any wppa+ admin activity.
* Fixed a missing tag end in an img tag in photo of the day widget.

= New Features =

* There is now an Auto Update version of the Settings page. The default is 'on'. If you want to go back to the classic version, uncheck Table IX item 19.
* You can select a new way to start the slideshow: Still at the first photo the visitor did not rate. Table IV item 3.
* You can now switch off the wrapping around of the slideshow: Table IV item 29.

= 4.2.7 =

= Bug Fixes =

* The helptext of Table IX item 16 and 17 did not show up. Fixed.
* Fixed spurious error 106 in rating with ajax enabled while WP Supercache is activated.
* The green checkmark will now always show up when a vote is issued.

= Other changes =

* There is an alternate Album Admin page that updates album info and photo details immediately without the need to press a Save Changes button.
Enable this by checking Table IX item 18: Album Admin Autosave. This option is especially usefull in editing albums with very many photos.

= 4.2.6 =

= Other Changes =

* You can set 'Rating use Ajax' for the fastest way to rate photos. The page is not reloaded, but updated. Table IV item 27.
* The Rating star transparency in the off state can be set in Table IV item 28.
* The errormessage stating that the db tables do not exists for systems that do not properly respond to SHOW TABLES is now suppressed.

= 4.2.5 =

= Bug Fixes =

* If rating multi is enabled (Table IV item 18), My Rating is now correctly displayed as my avarage rating for this photo.

= New Features =

* You can set 'Next after vote' to jump directly to the next image of a slideshow after voting. See Table IV item 26.
* You can switch off the display of the avarage rating. See Table II item 24.

= 4.2.4 =

= Hotfix =

* 001: Pagetitles in breadcrumb will be processed by qTranslate.

= New Features =

* You can PS Overrule the fullsize images in the slideshow.

= Other Changes =

* An activation hook is supplied for those who trust on the healing effects of de- and re-activation of the plugin.
It acts the same as Table VIII item 3.
* Database table entry ids will not be re-used after deletion. Except of the import of previously exported photos and albums, their original ids will still be used if they are available.
* The existance of the required database tables and directories as well as the writability of those direcories is checked on entering the Settings admin page.
If anything misses or is not useable an errormessage will be displayed.
* The default value of the filter priority (Table IX item 10) has been changed from 10 (WP default) to 1001.

= 4.2.3 =

= New features =

* There is a new widget: Recent comments on photos.
* The Yellow stars ar split into two different items: star.png for the rating system, new.png for the new indicator.
A new.png is supplied. These images will have no border, padding, margin or box-shadow.

= Other changes =

* You will now see the existing comments even if entering comments are allowed when logged in only.
* A popuped thumbnail will now pop down by a rightclick.

= 4.2.2 =

= Bug fixes =

* Link to slideshow from topten widget linked to only one fullsize photo when topten was systemwide. Fixed.

= New features =

* You can apply a watermark to the fullsize image during upload/import. See Table IX item 14 .. 17.
* You can give album admin rights and upload rights to subscribers. See Table VII item 4 and 5.
If you use this feature, it is strongly recommended to set the album access to 'owners only' (Table VII item 2).
* The owner of an album can be set to --- public ---. When album access is set to 'Owners only' (Table VII item 2),
and upload rights are granted to certain roles, the corresponding users can upload to all their 'own' albums as well as
to --- public --- albums.

= Other changes =

* You will still get a warning message if you are uploading/importing images that are smaller than the thumbnail size, but they will be there. 
The thumbnails will be stretched to their minimum required size.

= 4.2.1 =

= Bug fixes =

* Fatal error on upload with update switch. Fixed.
* Under some circumstances it looked like photos were imported, but they were lost. Fixed.
* Delete album with move photos now works as designed.

= Other changes =

* Improved error handling and reporting during import / upload.

= 4.2.0 =

= Bug fixes =

* A security issue has been fixed
* Minor fix in filmstrip when size=auto.

= New features =

* There is an additional navigation tool: Number bar. See Table I-24, III-11&12, V-22&23&24. This requires the newly supplied wppa-style.css

= Other changes =

* Auto fix db can now be switched on or off
* More diagnostics in upload

= 4.1.1 =

= Bug fixes =

* When using album names in script shortcodes, quotes and html special characters are handled correctly now.
* Minor fixes and enhancements in the display of the Settings page.

= New features =

* You can specify a screensize different from the Full Size width and height when resize on upload is checked. Nice when you use lightbox!
* Photo Albums menu added to the admin bar, including a pending comments indicator.
* You can now select a linktype for an album cover (on a per album basis on the edit album admin page). 

= Other changes =

* The auto_increment clause has been removed from the id field of all 4 wppa db tables. 

= 4.1.0 =

= Bug fixes =

* Previous page link acts as next pagelink in comment admin page. Fixed.
* Repaired form validation in submit comment.

= New features =

* You can upload photos from the album cover and/or the thumbnail area display if this feature is enabled, you are logged in and have access to the album.
* Smilies will be displayed in the comments on photos if this feature is enabled in wp core.
* You can use names in album script shortcode tags like %%album=$My Album%% %%slide=, %%cover= and %%slideonly=. Note that the name is preceeded by a dollar sign.

= Other changes =

* All get-variables have a wppa- prefix. This increases the immunity to conflicts with certain themes and other plugins.
The old syntax is maintained to render properly for backward compatibility, i.e. saved urls with &album= etc. as opposed to the new &wppa-album= will still give the right results.
* Small changes and some additions to wp-photo-album-plus/theme/theme.css
* Fixed additional small collapse issues (see 4.0.12).
* Added IP field in comment admin to ease the finding of spam sources.
* Changed submit method for comments from 'get' to 'post'.

= 4.0.12 =

= Bug fixes =

* Copy photo error 4 fixed.
* Sql warning in create album fixed.
* Fixed various layout issues for browsers that do not support style property visibility:collapse on table(elements): in Settings screen and in comments display.

= New features =

* If you enable lightbox and disable big browse buttons, the fullsize images are clickable to a lightbox overlay.
* You can reverse order the comments on photos now. See Table IV item 25.

= Other changes =

* There are still users that have #content .img { max-width: 640px; } and Table I item 1 larger than 640, so we now increase max-width inline to column_width when it is not auto.
* You can now enter a photo description template that can be set to apply for new added photos. See Table IX item 11 and 12.

= 4.0.11 =

= Bug fixes =

* The slideframe height was 2 times the border width too small when v-align is set to 'fit'. Fixed.
* The BBB's overlapped downwards when v-align is set to 'fit'. Fixed.
* In IE9 the thumbnail popup links did not work. Fixed.

= New Features =

* The height of the slidefame in the slideshow widget is now explicitly settable as opposed to the calculated value from Table I item 2 and 3, vertical align 'fit' will still overrule, a value of 0 defaults to the old method.
* The ability to update existing photos with new versions. You can chek 'Update' in the Import Photos admin screen.
* There is now a custom box in the slideshow box list that you can fill with any html. See Table II item 21 and 22, Table III item 10.

= Other changes =

* In spurous situations the auto increment generated database key returned MAXINT, preventing us from further adding records.
The associated error message was: Could not insert photo. query=INSERT INTO wp_wppa_photos (id, ...
The new incremented key is now calculated outside mysql.

= Wish List =

* The ability to automaticly import photos from a given directory to a given album.

= 4.0.10 =

= New Features =

* There is now a tool to regenerate ratings (Table VIII item 8)

= Bug fixes =

* Changed the CDATA declarations to a form that will hopefully work in all themes.

= Other changes =

* The Create new album mechanism has been simplified.
* Scrolling back to the (previous) photo position after delete, copy or rotate in the album admin screen.
* Check/uncheck all in import admin page.

= 4.0.9 =

= New Features =

* Name and description in the sidebar slideshow widget.

= Bug Fixes =

* Removed blue color of comment age.
* Photo of the day widget defined link stopped working. Fixed.

= Other Changes =

* You can set the wppa+ filter priority value. This may be usefull to prevent conflicts with certain themes and/or plugins.

= 4.0.8 =

= New features =

* Lightbox configuration possibilities. See Settings page Table I item 23, III 8 & 9, IV 24, V 19 & 20 & 21.
* Order sequence settable for fullsize name and description. (See Table IX item 6.9)

= Bug Fixes =

* Popups pop down again at mouse leave.
* Under some circumstances, possible link page selection box was shown in Table VI items 2 and 3 where not appropriate. Fixed.
* All script is now embedded in CDATA blocks. This will fix certain causes of slideshow not functioning in certain themes.

= Other changes =

* Got rid of z-indexes, you need no longer change the menu css for overlapping slides.
* Improved errormessages and messages on inconsistent settings.

= Open Wish List =

* Name and description in the sidebar slideshow.

= 4.0.7 =

= New Features =

* lightbox support on thumbnails and topten thumbnails (See Table VI items 2 and 3, Table II item 20)

= Bug Fixes =

* Setting upload rights to contributors failed due to a typo. Fixed.
* Possible further fix to IE8 narrow images problem.
* Sites without qTranslate active would sometimes get qTranslate tags in names and descriptions. Fixed.

= 4.0.6 =

= New Features =

* Configurable New indicators on album covers and thumbnail images (See Settings page Table IX item 7 and 8).
* You can now easily import setting files other than your own backup. See OpaJaap-green.skin in Table VIII item 5. The file is located in wp-photo-album-plus/theme.

= Bug Fixes =

* The wppa+ admin menu structure has been revised to cope with several problems that made it impossible to save changes on wppa+ admin pages on some installations.

= Other Changes =

* Various cosmetic and functional improvements on the settings screen.

= 4.0.5 =

= New Features =

* Borders around fullsize images. See Settings page Table I item 22 and Table III item 7.
* You can now execute bulk actions on comments.
* Hebrew theme language files added

= Bug Fixes =

* Rating system stopped working at 4.0.4, fixed.

= 4.0.4 =

= Bug Fixes =

* When the coverwidth is set so that there will be more than 3 covers in a row, they will show up no longer in one column.

= Other changes = 

* Added height and width attributes to img tags. This may fix some layout problems with old browsers.

= 4.0.3 =

= Bug Fix =

* Repaired using get_bloginfo('wpurl') as opposed to get_bloginfo('url') to fix problem where sites using a non-default site address stopped displaying photos.

= Other changes =

* Changed display of phpinfo (Table X)

= 4.0.2 =

= Bug Fixes =

* Photo of the day admin caused a fatal error, fixed

= New Features =

* You can select *Top* and *Bottom* additionally to Right and Left for coverphoto display position (Table IV item 13). 
A spinoff of this enhancement are the folowing:
* The 2 and 3 column treshold values (Table I items 17 and 18) have been replaced by Maximum cover width (item 17).
This basically does the same as the 2 column treshod value, but is more user understandable and makes the 3col treshold superfluous.
Note: If you had set the 2 column treshold exactly to the column width before, 
you may need to change this setting as the old value (that will be used) will result in one column instead of two.
* There is a new item 18: Minimal cover text frame height, that makes it easier to get the covers equal in height. 
Additionally will you need to keep the coverphotos all landscape (with the same aspect ratio) or portrait to keep the covers equal in height.

= 4.0.1 =

* The Big Browse Buttons are now optional (Table II item 19).
* The BBB's will have no border.


= 4.0.0 =

= New features =

* WPPA+ Now supports multisite installations.

= Bug fixes =

* A clicking monkey will no longer be able to get the slideshow into a hangup state.

= Other changes =

* The sequence order of the slideshow parts (bars and photoframe) is now settable in the Settings screen (Table IX item 6.x). 
There is no longer a known reason as of to modify wppa-theme.php.
* The Big Browse Buttons are now invisible, but have a title and a cursor and have the size of half the slideframe each.
* The Filenames now comply with the wp coding standards.
* The Photo Links can be set to overrule with the photo specific link - if any. 
This behaviour can be set for all photo link types independantly. 
The 'Use photo specific link' linktype is hereby obsolete and has been removed as a selection option.
* Table X has been extended with WPPA+ constants and all other PHP settings.
* Border radius in css3 format added (IE9)

= Wish List =

* Cover photo above or below the text (Vertical shape of cover).
* Indication of NEW for photos and albums with configurable NEW period.


= 3.1.8 =

= Bug fixes = 

* fixed an errormessage in debug mode

= New features = 

* You can set the thumbnail popup image size now explicitly. (Before it was the unscaled thumbnail image)


= 3.1.7 =

= Bug Fixes =

* After introduction of the link with print option, all other linktypes failed. Fixed.


= 3.1.6 =

= New features =

* New link type added for thumbnails and topten thumbnails: the fullsize photo with a print button. 
This will open the fullsize photo in a new browser window and enables you to print the photo with the description below it.

= Bug Fixes =

* Fixed an RSS bug in displaying thumbnails.

= Other changes =

* Reverted the change made in version 3.1.3 for the algorithm to decide if the indicator must be printed. 
It turned out to create a bigger problem than it solved. (This change was made in 3.1.5 but not yet documented as such.


= 3.1.4 =

= Bug Fixes =

* The static text in the photo comment form and alert boxes is now properly translatable.
* The behaviour after input of incomplete comment has been corrected.

= Other changes =

* Cosmetic and reliability enhancements in slideshow.
* Update text 3.1.3 Other changes to fix the first item issue.


= 3.1.3 =

= Other changes =

* The algoritm to decide if the indicator [WPPA+ Photo display] must be printed has been improved. 
Only the first in a list of excerpts (archive or search results when the_excerpt() is used as opposed to the_content()) may be wrong.
You can correct this by adding the following line of code just prior to *the_excerpt();* in the template files involved: *global $wppa; $wppa['is_excerpt'] = true;*
* Uses display name rather than login name in comments on photos.

= 3.1.2 =

= Bug Fixes =

* Fixed breaking js execution caused by a newline in an comment edit.

= 3.1.1 =

= Bug Fixes =

* You can have single quotes in comments now.

= Other changes =

* Removed changelog prior to version 3.0.0
* Minor cosmetic changes

= 3.1.0 =

= New Features =

* A per photo based comment system has been added.
* Big Browsing Buttons. When hovering near the left and right edges of the fullsize image when the slideshow is stopped, big left (previous) and right (next) browse buttons appear.

= Enhancements =

* Admin pages load only when used, this results in less server memory usage and speed-up of all admin pages.
* The name and description under the fullsize images is now combined in a wppa+ box. You can still set fonts individually, you can also switch them on/of individually.
If you like the 'old' display method, this is still possible; see the explanation in /theme/wppa_theme.php.

= Bug Fixes =

* You can manipulate and delete Albums and Photos now even when their id is greater then 2147483647.

= 3.0.7 =

= Enhancements =

* The way the plugin is re-activated after an update has been changed due to the fact that wp does no longer run the activation hook after update.
You should no longer get the messages that the 'database rev is not yet updated' and 'i fixed that for you'. 
Manual re-initialization still remains possible with the settings page table VIII item 3.
* The horizontal alignment of the photo of the day widget content can be set to none, left, center or right on the photo of the day admin page.
The text goes along; if you want the photo and the text align differently, set alignment to --- none --- and use css (classes wppa-widget-photo and wppa-widget-text).
* Added script keyword: #last. %%photo=#last%% or %%mphoto=#last%% gives the last added photo. %%album, %%cover, %%slide=#last%% etc gives the last added album.
* Better qTranslate support for the photo of the day admin page.

= Bug fixes =

* In an archive, you will get a marker at the place of an wppa+ invocation rather than the display of javascript.

= 3.0.6 =

= New features =

* You can now easily disable the display of all text except the album title from the albumcover. Table II item 17.
* You can append &debug (?debug if it is the first argument) in the adress bar of the browser to switch debug mode on.
An optional integer can be set to set the php error reporting switches. Default = 6143 (E_ALL). Example: &debug=-1 (switches everything on: wppa debug, php's E_ALL and E_STRICT).
This feature can be anabled/disabled by the setting in Table IX item 5.
If switched on, the WPPA+ system will produce diagnostic messages, together with the normal php errors and warnings.
It works for both admin as well as site views. Links within the WPPA+ system include the debug switch (and optional value).
The main wp admin menu items are beyond the scope of this feature. Press the menuitem, append &debug to the adressbar here.
* You can optionally switch the filmstrip and/or the browsebar on in the slideshow widget.
* Clicking the counter (Photo xx of yy, or xx / yy in the mini version) will start/stop the slideshow.
* You can specify an album for the topten widget. Now it is usefull to have more than one topten widget by using different albums.
* A start has been made with 'keywords' in places of numbers. You can issue the script command: %%photo=#potd%% to use the photo of the day in a page or post.

= Enhancements =

* In a widget, the album cover text will appear above or below the cover photo. This can be set by the coverphoto left/right switch. Table IV item 13.
This works also for "thumbnail as covers".
* The Photo of the day widget photo will be centered horizontally, no padding setting is required anymore.
* The filmstrip will be half the normal size in widgets.

= 3.0.5 =

= Bug fixes =

* IMPORTANT Fix: All problems that are related to pre-rendering are fixed. 
The problems with themes like Thesis and plugins like the face-book-meta-tags-plugin that 
perform a pre-rendering of a post or excerpt are solved now. 
The restrictions on using the rating system (that did not work anyway) are no longer applicable.
* Under some circumstances when using qTranslate, the proper language file was not loaded. Fixed.

= Hot fixes after initial release =

* 001: Fixed erroneous link in albumcover

= 3.0.4 =

= New features = 

* You can back-up and restore the settings and reset them to default values.
* Added table X in the settings panel, being a read only table displaying the php configuration.

= Enhancements =

* Improved error reporting and documentation of limitations in admin pages.

= Bug fixes =

* Fixed an no harmfull warning in photo of the day widget admin page.
* Removed a superfluous p-opening tag.

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).

= Hotfixes after initial release =

* 001: Added class wppa-slideshow-browse-link to enable hiding it with display: none. This was a special cutomer request and not an error.
* 002: Photo specific link will now also be copied during a copy photo action.
* 003: Removed an empty <p></p> right before a wppa invocation. 
* 004: Fix for facebook plugin (?)

= 3.0.3 =

= New features = 

* Increase configurability of links from album cover photo.
* A re-initializing action (Table VIII, item 3) has been added. This will be helpfull in multiblog (network) sites.

= Bug fixes =

* Includes all hot-fixes since 3.0.2.000.
* Minor cosmetic changes in the new settings page.

= Hot fixes after initial release =

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).


= 3.0.2 =

= New features = 

* The Settings page has been rewritten to make it more user friendly. 
All settinges are grouped into tables, and are identifiable by its table number and item number.
* Increased link configurability. You can link mphotos and thumbnails now also to the plain file. 
You can define photo specific links: All photos can have a unique link url and title. 
You can choose to use that link in all 5 different places where a photo link can be configured. 
Please check the link settings in the Settings screen, Table VI. You might want to change something there.
* Additionally to the family and size you can now also set the colors for the fonts used in wppa+.

= Bug fixes =

* Includes all hot-fixes since 3.0.1.000.
* The mouseover effect now also works on TopTen thumbnail images.
* Fix for Column width = auto. This works now the same like %%size=auto%%

= Hot fixes after initial release =

* 001: Made noncefield conditional to rating system enabled
* 002: Admin functions now also work in SSL admin
* 003: If an image has a link configured, the cursor will be a pointer (hand).


= 3.0.1 =

= New features =

* WPPA+ Now supports Multi language sites that use qTranslate. 
Both album and photo names and descriptions follow the qTranslate multilanguage rules.
In the Album Admin page all fields that are multilingual have separate edit fields for each activated language.
For more information on multilanguage sites, see the documentation of the qTranslate plugin.

= Enhancements =

* You can link media-like photos (those made with %%mphoto=..%%) to a different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You will now get a warning message inclusive an uncheck of the box if your jQuery version does not support delay and therefor not the fadein after fadeout feature.
* Improved consistency in the layout of the different types of navigation bars.

= Pending enhancement requests =

* Multisite support
* More than one photo of the day
* Fullscreen slideshow

= Known bugs =

* None, if you find one, please let me know and i will fix 'm

= Hot fixes since the initial release =

* 001: HTML in photo of the day widget fixed
* 002: Fixed 'Start undefined'
* 003: You can now rotate images when they are already uploaded
* 004: Photo of the day option change every pageview added
* 005: Photo of the day split padding top and left
* 006: If Filmstrip is off you can overrule display filmstrip by using %%slidef=.. and %%slideonlyf=..
* 007: Clear:both added to thumbnail area
* 008: Fixed a problem where photos were not found if the number of found photos was less than or equal to the photocount treshold value
* 009: You can now upload zipfiles with photos if your php version is at least 5.2.7.
* 010: Fixed a Invalid argument supplied for foreach() warning in upload.
* 011: Fixed a wrong link from thumbnail to slideshow.
* 012: Changed the check for minimal size of thumbnail frame.
* 013: Fixed a problem where a bullet was displayed as &bull in some browsers.
* 014: Fixed a problem where the navigation arrows in the filmstrip were not hidden if the startstop bar was disabled.
* 015: New feature: If slideshow is enabled, double clicks on filmthumbs toggles Start/stop running slideshow. Tooltip documents it.
* 016: Slides and filmthumbs have the same sequence now when ordering is Random.
* 017: Some people do not read the settings page and get in panic when they see two or three colums of album covers after an upgrade, so i changed the defaults for the columns tresholds to 1024.
* 018: TopTen widget initializes runtime also now, just in case it is the first.
* 019: Fixed alignment problem in multi column, unequal cover heights.
* 020: Photo of the day widget now also initializes runtime.
* 021: Fix for pre-rendering themes like thesis.

= 3.0.0 =

= New features =

* You can link thumbnails to different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You can link the photo of the day to a full-size photo on its own or in a slideshow/browseable or to the current photos album contents display (thumbnails).
* You can set the thumbnail display type to --- none ---. This removes the 'View .. photos' link on album covers, while keeping the 'View .. albums' link.
* When the Slideshow is disabled and there are more than the photocount treshold photos, the 'Slideshow'-link is changed to 'Browse photos' with the corresponding action.
* The front end (theme) is now seperately translatable. Only 43 words/small sentences need translation. A potfile is included (wppa_theme.pot).
* You can now easy copy a single photo to an other album in the Photo Albums -> Edit album admin page.
* There is a new script command: %%mphoto=..%%. This is an alternative for %%photo=..%% and displays the single photo with the same style as normal media photos with background and caption. No associated links yet.

= Bug fixes =

* The 'Slideshow' and 'Browse photos' link now also point to the page selected in the edit album form.

= Hot fixes after initial release =

* 001: [caption] is not allowed to have html (wp restriction), tags are now removed from photo description for use with [caption]
* 002: Fixed a breadcrumb nav that did not want to hide itself when Display breadcrumb was unchecked
* 003: You can now import media photos from the upload directory you specified in the wp media settings page also when it is not the default dir.
* 004: Fixed a problem where, when pagination is off, in a mixed display of covers and thumbs, the covers were not shown.
* 005: added class size-medium to mphotos ([caption])


= Notes =

* Due to internal changes, there is a speed-up of apprix 30% with respect to earlier versions.
* Due to internal changes, you will have to re-modify wppa_theme.php if you used a modified one. wppa_theme is now a function.
* Due to internal changes, it is most likely that this problem will be fixed: http://wordpress.org/support/topic/plugin-wp-photo-album-plus-page-drops-when-activated-on-page?replies=24#post-1965780
* If you had set *No Links* for thumbnails, you will have to set it again.

== Known issues ==

* The Big Browse Buttons are transparent. IE 6 does not know about transparency. Therefor the slidshow will not display properly in IE6 with BBB's enabled.
* The plugin My Live Signature completely destroys the display from wppa+ and also damages other filters. DO NOT INSTALL My Live Signature!
* The theme Moses from Churchthemer.com uses jQuery in unsafe mode. This conflicts with prototype. Therefor you can NOT use WPPA+ embedded lightbox.
* The plugin Shortcodes Ultimate formats the content and thereby damages the wppa+ generated code by a filter at priority 99. 
Set the wppa+ filter priority to at least 100 to deal with this conflicting situation. (Table IX item 10)

== About and Credits ==

* WP Photo Album Plus is extended with many new features and is maintained by J.N. Breetvelt, ( http://www.opajaap.nl/ ) a.k.a. OpaJaap
* Thanx to R.J. Kaplan for WP Photo Album 1.5.1, the basis of this plugin.

== Licence ==

WP Photo Album is released under the GNU GPL licence. ( http://www.gnu.org/copyleft/gpl.html )