<?php

require "php/user.php";
$user = new User();
if (!$user->isUserLogedIn($user)) header("Location: login.php");
$uid = $user->getUserId();

// paths 
if (!isset($_GET['path'])) {
    header("Location:./") or die();
}

$path = htmlentities($_GET['path']);
$main_path = 'C:\xampp\htdocs\filemanager\files/';
$user_path = "user-$uid/";
$fullpath = $main_path . $user_path . $path;

if (!file_exists($fullpath)) echo 'file does not exists or deleted !';
else {
    if (is_file($fullpath)) {

        $file_info = pathinfo($fullpath);
        $ext = $file_info['extension'];
        $name = $file_info['filename'];
        $basename = $file_info['basename'];

        // preview file

        $type = "text/plain";
        if ($ext === 'pdf') {
            header("Content-Disposition: inline;filename=$basename");
            header("Accept-Range:bytes");
            
            $type = 'application/pdf';
        } else if ($ext === 'mp4') {
            $type = 'video/mp4';
        } else if ($ext === 'mp3' || $ext === 'wav') {
            $type = 'audio/' . $ext;
        } else if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'avif'])) {
            $type = 'image/' . $ext;
        }

        header("Content-Disposition: inline;filename=$basename");

        header("Content-Type:$type");
        echo file_get_contents($fullpath);
    } else echo 'something went wrong !';
}

?>