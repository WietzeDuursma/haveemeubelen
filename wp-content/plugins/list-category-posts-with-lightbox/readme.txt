=== List category posts with lightbox===
Contributors: opensourcetech
Plugin URI: http://www.opensourcetechnologies.com/
Tags: list, categories, posts, cms , Lightbox, popup
Author: Opensourcetechnologies
Author URI: http://www.opensourcetechnologies.com/
Stable tag: 1.0
Requires at least: 2.8
Tested up to: 3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

List members posts with lightbox allows you to list posts (with featured image) from a category into a post/page using the [catlist] shortcode.

== Description ==
List members posts with lightbox (with featured image) allows you to list posts from a category into a post/page using the [catlist] shortcode.

The shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can also display the post author, date, excerpt, custom field values, even the content! The [catlist] shortcode can be used as many times as needed with different arguments on each post/page.
 A lightbox will be popup after clicking a particular image of a post. It will display the post content regarding the featured image.
Great to use WordPress as a CMS, and create pages with several categories posts.

 If you want custom changes or styles you can contact us at info@opensourcetechnologies.com


**Widget**: It also includes a widget which works pretty much the same as the plugin. Just add as many widgets as you want, and select all the available options from the Appearence > Widgets page.

**Usage**

`[catlist thumbnail=yes id=16 thumbnail_size=70,70]`

==Installation==

* Upload zip file into your wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* You can find the List Category Posts widget in the Appearence > Widgets section on your WordPress Dashboard.
* If you want to customize the way the plugin displays the information, check the section on Templates on this documentation.

==Other notes==

**Selecting the category**

The plugin can figure out the category from which you want to list posts in three different ways: Using the *category id*, the *category name or slug* and *detecting the current post's category*.
When using List members posts with lightbox inside a post, if you don't pass the category id, name or slug, it will post the latest posts from every category. 
You can use the *categorypage* parameter to make it detect the category id of the current posts, and list posts from that category.
 The parameters for choosing the category id are:

* **name** - To display posts from a category using the category's name or slug. Ex: [catlist name=mycategory]

* **id** - To display posts from a category using the category's id. Ex: [catlist id=24]. You can **include several categories**: Ex: [catlist id=17,24,32] or **exclude** a category with the minus (-)

* **categorypage** - Set it to "yes" if you want to list the posts from the current post's category.

**Other parameters**

* **tags** - Tag support, you can display posts from a certain tag. 

* **orderby** - To customize the order. Valid values are: 
  * **author** - Sort by the numeric author IDs.
  * **category** - Sort by the numeric category IDs.
  * **content** - Sort by content.
  * **date** - Sort by creation date.
  * **ID** - Sort by numeric post ID.
  * **menu_order** - Sort by the menu order. Only useful with pages.


  * **name** - Sort by stub.


  * **rand** - Randomly sort results.

  * **title** - Sort by title.


* **order** - How to sort **orderby**. Valid values are:
  * **ASC** - Ascending (lowest to highest).
  * **DESC** - Descending (highest to lowest). Ex: [catlist name=mycategory orderby=title order=asc]

* **numberposts** - Number of posts to return. Set to 0 to use the max number of posts per page. Set to -1 to remove the limit. Default: 5. Ex: [catlist name=mycategory numberposts=10]

* **date** - Display post's date next to the title. Default is 'no', use date=yes to activate it.

* **author** - Display the post's author next to the title. Default is 'no', use author=yes to activate it.

* **dateformat** - Format of the date output. Default is get_option('date_format'). Check http://codex.wordpress.org/Formatting_Date_and_Time for possible formats.

* **excerpt** - Display the post's excerpt. Default is 'no', use excerpt=yes to activate it.

* **excerpt_size** - Set the number of characters to display from the excerpt. Default is 255. Eg: `excerpt_size = 300`

* **excludeposts** - IDs of posts to exclude from the list. Use 'this' to exclude the current post. Ex: [catlist excludeposts=this,12,52,37]

