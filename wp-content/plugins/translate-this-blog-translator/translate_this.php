<?php
/*
Plugin Name: Translate This Language Translation Plugin
Plugin URI: http://translation-cloud.com/translate-this-wordpress/
Description: Allows your visitors to translate your blog into many different languages. The button is added to the top of every post.
Version: 2.0
Author: Translation Cloud
Author URI: http://translation-cloud.com/
License: GPL2
*/

/*
Copyright 2012 Translation Cloud (email : alex.buran@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class TranslateThisWidget {
	// ==========================================
	// PLEASE NOTE:
	// If your page is not written in English, you must update the line below.
	// Use the language CODE of your website, not the full language name.
	// For a full list of usable languages and codes, please visit: 
	// http://code.google.com/apis/language/translate/v1/reference.html#LangNameArray
	// 
	// e.g. If your page is written in Spanish, then the line:
	// var $translate_this_src = 'en';
	// Should be changed to:
	// var $translate_this_src = 'es';
	// ==========================================
	private $translate_this_src				= 'en';
	private $translate_this_include_jquery	= false;
	private $translate_this_languages		= array(
		"ar-XA" => "Arabic", 
		"bg" => "Bulgarian", 
		"zh-chs" => "Chinese (Simplified)", 
		"zh-cht" => "Chinese (Traditional)", 
		"hr" => "Croatian", 
		"cs" => "Czech", 
		"da" => "Danish", 
		"nl" => "Dutch", 
		"en" => "English", 
		"et" => "Estonian", 
		"fi" => "Finnish", 
		"fr" => "French", 
		"de" => "German", 
		"el" => "Greek", 
		"he" => "Hebrew", 
		"hi" => "Hindi", 
		"hu" => "Hungarian", 
		"ga" => "Irish", 
		"it" => "Italian", 
		"ja" => "Japanese", 
		"ko" => "Korean", 
		"lv" => "Latvian", 
		"lt" => "Lithuanian", 
		"no" => "Norwegian", 
		"pl" => "Polish", 
		"pt" => "Portuguese", 
		"ro" => "Romanian", 
		"ru" => "Russian", 
		"sr-CS" => "Serbian", 
		"sk" => "Slovak", 
		"sl" => "Slovenian", 
		"es" => "Spanish", 
		"sv" => "Swedish", 
		"th" => "Thai", 
		"tr" => "Turkish", 
		"uk-UA" => "Ukrainian"
	);
	
	// Constructor.
	function TranslateThisWidget() {
		// Add functions to the content and excerpt.
		add_filter('the_content', array(&$this, 'codeToContent'));
		add_filter('get_the_excerpt', array(&$this, 'translateThisExcerptTrim'));
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'pluginSettingsLink'));
		// Initialize the plugin.
		add_action('admin_menu', array(&$this, '_init'));
		// Get the plugin options.
		if (get_option('translate_this_src')) {
			$this->translate_this_src = get_option('translate_this_src');
		}
		if (get_option('translate_this_include_jquery')) {
			$this->translate_this_include_jquery = get_option('translate_this_include_jquery');
		}
		// Get our version of jQuery, as needed.
		if (!is_admin() && $this->translate_this_include_jquery) {
			wp_deregister_script('jquery'); 
			wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js');
		}
		// Load our scripts and enqueue jQuery.
		wp_register_script('tctt_translate_this', 'http://s1.translation-cloud.com/_1_0/javascript/e.js', 'jquery', '1.0', true);
		wp_enqueue_script('jquery');
		wp_enqueue_script('tctt_translate_this');
	}
	
	function _init() {
		// Add the options page.
		add_options_page('Translate This Settings', 'Translate This', 'manage_options', 'translation-cloud-translate-this', array(&$this, 'pluginOptions'));
		// Register our plugin settings.
		register_setting('tctt_options', 'translate_this_src', array(&$this, 'validateLanguage'));
		register_setting('tctt_options', 'translate_this_include_jquery');
	}
	
	// Called whenever content is shown.
	function codeToContent($content) {
		// What we add depends on type.
		if (is_feed()) {
			// Add nothing to RSS feed.
			return $content;
		} else if (is_category()) {
			// Add nothing to categories.
			return $content;
		} else {
			// For everything else we add the button to the content normally.
			return $this->getTranslateThisCode() . $content;
		}
	}
	
	// Get the actual button code.
	function getTranslateThisCode() {
		$translate_this_code	= '<div style="clear:both; display:block; height:21px; margin:5px 0; overflow:hidden">';
		$translate_this_code	.= '<div style="clear:both; float:right; width:auto">';
		$translate_this_code	.= '<div class="translate_this">';
		$translate_this_code	.= '<a href="http://translation-cloud.com/" title="Translation" class="translate_this_drop">';
		$translate_this_code	.= '<span class="translate_this_d_86_21_1">Translation</span>';
		$translate_this_code	.= '</a>';
		$translate_this_code	.= '</div>';
		$translate_this_code	.= sprintf('<script type="text/javascript">var translate_this_src = "%s";</script>', $this->translate_this_src);
		$translate_this_code	.= '</div>';
		$translate_this_code	.= '</div>';
		return $translate_this_code;
	}
	
	// Admin page display.
	function pluginOptions() {
		if (!current_user_can('manage_options'))  {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		?>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php settings_fields('tctt_options'); ?>
				<h2>Translate This Settings</h2>
				<p>Update the language and other settings for the Translate This Blog Translator plugin.</p>
				<table class="widefat">
					<tbody>
						<tr>
							<td style="padding:25px;font-family:Verdana, Geneva, sans-serif;color:#666;">
								<p><label id="translate_this_src" for="translate_this_src">Your Site's Current Language</label></p>
								<p>
									<select name="translate_this_src">
										<?php
										$current_src = get_option('translate_this_src') ? get_option('translate_this_src') : $this->translate_this_src;
										foreach ($this->translate_this_languages as $key => &$value) {
											$selected = $current_src == $key ? 'selected="selected"' : '';
											printf('<option %s value="%s">%s</option>', $selected, $key, $value);
										}
										unset($value);
										?>
									</select>
								<p>
								<p>Set this to whatever language your blog is written in. If your blog is in English, and you want visitors to be able to view it in Spanish, Russian, and Japanese, select &quot;English.&quot;</p>
							</td>
						</tr>
						<tr>
							<td style="padding:25px;font-family:Verdana, Geneva, sans-serif;color:#666;">
								<input name="translate_this_include_jquery" type="hidden" value="0" />
								<p><label for="translate_this_include_jquery"><input id="translate_this_include_jquery" <?php echo $this->translate_this_include_jquery ? 'checked="checked"' : ''; ?> name="translate_this_include_jquery" type="checkbox" value="1" /> Use a Validated jQuery Version</p>
								<p>Use this only if you are having trouble getting the Translate This button to work.</p>
								<p><b>Caution:</b> his will override the jQuery script in your theme and could cause conflicts with other plugins and scripts on your blog.</p>
							</td>
						</tr>
						<tr>
							<td style="padding:25px;font-family:Verdana, Geneva, sans-serif;color:#666;">
								<b>Note:</b> if you are using any caching plugins, such as WP Super Cache, you will need to clear your cached pages after updating your Translate This settings.
							</td>
						</tr>
						<tr>
							<th><input name="submit" type="submit" value="Save Settings" class="button-primary" style="padding:8px;" /></th>
						</tr>
					</tbody>
				</table>
				<p><b>Translate This</b> is a project by <a href="http://translation-cloud.com/" target="_blank">Translation Cloud</a>. Get a free quote for your professional or personal translation project at Translation Cloud now!</p>
			</form>
		</div>
		<?php
	}
	
	// Add settings link on plugin page
	function pluginSettingsLink($links) { 
		$settings_link = '<a href="options-general.php?page=translation-cloud-translate-this">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}
	
	// Remove (what's left of) our button code from excerpts.
	function translateThisExcerptTrim($text) {
		$pattern		= '/Translationvar translate_this_src = "(.*?)";/i';
		$replacement	= '';
		return preg_replace($pattern, $replacement, $text);
	}
	
	// Sanitize plugin settings options.
	function validateLanguage($language = null) {
		$return = $this->translate_this_src;
		if (array_key_exists($language, $this->translate_this_languages)) {
			$return = $language;
		}
		return $return;
	}
}

$translate_this &= new TranslateThisWidget();
?>