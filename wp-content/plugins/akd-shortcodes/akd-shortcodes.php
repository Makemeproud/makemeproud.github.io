<?php
/*
Plugin name: AKD Shortcodes
Plugin URI: http://designingmedia.com/
Description: A simple plugin to add custom codes.
Author: AKD
Author URI: http://designingmedia.com
Version: 2.5
Text Domain: AKD
Domain Path: /languages
*/
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die( 'You shouldnt be here' );
}
 
/**
* Function when plugin is activated
*
* @param void
*
* @return void
*//*
function vcas_plugin_active(){
    // checking if visual composer is active
    if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
        wp_die( 'Please activate Visual Composer, and try again' );
    }
}
register_activation_hook( __FILE__ , 'vcas_plugin_active' );*/
 
//Including file that manages all template
require_once plugin_dir_path( __FILE__ ) . '/inc/vc_map.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/shortcode.php';

//shortcodes for layout 07

