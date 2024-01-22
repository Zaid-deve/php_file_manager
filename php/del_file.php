<?php


if (isset($_GET['path'])) {
    require "user.php";
    $user = new User();

    if (!$user->isUserLogedIn()) die('please login to continue !');
    $uid = $user->getUserId();
    $path = htmlentities($_GET['path']);

    $main_path = 'C:\xampp\htdocs\filemanager\files/';
    $user_path = "user-$uid/";
    $fullpath = $main_path . $user_path . $path;

    if (file_exists($fullpath)) {
        function rmfiles($dpath)
        {
            if (!is_dir($dpath)) return unlink($dpath);
            $files = array_diff(scandir($dpath), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $dpath . '/' . $file;

                if (is_dir($filePath)) {
                    rmfiles($filePath);
                } else {
                    unlink($filePath);
                }
            }

            return rmdir($dpath);
        }
        rmfiles($fullpath);


        echo "success";
    } else echo 'file/folder does not exists !';
}
