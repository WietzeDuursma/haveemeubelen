<?php

// this file contains all settings pages and options

function cmm_settings_page()
{
	global $cmm_options;
		
	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">
			<h2>Maintenance Mode</h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'cmm_settings_group' ); ?>
				
				<h4>Enable</h4>
				<p>
					<input id="cmm_settings[enable]" name="cmm_settings[enable]" type="checkbox" value="1" <?php checked( '1', $cmm_options['enable'] ); ?>/>
					<label class="description" for="cmm_settings[enable]"><?php _e( 'Enable Maintenance Mode?' ); ?></label>
				</p>
				
				<h4>IP Addresses</h4>
				<p>
					<label class="description" for="cmm_settings[ips]"><?php _e( 'Enter the IP addresses that should have access to the site, one per line' ); ?></label><br/>					
					<textarea id="cmm_settings[ips]" style="width: 400px; height: 150px;" name="cmm_settings[ips]" type="text"><?php echo $cmm_options['ips'];?></textarea>
				</p>
				
				<h4>Page or URL</h4>
				<p>
					<input type="radio" name="cmm_settings[page_or_url]" value="page" <?php checked( 'page', $cmm_options['page_or_url'] ); ?>/> Page
					<input type="radio" name="cmm_settings[page_or_url]" value="url"<?php checked( 'url', $cmm_options['page_or_url'] ); ?>/> URL 
					<label class="description" for="cmm_settings[page_or_url]"><?php _e( 'Send unauthorized visitors to a WordPress Page or external URL?' ); ?></label><br/>
				</p>
				
				<h4>Redirect Page</h4>
				<p>
					<?php $pages = get_pages(); ?>
					<select id="cmm_settings[page]" name="cmm_settings[page]">
						<?php foreach($pages as $page) { ?>
							<option value="<?php echo $page->ID; ?>" <?php if($cmm_options['page'] == $page->ID) { echo 'selected="selected"'; } ?>><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
					<label class="description" for="cmm_settings[page]"><?php _e( 'Page to send users without IP access' ); ?></label><br/>
				</p>
				
				<h4>Redirect URL</h4>
				<p>
					<input id="cmm_settings[redirect_url]" name="cmm_settings[redirect_url]" type="text" value="<?php echo $cmm_options['redirect_url'];?>" />
					<label class="description" for="cmm_settings[redirect_url]"><?php _e( 'URL to send users without IP access' ); ?></label><br/>
				</p>
				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
				</p>
								
				
			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
		
	<?php
}

// register the plugin settings
function cmm_register_settings() {

	// create whitelist of options
	register_setting( 'cmm_settings_group', 'cmm_settings' );
}
//call register settings function
add_action( 'admin_init', 'cmm_register_settings' );


function cmm_settings_menu() {

	// add settings page
	add_submenu_page('options-general.php', 'Maintenance Mode', 'Maintenance Mode','manage_options', 'maintenace-mode', 'cmm_settings_page');
}
add_action('admin_menu', 'cmm_settings_menu');