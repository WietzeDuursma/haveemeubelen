<?php
session_start();
/*
Plugin Name: Modellen
Plugin URI: http://i-nix.nl/
Description: Havee meubelen model chart.
Version: 1.0.4
Author: Wietze Duursma
Author URI: http://i-nix.nl/
License: GPL2
 */
/*  Copyright 2013 Wietze Duursma (email : wiet@i-nix.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

global $wpdb;
define ( 'ONETAREK_WPMUT_PLUGIN_URL', plugin_dir_url(__FILE__)); // with forward slash (/).
//echo ONETAREK_WPMUT_PLUGIN_URL.'onetarek-wpmut-admin-script.js'; 

function onetarek_wpmut_admin_scripts() 
{
 if (isset($_GET['page']) && $_GET['page'] == 'modellen')
	 {
		 wp_enqueue_script('jquery');
		 wp_enqueue_script('media-upload');
		 wp_enqueue_script('thickbox');
		 wp_register_script('my-upload', ONETAREK_WPMUT_PLUGIN_URL.'onetarek-wpmut-admin-script.js', array('jquery','media-upload','thickbox'));
		 wp_enqueue_script('my-upload');
	 }
}

function onetarek_wpmut_admin_styles()
{
 if (isset($_GET['page']) && $_GET['page'] == 'modellen')
	 {
		 wp_enqueue_style('thickbox');
	 }
}
add_action('admin_print_scripts', 'onetarek_wpmut_admin_scripts');
add_action('admin_print_styles', 'onetarek_wpmut_admin_styles');


if ( ! function_exists( 'is_ssl' ) ) {
    function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}

if ( version_compare( get_bloginfo( 'version' ) , '3.0' , '<' ) && is_ssl() ) {
    $wp_content_url = str_replace( 'http://' , 'https://' , get_option( 'siteurl' ) ) . "HEY";
} else {
    $wp_content_url = get_option( 'siteurl' ) . "DUDE";
}
$wp_content_url = '/wp-content';
$wp_content_dir = ABSPATH . 'wp-content';
$wp_plugin_url = $wp_content_url . '/plugins';
$wp_plugin_dir = $wp_content_dir . '/plugins';
$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';

$wp_noshop_url = $wp_plugin_url . '/modellen';
$wp_noshop_dir = $wp_plugin_dir . '/modellen';
$wpmu_noshop_url = $wpmu_plugin_url . '/modellen';
$wpmu_noshop_dir = $wpmu_plugin_dir . '/modellen';

$wpdb->show_errors();

global $noshop_version;
global $noshop_db_version;
$noshop_version = "1.0.4";
$noshop_db_version = "1.0.4";

register_activation_hook(__FILE__,'NoShop::noshop_activate');

if (!class_exists("NoShop")) {

    class NoShop {
        //constructor
        function NoShop()
        {
            // Hook up Actions and Filters
            //if (isset($noshop_plugin)) {
            //Actions
            add_action('wp_head', 'NoShop::CSS');
            add_action('wp_footer', 'NoShop::Products');

            //Filters
            add_filter('the_content', 'NoShop::ShowTable');
            add_action('admin_menu', 'NoShop::PluginMenu');
            //}
        }

        // Install part: Create table for products (or check if it's there at least)
        public static function noshop_activate() {
            global $wpdb, $noshop_version, $noshop_db_version;

            // First the Product Table
            $table_name = $wpdb->prefix . "noshop_products";
            //				if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE `" . $table_name . "` (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						time bigint(11) DEFAULT '0' NOT NULL,
						category tinytext NOT NULL,
						title tinytext NOT NULL,
						description text NOT NULL,
						url VARCHAR(250) NOT NULL,
						imgurl VARCHAR(250) NOT NULL,
						timgurl VARCHAR(250) NOT NULL,
						timgurl2 VARCHAR(250) NOT NULL,
						timgurl3 VARCHAR(250) NOT NULL,
						imgurlmode VARCHAR(1) NOT NULL,
						ndx mediumint(9) DEFAULT '0' NOT NULL ,
						ho VARCHAR(4) NOT NULL,
						hoh VARCHAR(4) NOT NULL,
						diep VARCHAR(4) NOT NULL,
						zie VARCHAR(4) NOT NULL,
						UNIQUE KEY  id (id),
						PRIMARY KEY  primarykey (id)
					);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            // Second some Product Specs
            $table_name = $wpdb->prefix . "noshop_product_specs";
            //				if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE `" . $table_name . "` (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						product_id mediumint(9) NOT NULL,
						time bigint(11) DEFAULT '0' NOT NULL,
						spectitle tinytext NOT NULL,
						specvalue text NOT NULL,
						specvalueD text NOT NULL,
						specvalueH text NOT NULL,
						specvalueHH text NOT NULL,
						specvalueZ text NOT NULL,
						specvalueZh text NOT NULL,
						specvalueBia text NOT NULL,
						specvalueBiaS text NOT NULL,
						specvalueBiaL text NOT NULL,
						specvalueHHu text NOT NULL,
						specvalueHLu text NOT NULL,
						specvalueU text NOT NULL,
						specvalueHs text NOT NULL,

						UNIQUE KEY  id (id)
					);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            add_option("noshop_db_version", $noshop_db_version);
            update_option("noshop_db_version", $noshop_db_version);
            
        } // End noshop_activate()




        // This just echoes the chosen line, we'll position it later
        public static function Products() {
            global $wpdb;
            //$wpdb->print_error();
        } // End Products()




        // Now we set that function up to execute when the admin_footer action is called

        // We need some CSS to position the paragraph
        public static function CSS() {
            global $wp_noshop_url;
            global $wpmu_noshop_url;

            // This makes sure that the posinioning is also good for right-to-left languages
            //          $x = ( is_rtl() ) ? 'left' : 'right';
            echo "<link rel=\"stylesheet\" href=\"".plugins_url()."/modellen/noshop.css\" >\n";
            echo "<!-- ".$wp_noshop_url." -->\n";
            echo "<!-- ".$wpmu_noshop_url." -->\n";

        } // End CSS()
		
        public static function ShowTable($content) {
            $cat = "";
            $search = "@\s*\[NoShop ([^\]]+)\]\s*@i";
            if(preg_match_all($search, $content, $matches)) {
                if(is_array($matches)) {
                    //print("\n<br />MATCHES, MATCH\n");
                    //print_r($matches);
                    //print("\n<br />\n");
                    foreach($matches[1] as $key => $cat) {
                        // Get the data from the tag
                        //print("\n<br />MATCH, +\n");
                        //print("\n<pre>\n");
                        //print($cat);
                        //print(" | ");
                        //print($matches[0][$key]);
                        //print("\n</pre>\n");
                        //print("\n<br />\n");
                        $sstr = addslashes($matches[0][$key]);
                        //$cat = $match;
                        //print("\n<br />\n");
                        //print("\n<pre>MATCH, SSTR, CAT\n");
                        //print("<!-- *** SSTR: ".$sstr." -->\n\n");
                        //print("<!-- *** CAT: ".$cat." -->\n\n");
                        //print("\n</pre>\n");
                        //print("\n<br />\n");

                        //$content = str_replace ($search, $replace, $content);
                        $content = str_replace( "$sstr", NoShop::createtable($cat), $content );
                    }
                }
            }

            //$content = str_replace( "[NoShop]", "THIS?", $content );
            return $content;
        } // End ShowTable($content)
		
        public function createtable($cat) {
            //echo "<p>Category is [ ".$cat." ]</p>";

            // get options
            global $wp_noshop_url;
            global $wp_noshop_dir;
            global $wpmu_noshop_url;
            global $wpmu_noshop_dir;

            $options = get_option('noshop_options');

            if($wptouch)	$width=$options['wptouchwidth'];
            else			$width=$options['width'];
            $widthparam = " width=".$width." style=\"width:".$width."px;\" ";

            global $table_prefix, $wpdb;
            $table_name = $wpdb->prefix . "noshop_products";

            if($cat<>"") {
                $sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE category=%s ORDER BY ndx, title", $cat );
            } else {
                $sql = $wpdb->prepare( "SELECT * FROM $table_name ORDER BY ndx, title" );
            };
            
            $return = '';
            $myrows = $wpdb->get_results( $sql );


            foreach ($myrows as $myrows) {
                //$ret = file_get_contents( $wp_noshop_dir . '/table.template.xhtml' );
				//
			$product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
            $dbversion = $wpdb->get_var( "SELECT option_value FROM $wpdb->options WHERE option_name LIKE 'noshop_db_version'" );
			$pid=$myrows->id; 
			//echo $pid.'|';
			$product_hhcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueHH>=1"));
			$product_Biacount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueBia>=1"));
			$product_BiaScount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueBiaS>=1"));
			$product_BiaLcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueBiaL>=1"));
			$product_hhucount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueHHu>=1"));
			$product_hlucount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueHLu>=1"));
			$product_zcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueZ>=1"));
			$product_zhcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueZh>=1"));
			$product_ucount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueU>=1"));
			$product_hscount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueHs>=1"));
			$product_bcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalue>=1"));
			$product_dcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueD>=1"));
			$product_hcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueH>=1"));
			$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid "));
			
			$t1_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl>"" '));
			if ( $t1_count>=1) {  echo "<link rel=\"stylesheet\" href=\"".plugins_url()."/modellen/t1.css\" >\n"; }
			
			$t2_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl2>"" '));
			if ( $t2_count>=1) {  echo "<link rel=\"stylesheet\" href=\"".plugins_url()."/modellen/t2.css\" >\n"; }
			
			$t3_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl3>"" '));
			if ( $t3_count>=1) {  echo "<link rel=\"stylesheet\" href=\"".plugins_url()."/modellen/t3.css\" >\n"; }
			
			$_SESSION['product_hhcount']=$product_hhcount;
			$_SESSION['product_biacount']=$product_Biacount;
			$_SESSION['product_biascount']=$product_BiaScount;
			$_SESSION['product_bialcount']=$product_BiaLcount;
			$_SESSION['product_hhucount']=$product_hhucount;
			$_SESSION['product_hlucount']=$product_hlucount;
			$_SESSION['product_zcount']=$product_zcount;
			$_SESSION['product_zhcount']=$product_zhcount;
			$_SESSION['product_ucount']=$product_ucount;
			$_SESSION['product_bcount']=$product_bcount;
			$_SESSION['product_dcount']=$product_dcount;
			$_SESSION['product_hcount']=$product_hcount;
			$_SESSION['product_hscount']=$product_hscount;
			$_SESSION['count']=$count;
			
			
			//echo $product_hhcount.' Bia | '; 
//
				$ret = file_get_contents( plugins_url().'/modellen/table.template.php' );
                $ret = str_replace( '{url}', $myrows->url, $ret );
				
                if($myrows->url<>"") {
                    $ret = str_replace( '{autourlbegin}', '<a href="'.$myrows->url.'">', $ret );
                    $ret = str_replace( '{autourlend}', '</a>', $ret );
					
					if ( $t2_count>=1) { 
					$ret = str_replace( '{t1-begin}', '<a href="'.$myrows->timgurl.'">', $ret );
                    $ret = str_replace( '{t1-end}', '</a>', $ret );
					} else {
					$ret = str_replace( '{t1-begin}', '', $ret );
                    $ret = str_replace( '{t1-end}', '', $ret );
					}
					
					if ( $t2_count>=1) { 
					$ret = str_replace( '{t2-begin}', '<a href="'.$myrows->timgurl2.'">', $ret );
                    $ret = str_replace( '{t2-end}', '</a>', $ret );
					} else {
					$ret = str_replace( '{t2-begin}', '', $ret );
                    $ret = str_replace( '{t2-end}', '', $ret );
					}
						
					if ( $t3_count>=1) { 
					$ret = str_replace( '{t3-begin}', '<a href="'.$myrows->timgurl3.'">', $ret );
					$ret = str_replace( '{t3-end}', '</a>', $ret );
					} else {
					$ret = str_replace( '{t3-begin}', '', $ret );
					$ret = str_replace( '{t3-end}', '', $ret );
					}
                } else {
                    $ret = str_replace( '{autourlbegin}', '', $ret );
                    $ret = str_replace( '{autourlend}', '', $ret );
                }
                $ret = str_replace( '{imgurl}', ( $myrows->imgurl ? $myrows->imgurl : $options['defimg'] ), $ret );
				$ret = str_replace( '{timgurl1}', ( $myrows->timgurl ? $myrows->timgurl  : $options['defimg'] ), $ret );
				$ret = str_replace( '{timgurl2}', ( $myrows->timgurl ? $myrows->timgurl2 : $options['defimg'] ), $ret );
				$ret = str_replace( '{timgurl3}', ( $myrows->timgurl ? $myrows->timgurl3 : $options['defimg'] ), $ret );
                $ret = str_replace( '{title}', $myrows->title, $ret );
                $ret = str_replace( '{hashtitle}', urlencode($myrows->title), $ret );
                $ret = str_replace( '{description}', $myrows->description, $ret );
				$ret = str_replace( '{count}', $count, $ret );
                $ret = str_replace( '{subtable}', NoShop::createsubtable($myrows->id), $ret );
				$ret = str_replace( '{width}', $width, $ret );
                $ret = str_replace( '{widthparam}', $widthparam, $ret );
                $ret = str_replace( '{cat}', $cat, $ret );
                $ret = str_replace( '{ndx}', $myrows->ndx, $ret );
				
				
				if ($product_bcount==0) 	{$ret = str_replace( 'Breedte:','', $ret );					$ret = str_replace( 'noshop-product-spec-valDHZ-1','noshop-product-spec-valDHZ-none', $ret ); 
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-1','noshop-product-spec-none', $ret );}
																										
				if ($product_Biacount==0) 	{$ret = str_replace( 'Breedte inc armleuning:','', $ret );	$ret = str_replace( 'noshop-product-spec-valDHZ-2','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-2','noshop-product-spec-none', $ret );}
																										
				if ($product_BiaScount==0) 	{$ret = str_replace( 'Breedte inc armleuning S:','', $ret );$ret = str_replace( 'noshop-product-spec-valDHZ-3','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-3','noshop-product-spec-none', $ret );}
																										
				if ($product_BiaLcount==0) 	{$ret = str_replace( 'Breedte inc armleuning L:','', $ret );$ret = str_replace( 'noshop-product-spec-valDHZ-4','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-4','noshop-product-spec-none', $ret );}
																										
				if ($product_hcount==0) 	{$ret = str_replace( 'Hoogte:','', $ret );  				$ret = str_replace( 'noshop-product-spec-valDHZ-5','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-5','noshop-product-spec-none', $ret );}
																										
				if ($product_hhcount==0) 	{$ret = str_replace( 'Hoogte inc hoofdsteun:','', $ret );	$ret = str_replace( 'noshop-product-spec-valDHZ-6','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-6','noshop-product-spec-none', $ret );}
																										
				if ($product_hhucount==0) 	{$ret = str_replace( 'Hoge uitvoering:','', $ret );			$ret = str_replace( 'noshop-product-spec-valDHZ-7','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-7','noshop-product-spec-none', $ret );}
																										
				if ($product_hlucount==0) 	{$ret = str_replace( 'Lage uitvoering:','', $ret );			$ret = str_replace( 'noshop-product-spec-valDHZ-8','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-8','noshop-product-spec-none', $ret );}
																										
				if ($product_dcount==0) 	{$ret = str_replace( 'Diepte:','', $ret );					$ret = str_replace( 'noshop-product-spec-valDHZ-9','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-9','noshop-product-spec-none', $ret );}
																										
				if ($product_zhcount==0) 	{$ret = str_replace( 'Zit hoogte:','', $ret );				$ret = str_replace( 'noshop-product-spec-valDHZ-a1','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-a1','noshop-product-spec-none', $ret );}
																										
				if ($product_zcount==0) 	{$ret = str_replace( 'Zit diepte:','', $ret );				$ret = str_replace( 'noshop-product-spec-valDHZ-a2','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-a2','noshop-product-spec-none', $ret );}
																										
				if ($product_ucount==0) 	{$ret = str_replace( 'Uitval:','', $ret );					$ret = str_replace( 'noshop-product-spec-valDHZ-a3','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-a3','noshop-product-spec-none', $ret );}

				if ($product_hscount==0) 	{$ret = str_replace( 'Hoofdsteun breedte:','', $ret );		$ret = str_replace( 'noshop-product-spec-valDHZ-a4','noshop-product-spec-valDHZ-none', $ret );
																										$ret = str_replace( 'noshop-product-spec-valDHZtxt-a4','noshop-product-spec-none', $ret );}
			
                $return .= "\n\n<!-- -------------------- NoShop Item: ".$myrows->category." / ".$myrows->title." -------------------- -->\n" . $ret . "\n";
            } // End foreachVersie', '', '', 
            return $return;
        } // End createtable()

        public function createsubtable($id) {
            global $table_prefix, $wpdb;
            global $wp_noshop_url;
            global $wp_noshop_dir;
            global $wpmu_noshop_url;
            global $wpmu_noshop_dir;

            $options = get_option('noshop_options');

            $spectitlewidth=$options['spectitlewidth'];
            $spectitlewidthparam = " width=".$spectitlewidth." style=\"width:".$spectitlewidth."px;\" ";

            $table_name = $wpdb->prefix . "noshop_product_specs";

            $return = '';
			$temcoundHH=0;

			
            $myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE product_id=" . intval($id) ."" );
            foreach ($myrows as $myrows) {
				
			 
			//echo $_SESSION['product_hhucount'].' hh  | <br>'; 
			
			
                $ret = file_get_contents( $wp_noshop_dir . '/subtable.template.php' );
				$tempSpecTitle= $myrows->spectitle;
				if ($tempSpecTitle=="4.5 zits") {$tempSpecTitle="4&#189; zits"; } else
				if ($tempSpecTitle=="3.5 zits") {$tempSpecTitle="3&#189; zits"; } else
				if ($tempSpecTitle=="2.5 zits") {$tempSpecTitle="2&#189; zits"; }
				
				
				$ret = str_replace( '{spectitle}', $tempSpecTitle, $ret );
              
				$temcoundB=$_SESSION['product_bcount']; 
				$tempspecvalueB= $myrows->specvalue;
				$temcoundB=$temcoundB+$tempspecvalueB; 
				if ($tempspecvalueB=="" and $temcoundB>=1 ) {$tempspecvalueB="*"; }  else { $tempspecvalueB=$tempspecvalueB;} 
				if ($tempspecvalueB=="" and $temcoundB==0 ) {$tempspecvalueB=""; }   else { $tempspecvalueB=$tempspecvalueB;}
				$ret = str_replace( '{specvalue}', $tempspecvalueB, $ret );
				
				
				
				$temcoundBia=$_SESSION['product_biacount']; 
				$tempspecvalueBia= $myrows->specvalueBia;
				$temcoundBia=$temcoundBia+$tempspecvalueBia; 
				if ($tempspecvalueBia=="" and $temcoundBia>=1 ) {$tempspecvalueBia="-"; }  else { $tempspecvalueBia=$tempspecvalueBia;} 
				if ($tempspecvalueBia=="" and $temcoundBia==0 ) {$tempspecvalueBia=""; }   else { $tempspecvalueBia=$tempspecvalueBia;}
				$ret = str_replace( '{specvalueBia}', $tempspecvalueBia, $ret );
				
				$temcoundBiaS=$_SESSION['product_biascount']; 
				$tempspecvalueBiaS= $myrows->specvalueBiaS;
				$temcoundBiaS=$temcoundBiaS+$tempspecvalueBiaS; 
				if ($tempspecvalueBiaS=="" and $temcoundBiaS>=1 ) {$tempspecvalueBiaS="-"; }  else { $tempspecvalueBiaS=$tempspecvalueBiaS;} 
				if ($tempspecvalueBiaS=="" and $temcoundBiaS==0 ) {$tempspecvalueBiaS=""; }   else { $tempspecvalueBiaS=$tempspecvalueBiaS;}
				$ret = str_replace( '{specvalueBiaS}', $tempspecvalueBiaS, $ret );
				
				$temcoundBiaL=$_SESSION['product_bialcount']; 
				$tempspecvalueBiaL= $myrows->specvalueBiaL;
				$temcoundBiaL=$temcoundBiaL+$tempspecvalueBiaL; 
				if ($tempspecvalueBiaL=="" and $temcoundBiaL>=1 ) {$tempspecvalueBiaL="-"; }  else { $tempspecvalueBiaL=$tempspecvalueBiaL;} 
				if ($tempspecvalueBiaL=="" and $temcoundBiaL==0 ) {$tempspecvalueBiaL=""; }   else { $tempspecvalueBiaL=$tempspecvalueBiaL;}
				$ret = str_replace( '{specvalueBiaL}', $tempspecvalueBiaL, $ret );
				
				
				$temcoundD=$_SESSION['product_dcount']; 
				$tempspecvalueD= $myrows->specvalueD;
				$temcoundD=$temcoundD+$tempspecvalueD; 
				if ($tempspecvalueD=="" and $temcoundD>=1 ) {$tempspecvalueD="-"; }  else { $tempspecvalueD=$tempspecvalueD;} 
				if ($tempspecvalueD=="" and $temcoundD==0 ) {$tempspecvalueD=""; }   else { $tempspecvalueD=$tempspecvalueD;}
				$ret = str_replace( '{specvalueD}', $tempspecvalueD, $ret );
				
				
				$temcoundZ=$_SESSION['product_zcount']; 
				$tempspecvalueZ= $myrows->specvalueZ;
				$temcoundZ=$temcoundZ+$tempspecvalueZ; 
				if ($tempspecvalueZ=="" and $temcoundZ>=1 ) {$tempspecvalueZ="-"; }  else { $tempspecvalueZ=$tempspecvalueZ;} 
				if ($tempspecvalueZ=="" and $temcoundZ==0 ) {$tempspecvalueZ=""; }   else { $tempspecvalueZ=$tempspecvalueZ;}
				$ret = str_replace( '{specvalueZ}', $tempspecvalueZ, $ret );
				
				$temcoundZh=$_SESSION['product_zhcount']; 
				$tempspecvalueZh= $myrows->specvalueZh;
				$temcoundZh=$temcoundZh+$tempspecvalueZh; 
				if ($tempspecvalueZh=="" and $temcoundZh>=1 ) {$tempspecvalueZh="-"; }  else { $tempspecvalueZh=$tempspecvalueZh;} 
				if ($tempspecvalueZh=="" and $temcoundZh==0 ) {$tempspecvalueZh=""; }   else { $tempspecvalueZh=$tempspecvalueZh;}
				$ret = str_replace( '{specvalueZh}', $tempspecvalueZh, $ret );
				
				$temcoundH=$_SESSION['product_hcount']; 
				$tempspecvalueH= $myrows->specvalueH;
				$temcoundH=$temcoundH+$tempspecvalueH; 
				if ($tempspecvalueH=="" and $temcoundH>=1 ) {$tempspecvalueH="-"; }  else { $tempspecvalueH=$tempspecvalueH;} 
				if ($tempspecvalueH=="" and $temcoundH==0 ) {$tempspecvalueH=""; }   else { $tempspecvalueH=$tempspecvalueH;}
				$ret = str_replace( '{specvalueH}', $tempspecvalueH, $ret );
				
		
				$temcoundHH=$_SESSION['product_hhcount']; 
				$tempspecvalueHH= $myrows->specvalueHH;
				$temcoundHH=$temcoundHH+$tempspecvalueHH; 
				if ($tempspecvalueHH=="" and $temcoundHH>=1 ) {$tempspecvalueHH="-"; }  else { $tempspecvalueHH=$tempspecvalueHH;} 
				if ($tempspecvalueHH=="" and $temcoundHH==0 ) {$tempspecvalueHH=""; }   else { $tempspecvalueHH=$tempspecvalueHH;}
				$ret = str_replace( '{specvalueHH}', $tempspecvalueHH, $ret );
				
				$temcoundHHu=$_SESSION['product_hhucount']; 
				$tempspecvalueHHu= $myrows->specvalueHHu;
				$temcoundHHu=$temcoundHHu+$tempspecvalueHHu; 
				if ($tempspecvalueHHu=="" and $temcoundHHu>=1 ) {$tempspecvalueHHu="-"; }  else { $tempspecvalueHHu=$tempspecvalueHHu;} 
				if ($tempspecvalueHHu=="" and $temcoundHHu==0 ) {$tempspecvalueHHu=""; }   else { $tempspecvalueHHu=$tempspecvalueHHu;}
				$ret = str_replace( '{specvalueHHu}', $tempspecvalueHHu, $ret );
				
				$temcoundHLu=$_SESSION['product_hlucount']; 
				$tempspecvalueHLu= $myrows->specvalueHLu;
				$temcoundHLu=$temcoundHHu+$tempspecvalueHLu; 
				//echo $temcoundHLu.'   hhu| '.$product_hlucount.'|'; 
				if ($tempspecvalueHLu=="" and $temcoundHLu>=1 ) {$tempspecvalueHLu="-"; }  else { $tempspecvalueHLu=$tempspecvalueHLu;} 
				if ($tempspecvalueHLu=="" and $temcoundHLu==0 ) {$tempspecvalueHLu=""; }   else { $tempspecvalueHLu=$tempspecvalueHLu;}
				$ret = str_replace( '{specvalueHLu}', $tempspecvalueHLu, $ret );
				
				$temcoundU=$_SESSION['product_ucount']; 
				$tempspecvalueU= $myrows->specvalueU;
				$temcoundU=$temcoundU+$tempspecvalueU; 
				if ($tempspecvalueU=="" and $temcoundU>=1 ) {$tempspecvalueU="-"; }  else { $tempspecvalueU=$tempspecvalueU;} 
				if ($tempspecvalueU=="" and $temcoundU==0 ) {$tempspecvalueU=""; }   else { $tempspecvalueU=$tempspecvalueU;}
				$ret = str_replace( '{specvalueU}', $tempspecvalueU, $ret );

				$temcoundHs=$_SESSION['product_hscount']; 
				$tempspecvalueHs= $myrows->specvalueHs;
				$temcoundHs=$temcoundHs+$tempspecvalueHs; 
				if ($tempspecvalueHs=="" and $temcoundHs>=1 ) {$tempspecvalueHs="-"; }  else { $tempspecvalueHs=$tempspecvalueHs;} 
				if ($tempspecvalueHs=="" and $temcoundHs==0 ) {$tempspecvalueHs=""; }   else { $tempspecvalueHs=$tempspecvalueHs;}
				$ret = str_replace( '{specvalueHs}', $tempspecvalueHs, $ret );
				
				
                $ret = str_replace( '{spectitlewidth}', $spectitlewidth, $ret );
                $ret = str_replace( '{spectitlewidthparam}', $spectitlewidthparam, $ret );
				$ret = str_replace( '(1)','<span style="color: #F00; font-size: 9px;"><sup>1</sup></span>', $ret );
				$ret = str_replace( '(2)','<span style="color: #F00; font-size: 9px;"><sup>2</sup></span>', $ret );
				$ret = str_replace( '(3)','<span style="color: #F00; font-size: 9px;"><sup>3</sup></span>', $ret );
				$ret = str_replace( '(4)','<span style="color: #F00; font-size: 9px;"><sup>4</sup></span>', $ret );
				
				
					
               $return .= "\n\n<!-- -------------------- NoShop Item Specification: ".$myrows->spectitle." -------------------- -->\n" . $ret . "\n";
            } // End foreach
            return $return;
        } // End createsubtable()







        // //////////////////////////////////////////////////////////////////////////////////////////////////////////// //
        //                                                                                                              //
        //   OPTION PAGE                                                                                                //
        //                                                                                                              //
        // //////////////////////////////////////////////////////////////////////////////////////////////////////////// //
        public static function OptionPage() {
            global $wpdb, $noshop_version, $noshop_db_version;

            // get options
            $options = $newoptions = get_option('noshop_options');

            $dir_name = '/wp-content/plugins/modellen';
            $url = get_bloginfo('wpurl');
            $myURL = $url.$dir_name.'/';
           			//	printf( __('My URL: %s.', 'noshop'), $myURL);

            // Check for permissions
            if (!current_user_can('manage_options'))  {
                wp_die( __('You do not have sufficient permissions to access this page.') );
            }

            global $table_prefix, $wpdb;
            $table_name = $wpdb->prefix . "noshop_products";
            $subtable_name = $wpdb->prefix . "noshop_product_specs";

            // if submitted, process results
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="options") {
                //echo "*** Updating options!!! ***";
                $newoptions['width'] = strip_tags(stripslashes($_POST["width"]));
                $newoptions['wptouchwidth'] = strip_tags(stripslashes($_POST["wptouchwidth"]));
                $newoptions['defimg'] = strip_tags(stripslashes($_POST["defimg"]));
                $newoptions['spectitlewidth'] = strip_tags(stripslashes($_POST["spectitlewidth"]));
                $newoptions['noshopcss'] = strip_tags(stripslashes($_POST["noshopcss"]));

                if ($_POST["visibleerrors"]=="on") {
                    $newoptions['visibleerrors'] = "true";
                } else {
                    $newoptions['visibleerrors'] = "";
                }

                // //////////////////////////////////////////////////////////// //
                // check if installed (hook is not called if used as mu-plugin) //
                // //////////////////////////////////////////////////////////// //
                //$wtemp = get_option('noshop_width');
                //if( empty($wtemp) ){
                //	echo "WHAT NOT INST?!? ****";
                //	NoShop::noshop_activate();
                //}
            }

            // if product selected, save selection persistantly as an option
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="productselect") {
                $newoptions['selectproduct'] = strip_tags(stripslashes($_POST["selectproduct"]));
                // Update selected product option
                update_option('noshop_selectproduct', $_POST['noshop_selectproduct']);
            }

            // any changes? save!
            if ( $options != $newoptions ) {
                $options = $newoptions;
                update_option('noshop_options', $options);
            }

            // If needed, add a product
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="productadd") {
                $wpdb->query(
                    $wpdb->prepare( "INSERT INTO $table_name ( category, title, description, url, imgurl, timgurl, timgurl2, timgurl3,imgurlmode ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s  )",
                                    array(
                                            'New Category',
                                            'New Product',
                                            'Please type a description',
                                            '',
                                            '',
                                            '',
											'',
                                            '',
											''
                                    )
                    )
                );
            }

            // If needed, delete a product
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="productdelete") {
                $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id=%d", array( $options['selectproduct'] ) ) );
            }

            // If needed, add a specification row
            //				if ($_POST["addspec"]=="on") {
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="productaddspec") {
                $wpdb->query(
                    $wpdb->prepare( "INSERT INTO $subtable_name ( product_id, spectitle, specvalue, specvalueD, specvalueZ, specvalueZh, specvalueH, specvalueHH, specvalueBia, specvalueBiaS, specvalueBiaL, specvalueHHu, specvalueHLu, specvalueU, specvalueHs ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
								  array($options['selectproduct'], 'Versie', '', '', '', '', '', '', '', '', '', '', '', '', '', '') )
                );
            }


            // ////////////////////////////////////////////////////////// //
            // option handling - db interaction                           //
            // ////////////////////////////////////////////////////////// //

            // if options form was sent, process those...
            if ( isset($_POST["noshop_submit"]) && $_POST["noshop_submit"]=="productupdate") {
                //if( isset($_POST['action']) && $_POST['action'] == "updateoptions" ){

                // Update the product info
                $sql = $wpdb->prepare( "UPDATE $table_name SET category=%s, title=%s, ndx=%s, description=%s, url=%s, imgurl=%s, timgurl=%s, timgurl2=%s, timgurl3=%s, ho=%s, hoh=%s, diep=%s, zie=%s, imgurlmode=%s WHERE id=%d",
                                        array(
                                            mysql_real_escape_string($_POST[prodcat]),
                                            mysql_real_escape_string($_POST[prodtitle]),
                                            intval($_POST[prodndx]),
                                            mysql_real_escape_string($_POST[proddesc]),
                                            mysql_real_escape_string($_POST[produrl]),
											mysql_real_escape_string($_POST[prodimgurl]),
											mysql_real_escape_string($_POST[thumimgurl]),
											mysql_real_escape_string($_POST[thumimgurl2]),
											mysql_real_escape_string($_POST[thumimgurl3]),
											mysql_real_escape_string($_POST[prodho]),
											mysql_real_escape_string($_POST[prodhoh]),
											mysql_real_escape_string($_POST[proddiep]),
											mysql_real_escape_string($_POST[prodzie]),
                                            
                                            mysql_real_escape_string($_POST[prodimgurlmode]),
                                            $options['selectproduct']
                                        )
                );
                $wpdb->query( $sql );
                //echo "SQL: $sql<br />";

                // Update the product spec if there (Thanks Gayan)
                if(is_array($_POST["spec"])){
                    foreach($_POST["spec"] as $spec) {
                        if($spec[title]!="") {
                            $sql = $wpdb->prepare( "UPDATE $subtable_name SET spectitle=%s, specvalue=%s, specvalueD=%s, specvalueZ=%s, specvalueZh=%s, specvalueH=%s, specvalueHH=%s, specvalueBia=%s, specvalueBiaS=%s, specvalueBiaL=%s, specvalueHHu=%s, specvalueHLu=%s, specvalueU=%s, specvalueHs=%s  WHERE product_id=%d AND id=%d",
                                                    array($spec[title], $spec[value], $spec[valueD], $spec[valueZ], $spec[valueZh], $spec[valueH], $spec[valueHH], $spec[valueBia], $spec[valueBiaS], $spec[valueBiaL], $spec[valueHHu], $spec[valueHLu], $spec[valueU], $spec[valueHs], $options['selectproduct'], $spec[id] ) );
                        } else {
                            echo "One specification line item being deleted!<br />";
                            $sql = $wpdb->prepare( "DELETE FROM $subtable_name WHERE product_id=%d AND id=%d",
                                                    array($options['selectproduct'], $spec[id] ) );
                        }
                        $wpdb->query( $sql );
                    }
                }
            }




            
            
            
            
            
            
            
            
            
            
            


            // ////////////////////////////////////////////////////////// //
            // Build Product Option Page!
            // ////////////////////////////////////////////////////////// //
            $product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
            $dbversion = $wpdb->get_var( "SELECT option_value FROM $wpdb->options WHERE option_name LIKE 'noshop_db_version'" );

            echo '<div class="wrap"><p><h2>'.__('Model Options', 'noshop').'</h2></p>';
            echo '<p>';
            echo 'Total number of items in database: <b>' . $product_count . '</b> ';
            echo '| Currently selected item ID: ' . $options['selectproduct'] . ' ';
            echo '| Plugin Version: ' . $noshop_version . ' ';
            echo '| Plugin DB Version: ' . $noshop_db_version . ' ';
            
            echo '</p>';
            echo '</div>';
			$pid=$options['selectproduct']; 
			$product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid"));
			
			$t1_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl>"" '));
			if ( $t1_count>=1) {  echo "<link rel=\"stylesheet\" href=\"".plugins_url()."/modellen/t1.css\" >\n"; }
			
			$t2_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl2>"" '));
			$t3_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM wp_noshop_products WHERE id='.$pid.' and timgurl3>"" '));
			$product_hhcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_noshop_product_specs WHERE product_id=$pid and specvalueHH>=1"));
            $dbversion = $wpdb->get_var( "SELECT option_value FROM $wpdb->options WHERE option_name LIKE 'noshop_db_version'" );

            echo '<div class="wrap"><p><h2>'.__('Model Options', 'noshop').'</h2></p>';
            echo '<p>';
            echo 'totaal aantal modellen: <b>' . $product_count . ' id: '.$pid.'</b> waarvan ';
			
                     
            echo '</p>';
            echo '</div>';
            
            // ////////////////////////////////////////////////////////// //
            // Product creation
            // ////////////////////////////////////////////////////////// //

            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';
            echo '<div class="wrap">';
            echo '<input type="hidden" name="noshop_submit" value="productadd"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Nieuw model aanmaken &raquo;', 'noshop').'"></input></p>';
            echo "</div>";
            echo '</form>';

            // ////////////////////////////////////////////////////////// //
            // Product selection
            // ////////////////////////////////////////////////////////// //


            $mycategories = $wpdb->get_results( "SELECT DISTINCT category FROM " . $table_name . " ORDER BY category");
            $mytitles = $wpdb->get_results( "SELECT title FROM " . $table_name . " ORDER BY category");

            // print_r($mycategories);

            $product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
			$count_t1	   = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
			
            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';
            echo '<div class="wrap">';
            $myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " ORDER BY Category, Title");
            echo '<select name="selectproduct" onchange=\'this.form.submit()\'>';
            foreach ($myrows as $myrows) {
                echo '<option value=' . $myrows->id . ''. ($myrows->id==$options['Selecteer model']?' SELECTED':'') . '>' . $myrows->category . ' | ' . $myrows->title . '';
            }
            echo '</select>';

            // SUBMIT
            echo '<input type="hidden" name="noshop_submit" value="productselect"></input>';
            echo '<span class="submit"><input type="submit" value="'.__('Selecteer model &raquo;', 'noshop').'"></input></span>';
            echo "</div>";
            echo '</form>';


            // ////////////////////////////////////////////////////////// //
            // Products Form
            // ////////////////////////////////////////////////////////// //

            $mycurrentprod = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE ID=".intval($options['selectproduct']) );

            // settings
            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';

            // Image thumbnail
            //				echo '<div style="float:right; max-width:160px; max-height:160px; ">';
            //				echo '<img src="'.$mycurrentprod[0]->imgurl.'" style="float:right; max-width:160px; max-height:160px; " />';
            //				echo '</div>';

            echo '<div class="wrap">';

            echo '<table class="form-table">';

            // Category and Title
            echo '<tr valign="top"><th scope="row">'.__('Category, Title and Sort Index', 'noshop').'</th>';
            echo '<td>';
            echo '<input type="text" name="prodcat" value="'.$mycurrentprod[0]->category.'" size="15"></input>';
            echo '<input type="text" name="prodtitle" value="'.$mycurrentprod[0]->title.'" size="35"></input>';
            echo '<input type="text" name="prodndx" value="'.$mycurrentprod[0]->ndx.'" size="5"></input>';
            echo '</td></tr>';

            // Image URL
            echo '<tr valign="top"><th scope="row">'.__('Product foto<br><i>720 x 440 px</i>', 'noshop').'</th>';
            echo '<td>';
            echo '<input type="hidden" name="prodimgurl" value="'.$mycurrentprod[0]->imgurl.'" size="80"></input>';
			?>
            <input type="hidden"  name="prodimgurl"  value="<?PHP echo $mycurrentprod[0]->imgurl; ?>" size="40" />
            <input type="button" class="onetarek-upload-button button" value="Upload Image" />
			<?PHP 
			echo '<img src="'.$mycurrentprod[0]->imgurl.'" style="float:left; max-width:160px; max-height:160px; border: 1px #CCC dotted; margin-right: 10px" />';
			echo '<select name="prodimgurlmode">';
            echo '<option value=\'\''. ($mycurrentprod[0]->imgurlmode==''?' SELECTED':'') . '>Standard Link';
            echo '<option value=\'W\''. ($mycurrentprod[0]->imgurlmode=='W'?' SELECTED':'') . '>Popup in new Window';
            echo '<option value=\'T\''. ($mycurrentprod[0]->imgurlmode=='T'?' SELECTED':'') . '>Popup in new Tab';
            echo '</select>';
            echo '</td></tr>';
			
			 // Image URL
            echo '<tr valign="top"><th scope="row">'.__('Product foto 1<br><i>720 x 440 px max.', 'noshop').'</th>';
            echo '<td>';
            echo '<input type="hidden" name="thumimgurl" value="'.$mycurrentprod[0]->timgurl.'" size="80"></input>';
			?>
            <input type="hidden"  name="thumimgurl"  value="<?PHP echo $mycurrentprod[0]->timgurl; ?>" size="40" />
            <input type="button" class="onetarek-upload-button button" value="Upload Image" /><?PHP 
			echo '<img src="'.$mycurrentprod[0]->timgurl.'" style="float:left; max-width:160px; max-height:160px; border: 1px #CCC dotted; margin-right: 10px" />';
			echo '</td></tr>';
			
			 // Image URL 2
            echo '<tr valign="top"><th scope="row">'.__('Product foto 2<br><i>720 x 440 px max.', 'noshop').'</th>';
            echo '<td>';
            echo '<input type="hidden" name="thumimgurl2" value="'.$mycurrentprod[0]->timgurl2.'" size="80"></input>';
			?>
            <input type="hidden"  name="thumimgurl2"  value="<?PHP echo $mycurrentprod[0]->timgurl2; ?>" size="40" />
            <input type="button" class="onetarek-upload-button button" value="Upload Image" /><?PHP 
			echo '<img src="'.$mycurrentprod[0]->timgurl2.'" style="float:left; max-width:160px; max-height:160px; border: 1px #CCC dotted; margin-right: 10px" />';
			echo '</td></tr>';
			
			 // Image URL 3
            echo '<tr valign="top"><th scope="row">'.__('Product foto 3<br><i>720 x 440 px max.', 'noshop').'</th>';
            echo '<td>';
            echo '<input type="hidden" name="thumimgurl3" value="'.$mycurrentprod[0]->timgurl3.'" size="80"></input>';
			?>
            <input type="hidden"  name="thumimgurl3"  value="<?PHP echo $mycurrentprod[0]->timgurl3; ?>" size="40" />
            <input type="button" class="onetarek-upload-button button" value="Upload Image" /><?PHP 
			echo '<img src="'.$mycurrentprod[0]->timgurl3.'" style="float:left; max-width:160px; max-height:160px; border: 1px #CCC dotted; margin-right: 10px" />';
			echo '</td></tr>';
			
			echo '<tr valign="top"><th scope="row">'.__('', 'noshop').'</th>';
            echo '<td>';
			echo '<input type="hidden" name="noshop_submit" value="productupdate"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Update fotos &raquo;', 'noshop').'"></input></p>';
            echo '</td></tr>';
           
			
            // Description
            echo '<tr valign="top"><th scope="row">'.__('Omschrijving', 'noshop').'</th>';
            echo '<td>';
            echo '<textarea name="proddesc" rows=8 cols=80>'.$mycurrentprod[0]->description.'</textarea>';
            
            echo '</td></tr>';

            // URL
            echo '<tr valign="top"><th scope="row">'.__('Reference URL', 'noshop').'</th>';
            echo '<td><input type="text" name="produrl" value="'.$mycurrentprod[0]->url.'" size="80"></input>';
            echo '</td></tr>';
			
			//hoogte
			echo '<tr valign="top"><th scope="row">'.__('(1) verklaaring', 'noshop').'</th>';
            echo '<td><input type="text" name="prodho" value="'.$mycurrentprod[0]->ho.'" size="80"></input> ';
            echo '</td></tr>';
			echo '<tr valign="top"><th scope="row">'.__('(2) verklaaring', 'noshop').'</th>';
            echo '<td><input type="text" name="prodhoh" value="'.$mycurrentprod[0]->hoh.'" size="80"></input>';
            echo '</td></tr>';
			echo '<tr valign="top"><th scope="row">'.__('(3) verklaaring').'</th>';
            echo '<td><input type="text" name="proddiep" value="'.$mycurrentprod[0]->diep.'" size="80"></input> ';
            echo '</td></tr>';
			echo '<tr valign="top"><th scope="row">'.__('(4) verklaaring', 'noshop').'</th>';
            echo '<td><input type="text" name="prodzie" value="'.$mycurrentprod[0]->zie.'" size="80"></input> ';
            echo '</td></tr>';
			
			            
			echo '</table>';


            $mycurrentspecs = $wpdb->get_results( "SELECT * FROM " . $subtable_name . " WHERE product_id=".intval($options['selectproduct']) );
            echo '<table class="form-table">';
            foreach ($mycurrentspecs as $mycurrentspecs) {
                // Specifications
				
                echo "<tr valign=\"top\"><th scope=\"row\">".__($mycurrentspecs->spectitle, 'noshop')."</th>";
                echo "<td>";
                //echo "<input type=\"text\" name=\"spec[".$mycurrentspecs->id."][title]\" value=\"".$mycurrentspecs->spectitle."\" size=\"15\" />";
				
				echo "<select name=\"spec[".$mycurrentspecs->id."][title]\">";
				echo '<option value=\'\''. 		($mycurrentspecs->spectitle== ''?' SELECTED':'') . '>';
				echo '<option value=\'4.5 zits\''. 	($mycurrentspecs->spectitle =='4.5 zits'?' SELECTED':'') . 	'>4&#189; zits';
				echo '<option value=\'4 zits\''	. 	($mycurrentspecs->spectitle =='4 zits'?' SELECTED':''). 	'>4 zits';
				echo '<option value=\'3.5 zits\''. 	($mycurrentspecs->spectitle =='3.5 zits'?' SELECTED':''). 	'>3&#189; zits';
				echo '<option value=\'3 zits\''. 	($mycurrentspecs->spectitle =='3 zits'?' SELECTED':''). 	'>3 zits';
				echo '<option value=\'2.5 zits\''. 	($mycurrentspecs->spectitle =='2.5 zits'?' SELECTED':''). 	'>2&#189; zits';
				echo '<option value=\'2 zits\''. 	($mycurrentspecs->spectitle =='2 zits'?' SELECTED':''). 	'>2 zits';
				echo '<option value=\'Loveseat\''. 	($mycurrentspecs->spectitle =='Loveseat'?' SELECTED':''). 	'>Loveseat';
				echo '<option value=\'Fauteuil\''. 	($mycurrentspecs->spectitle =='Fauteuil'?' SELECTED':''). 	'>Fauteuil';
				echo '<option value=\'Fauteuil hoog\''. 	($mycurrentspecs->spectitle =='Fauteuil hoog'?' SELECTED':''). 	'>Fauteuil hoog';
				echo '<option value=\'Fauteuil laag\''. 	($mycurrentspecs->spectitle =='Fauteuil laag'?' SELECTED':''). 	'>Fauteuil laag';
				echo '<option value=\'Hocker\''. 	($mycurrentspecs->spectitle =='Hocker'?' SELECTED':''). 	'>Hocker';
				echo '<option value=\'Hocker - Salontafel\''. 	($mycurrentspecs->spectitle =='Hocker - Salontafel'?' SELECTED':''). 	'>Hocker -Salontafel';
				echo '<option value=\'Poef\''. 	($mycurrentspecs->spectitle =='Poef'?' SELECTED':''). 	'>Poef';
				echo '<option value=\'Sofa element\''. 	($mycurrentspecs->spectitle =='Sofa element'?' SELECTED':''). 	'>Sofa element';
				echo '<option value=\'Hoekelement\''. 	($mycurrentspecs->spectitle =='Hoekelement'?' SELECTED':''). 	'>Hoekelement';
				echo '<option value=\'Armleuning\''. 	($mycurrentspecs->spectitle =='Armleuning'?' SELECTED':''). 	'>Armleuning';
				echo '<option value=\'Armleuning smal\''. 	($mycurrentspecs->spectitle =='Armleuning smal'?' SELECTED':''). 	'>Armleuning smal';
				echo '<option value=\'Armleuning breed\''. 	($mycurrentspecs->spectitle =='Armleuning breed'?' SELECTED':''). 	'>Armleuning breed';
				echo '<option value=\'Armleuning smal XL\''. 	($mycurrentspecs->spectitle =='Armleuning smal XL'?' SELECTED':''). 	'>Armleuning smal XL';
				echo '<option value=\'Armleuning breed XL\''. 	($mycurrentspecs->spectitle =='Armleuning breed'?' SELECTED':''). 	'>Armleuning breed XL';
				echo '<option value=\'Arm blok\''. 	($mycurrentspecs->spectitle =='Arm blok'?' SELECTED':''). 	'>Arm blok';
				echo '<option value=\'Arm hocker\''. 	($mycurrentspecs->spectitle =='Arm hocker'?' SELECTED':''). 	'>Arm hocker';
				echo '<option value=\'Hooftsteun\''. 	($mycurrentspecs->spectitle =='Hooftsteun'?' SELECTED':''). 	'>Hooftsteun';
				echo '<option value=\'Voetensteun\''. 	($mycurrentspecs->spectitle =='Voetensteun'?' SELECTED':''). 	'>Voetensteun';	
				echo '</select>';
	
                echo "<strong>Breedte: </strong><input type=\"text\" name=\"spec[".$mycurrentspecs->id."][value]\" value=\"".$mycurrentspecs->specvalue."\" size=\"1\" /> ";
				echo " <strong>Diepte: </strong><input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueD]\" value=\"".$mycurrentspecs->specvalueD."\" size=\"1\" /> ";
				echo " <strong>Hoogte: </strong><input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueH]\" value=\"".$mycurrentspecs->specvalueH."\" size=\"1\" /> <br>";
				echo "<div style=\"border-bottom: 1px solid #CCC\" >";
				echo " <strong style=\"color: #00F;  font-weight: bold; margin-left: 10px;\">EXTRA opties: </strong><br> ";
				echo "</div>"; 
				
				echo "<div style=\"border: 1px solid #CCC; margin: 10px; float: left; \" >";
				echo "<div style=\" margin: 10px; float: left; line-height: 25px; \" >";
				echo " Breedte inc armleuning: <br> ";
				echo " Breedte inc arm S: <br> ";
				echo " Breedte inc arm L: <br> ";
				echo "</div>";
				echo "<div style=\" margin: 10px; float: left; \" >";
				echo "<input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueBia]\" value=\"".$mycurrentspecs->specvalueBia."\" size=\"1\" /><br> ";
				echo "<input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueBiaS]\" value=\"".$mycurrentspecs->specvalueBiaS."\" size=\"1\" /><br> ";
				echo "<input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueBiaL]\" value=\"".$mycurrentspecs->specvalueBiaL."\" size=\"1\" /><br> ";
				echo "</div>"; 
				echo "</div>"; 

				echo "<div style=\"border: 1px solid #CCC; margin: 10px; float: left; \" >";
				echo "<div style=\" margin: 10px; float: left; line-height: 25px; \" >";
				echo " Hoogte+Hoofdsteun:<br> ";
				echo " Hoge uitvoering:<br> ";
				echo " Lage uitvoering:<br> ";
				echo "</div>";
				echo "<div style=\" margin: 10px; float: left; \" >";
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueHH]\" value=\"".$mycurrentspecs->specvalueHH."\" size=\"1\" /><br> ";
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueHHu]\" value=\"".$mycurrentspecs->specvalueHHu."\" size=\"1\" class='noshop' /><br> ";
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueHLu]\" value=\"".$mycurrentspecs->specvalueHLu."\" size=\"1\" /><br> ";
				echo "</div>"; 
				echo "</div>";


				echo "<div style=\"border: 1px solid #CCC; margin: 10px; float: left; \" >";
				echo "<div style=\" margin: 10px; float: left; line-height: 25px; \" >";
				echo " Zitdiepte:<br> ";
				echo " Zithoogte:<br> ";
				echo " Uitval:<br> ";
				echo "</div>";
				echo "<div style=\" margin: 10px; float: left; \" >";				
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueZ]\" value=\"".$mycurrentspecs->specvalueZ."\" size=\"1\" /><br> ";
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueZh]\" value=\"".$mycurrentspecs->specvalueZh."\" size=\"1\" /><br> ";
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueU]\" value=\"".$mycurrentspecs->specvalueU."\" size=\"1\" /><br> ";
				echo "</div>"; 
				echo "</div>";

				echo "<div style=\"border: 1px solid #CCC; margin: 10px; float: left; \" >";
				echo "<div style=\" margin: 10px; float: left; line-height: 25px; \" >";
				echo " Hooftsteun:<br> ";
				echo "</div>";
				echo "<div style=\" margin: 10px; float: left; \" >";				
				echo " <input type=\"text\" name=\"spec[".$mycurrentspecs->id."][valueHs]\" value=\"".$mycurrentspecs->specvalueHs."\" size=\"1\" /><br> ";
				echo "</div>"; 
				echo "</div>";

                echo "<input type=\"hidden\" name=\"spec[".$mycurrentspecs->id."][id]\" value=\"".$mycurrentspecs->id."\" />";
                echo "</td></tr>";
            }
            echo "<tr valign=\"top\"><th scope=\"row\"></th>";
            echo "<td>";
            echo "<b>Erase all text from the specification lines you wish to delete, and then press Update Product.</b>";
            echo "</td></tr>";
            echo '</table>';

            // SUBMIT
            echo '<input type="hidden" name="noshop_submit" value="productupdate"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Update Product &raquo;', 'noshop').'"></input></p>';
            echo "</div>";
            echo '</form>';

			// ////////////////////////////////////////////////////////// //
            // Product specs creation
            // ////////////////////////////////////////////////////////// //

            $product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';
            //echo '<div class="wrap">';
            echo '<input type="hidden" name="noshop_submit" value="productaddspec"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Add a new specification line to above product &raquo;', 'noshop').'"></input></p>';
            //echo "</div>";
            echo '</form>';

            // ////////////////////////////////////////////////////////// //
            // Delete Product
            // ////////////////////////////////////////////////////////// //

            // $product_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_name));
            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';
            //echo '<div class="wrap">';
            echo '<input type="hidden" name="noshop_submit" value="productdelete"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Delete the above product &raquo;', 'noshop').'"></input></p>';
            //echo "</div>";
            echo '</form>';




            

            echo '<hr />';

            // ////////////////////////////////////////////////////////// //
            // Using Information
            // ////////////////////////////////////////////////////////// //
			echo '<div class="wrap">';
            echo '	<p><h2>'.__('Using noshop', 'noshop').'</h2></p>';
            echo '	<p>Create a Page or Post for your "Shopping Cart".</p>';
            echo '	<p>Add the tag [NoShop &lt;category&gt;] to the page or post to show the list of items in that category.</p>';
            echo '	<p>The currently selected product will show up if you use the tag <b><i>[NoShop '.$mycurrentprod[0]->category.']</i></b> :-)</p>';
            echo '	<p>Adding several tags after each other, like [NoShop Boats] followed by [NoShop MonoCycles] will show the two lists after each other.</p>';
            echo '</div>';

            echo '<div class="wrap">';
            echo '	<p><h2>'.__('Help!', 'noshop').'</h2></p>';
            echo '	<p>If you find errors, have suggestions or simple need more help, feel free to visit the plugin support page at ';
            echo '	<a href="http://wordpress.org/support/plugin/noshop">http://wordpress.org/support/plugin/noshop.</a>';
            echo '</div>';

            
            echo '<hr />';
           

            
            echo '<hr />';

            // ////////////////////////////////////////////////////////// //
            // Options Form
            // ////////////////////////////////////////////////////////// //

            echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=modellen">';
            echo '<div class="wrap"><p><h2>'.__('Plugin Options', 'noshop').'</h2></p>';
            // settings
            echo '<table class="form-table">';
            // width
            echo '<tr valign="top"><th scope="row">'.__('Product Display Width', 'noshop').'</th>';
            echo '<td><input type="text" name="width" value="'.$options['width'].'" size="10"></input>&nbsp;'.__('Maximum width of pictures in pixels', 'noshop').'</td></tr>';
            // wptouchwidth
            echo '<tr valign="top"><th scope="row">'.__('Product Display Width in WPtouch', 'noshop').'</th>';
            echo '<td><input type="text" name="wptouchwidth" value="'.$options['wptouchwidth'].'" size="10"></input>&nbsp;'.__('Maximum width of pictures in WPtouch mode', 'noshop').'</td></tr>';
            // default picture
            echo '<tr valign="top"><th scope="row">'.__('Default Image URL', 'noshop').'</th>';
            echo '<td><input type="text" name="defimg" value="'.$options['defimg'].'" size="64"></input>&nbsp;'.__('Default image to show if product has no image.', 'noshop').'</td></tr>';
            // spectitlewidth
            echo '<tr valign="top"><th scope="row">'.__('Width of value headers', 'noshop').'</th>';
            echo '<td><input type="text" name="spectitlewidth" value="'.$options['spectitlewidth'].'" size="10"></input>&nbsp;'.__('Maximum width of value headers under product description', 'noshop').'</td></tr>';
            // customcss
            echo '<tr valign="top"><th scope="row">'.__('Custom CSS', 'noshop').'</th>';
            echo '<td><input type="text" name="noshopcss" value="'.$options['noshopcss'].'" size="64"></input>&nbsp;'.__('Path to custom CSS file, blank results in using default noshop.css', 'noshop').'</td></tr>';
            // errors
            echo '<tr valign="top"><th scope="row">'.__('Show DB Errors', 'noshop').'</th>';
            if (isset($options['visibleerrors']) && !empty($options['visibleerrors'])) {
                $checked = " checked=\"checked\"";
            } else {
                $checked = "";
            }
            echo '<td><input type="checkbox" name="visibleerrors" value="on"'.$checked.' />&nbsp;'.__('Attempt to show all database related errors.', 'noshop').'</td></tr>';
            echo '</table>';

            // SUBMIT
            echo '<input type="hidden" name="noshop_submit" value="options"></input>';
            echo '<p class="submit"><input type="submit" value="'.__('Update Options &raquo;', 'noshop').'"></input></p>';
            echo "</div>";
            echo '</form>';

            echo '<hr />';

            echo '

				<p>Please consider a donation, it\'ll all go to food, warmth and toys for my children.</p>

                <!-- I am married to a Princess :-) Her name is Kianna Angelo, and I love her endlessly. -->
                
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="D9VGL9C245FRQ">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
				
				';

        } // End OptionPage()

        public static function PluginMenu() {
            add_menu_page('NoShop Plugin Options', 'Modellen!', 'manage_options', 'modellen', 'NoShop::OptionPage');
        }
	

    } //End Class NoShop

} // End if (!class_exists("NoShop"))


// ////////////////////////////////////////////////////////// //
// Kick-off: Load instance if class created correctly
// ////////////////////////////////////////////////////////// //

if (class_exists("NoShop")) {
    $noshop_plugin = new NoShop();
}

?>