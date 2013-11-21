<?php
/*
Plugin Name: amtyThumb Posts
Plugin URI: http://article-stack.com/
Description: Shows Recently written, Recently viewed, Random, Mostly & Rarely Viewd, Mostly Commented posts with thumbnail.
You may customize it in any way. It uses amtyThumb plugin to extracts first image of your current post.
Fully customizable. You may control thumbnail size, Title length, apperance, and alomst everything

Author: Amit Gupta
Version: 8.1.3
Author URI: http://article-stack.com/
*/
include("supportingfunctions.php");

add_action( 'widgets_init', 'thumb_posts_widgets' );
add_shortcode( 'amtyThumb', 'amtyThumb_shortcode' );

function thumb_posts_widgets() {
	register_widget( 'amtyThumb_posts' );
}


class amtyThumb_posts extends WP_Widget {
	
	function amtyThumb_posts() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'amty_thumb', 'description' => __('Displays posts with thumbnail', 'amtyThumb_posts') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'amty-thumb-recent' );
		
		/* Create the widget. */
		$this->WP_Widget( 'amty-thumb-recent', __('Amty Thumb Posts', 'amtyThumb_posts'), $widget_ops, $control_ops );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and width & Height to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titlelen'] = strip_tags( $new_instance['titlelen'] );
		$instance['contentlength'] = strip_tags( $new_instance['contentlength'] );//contentlength
		$instance['maxpost'] = strip_tags( $new_instance['maxpost'] );
		$instance['width'] = strip_tags( $new_instance['width'] );
		$instance['height'] = strip_tags( $new_instance['height'] );		
		
		//$instance['show_default'] = $new_instance['show_default'];
		$instance['default_img_path'] = $new_instance['default_img_path'];
		$instance['pretag'] = $new_instance['pretag'];
		$instance['posttag'] = $new_instance['posttag'];
		$instance['template'] = $new_instance['template'];		
		$instance['category'] = $new_instance['category'];
		$instance['widgettype'] = $new_instance['widgettype'];
		return $instance;
	}



