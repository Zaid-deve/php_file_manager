<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents("php://input");
    $dec_data = json_decode($data, true);

    if (!empty($dec_data)) {
        include "../php/user.php";
        $user = new User();
        $uid = $user->getUserId();


        $allfiles = $dec_data['files'];
        $root = 'C:\xampp\htdocs\filemanager/files/';
        $user_path = "user-{$uid}";
        $main = $root . $user_path;

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


        foreach ($allfiles as $file) {
            if (str_starts_with($file,'/')) {
                $fullpath = $main . $file;
            } else $fullpath = $main . '/' . $file;
            if (file_exists($fullpath))rmfiles($fullpath);
        }

        echo "success";
    }
}
