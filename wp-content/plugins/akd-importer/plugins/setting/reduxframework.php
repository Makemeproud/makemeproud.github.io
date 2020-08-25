<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/1/2016
 * Time: 10:54 AM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_setting_export($file){
    global $wp_filesystem;

    $option_name = akd_setting_get_opt_name($file);

    $file_contents = get_option($option_name);

    if(!$file_contents)
        return;

    $file_contents = json_encode($file_contents);

    $wp_filesystem->put_contents($file, $file_contents, FS_CHMOD_FILE); // Save it
}

function akd_setting_import($file){
    // File exists?
    if (file_exists($file)){
        // Get file contents and decode
        $data = file_get_contents($file);

        $data = json_decode($data, true);

        $data = akd_replace_theme_options($data);

        $option_name = akd_setting_get_opt_name($file);

        update_option($option_name, $data);
    }
}

function akd_setting_get_opt_name($file){

    $opt_name = apply_filters('akd-theme-options-opt-name', 'opt_theme_options');

    if(file_exists($file))
        return $opt_name;

    $options = file_get_contents($file);

    $options = json_decode($options, true);

    return !empty($options['opt-name']) ? $options['opt-name'] : $opt_name ;
}

function akd_replace_theme_options($options){

    $_replaces = apply_filters('akd-replace-theme-options', array());

    foreach ($_replaces as $pattern => $_replace){
        if(isset($options[$pattern])){
            $options[$pattern] = $_replace;
        }
    }

    return $options;
}