function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Amty Thumb Recently Written',
					 'width' => '70',
					 'height' => '70',
					 'default_img_path' => '',
					 'pretag' => '<ul>',
					 'template' => '<li><img src="%POST_THUMB%" /><a href="%POST_URL%"  title="%POST_TITLE%">%POST_TITLE%</a></li>',
					 'posttag' => '</ul>',
					 'titlelen' => 30,
					 'contentlength' => 200,
					 'maxpost' =>  10,
					 'category' => 'All',
					 'widgettype' => 'Recently Written'
					  );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>

		<p>
			<select id="<?php echo $this->get_field_id( 'widgettype' ); ?>" name="<?php echo $this->get_field_name( 'widgettype' ); ?>" style="width:80px;">
				<option <?php if ( 'Recently Written' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Recently Written</option>
				<option <?php if ( 'Random' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Random</option>
				<option <?php if ( 'Most Commented' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Most Commented</option>
				<option <?php if ( 'Most Viewed' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Most Viewed</option>
				<option <?php if ( 'Least Viewed' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Least Viewed</option>
				<option <?php if ( 'Recently Viewed' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Recently Viewed</option>
				<option <?php if ( 'Historical' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Historical</option>
			</select> Posts,
			Category :
			<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" style="width:100px;" >
				 <option <?php if ( 'All' == $instance['category'] ) echo 'selected="selected"'; ?>>All</option>
				 <?php
				  $categories=  get_categories();
				  foreach ($categories as $cat) { ?>
				   <option <?php if ( $cat->cat_name == $instance['category'] ) echo 'selected="selected"'; ?>><?php echo $cat->cat_name ?> </option>;
		  <?php }?>
			</select>
        </p>
		<!-- Post Count: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'maxpost' ); ?>"><?php _e('Maximum Posts:', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'maxpost' ); ?>" name="<?php echo $this->get_field_name( 'maxpost' ); ?>" value="<?php echo $instance['maxpost']; ?>" style="width:30px;" />
		</p>
<hr />
		<!-- Width: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width:', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:30px;" />
		<!-- Height: Text Input -->
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height:', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:30px;" />
		</p>

		<p>
			<select id="<?php echo $this->get_field_id( 'constrain' ); ?>" name="<?php echo $this->get_field_name( 'constrain' ); ?>" style="width:100px;" >
				<option <?php if ( 'stretch' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>stretch</option>
				<option <?php if ( 'Resize by ratio' == $instance['widgettype'] ) echo 'selected="selected"'; ?>>Resize by ratio</option>
			</select>
		</p>
		<!-- Show default image Checkbox -->
		<?php /*<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_default'], true ); ?> id="<?php echo $this->get_field_id( 'show_default'); ?>" name="<?php echo $this->get_field_name( 'show_default'); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_default' ); ?>"><?php _e('Display default image?', 'amtyThumb_posts'); ?></label>
		</p>*/?>
		<!-- Default Image Path: Text Input -->
		<p>
		<!-- default_img_path: Text Input -->
			<label for="<?php echo $this->get_field_id( 'default_img_path' ); ?>"><?php _e('Default thumbnail path: ', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'default_img_path' ); ?>" name="<?php echo $this->get_field_name( 'default_img_path' ); ?>" value="<?php echo $instance['default_img_path']; ?>" style="width:95%;" />
		</p>
		<hr />
		<h3>Template</h3>
		
		<p>
		<!-- PreTag: Text Input -->
			<label for="<?php echo $this->get_field_id( 'pretag' ); ?>"><?php _e('PreTag:', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'pretag' ); ?>" name="<?php echo $this->get_field_name( 'pretag' ); ?>" value="<?php echo $instance['pretag']; ?>" style="width:60px;" />
		<!-- template: Text Input -->
		<br />
			<label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _e('Template:', 'amtyThumb_posts'); ?></label><br />
			<textarea id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>" rows="4" style="width:100%;"> <?php echo $instance['template']; ?></textarea>
		<br />
		<!-- PostTag: Text Input -->
			<label for="<?php echo $this->get_field_id( 'posttag' ); ?>"><?php _e('PostTag:', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'posttag' ); ?>" name="<?php echo $this->get_field_name( 'posttag' ); ?>" value="<?php echo $instance['posttag']; ?>" style="width:60px;" />
		</p>
		<p>
		<!-- Length: Text Input -->
			<label for="<?php echo $this->get_field_id( 'titlelen' ); ?>"><?php _e('Title Length(in number of chars):', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'titlelen' ); ?>" name="<?php echo $this->get_field_name( 'titlelen' ); ?>" value="<?php echo $instance['titlelen']; ?>" style="width:30px;" />
			<br />
			<label for="<?php echo $this->get_field_id( 'contentlength' ); ?>"><?php _e('Content text limit(in number of chars):', 'amtyThumb_posts'); ?></label>
			<input id="<?php echo $this->get_field_id( 'contentlength' ); ?>" name="<?php echo $this->get_field_name( 'contentlength' ); ?>" value="<?php echo $instance['contentlength']; ?>" style="width:30px;" />
		</p>
		<hr />
		Powered by <a href="http://article-stack.com">article-stack</a> & <a href="http://thinkzarahatke.com">thinkzarahatke</a>
	<?php
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$width = $instance['width'];
		$height = $instance['height'];
		$constrain = $instance['constrain'];
		$maxpost = $instance['maxpost'];
		$default_img_path = $instance['default_img_path'];			//path for default image in case of no image in post
		$pretag = $instance['pretag'];
		$template = $instance['template'];
		$posttag = $instance['posttag'];			
		$titlelen = $instance['titlelen'];				//Stripping Post title for better display; if 0 then no stripping
		$contentlength = $instance['contentlength'];
		$categoryName = $instance['category'];		//limiting search to a particular category
		$widgetType = $instance['widgettype'];		//Recent/random/popular etc.
		/* Before widget (defined by themes). */
		
		echo $before_widget;
		
		
		displayPosts($before_title ,$after_title,$title, $width ,$height , $constrain, $maxpost ,$default_img_path,$pretag ,$template,$posttag,$titlelen,$contentlength ,$categoryName ,$widgetType);
		/* After widget (defined by themes). */
		echo $after_widget;
	}
} //class end

function amtyThumb_shortcode( $attr, $content = null ) {
    extract( shortcode_atts( array( 'title' => 'Amty Thumb Posts', //'' to hide it
								   'before_title' => '<h2>',
								   'after_title' => '</h2>',
					 'thumb_width' => '70',
					 'thumb_height' => '70',
					 'constrain' => '1',
					 'default_img_path' => '',
					 'pretag' => '',
					 'template' => '',					 
					 'posttag' => '',
					 'title_len' => 30,
					 'contentlength' => 200,
					 'max_post' =>  10,
					 'category' => 'All',
					 'widgettype' => 'Recently Written' //'Recent','Random','Most Commented'
					 ), $attr ) );

displayPosts($before_title, $after_title,$title, $thumb_width,$thumb_height,$constrain,$max_post,$default_img_path,$pretag,$template,$posttag,$title_len,$contentlength,$category,$widgettype);

}


function displayPosts($before_title, $after_title, $title = '',$width = 70,$height = 70 ,$constrain=1, $maxpost  = 10 ,$default_img_path = '',$pretag = '',$template , $posttag = '',$titlelen = 30,$contentlength=200,$categoryName = 'All',$widgetType = 'Recently Written'){
	global $wpdb;
	if ( $title != '' ){
		echo $before_title . $title . $after_title;
	}
	if ($categoryName != 'All') {
		$theCatId = get_cat_id($categoryName );
		//$theCatId = $theCatId->term_id;
		$category = "cat=" . $theCatId . "&";
	}else{
			$category = '';
	}
	
	if($widgetType == 'Recently Written')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&orderby=date");
	elseif($widgetType == 'Random')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&orderby=rand");
	elseif($widgetType == 'Most Commented')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&orderby=comment_count");
	elseif($widgetType == 'Most Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtyviewcount&orderby=meta_value_num&order=DESC");
	elseif($widgetType == 'Least Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtyviewcount&orderby=meta_value_num&order=ASC");
	elseif($widgetType == 'Recently Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtylastviewed&orderby=meta_value_num&order=DESC");
	elseif($widgetType == 'Historical')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtylastviewed&orderby=meta_value_num&order=ASC");

	$temp = "";
		 if($amty_posts) {			
			foreach ($amty_posts as $post) {
				
				$temp = $template;
				//echo $temp;
				//Decorating Post title
				//$ptitle = get_the_title($post);
				$ptitle = $post->post_title;
				
				if($titlelen != 0)
				{
				  if (strlen($ptitle)> $titlelen )
				  {
					$stitle = substr($ptitle,0,$titlelen );
				  }
				  else{
					$stitle = $ptitle;
				  }
				}
				
				//'<li><img src="%POST_THUMB%" /><a href="%POST_URL%"  title="%POST_TITLE%">%POST_TITLE%</a></li>'
					
				$post_views =  get_post_meta($post->ID, 'amtyviewcount', true);
				
				//$amtylastviewed = get_post_meta($post->ID, 'amtylastviewed', true);
				//$lastviewddate = "viewed at " .  date($dataformat, $stamp);
				$comments_count = $post->comment_count;
				if(strrpos($temp, "%POST_AUTHOR%")){
					$temp = str_replace("%POST_AUTHOR%", $author, $temp);
					$author = get_the_author_meta("display_name",$post->post_author);
				}
				$temp = str_replace("%VIEW_COUNT%", number_format_i18n($post_views), $temp);			
				$temp = str_replace("%COMMENTS_COUNT%", $comments_count, $temp);
				//$temp = str_replace("%POST_LAST_VIEWED%", $amtylastviewed, $temp);
				
				$temp = str_replace("%POST_TITLE%", $ptitle, $temp);
				$temp = str_replace("%SHORT_TITLE%", $stitle, $temp);
				$temp = str_replace("%POST_EXCERPT%", $post->post_excerpt, $temp);
				if(strrpos($temp, "%POST_CONTENT%")){
					$strippedConetnts = getStrippedContent($post->post_content,$contentlength);
					$temp = str_replace("%POST_CONTENT%",$strippedConetnts, $temp);					
				}
				$temp = str_replace("%POST_URL%", get_permalink($post->ID), $temp);
				$temp = str_replace("%POST_DATE%", mysql2date('F j, Y', $post->post_date, false), $temp);
				$pos = strrpos($temp, "%POST_THUMB%");
				
				if ($pos === false) { 
					//Do nothing
				}else{
					$imgpath = lead_img_thumb_post($width ,$height ,$default_img_path , $post->ID , $constrain);
					//echo "<br />" . $imgpath;
					$temp = str_replace("%POST_THUMB%", $imgpath , $temp);
				}
				
				$output .= $temp;
			}
			$output = $pretag . $output . $posttag;
			echo $output;
		}
		 ?>
            
            <!-- <a href="<?php echo "http://article-stack.com"; ?>">*</a> -->
            <div style="clear:both;"></div>
	<?php
}//function end

function lead_img_thumb_post($w=70,$h=70,$default_src='',$post_id,$constrain) {
	
	if (function_exists('amty_lead_img')) {
		if($post_id != '')
		  $img_url = amty_lead_img($w,$h,$constrain,'','',0,$post_id,'y',$default_src);
		else
		  $img_url = amty_lead_img($w,$h,$constrain,'','',0,'','y',$default_src);
	}
	else{
		echo "amtyThumb plugin is missing";
		$img_url = "";
	}
	return $img_url;
}//function end

//To remove dependency from wp-postviews
register_activation_hook( __FILE__, "update_count" );
function update_count(){
	global $wpdb;
	$amty_posts = get_posts("post_status=publish");
	if($amty_posts) {
		foreach ($amty_posts as $post) {
			$views = get_post_meta($post->ID,'views', true);
			if($views){
				add_post_meta($post->ID, "amtyviewcount", $views, true);
			}else{
				add_post_meta($post->ID, "amtyviewcount", 0, true);
			}
		}
	}
}

add_action('the_content', 'amtyupdatemeta');
 function amtyupdatemeta($content) {
	if(is_single()){
		//$content .= "<script>alert(".get_the_ID().");</script>";
		$views = get_post_meta(get_the_ID(),'amtyviewcount', true);
		update_post_meta(get_the_ID(), "amtyviewcount", $views + 1);
		update_post_meta(get_the_ID(), "amtylastviewed", strtotime(date("m/d/Y h:i:s")));
	}
 	return $content;
 }
 
function amtyThumbPosts_admin() {
	include('amtyThumbPostsAdminPg.php');
}

function amtyThumbPosts_admin_actions() {
    add_options_page("amtyThumbPosts Options", "amtyThumbPosts Options", "activate_plugins", "amtyThumbPostsOptions", "amtyThumbPosts_admin");
}

add_action('admin_menu', 'amtyThumbPosts_admin_actions');

 function getAmtyViewCount($pid){
	return get_post_meta($pid,'amtyviewcount', true);
 }
 
 function getLastVisitedTime($pid){
	$time = get_post_meta($pid,'amtylastviewed', true);
	if($time > 0 )
		return date("d-M-Y h:i:s",$time);
	else
		return '';
 }
 
  function getLastVisitedInterval($pid){
	$time = get_post_meta($pid,'amtylastviewed', true);
	$currenttime = time();
	
	if($time > 0 )
		return date("d-M-Y h:i:s",$time);
	else
		return '';
 }
?>