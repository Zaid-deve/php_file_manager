<?php


if (isset($_GET['path'])) {
    $file = htmlentities($_GET['path']);
    include "user.php";
    $user = new User();




    if (!$user->isUserLogedIn()) die('please login to continur');

    $uid = $user->getUserId();
    $user_path = "user-$uid/";
    $main_path = 'C:\xampp\htdocs\filemanager\files/';
    $fullpath = $main_path . $user_path . $file;
    if (file_exists($fullpath)) {
        echo 'File Already Exists !';
    }
    else {
        $p = fopen($fullpath, 'w');
        fclose($p);
        if($p) echo 'success';
        else echo 'Failed to Create File';
    }
}
