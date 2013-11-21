<?php

class colorwp_twitter_widget extends WP_Widget
{
	
	// Widget initialization
	function colorwp_twitter_widget(){
		// Setup basic widget options
		$widget_ops = array('classname' => 'colorwp_twitter_widget', 'description' => 'Displays a configurable number of tweets from any Twitter username in the sidebar.');
		$this->WP_Widget('ColorWP_Twitter_Widget', 'ColorWP.com Twitter Widget', $widget_ops);
	}
 
	// Widget options in admin backend
	function form($instance){
		// Get plugin options
		$instance = wp_parse_args((array) $instance, array( 'title' => '' ));
		
		$title =		(!empty($instance['title']) ? $title = $instance['title'] : '');
		$username =		(!empty($instance['user']) ? $instance['user'] : '');
		$num =			(!empty($instance['num']) ? $instance['num'] : '20');
		$text_align =	(!empty($instance['align']) ? $instance['align'] : '');
		$follow_button_location = (!empty($instance['follow_button_location']) ? $instance['follow_button_location'] : '');
		
		// Values for the form about how much tweets to display in the frontend
		$tweets_count_values = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20);
		?>

		<h3><?php _e('General options:' , cwp_twitter_widget_plugin::$textdomain); ?></h3>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('user'); ?>">Twitter Username: <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($username); ?>" /></label></p>
		
		<h3><b><?php _e('Follow button:', cwp_twitter_widget_plugin::$textdomain); ?></b></h3>
		
		<p><label for="<?php echo $this->get_field_id('follow_button_location'); ?>"><?php _e('Show follow button:', cwp_twitter_widget_plugin::$textdomain); ?>
				<select name="<?php echo $this->get_field_name('follow_button_location'); ?>" class="widefat">
					<option value="above_below" name="1" <?php echo ($follow_button_location=='above_below' ? 'selected' : '') ?>>
						<?php _e('Above and below tweets', cwp_twitter_widget_plugin::$textdomain); ?>
					</option>
					<option value="above" name="1" <?php echo ($follow_button_location=='above' ? 'selected' : '') ?>>
						<?php _e('Above tweets', cwp_twitter_widget_plugin::$textdomain); ?>
					</option>
					<option value="below" name="1" <?php echo ($follow_button_location=='below' ? 'selected' : '') ?>>
						<?php _e('Below tweets', cwp_twitter_widget_plugin::$textdomain); ?>
					</option>
					<option value="no" name="1" <?php echo (!($follow_button_location) || $follow_button_location=='no' ? 'selected' : '') ?>><?php _e('Do not show', cwp_twitter_widget_plugin::$textdomain); ?></option>
				</select>
		</p>
		
		<h3><?php _e('Tweet display:' , cwp_twitter_widget_plugin::$textdomain); ?></h3>
		
		<p><label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of tweets to show:' , cwp_twitter_widget_plugin::$textdomain); ?>
				<select name="<?php echo $this->get_field_name('num'); ?>" class="">
					<?php for($val=1;$val<=20;$val++): ?>
						<option value="<?php echo $val; ?>" name="<?php echo $val; ?>" <?php echo ($num==$val)?'selected':''; ?>>
							<?php echo $val; ?>
						</option>
					<?php endfor; ?>
				</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('align'); ?>">Align text to:
				<select name="<?php echo $this->get_field_name('align'); ?>" class="">
					<option value="left" name="1" <?php echo (($text_align=='left' || !$text_align)?'selected':''); ?>><?php _e('Left', cwp_twitter_widget_plugin::$textdomain); ?></option>
					<option value="center" name="1" <?php echo (($text_align=='center')?'selected':''); ?>><?php _e('Center', cwp_twitter_widget_plugin::$textdomain); ?></option>
					<option value="right" name="1" <?php echo (($text_align=='right')?'selected':''); ?>><?php _e('Right', cwp_twitter_widget_plugin::$textdomain); ?></option>
					<option value="justify" name="1" <?php echo (($text_align=='justify')?'selected':''); ?>><?php _e('Justify', cwp_twitter_widget_plugin::$textdomain); ?></option>
				</select>
		</p>
		
