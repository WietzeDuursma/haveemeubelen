<?php

// Performs an IP check on the current user when the page is loaded and redirect user if not in the array
function cmm_restrict_access() {
	global $cmm_options;
		
	if($cmm_options['enable']) {
		
		$ips = array_map('trim', explode("\n", $cmm_options['ips']));

		if($cmm_options['page_or_url'] == 'page') {
			if(cmm_ip_test($ips) == false && !is_page(intval($cmm_options['page']))) {
				wp_redirect( get_permalink($cmm_options['page'])); exit;
			}
		} elseif($cmm_options['page_or_url'] == 'url') {
			if(cmm_ip_test($ips) == false && $cmm_options['redirect_url'] != '') {
				wp_redirect( $cmm_options['redirect_url']); exit; 
			}
		}
	}
}
add_action('template_redirect', 'cmm_restrict_access', 0);

// checks the current user's IP address
// @return - true if the user's IP is in the array, false otherwise
function cmm_ip_test($ips){
	
	if(in_array($_SERVER['REMOTE_ADDR'], $ips)) {
		return true;
	}
	return false;
}