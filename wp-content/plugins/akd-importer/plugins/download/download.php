<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/4/2016
 * Time: 8:38 AM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_download_demo_zip(){

    $_cache = trailingslashit(ABSPATH . 'wp-content/uploads/akd_demo');

    if(!is_dir($_cache))
        wp_mkdir_p($_cache);

    if(!class_exists('ZipArchive'))
        exit();

    $zip = new ZipArchive;
    $zip->open($_cache . 'demo-data.zip', ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

    folderToZip(akd_importer()->theme_dir, $zip);

    $zip->close();

    return $_cache . 'demo-data.zip';
}

function folderToZip($folder, $zipFile, $sub = '', $remove = array()) {

    if ($zipFile == null) {
        // no resource given, exit
        return false;
    }
    // we start by going through all files in $folder
    $f = scandir($folder);

    $f = array_diff($f, array('..', '.'));

    $sub = !empty($sub) ? $sub . '/' : '' ;

    foreach ($f as $_f){

        if(in_array($_f, $remove)) continue;

        if(is_dir($folder . $_f)){

            $__f = trailingslashit($folder . $_f);

            $zipFile->addEmptyDir($sub . $_f);

            folderToZip($__f, $zipFile, $sub . $_f);

        } elseif (is_file($folder . $_f)){
            $zipFile->addFile($folder . $_f, $sub . $_f);
        }
    }
}