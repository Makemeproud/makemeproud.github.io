<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 8/13/2016
 * Time: 10:29 AM
 */
function akd_git_shell(){

    if(!akd_git_exists()) return;

    $log = shell_exec('cd '.get_template_directory().' && git reset --hard origin/master 2>&1; git pull 2>&1; git add --all 2>&1; git commit -m Demo 2>&1; git push 2>&1');
    echo $log;
}

function akd_git_exists(){
    $git = get_template_directory() . '/.git';

    if(!is_dir($git)) return false;

    return true;
}