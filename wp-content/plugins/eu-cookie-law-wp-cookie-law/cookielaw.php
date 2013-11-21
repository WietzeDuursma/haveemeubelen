
<?php
/*
Plugin Name: WP Cookie Law
Plugin URI: http://www.wpcookielaw.com/
Description: Detects EU countries and displays optin for users to accept cookies - update to pro version here <a href="http://www.wpcookielaw.com/" title="Update To Pro Version">Update To Pro Version</a> 
Version: 1.3
Author: Andrew Lewin
Author URI: http://andrewlewin.info/
License: GPLv2
*/


define ( 'ALEU_INSERTJS', plugin_dir_url(__FILE__).'js');
define ( 'ALEU_INSERTCSS', plugin_dir_url(__FILE__).'css');
define ( 'ALEU_INSERTPHP', plugin_dir_url(__FILE__).'includes');
define ( 'ALEU_INSERTIMAGES', plugin_dir_url(__FILE__).'images');



class EuCookie {

	public $options;

	public function __construct(){
		$this->options = get_option('aleu_settings');
		$this->register_settings_and_fields();
	}

	public function addMenuPage(){
		add_options_page('WP Cookie Law', 'WP Cookie Law', 'administrator', __FILE__, array('EuCookie', 'display_options_page'));
	}

	public function display_options_page(){
		?>

		<div class="wrap">
			<?php screen_icon(); ?>
			<h2> Showroomsluiting popup </h2>
			<form method="post" action="options.php" enctype="multipart/form-data" name="aleu_form">
				<input id='aleu_reset_default' name='aleu_reset_default' type='hidden'/>			
				<?php settings_fields('aleu_settings'); ?>
				<?php do_settings_sections(__FILE__); ?>

				<p class="submit">
					<input name="submit" type="submit" class="button-primary" value="Save Changes"/>
				</p>
			</form>
		</div>

		<?php

	}

