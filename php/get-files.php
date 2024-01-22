<?php


if (isset($_GET['path']) or isset($loadDefPath)) {
    $output = 'Something Went Wrong !';

    if (!isset($user)) {
        require "user.php";
        $user = new User();
    }
    if (!$user->isUserLogedIn()) die('please login to continue !');

    $uid = $user->getUserId();
    $main_path = 'C:\xampp\htdocs\filemanager/files/';
    $user_path = "user-$uid/";
    $fullpath = $main_path . $user_path;
    $fileid = "";

    if (!isset($loadDefPath)) {
        $fileid = htmlentities($_GET['path']);
        $fullpath .= $fileid;
    }

    if (file_exists($fullpath)) {
        $output = "";
        $files = scandir($fullpath);
        array_splice($files, 0, 2);

        if (count($files) > 0) {
            foreach ($files as $file) {

                $path = "$fullpath/$file";
                $npath = "$fileid/$file";

                $pathinfo = pathinfo($path);
                $name = $pathinfo['basename'];
                $filescount_span = "";

                if (is_dir($path)) {
                    $fileicon = "663341.png";
                    $callback = "viewFileContents(this)";
                    $dirfiles = scandir($path);
                    array_splice($dirfiles, 0, 2);
                    $filescount = count($dirfiles);

                    if ($filescount < 1) $filescount = 'empty';
                    else $filescount = "$filescount files";

                    $filescount_span = "<span>$filescount</span>";
                } else {
                    $ext = $pathinfo['extension'];
                    $img_formats = ['png', 'jpeg', 'jpeg', 'webp', 'gif'];
                    $pg_formats = ['html', 'css', 'js', 'php', 'python', 'java', 'c', 'c++', 'c#'];
                    $callback = "viewFile(this)";


                    $fileicon = "images-removebg-preview.png";
                    if (in_array($ext, $img_formats)) {
                        $fileicon = "4503941.png";
                    }

                    // file is prigramming file
                    else if (in_array($ext, $pg_formats)) {
                        $fileicon = "1090982.png";
                    }
                }

                $output .= "<div class='file-item' onclick='$callback' data-path='$npath'>
                                <div class='file-icon'>
                                    <img src='images/$fileicon' alt='#'>
                                </div>
                                <h3>$name</h3>
                                $filescount_span
                                <input type='checkbox' id='wrapper-1'>
                            </div>";
            }
        } else $output = "NO FILES IN THIS FOLDER";
    }

    echo $output;
}
