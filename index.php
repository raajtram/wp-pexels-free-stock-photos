<?php
/*
	Plugin Name: Pexels: Free Stock Photos
	Plugin URI: https://raajtram.com/plugins/pexels
	Description: Pexels provides high quality and completely free stock images for personal and commercial use. This plugin helps you search, browse and download those photos directly to your WordPress site, giving you the benefits of hosting them (cropping, compressing, caching etc.).
	Version: 1.2.2
	Author: Raaj Trambadia
	Author URI: https://raajtram.com
	License: GPLv2
	Text Domain: pexels_fsp_images
	Domain Path: /languages
*/

/* Add Plugin Settings */
include(plugin_dir_path(__FILE__) . 'settings.php');

/* Add the "Settings" Link to the /plugins Page */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pexels_fsp_action_links' );

function pexels_fsp_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'upload.php?page=pexels_fsp_images_settings') ) .'">Get Photos</a>';
   return $links;
}

/* Load plugin textdomain */
function plugin_load_textdomain() {
   load_plugin_textdomain( 'pexels_fsp_images', false, basename( dirname( __FILE__ ) ) . '/languages/' ); 
} 

add_action( 'init', 'plugin_load_textdomain' );

?>