	public function register_settings_and_fields(){

		register_setting('aleu_settings','aleu_settings', array($this,'aleu_settings_validate_settings'));
		add_settings_section('aleu_settings', 'Edit Settings', array($this,'main_section_cb'), __FILE__);
		
		add_settings_field('aleu_enable_setting', 'Disable WP Cookie Law', array($this, 'aleu_enable_setting'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_text', 'Popup Message', array($this, 'aleu_input_setting'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_sub_text', 'Popup Sub Text Message', array($this, 'aleu_message_sub_text'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_date_van', 'Datum van:', array($this, 'aleu_message_date_van'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_date_tot', 'Datum tot:', array($this, 'aleu_message_date_tot'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_opening1', 'extra opening:', array($this, 'aleu_message_opening1'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_opening2', 'extra opening:', array($this, 'aleu_message_opening2'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_opening3', 'extra opening:', array($this, 'aleu_message_opening3'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_opening4', 'extra opening:', array($this, 'aleu_message_opening4'), __FILE__, 'aleu_settings');
		
/**
		add_settings_field('aleu_message_tijdv1', '', array($this, 'aleu_message_tijdv1'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdv2', '', array($this, 'aleu_message_tijdv2'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdv3', '', array($this, 'aleu_message_tijdv3'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdv4', '', array($this, 'aleu_message_tijdv4'), __FILE__, 'aleu_settings');

		add_settings_field('aleu_message_tijdt1', '', array($this, 'aleu_message_tijdt1'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdt2', '', array($this, 'aleu_message_tijdt2'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdt3', '', array($this, 'aleu_message_tijdt3'), __FILE__, 'aleu_settings');
		add_settings_field('aleu_message_tijdt4', '', array($this, 'aleu_message_tijdt4'), __FILE__, 'aleu_settings');
*/
		add_settings_field('aleu_message_type', 'Message Type', array($this, 'aleu_message_type'), __FILE__, 'aleu_settings');
		
		add_settings_field('aleu_reset_button', 'Reset To Default Settings', array($this, 'aleu_reset_button'), __FILE__, 'aleu_settings');
		
	}

	public function aleu_settings_validate_settings($plugin_options){
		
		//reset the form
		if ( !empty( $_POST[ 'aleu_reset_default' ] ) ){ 			delete_option('aleu_settings');
			return;
		}
		
		$plugin_options['aleu_message_text'] = esc_attr($plugin_options['aleu_message_text']);

		$plugin_options['aleu_message_sub_text'] = esc_attr($plugin_options['aleu_message_sub_text']);
		$plugin_options['aleu_message_date_van'] = esc_attr($plugin_options['aleu_message_date_van']);
		$plugin_options['aleu_message_date_tot'] = esc_attr($plugin_options['aleu_message_date_tot']);
		$plugin_options['aleu_message_opening1'] = esc_attr($plugin_options['aleu_message_opening1']);
		$plugin_options['aleu_message_opening2'] = esc_attr($plugin_options['aleu_message_opening2']);
		$plugin_options['aleu_message_opening3'] = esc_attr($plugin_options['aleu_message_opening3']);
		$plugin_options['aleu_message_opening4'] = esc_attr($plugin_options['aleu_message_opening4']);
		$plugin_options['aleu_message_tijdv1'] = esc_attr($plugin_options['aleu_message_tijdv1']);
		$plugin_options['aleu_message_tijdv2'] = esc_attr($plugin_options['aleu_message_tijdv2']);
		$plugin_options['aleu_message_tijdv3'] = esc_attr($plugin_options['aleu_message_tijdv3']);
		$plugin_options['aleu_message_tijdv4'] = esc_attr($plugin_options['aleu_message_tijdv4']);
		$plugin_options['aleu_message_tijdt1'] = esc_attr($plugin_options['aleu_message_tijdt1']);
		$plugin_options['aleu_message_tijdt2'] = esc_attr($plugin_options['aleu_message_tijdt2']);
		$plugin_options['aleu_message_tijdt3'] = esc_attr($plugin_options['aleu_message_tijdt3']);
		$plugin_options['aleu_message_tijdt4'] = esc_attr($plugin_options['aleu_message_tijdt4']);
		
		
		return $plugin_options;
	}

	public function main_section_cb(){
		//here wu get the ip address and output it to the screen
	}


	/*
	*
	* Inputs
	*
	*/ 
	
	public function aleu_message_type(){

		$items = array('Implicit', 'Explicit');
		echo "<select name='aleu_settings[aleu_message_type]' width='100' style='width: 100px;'>";
		foreach($items as $item){
			$selected = ( $this->options['aleu_message_type'] === $item) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select> <i>Implicit Mode = top banner, Explicit Mode = light box</i>";

	}
	
	public function aleu_enable_setting(){
		if (isset($this->options['aleu_enable_setting']) && $this->options['aleu_enable_setting']=="true"){
			$checked = "checked";
		} else {
			$checked = "";
		}	
		echo "<input size='40' name='aleu_settings[aleu_enable_setting]' type='checkbox' value='true' {$checked}/>";
	}
	
	public function aleu_upgrade_button(){
		echo '<b>To Get the pro version with GEO location detection and full image and colour configuration click<br/><a style="font-size:150%" href="http://www.wpcookielaw.com/" target="_blank">Upgrade To WP Cookie Law Pro Version</a></b>';
	}
	
	public function aleu_reset_button(){
		echo '<input type="submit" class="button" value="Restore Defaults" name="aleu_reset_button" onClick="javascript:document.getElementById(\'aleu_reset_default\').value = \'true\';" style="width:auto;" />';
	}
	public function aleu_message_date_van(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_date_van]' value='{$this->options['aleu_message_date_van']}'> <i></i>";
	}
	public function aleu_message_date_tot(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_date_tot]' value='{$this->options['aleu_message_date_tot']}'> <i></i>";
	}
	public function aleu_message_opening1(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_opening1]' value='{$this->options['aleu_message_opening1']}'> <i>van: </i> ";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdv1]' value='{$this->options['aleu_message_tijdv1']}' size='4'> <i> tot: </i>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdt1]' value='{$this->options['aleu_message_tijdt1']}' size='4'>";
	}
	public function aleu_message_tijdv1(){}
	public function aleu_message_tijdt1(){}
		
		
	public function aleu_message_opening2(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_opening2]' value='{$this->options['aleu_message_opening2']}'> <i>van: </i> ";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdv2]' value='{$this->options['aleu_message_tijdv2']}' size='4'> <i> tot: </i>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdt2]' value='{$this->options['aleu_message_tijdt2']}' size='4'>";
	}
	public function aleu_message_tijdv2(){}
	public function aleu_message_tijdt2(){}

	public function aleu_message_opening3(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_opening3]' value='{$this->options['aleu_message_opening3']}'> <i>van: </i> ";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdv3]' value='{$this->options['aleu_message_tijdv3']}' size='4'> <i> tot: </i>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdt3]' value='{$this->options['aleu_message_tijdt3']}' size='4'>";
	}
	public function aleu_message_tijdv3(){}
	public function aleu_message_tijdt3(){}

	public function aleu_message_opening4(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_opening4]' value='{$this->options['aleu_message_opening4']}'> <i>van: </i> ";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdv4]' value='{$this->options['aleu_message_tijdv4']}' size='4'> <i> tot: </i>";
		echo "<input  rows='1' name='aleu_settings[aleu_message_tijdt4]' value='{$this->options['aleu_message_tijdt4']}' size='4'>";
	}
	public function aleu_message_tijdv4(){}
	public function aleu_message_tijdt4(){}

	public function aleu_input_setting(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<TEXTAREA cols='40' rows='4' name='aleu_settings[aleu_message_text]'>{$this->options['aleu_message_text']}</TEXTAREA> <i>Leave blank for default text</i>";
	}

	public function aleu_message_sub_text(){
		//echo "<input name='aleu_settings[aleu_message_text]' type='text' value='{$this->options['aleu_message_text']}'/>";
		echo "<TEXTAREA cols='40' rows='4' name='aleu_settings[aleu_message_sub_text]'>{$this->options['aleu_message_sub_text']}</TEXTAREA> <i>Leave blank for default text</i>";
	}
	
	
	public function checkCookies(){
		
		if (aleu_enable_setting()=='true'){
			return;
		}
		
		wp_enqueue_script('jquery');
		
		if(!isset($_COOKIE['aleu_useCookies'])){
			//There is no cookie set so we need to setup the splash screen
	  	    	
	  	    	
    	  	wp_enqueue_script('jquery-ui-dialog');
	  	    wp_register_script( 'aleu_insert_lightbox', ALEU_INSERTJS .'/lightbox.js', array( 'jquery-ui-dialog' ) );
	  	    wp_enqueue_script('aleu_insert_lightbox');	
	  	    wp_enqueue_script('aleu_insert_cookie',  ALEU_INSERTJS .'/alueCookie.js');
	  	    wp_enqueue_script('aleu_insert_script',  ALEU_INSERTJS .'/script.js');
			wp_enqueue_style('aleu_insert_style',  ALEU_INSERTCSS .'/style.css');	
			wp_enqueue_style('aleu_insert_button_style',  ALEU_INSERTCSS .'/buttonize.min.css');
			
			//get the correct logo
			$logo =   ALEU_INSERTIMAGES . '/Privacy-red-1.png';
			 
			//get the correct message text
			$message = aleu_message_text();
			
			$subMessage = aleu_message_sub_text();
			$subDVMessage = aleu_message_date_van();
			$subDTMessage = aleu_message_date_tot();
			$subOpening1 = aleu_message_opening1();
			$subOpening2 = aleu_message_opening2();
			$subOpening3 = aleu_message_opening3();
			$subOpening4 = aleu_message_opening4();
			$subtijdv1 = aleu_message_tijdv1();
			$subtijdv2 = aleu_message_tijdv2();
			$subtijdv3 = aleu_message_tijdv3();
			$subtijdv4 = aleu_message_tijdv4();
			$subtijdt1 = aleu_message_tijdt1();
			$subtijdt2 = aleu_message_tijdt2();
			$subtijdt3 = aleu_message_tijdt3();
			$subtijdt4 = aleu_message_tijdt4();
						
			//get the redirect URL
			$redirectUrl = 'http://www.wpcookielaw.com/cookie-privacy';
			
			$popupType = aleu_message_type();
		
	if ($popupType=="Explicit"){
					
				
			
			$optin = <<<HTML
			  '<div class="aleu-lightbox-optin">
			  <form method="post" class="aleu-optin" style="background: #000000; color: #ffffff; border-color: #e7e7e7;">
			  	<fieldset>
			      <div class="sticker" ><img src="{$logo}" alt="" /></div>
			      <p class="pitch"><b>{$message}</b></p>
			      <p class="sub-pitch">{$subMessage}</p>
				  <p class="sub-pitch">Wij zijn van: {$subDVMessage} t/m {$subDTMessage}</p>
			      <div class="fields">
        			<p class="pitch"><a class="wp-cookie-button green" style="color: #ffffff; font-weight: bold; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.6);" onclick="alue_cookie.doClick();" title="Niet meer weergeven">Niet meer weergeven</a></p>
  				  </div>
  				  <div class="fields">
  				  	
		  		  </div>
			    </fieldset>
			    </form>
			  </div>'
HTML;
				} else {
					
					$poweredBy = str_replace('id="powered-by" ','',$poweredBy);
					$optin = <<<HTML
			  '<script>
				jQuery(document).ready(function() {
					var options = {
				      imgLocation: \'{$logo}\' ,  
						headerTxt: "{$message}",
						subHeaderTxt: "{$subMessage}",
						subDVHeaderTxt: "{$subDVMessage}",
						subDTHeaderTxt: "{$subDTMessage}",
						subOpening1: "{$subOpening1}",
						subtijdv1: "{$subtijdv1}",
						subtijdt1: "{$subtijdt1}",
						subOpening2: "{$subOpening2}",
						subtijdv2: "{$subtijdv2}",
						subtijdt2: "{$subtijdt2}",

						redirectUrl: "{$redirectUrl}",
  					  poweredBy: \'\'
				    };
  				jQuery("body").wpCookieSlider(options);
			});
		
  </script>'
HTML;
					wp_enqueue_script('aleu_insert_slider',  ALEU_INSERTJS .'/jQuery.slider-1.0.js');
				}
  	    		
			add_action( 'wp_footer', create_function( '', "echo $optin;" ) );
	  	    
	  	    
	  	    	  
		} 
		
	}

}

