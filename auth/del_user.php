<?php

require "../php/user.php";
require "../db/db_config.php";
$user = new User();
if (!$user->isUserLogedIn()) {
    die();
}
$uid = $user->getUserId();
$user_path = 'C:\xampp\htdocs\filemanager\files/user-' . $uid;

require "../db/db_conn.php";
$qry = mysqli_query($conn, "DELETE FROM users WHERE user_id = '$uid'");
if ($qry) {
    if (file_exists($user_path)) {
        
        function rm_user_dir($dir) {
            if (!is_dir($dir)) return unlink($dir);
            $files = array_diff(scandir($dir), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $dir . '/' . $file;

                if (is_dir($filePath)) {
                    rm_user_dir($filePath);
                } else {
                    unlink($filePath);
                }
            }

            return rmdir($dir);
        }
        rm_user_dir($user_path);
        
        
        header('Location: logout.php');
    }
}
echo 'Cannot Delete User !';