		<?php
	}
 
	// Process saved values after widget options update
	function update($new_instance, $old_instance){
		$instance                   = $old_instance;
		$instance['title']          = $new_instance['title'];
		$instance['user']           = $new_instance['user'];
		$instance['num']            = (int) $new_instance['num'];
		$instance['align']          = $new_instance['align'];
		
		// Validate that a valid value is entered
		if(isset($new_instance['follow_button_location'])){
			switch($new_instance['follow_button_location']){
				case 'above_below':
				case 'above':
				case 'below':
					$instance['follow_button_location'] = $new_instance['follow_button_location'];
					break;
				default:
					$instance['follow_button_location'] = 'no';
			}
		}
		return $instance;
	}
 
	// Widget display in frontend
	function widget($args, $instance){
		extract($args, EXTR_SKIP);

		echo (($before_widget)?$before_widget:'');
		$widget_title = (empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']));
		$twitter_username = (empty($instance['user']) ? '' : strip_tags(stripslashes($instance['user'])));
		$follow_button_location = (empty($instance['follow_button_location']) ? '' : strip_tags(stripslashes(($instance['follow_button_location']))));
		
		// Check where to align tweet texts (default: left)
		if(isset($instance['align'])){
			switch($instance['align']){
				case 'center': case 'right': case 'justify':
					$align = $instance['align'];
					break;
				default: $align = 'left';
			}
		}else{
			$align = 'left';
		}

		// Display widget title area
		echo (($before_title)?$before_title:'');
		echo (empty($widget_title) ? '' : $widget_title);
		echo (($after_title)?$after_title:'');
		
		$follow_button_content =
				'<div class="colorwp-twitter-follow-button">
					<a href="https://twitter.com/'.$twitter_username.'" class="twitter-follow-button" 
					data-show-count="false" data-size="large" data-show-screen-name="false">
					Follow @'.$twitter_username.'</a>
				</div>';

		// If a Twitter username is set in the widget options
		if($twitter_username){
			
			// Initialize the RSS parser
			require_once "lastrss.php";
			$parser = new cwp_lastRSS;
			
			// Limit the number of displayed tweets, if set in widget options
			if(isset($instance['num']) && is_numeric($instance['num']))
				$parser->items_limit = $instance['num'];
			
			
			// Load the latest tweets from Twitter
			$rs = $parser->get('http://api.twitter.com/1/statuses/user_timeline.rss?screen_name='.$twitter_username);
			
			// Should we show a Follow button?
			if($follow_button_location=='above' || $follow_button_location=='above_below'){
				echo $follow_button_content;
			}
			
			if ($rs) {
				
				echo "<div class='colorwp-tweets-wrapper' style='text-align:{$align}'>";
				if(is_array($rs['items'])){
					// Loop through the tweets that we got from the API
					foreach($rs['items'] as $tweet){
						
						$tweet_text = (isset($tweet['title']) ? trim($tweet['title']) : '');
						if(!$tweet_text) continue; // Tweet content blank, skip it
						
						// Remove the tweet author from self-tweets
						$tweet_text = str_ireplace($instance['user'].':', '', $tweet_text);
						
						// Remove any possible tags from tweets (allow the <a href> tag though)
						$tweet_text = strip_tags($tweet_text, 'a');
						
						echo "<p class='colorwp-tweet-text'>{$tweet_text}</p>";
					}
				}
				echo '</div><!--.colorwp-tweets-wrapper-->';
			}
			else {
				_e('Can not get latest tweets', cwp_twitter_widget_plugin::$textdomain);
			}
			
			// Should we show a Follow button?
			if($follow_button_location=='below' || $follow_button_location=='above_below'){
				echo $follow_button_content;
			}
			
			if($follow_button_location && $follow_button_location!='no'){
				// Follow button shown. Render the JS provided by Twitter to "beautify" it
				echo '<script type="text/javascript">!function(d,s,id){'.
						'var js,fjs=d.getElementsByTagName(s)[0];'.
						'if(!d.getElementById(id)){js=d.createElement(s);js.id=id;'.
						'js.src="//platform.twitter.com/widgets.js";'.
						'fjs.parentNode.insertBefore(js,fjs);'.
						'}}(document,"script","twitter-wjs");</script>
					';
			}
		}else{
			_e('No Twitter username specified', cwp_twitter_widget_plugin::$textdomain);
		}

		echo (($after_widget)?$after_widget:'');
	}
}