<?php

/**

 * Created by PhpStorm.

 * User: FOX

 * Date: 4/1/2016

 * Time: 2:56 PM

 */



// No direct access

if ( ! defined( 'ABSPATH' ) ) exit;



function akd_revslider_export($file)
{
   /* if class RevSlider does not exists. */
    if (!class_exists('RevSlider'))
        return;
    if (!is_dir($file . 'revslider/'))
        wp_mkdir_p($file . 'revslider/');

    $slider = new RevSlider();
    $arrSliders = $slider->getArrSliders();
    if ($arrSliders) {
        $obj = new RevSliderSliderExport();
        foreach ($arrSliders as $slider) {
            $id = $slider->getID();
            $obj->init_by_id($id);
            $obj->set_parameters();
            $obj->remove_image_ids();
            $obj->remove_background_image();
            $obj->add_used_images();
            $obj->add_used_videos();
            //$obj->add_used_captions();
            //$obj->add_used_animations();
            $obj->add_used_navigations();
            $obj->add_used_svg();
            $obj->modify_used_data();
            $obj->serialize_export_data();
            $obj->serialize_navigation_data();
            $obj->prepare_caption_css();
            $obj->serialize_animation_data();
            $obj->create_export_zip();
            $obj->add_svg_to_zip();
            $obj->add_images_videos_to_zip();
            $obj->add_slider_export_to_zip();
            $obj->add_animations_to_zip();
            $obj->add_styles_to_zip();
            $obj->add_navigation_to_zip();
            $obj->add_static_styles_to_zip();
            $obj->add_info_to_zip();
            $obj->close_export_zip();
            $uplocaDIR = wp_upload_dir();
            $uplocaDIRpath = $uplocaDIR['basedir'];
            $exportname = (!empty($obj->slider_alias)) ? $obj->slider_alias . '.zip' : 'slider_export.zip';
            rename($uplocaDIRpath . '/export.zip', $file . 'revslider/' . $exportname);
        }
    }

}
