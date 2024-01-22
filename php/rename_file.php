<?php

if (isset($_GET['path']) && isset($_GET['fname'])) {
    require "user.php";
    $user = new User();

    if (!$user->isUserLogedIn()) die('please login to continue !');
    $uid = $user->getUserId();
    $path = htmlentities($_GET['path']);
    $fname = htmlentities($_GET['fname']);

    if (empty($path) or empty($fname)) {
        die('something went wrong');
    } else {
        $main_path = 'C:\xampp\htdocs\filemanager\files/';
        $user_path = "user-$uid/";
        $fullpath = $main_path . $user_path . $path;

        // new fname
        $newpath = explode('/', $fullpath);
        $newpath[count($newpath) - 1] = $fname;


        if (file_exists($fullpath)) {
            if (rename($fullpath, implode('/', $newpath))) {
                echo 'success';
            } else echo 'failed to rename file';
        } else echo 'file does not exists !';
    }
}
