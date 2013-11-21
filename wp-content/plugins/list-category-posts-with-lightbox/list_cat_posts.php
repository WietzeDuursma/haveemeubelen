<?php
/*
Plugin Name: List members posts with lightbox
Plugin URI: http://opensourcetechnologies.com
Description: List members posts with lightbox allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
Version: 0.24
Author: Opensourcetechnologies
Author URI: http://opensourcetechnologies.com
*/

/* Copyright 2012

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
wp_enqueue_script( 'jquery' );
//$plugin_url = plugins_url();
include 'include/ListCategoryPostsWidget.php';
require_once 'include/CatListDisplayer.php';
    /**
     * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
     */
	add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );
	add_action( 'wp_enqueue_scripts', 'fancybox' );
	add_action( 'wp_enqueue_scripts', 'fancybox_js' );
	//add_action( 'wp_enqueue_scripts', 'my_jquery' );
	
    /**
     * Enqueue plugin style-file
     */
    function prefix_add_my_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
        wp_enqueue_style( 'prefix-style' );
	//wp_enqueue_script( 'jquery' );

    }
	 function fancybox() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'my-fancybox-style', plugins_url('fancybox/jquery.fancybox.css', __FILE__) );
        wp_enqueue_style( 'my-fancybox-style' );
    }
	
	 function fancybox_js() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_script( 'my-fancybox_js', plugins_url('fancybox/jquery.fancybox.js?v=2.0.6', __FILE__) );
        wp_enqueue_script( 'my-fancybox_js' );
    }
	
	function my_jquery() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_script( 'my-jquery', plugins_url('jquery.min.js', __FILE__) );
       // wp_enqueue_style( 'prefix-style' );
			//wp_enqueue_script( 'my-jquery' );
			wp_enqueue_script( 'jquery' );
    }
	
class ListCategoryPosts{
    /**
     * Gets the shortcode parameters and instantiate plugin objects
     * @param $atts
     * @param $content
     */
    function catlist_func($atts, $content = null) {
            $atts = shortcode_atts(array(
                            'id' => '0',
                            'name' => '',
                            'orderby' => 'date',
                            'order' => 'desc',
                            'numberposts' => '5',
                            'date' => 'no',
                            'date_tag' => '',
                            'date_class' =>'',
                            'dateformat' => get_option('date_format'),
                            'author' => 'no',
                            'author_tag' =>'',
                            'author_class' => '',
                            'template' => 'default',
                            'excerpt' => 'no',
                            'excerpt_size' => '255',
                            'excerpt_tag' =>'',
                            'excerpt_class' =>'',
                            'exclude' => '0',
                            'excludeposts' => '0',
                            'offset' => '0',
                            'tags' => '',
                            'content' => 'no',
                            'content_tag' => '',
                            'content_class' => '',
                            'catlink' => 'no',
                            'catlink_string' => '',
                            'catlink_tag' =>'',
                            'catlink_class' => '',
                            'comments' => 'no',
                            'comments_tag' => '',
                            'comments_class' => '',
                            'thumbnail' => 'no',
                            'thumbnail_size' => 'thumbnail',
                            'thumbnail_class' => '',
                            'title_tag' => '',
                            'title_class' => '',
                            'post_type' => '',
                            'post_parent' => '0',
                            'class' => 'lcp_catlist',
                            'customfield_name' => '',
                            'customfield_value' =>'',
                            'customfield_display' =>'',
                            'taxonomy' => '',
                            'categorypage' => '',
                            'morelink' => '',
                            'morelink_class' => ''
                    ), $atts);

            $catlist_displayer = new CatListDisplayer($atts);
            return $catlist_displayer->display();
    }

}

add_shortcode( 'catlist', array('ListCategoryPosts', 'catlist_func') );

/**
 * TO-DO:
Add Older Posts at bottom of List members posts with lightbox page
  http://wordpress.stackexchange.com/questions/26398/add-older-posts-at-bottom-of-list-category-post-page
Getting the “more” tag to work with plugin-list-category-post
  http://wordpress.stackexchange.com/questions/30376/getting-the-more-tag-to-work-with-plugin-list-category-post
- Fix the code for the WordPress Coding Standards: http://codex.wordpress.org/WordPress_Coding_Standards
- i18n
- Pagination
- Simpler template system
- Exclude child categories
 */
