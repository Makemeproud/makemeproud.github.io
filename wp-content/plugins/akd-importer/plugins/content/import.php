<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/4/2016
 * Time: 3:17 PM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_content_import($options)
{
    $folder = trailingslashit($options['folder'] . 'content');

    /* folder not exists. */
    if(!is_dir($folder))
        return;

    // Load Importer API
    require_once ABSPATH . 'wp-admin/includes/import.php';

    if ( ! class_exists( 'WP_Importer' ) )
        require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';

    // include WXR file parsers
    require akd_importer()->plugin_dir . 'plugins/content/parsers.php';

    /* class WP_Import not exists */
    if(!class_exists('WP_Import'))
        require_once akd_importer()->plugin_dir . 'plugins/content/wordpress-importer.php';

    $wp_import = new WP_Import();

    /* add image placeholder */
    $attachment = empty($options['attachment']) ? akd_add_placeholder_image() : null;

    /* import files. */
    ob_start();

    $wp_import->import($folder . 'content-data.xml', $attachment);

    return ob_get_clean();
}

function akd_add_placeholder_image(){

    $attachment_exists = get_page_by_title(esc_html__('Image Placeholder', 'akd-importer'), OBJECT, 'attachment');

    if($attachment_exists)
        return $attachment_exists->ID ;

    $wp_upload_dir = wp_upload_dir();

    $_default_image = apply_filters('akd-placeholder-image', akd_importer()->acess_dir . 'akd-placeholder-image.jpg');

    copy($_default_image, $wp_upload_dir['path'] . '/akd-placeholder-image.jpg');

    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/akd-placeholder-image.jpg',
        'post_mime_type' => 'image/jpeg',
        'post_title'     => esc_html__('Image Placeholder', 'akd-importer'),
        'post_status'    => 'inherit'
    );

    $attachment_id = wp_insert_attachment($attachment, $wp_upload_dir['url'] . '/akd-placeholder-image.jpg');
    wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $wp_upload_dir['path'] . '/akd-placeholder-image.jpg' ) );

    return $attachment_id;
}

/**
 * replace content.
 *
 * @param $content
 * @param $attachment
 */
function akd_replace_content($content, $attachment){

    $_replaces = apply_filters('akd-replace-content', array(), $attachment);

    foreach ($_replaces as $pattern => $_replace){
        $content = preg_replace($pattern, $_replace, $content);
    }

    return $content;
}