<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/4/2016
 * Time: 2:44 PM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_revslider_import($folder)
{
    $log = array();

    /* if class RevSlider does not exists. */
    if (!class_exists('RevSlider'))
        return;

    $folder = trailingslashit($folder . 'revslider');

    if(!is_dir($folder))
        return;

    $slider = new RevSlider();

    $files = scandir($folder);

    $files = array_diff($files, array('..', '.'));

    foreach ($files as $_f){

        $_FILES["import_file"]["tmp_name"] = $folder . $_f;
        $_FILES['import_file']['error'] = '';

        ob_start();

        $slider->importSliderFromPost(true, true);

        $log[] = ob_get_clean();
    }

    return $log;
}