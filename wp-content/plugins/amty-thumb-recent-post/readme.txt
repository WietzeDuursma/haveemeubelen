=== amtyThumb posts ===
Contributors: Amit Gpta
Donate link: http://article-stack.com/
Tags: thumbnail, recent, random, popular, post, amty, image, widget, shortcode, mostly-viewed, rarely-viewed, recently-viewed, most-commented, last-viewed
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 8.1.3

All-In-One. It shows Recently written, Recently viewed, Random, Mostly & Rarely Viewd, Mostly Commented posts with thumbnail in your style.

== Description ==

You can customize the view in your way. It uses [amtyThumb](http://wordpress.org/extend/plugins/amtythumb/ "amtyThumb") plugin to extract first image for a post.

Fully customizable. You may control thumbnail size, Title length, appearance, etc.
If you don't have any image in a post, you can set default image too.

You can display posts for following with single plugin.
1. What other users are reading now.
2. Highly discussed posts
3. Highly visited posts
4. Random pick or pick of the day.
5. Untouched articles
6. Recently submitted posts


= Features over other plugins =

1. amtyThumb plugin is best to extract any type of image from any post.
2. No need to set separate field for image in your post.
4. If there is no image in the post it can show default image.
5. Appearance is fully customizable through widget panel or from short code.
6. You can test plugin through admin page.
7. List posts : You can list posts sorted by number of comments, views, last visted time etc. It'll help you

Coming soon : One call function to build theme pages.

= Dependency & Usage=

1. amtyThumb plugin must be installed for image extraction.

== Installation ==

Installation of plugin is similar to other wordpress plugin.

e.g.

1. Extract `amtyThumb_post.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add widget to your sidebar using widget panel of your dashboard.

You may also add amtyThumb_post anywehre in your post or page using short code. For his;

	[amtyThumb show_thumb='no']
	[amtyThumb max_post ='3']

Possible parameters with default values

	'title' => 'Amty Thumb Posts', //'' to hide it
	'before_title' => '<h2>',
	'after_title' => '</h2>',
	'thumb_width' => '70',
	'thumb_height' => '70',
	'constrain' => '1',
	'default_img_path' => '', //should be set
	'pretag' => '',
	'template' => '',	//<li><img src="%POST_THUMB%" /><a href="%POST_URL%"  title="%POST_TITLE%">%POST_TITLE%</a></li>				 
	'posttag' => '',
	'title_len' => 30,
	'max_post' =>  10,
	'category' => 'All',
	'widgettype' => 'Recently Written' //'Recently Written','Random','Most Commented'

= Template Parameter =

1. %VIEW_COUNT% - Display number of times post is visited.
2. %POST_THUMB% - Display thumbnail 
3. %POST_URL% - Display post param link
4. %POST_TITLE% - Display post title
5. %POST_CONTENT% - Display stripped post content
6. %POST_EXCERPT% - Display post excerpt
7. %POST_AUTHOR% - Display post author
8. %POST_DATE%	- Not supported when displaying recently viewed posts
9. %SHORT_TITLE% - Display short title.
10. %COMMENTS_COUNT% - Number of comments

= Functions =
1. getAmtyViewCount(post_id) to get number of views.
2. getLastVisitedTime(post_id) to get last visit time.

For any doubt or query visit [article-stack](http://article-stack.com/other/amty-thumb-recent-is-now-amty-thumb-posts.amty "amty thumb recent")


== Frequently Asked Questions ==



= Only default images are being shown =

Check whether you had optimized your database. Remove sub versions of your posts. It'll not only help to extract exact image but will decrease loading time of your blog.

= Still not able to see resized image =

Check whether you have PHP GD library installed.

= Does it supports all image types? =

No, It supports only jpg,png and gif. I hadn't tested it for others.


For more queries visit [article-stack](http://article-stack.com/other/amty-thumb-recent-is-now-amty-thumb-posts.amty "amty thumb recent")

== Screenshots ==

For live example visit [article-stack](http://article-stack.com/other/amty-thumb-recent-is-now-amty-thumb-posts.amty "amty thumb recent")
	or [Think Zara Hatke](http://thinkzarahatke.com "Colletion of unique thinks")

== Changelog ==

= 8.1.3 =
* fixed getAmtyViewCount($pid) function

= 8.1.2 =
* removed duplicate error. causing plugin instalation failure

= 8.1.1 =
* fixed a bug of calling amtyThumb plugin
* Now user can decide wheter an image should resized in ratio or be stretched as per given dimentions.


= 8.0.1 =
* fixed a vry minor and silly bug of broken image

= 8.0.0 =
* change in template codes for better customized apperance
* removed dependecny from 2 external plugins for analysing number of views and last visit time.
* Now you can display stripped post content
* extra functions to get views count or last visit time
* admin panel for admin to view posts stats.
* small code imporvment to spped up processing

= 7.1.0 =
* Addtion of %SHORT_TITLE% template code to display short title

= 7.0.0 =
* Template support for highly customized view.
* Many changes related to appearance
* Display Mostly, Rarely & Recently Viewed posts
* Support for WP-PostViews & Recently Viewed Posts plugins.


= 6.5 =
* Thumbnail part is separated.
* Support for amtyThumb plugin to generate thumbs.

= 6.1 =
* Bug fix for plugin directory name.

= 6.0 =
* Bug fix for finding first image.
* Support for generating thumbnail from youtube video

= 5.5 =
* Previous version was not able to extract first image, if your wordpress database is not optimized. Now it can work with database which is not optimized.


= 5.0 =
* Now it can be added anywhere in your source code or page or post.

= 4.0 =
* Compatible with New Wordpress version above than 2.9
* Display Recent or Random or Popular posts
* Display posts from specific category.
* Display Bug Fix. Previously, in case of Horizontal view, Next widget was being shown properly with come themes.
* Display Bug fix. Previously, in case of Horizontal view, Post title were overlapping.
* Changes in Widget control panel. Now it can remember what value you set last time.

= 3.0 =
* 20 times faster than previous version.
* Reduced page size.


= 2.0 =
* Display first image of recently added posts with subtitle.
* Display default image
* Don't reduce the image size. Just resize its height & width.

== Upgrade Notice ==

= 8.1.3 =
* fixed getAmtyViewCount($pid) function

= 8.1.2 =
* removed duplicate error. causing plugin instalation failure

= 8.1.1 =
* fixed a bug of calling amtyThumb plugin
* Now user can decide wheter an image should resized in ratio or be stretched as per given dimentions.

= 8.0.1 =
* fixed a vry minor and silly bug of broken image

= 8.0.0 =
* change in template codes for better customized apperance
* removed dependecny from 2 external plugins for analysing number of views and last visit time.
* Now you can display stripped post content
* extra functions to get views count or last visit time
* admin panel for admin to view posts stats.
* small code imporvment to spped up processing