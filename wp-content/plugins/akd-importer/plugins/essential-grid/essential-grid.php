<?php
    /**
     * Created by PhpStorm.
     * User: FOX
     * Date: 4/1/2016
     * Time: 10:54 AM
     */

// No direct access
    if (!defined('ABSPATH')) exit;

    function akd_grid_import($file)
    {
        if (class_exists('Essential_Grid_Import')) {
            if (file_exists($file . 'ess_grid.json')) {
                // Get file contents and decode
                $data = file_get_contents($file . 'ess_grid.json');

                $data = json_decode($data, true);

                $im = new Essential_Grid_Import();
                $im->set_overwrite_data($data); //set overwrite data global to class

                $skins = $data['skins'];
                if (!empty($skins) && is_array($skins)) {
                    $skins_ids = array();
                    foreach ($skins as $key => $skin) {
                        $skins[$key] = $skin;
                        $skins_ids[] = $skin['id'];
                    }

                    if (!empty($skins)) {
                        $im->import_skins($skins, $skins_ids);
                    }
                }

                $navigation_skins = @$data['navigation-skins'];
                if(!empty($navigation_skins) && is_array($navigation_skins)){
                    $navigation_skins_ids = array();
                    foreach($navigation_skins as $key => $navigation_skin){
                        $navigation_skins[$key] = $navigation_skin;
                        $navigation_skins_ids[] = $navigation_skin['id'];
                    }
                    if(!empty($navigation_skins)){
                        $im->import_navigation_skins(@$navigation_skins, $navigation_skins_ids);
                    }
                }

                $grids = @$data['grids'];
                if(!empty($grids) && is_array($grids)){
                    $grids_ids = array();
                    foreach($grids as $key => $grid){
                        $grids[$key] = $grid;
                        $grids_ids[] = $grid['id'];
                    }
                    if(!empty($grids)){
                        $im->import_grids($grids, $grids_ids);
                    }
                }

                $elements = @$data['elements'];
                if(!empty($elements) && is_array($elements)){
                    $elements_ids = array();
                    foreach($elements as $key => $element){
                       $elements[$key] = $element;
                       $elements_ids[] = $element['id'];
                    }
                    if(!empty($elements)){
                        $elements_ids = @$data['imports']['import-elements-id'];
                        $im->import_elements(@$elements, $elements_ids);
                    }
                }

                $custom_metas = @$data['custom-meta'];
                if(!empty($custom_metas) && is_array($custom_metas)){
                    $custom_metas_handle = array();
                    foreach($custom_metas as $key => $custom_meta){
                        $custom_metas[$key] = $custom_meta;
                        $custom_metas_handle[] = $custom_meta['handle'];
                    }
                    if(!empty($custom_metas)){
                        $im->import_custom_meta($custom_metas, $custom_metas_handle);
                    }
                }

                $custom_fonts = @$data['punch-fonts'];
                if(!empty($custom_fonts) && is_array($custom_fonts)){
                    $custom_fonts_handle = array();
                    foreach($custom_fonts as $key => $custom_font){
                        $custom_fonts[$key] = $custom_font;
                        $custom_fonts_handle[] =  $custom_font['handle'];
                    }
                    if(!empty($custom_fonts)){
                        $im->import_punch_fonts($custom_fonts, $custom_fonts_handle);
                    }
                }


                $global_css = @$data['imports']['global-css'];
                if(isset($global_css) && !empty($global_css))
                    $im->import_global_styles($global_css);

            }
        }

    }


    function akd_grid_export($file)
    {
        if (class_exists('Essential_Grid_Export')) {
            global $wp_filesystem;
            $ex = new Essential_Grid_Export();

            if (class_exists('Essential_Grid')) {
                $c_grids = new Essential_Grid();
                $grids = $c_grids->get_essential_grids();
                foreach ($grids as $grid) {
                    $grids_IDs[] = $grid->id;
                }
                $export['grids'] = $ex->export_grids($grids_IDs);
            }

            if (class_exists('Essential_Grid_Item_Skin')) {
                $skins_IDs = array();
                $item_skin = new Essential_Grid_Item_Skin();
                $skins = $item_skin->get_essential_item_skins();
                foreach ($skins as $skin) {
                    $skins_IDs[] = $skin['id'];
                }
                $export['skins'] = $ex->export_skins($skins_IDs);
            }

            if (class_exists('Essential_Grid_Item_Element')) {
                $elements_IDs = array();
                $item_ele = new Essential_Grid_Item_Element();
                $elements = $item_ele->get_essential_item_elements();
                foreach ($elements as $element) {
                    $elements_IDs[] = $element['id'];
                }
                $export['elements'] = $ex->export_elements($elements_IDs);
            }

            if (class_exists('Essential_Grid_Navigation')) {
                $navigation_skins_IDs = array();
                $nav_skin = new Essential_Grid_Navigation();
                $navigation_skins = $nav_skin->get_essential_navigation_skins();
                foreach ($navigation_skins as $skin) {
                    $navigation_skins_IDs[] = $skin['id'];
                }
                $export['navigation-skins'] = $ex->export_navigation_skins($navigation_skins_IDs);
            }

            if (class_exists('Essential_Grid_Meta')) {
                $metas_IDs = array();
                $metas = new Essential_Grid_Meta();
                $custom_metas = $metas->get_all_meta();
                foreach ($custom_metas as $meta) {
                    $metas_IDs[] = $meta['handle'];
                }
                $export['custom-meta'] = $ex->export_custom_meta($metas_IDs);
            }

            if (class_exists('')) {
                $fonts_IDs = array();
                $fonts = new ThemePunch_Fonts();
                $custom_fonts = $fonts->get_all_fonts();
                foreach ($custom_fonts as $font) {
                    $fonts_IDs[] = $font['handle'];
                }
                $export['punch-fonts'] = $ex->export_punch_fonts($fonts_IDs);
            }

            $export['global-css'] = $ex->export_global_styles('on');
            $wp_filesystem->put_contents($file . 'ess_grid.json', json_encode($export), FS_CHMOD_FILE);
        }
    }
