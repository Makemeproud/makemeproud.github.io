<?php
/**
 * Plugin Name: AKD-Framework
 * Plugin URI: http://http://akdesigner.com/
 * Description: AKD-Framework add Redux Framewor (theme option).
 * Version: 2.0.4
 * Author: AKD team
 * License: GPLv2 or later
 * Text Domain: AKD-Framework
 */
if (!defined('ABSPATH')) {
    exit();
}

	
//setup_globals
$file = __FILE__;

/* base name. */
$basename = plugin_basename($file);

/* base plugin. */
$plugin_dir = plugin_dir_path($file);
$plugin_url = plugin_dir_url($file);

/* base assets. */
$acess_dir = trailingslashit($plugin_dir . 'assets');
$acess_url = trailingslashit($plugin_url . 'assets');
//EOF //setup_globals
		
register_activation_hook(__FILE__, 'AKDFrameworktlm_activate'); 

  function AKDFrameworktlm_activate() {
            
    add_option('ep_do_activation_redirect', true); //add option for redirection
 }
  function redirect_dashboard() { //dashboard redirection function
    if (get_option('ep_do_activation_redirect', false)) {
        delete_option('ep_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
         {
			/*echo "is_plugin_active<pre>".(is_plugin_active( 'AKD-Framework/AKD-Framework.php' ))."</pre>";*/
            (wp_redirect(admin_url('admin.php?page=Hostiko'))); //redirection
       }
	}
}
add_action('admin_init', 'redirect_dashboard');

/* add WP_Filesystem. */
if ( !class_exists('WP_Filesystem') ) {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	WP_Filesystem();
}

/* inc redux framework */
require_once $plugin_dir . 'frameworks/ReduxCore/framework.php';


	