add_action( 'wp', 'aleu_check_cookies' );

function aleu_check_cookies(){
	EuCookie::checkCookies();
	
}

add_action('admin_menu','add_menu_page_from_EuCookie');

function add_menu_page_from_EuCookie(){
	EuCookie::addMenuPage();
}

add_action('admin_init','instantiate_EuCookie');

function instantiate_EuCookie(){
	new EuCookie();
}

function aleu_enable_setting(){
	
	$options = get_option('aleu_settings');
	
	if (isset($options['aleu_enable_setting']) && $options['aleu_enable_setting']=="true"){
		return 'true';
	} else {
		return 'false';
	}

}

function aleu_message_text(){
	
	$options = get_option('aleu_settings');
	
	if (isset($options['aleu_message_text'])){
		return ($options['aleu_message_text']=="") ? 'Our use of cookies.' : nl2br ($options['aleu_message_text']);
	} else {
		return 'Our use of cookies.';
	}

}

function aleu_message_sub_text(){
	
	$options = get_option('aleu_settings');
	
	if (isset($options['aleu_message_sub_text'])){
		return ($options['aleu_message_sub_text']=="") ? 'Like most websites we use cookies to provide a more personalised and responsive service.  We use cookies to enable our website to function more efficiently,  to improve performance and to tailor advertising with our partners.  If you continue we will assume you are happy to receive all the cookies from our website.' : nl2br ($options['aleu_message_sub_text']);
	} else {
		return 'Like most websites we use cookies to provide a more personalised and responsive service.  We use cookies to enable our website to function more efficiently,  to improve performance and to tailor advertising with our partners.  If you continue we will assume you are happy to receive all the cookies from our website.';
	}

}
	function aleu_message_date_van(){
		
		$options = get_option('aleu_settings');
		
		if (isset($options['aleu_message_date_van'])){
			return ($options['aleu_message_date_van']=="") ? 'Like most websites we use cookies to provide a more personalised and responsive service.  We use cookies to enable our website to function more efficiently,  to improve performance and to tailor advertising with our partners.  If you continue we will assume you are happy to receive all the cookies from our website.' : nl2br ($options['aleu_message_date_van']);
		} else {
			return 'Like most websites we use cookies to provide a more personalised and responsive service.  We use cookies to enable our website to function more efficiently,  to improve performance and to tailor advertising with our partners.  If you continue we will assume you are happy to receive all the cookies from our website.';
		}
	
	}
	function aleu_message_date_tot(){
		
		$options = get_option('aleu_settings');
		
		if (isset($options['aleu_message_date_tot'])){
			return ($options['aleu_message_date_tot']=="") ? '' : nl2br ($options['aleu_message_date_tot']);
		} else {
			return '';
		}
	
	}
	function aleu_message_opening1(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_opening1'])){ return ($options['aleu_message_opening1']=="") ? '' : nl2br ($options['aleu_message_opening1']);
		} else {
			return '';
		}
	
	}
	function aleu_message_opening2(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_opening2'])){ return ($options['aleu_message_opening2']=="") ? '' : nl2br ($options['aleu_message_opening2']);
		} else {
			return '';
		}
	
	}
	function aleu_message_opening3(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_opening3'])){ return ($options['aleu_message_opening3']=="") ? '' : nl2br ($options['aleu_message_opening3']);
		} else {
			return '';
		}
	
	}
	function aleu_message_opening4(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_opening4'])){ return ($options['aleu_message_opening4']=="") ? '' : nl2br ($options['aleu_message_opening4']);
		} else { 
			return '';
		}
	
	}
	function aleu_message_tijdv1(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdv1'])){ return ($options['aleu_message_tijdv1']=="") ? '' : nl2br ($options['aleu_message_tijdv1']);
		} else { return ''; }
	}
	function aleu_message_tijdv2(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdv2'])){ return ($options['aleu_message_tijdv2']=="") ? '' : nl2br ($options['aleu_message_tijdv2']);
		} else { return ''; }
	}
	function aleu_message_tijdv3(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdv3'])){ return ($options['aleu_message_tijdv3']=="") ? '' : nl2br ($options['aleu_message_tijdv3']);
		} else { return ''; }
	}
	function aleu_message_tijdv4(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdv4'])){ return ($options['aleu_message_tijdv4']=="") ? '' : nl2br ($options['aleu_message_tijdv4']);
		} else { return ''; }
	}

	function aleu_message_tijdt1(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdt1'])){ return ($options['aleu_message_tijdt1']=="") ? '' : nl2br ($options['aleu_message_tijdt1']);
		} else { return ''; }
	}
	function aleu_message_tijdt2(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdt2'])){ return ($options['aleu_message_tijdt2']=="") ? '' : nl2br ($options['aleu_message_tijdt2']);
		} else { return ''; }
	}
	function aleu_message_tijdt3(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdt3'])){ return ($options['aleu_message_tijdt3']=="") ? '' : nl2br ($options['aleu_message_tijdt3']);
		} else { return ''; }
	}
	function aleu_message_tijdt4(){ $options = get_option('aleu_settings'); if (isset($options['aleu_message_tijdt4'])){ return ($options['aleu_message_tijdt4']=="") ? '' : nl2br ($options['aleu_message_tijdt4']);
		} else { return ''; }
	}
function aleu_message_type(){
	
	$options = get_option('aleu_settings');
	
	if (isset($options['aleu_message_type'])){
		return $options['aleu_message_type'];
	} else {
		return 'Implicit';
	}

}
