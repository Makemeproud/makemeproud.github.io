<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/1/2016
 * Time: 10:54 AM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_ctp_ui_export($file){
    global $wp_filesystem;

    if(function_exists('cptui_get_post_type_data')) {
        $cptui_post_types = cptui_get_post_type_data();
        $wp_filesystem->put_contents($file . 'post-type.json', json_encode($cptui_post_types), FS_CHMOD_FILE);
    }

    if(function_exists('cptui_get_taxonomy_data')) {
        $cptui_taxonomies = cptui_get_taxonomy_data();
        $wp_filesystem->put_contents($file . 'taxonomies.json', json_encode($cptui_taxonomies), FS_CHMOD_FILE);
    }
}

/**
 * @param $file
 */
function akd_ctp_ui_import($file){

    if(!function_exists('cptui_import_types_taxes_settings'))
        return;

    // File exists?
    if (file_exists($file . 'post-type.json')){
        // Get file contents and decode
        $data = file_get_contents($file . 'post-type.json');

        cptui_import_types_taxes_settings(array('cptui_post_import' => $data, 'cptui_tax_import' => '' ));
    }

    // File exists?
    if (file_exists($file . 'taxonomies.json')){
        // Get file contents and decode
        $data = file_get_contents($file . 'taxonomies.json');

        cptui_import_types_taxes_settings(array('cptui_tax_import' => $data, 'cptui_post_import' => '' ));
    }
}