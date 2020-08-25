<?php

function akd_clear_tmp(){

    $upload_dir = wp_upload_dir();

    akd_delete_directory($upload_dir['basedir'] . '/attachment-tmp');
    akd_delete_directory($upload_dir['basedir'] . '/akd_demo');
}

function akd_delete_directory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir) || is_link($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!akd_delete_directory($dir . "/" . $item, false)) {
            chmod($dir . "/" . $item, 0777);
            if (!akd_delete_directory($dir . "/" . $item, false)) return false;
        };
    }
    return rmdir($dir);
}

function akd_remove_cache($folder){
	if (file_exists($folder)) {
		return rmdir($folder);
	}
}