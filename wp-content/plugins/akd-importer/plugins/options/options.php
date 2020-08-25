<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/4/2016
 * Time: 4:21 PM
 */
// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;
function akd_options_import($options){
    foreach ($options as $key => $option){
        switch ($key){
            case 'home':
                akd_options_set_home_page($option);
                break;
            case 'menus':
                akd_options_set_menus($option);
                break;
			case 'wp-options':
				akd_options_set_wp_options($option);
				break;
			case 'vc_template':
				akd_options_set_wp_VCTemplate($option);
				break;
        }
    }
}
function akd_options_export($file){
    global $wp_filesystem;
    $upload_dir = wp_upload_dir();
    $options = array();
    /* default. */
    $options['home'] = akd_options_get_home_page();
    $options['menus'] = akd_options_get_menus();
    $options['opt-name'] = akd_setting_get_opt_name($file);
    $options['export'] = !empty($_POST['types']) ? $_POST['types'] : array() ;
    /* wp options */
    $options['wp-options'] = akd_options_get_wp_options(apply_filters('akd-options-wp-options', array()));
    /* attachment */
    if(file_exists($upload_dir['basedir'] . '/attachment-tmp.zip'))
        $options['attachment'] = $upload_dir['baseurl'] . '/attachment-tmp.zip';
	/* Export for VC templates*/
	$vcTemplates = get_option( 'wpb_js_templates' );
	if(!empty($vcTemplates)){
		foreach ( $vcTemplates as $key => $template ) {
			$export_data[ $key ] = $vcTemplates[ $key ];
		}
		$options['vc_template'] = json_encode( $vcTemplates );
	}
    $wp_filesystem->put_contents($file, json_encode($options), FS_CHMOD_FILE);
}
function akd_options_get_home_page(){
    $home_id = get_option('page_on_front');
    if(!$home_id)
        return null;
    $page = new WP_Query(array('post_type' => 'page', 'posts_per_page' => 1, 'page_id' => $home_id));
    if(!$page->post)
        return null;
    return $page->post->post_name;
}
function akd_options_get_menus(){
    $theme_locations = get_nav_menu_locations();
    if(empty($theme_locations))
        return null;
    foreach ($theme_locations as $key => $id){
        $menu_object = wp_get_nav_menu_object( $id );
        $theme_locations[$key] = $menu_object->slug;
    }
    return $theme_locations;
}
function akd_options_get_wp_options($options = array()){
    if(empty($options))
        return $options;
    $_options = array();
    foreach ($options as $key){
        $_options[$key] = get_option($key);
    }
    return $_options;
}
function akd_options_set_home_page($slug){
    $page = new WP_Query(array('post_type' => 'page', 'posts_per_page' => 1, 'name' => $slug));
    if(!$page->post)
        return null;
    update_option('show_on_front', 'page');
    update_option('page_on_front', $page->post->ID);
}
function akd_options_set_menus($menus){
    if(empty($menus))
        return;
    $new_setting = array();
    foreach ($menus as $key => $menu){
        $_menu = get_term_by('slug', $menu, 'nav_menu');
        $new_setting[$key] = $_menu->term_id;
    }
    set_theme_mod('nav_menu_locations', $new_setting);
}
function akd_options_set_wp_options($options = array()){
    if(empty($options))
        return;
    foreach ($options as $key => $value){
        update_option($key, $value);
    }
}function akd_options_set_wp_VCTemplate($options = array()){
    if(empty($options))
        return;
    $vc_option_name = 'wpb_js_templates';
	$contents = json_decode($options,true);
	$theme_mods = get_option( $vc_option_name ); //abc
	// Mergers new options and clean
	$import_templates = array();
	if ( is_array( $contents ) ) {
		foreach ( $contents as $key => $template ) {
			$new_id                      = uniqid( 'akd_' );
			$template['name']            = $template['name'] . ' (' . current_time( 'mysql' ) . ')'; //abc (sdsadas)
			$import_templates[ $new_id ] = $template;
		}
	}
	if ( $theme_mods ) {
		$theme_mods = array_merge( $theme_mods, $import_templates );
	} else {
		$theme_mods = $import_templates;
	}
	update_option( $vc_option_name, $theme_mods );
}