* **offset** - You can displace or pass over one or more initial posts which would normally be collected by your query through the use of the offset parameter.

* **content** - Show the full content of the post. Default is 'no'. Ex: [catlist content=yes]

* **catlink** - Show the title of the category with a link to the category. Use the **catlink_string** option to change the link text. Default is 'no'. Ex: [catlist catlink=yes]. The way it's programmed, it should only display the title for the first category you chose, and include the posts from all of the categories. I thought of this parameter mostly for using several shortcodes on one page or post, so that each group of posts would have the title of that group's category. If you need to display several titles with posts, you should use one [catlist] shortcode for each category you want to display.

* **comments** - Show comments count for each post. Default is 'no'. Ex: [catlist comments=yes].

* **thumbnail** - Show post thumbnail. Default is 'no'. Ex: [catlist thumbnail=yes].

* **thumbnail_size** - Either a string keyword (thumbnail, medium, large or full) or 2 values representing width and height in pixels. Ex: [catlist thumbnail_size=32,32] or [catlist thumbnail_size=thumbnail]

* **thumbnail_class** - Set a CSS class to the thumbnail and style it.

* **post_type** - The type of post to show. Available options are: post - Default, page, attachment, any - all post types.

* **post_parent** - Show only the children of the post with this ID. Default: None.

* **class** - CSS class for the default UL generated by the plugin.

* **custom fields** - To use custom fields, you must specify two values: customfield_name and customfield_value. Using this only show posts that contain a custom field with this name and value. Both parameters must be defined, or neither will work.

* **customfield_display** - Display custom field(s). You can specify many fields to show, separating them with a coma.

* **template** - File name of template from templates directory without extension. Example: For 'template.php' value is only 'template'. Default is 'default', which displays an unordered list (ul html tag) with a CSS class. This class can be passed as a parameter or by default it's: 'lcp_catlist'. You can also use the default 'div' value. This will output a div with the 'lcp_catlist' CSS class (or one you pass as parameter with the class argument). The inner items (posts) will be displayed between p tags.

* **morelink** - Include a "more" link to access the category archive for the category. The link is inserted after listing the posts. It receives a string of characters as a parameter which will be used as the text of the link. Example: [catlist id=38 morelink="Read more"]

== HTML & CSS Customization ==

You can customize what HTML tags different elements will be sorrounded with and a CSS class for this element. The customizable elements are: author, catlink (category link), comments, date, excerpt, morelink ("Read More" link), thumbnail and title (post title).

The parameters are:
`autor_tag, author_class, catlink_tag, catlink_class, comments_tag, comments_class, date_tag, date_class, 
excerpt_tag, excerpt_class, morelink_class, thumbnail_class, title_tag, title_class`

So for example, let's say you want to wrap the displayed comments count with the p tag and a "lcp_comments" class, you would do:
`[catlist id=7 comments=yes comments_tag=p comments_class=lcp_comments]`
This would produce the following code:
`<p class="lcp_comments"> (3)</p>`

Or you just want to style the displayed date, you could wrap it with a span tag:
`[catlist name=blog date=yes date_tag=span date_class=lcp_date]`
This would produce the following code:
`<span class="lcp_date">March 21, 2011</span>`

== Template System ==

Templates for the List members posts with lightbox are searched for in your WordPress theme's folder. You should create a folder named List members posts with lightbox under 'wp-content/themes/your-theme-folder'. Template files are .php files.

You can use the included template as an example to start. It's in the plugin's template folder under the name default.php. To use a template, use this code:
[catlist id=1 template=templatename]
If the template file were templatename.php.

You can have as many different templates as you want, and use them in different pages and posts. The template code is pretty well documented, so if you're a bit familiar with HTML and PHP, you'll have no problems creating your own template. I'm planning on reworking the template system in order to have a really user friendly way to create templates.

== Screenshots ==

For Screenshots, please visit:<p>http://www.opensourcetechnologies.com/wordpress-plugins/list-category-posts-with-lightbox.html</p>
