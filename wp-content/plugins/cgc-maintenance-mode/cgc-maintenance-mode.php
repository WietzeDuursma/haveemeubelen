<?php
/*
Plugin Name: CGC Maintenance Mode
Plugin URI: http://pippinsplugins.com/cgc-maintenance-mode-plugin-for-wordpress/
Description: Allows you to put your site in maintenance mode and restrict address by IP addresses
Version: 1.0
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/

/******************************
* global vars
******************************/

// load the plugin settings
$cmm_options = get_option('cmm_settings');

/******************************
* load the include files
******************************/

include('includes/admin-page.php');
include('includes/restrict-access.php');