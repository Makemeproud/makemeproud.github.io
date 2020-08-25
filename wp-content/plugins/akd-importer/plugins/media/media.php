<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/5/2016
 * Time: 4:06 AM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function akd_media_export($folder_dir){
    global $wp_filesystem;

    $upload_dir = wp_upload_dir();

    $media_backup = $upload_dir['basedir'] . '/attachment-tmp/';

    $query = array(
        'post_type'         => 'attachment',
        'posts_per_page'    => -1,
        'post_status'       => 'inherit',
    );

    $media = new WP_Query($query);

    if(!$media->have_posts())
        return 0;

    while ($media->have_posts()) : $media->the_post();

        /* get file dir */
        $attached_file = (get_attached_file(get_the_ID()));

        if(!file_exists($attached_file))
            continue;

        /* get file name. */
        $attached_name = basename($attached_file);

        /* get file dir */
        $attached_dir = dirname($attached_file);

        /* get date folder. */
        $folder_date = str_replace($upload_dir['basedir'], '', $attached_dir);

        if(strpos($folder_date, 'revslider'))
            continue;

        /* new file. */
        $new_file = $media_backup . $folder_date . '/' . $attached_name;

        /* create date folder. */
        if(!is_dir($media_backup . $folder_date))
            wp_mkdir_p($media_backup . $folder_date);

        copy($attached_file, $new_file);

    endwhile;

    /* zip */
    if(!class_exists('ZipArchive'))
        return false;

    $zip = new ZipArchive;
    $zip->open($upload_dir['basedir'] . '/attachment-tmp.zip', ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

    folderToZip($media_backup, $zip);

    $zip->close();

    /* media */
    if(!is_dir($folder_dir . 'content')) wp_mkdir_p($folder_dir . 'content');
    $attachment = akd_export_wp(array('content' => 'attachment'));
    $wp_filesystem->put_contents($folder_dir . 'content/attachment-data.xml', $attachment, FS_CHMOD_FILE);

    return $upload_dir['basedir'] . '/attachment-tmp.zip';
}

function akd_media_import($options){
    global $wp_filesystem;

    if(empty($options['attachment']))
        return 'Media file not found!';

    $upload_dir = wp_upload_dir();

    /* download & unzip. */
    $_cache = trailingslashit($upload_dir['basedir'] .'/akd_demo');

    if(!is_dir($_cache))
        wp_mkdir_p($_cache);

    wp_safe_remote_get( $options['attachment'], array( 'timeout' => 300, 'stream' => true, 'filename' => $_cache . 'attachment-tmp.zip' ) );

    unzip_file($_cache . 'attachment-tmp.zip', $upload_dir['basedir']);

    @unlink($_cache . 'attachment-tmp.zip');

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

    ob_start();
    /* import files. */
    $wp_import->import($options['folder'] . 'content/attachment-data.xml', null);

    return ob_get_clean();

}