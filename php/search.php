<?php


if (isset($_GET['path']) && isset($_GET['qry'])) {
    $qry = htmlentities($_GET['qry']);
    $path = htmlentities($_GET['path']);
    if (!empty($path)) $path = '/' . $path;

    if (empty($qry)) die();

    // $user
    include "user.php";
    $user = new User();
    if (!$user->isUserLogedIn()) die('please login to continue !');
    $uid = $user->getUserId();


    // search
    $main_path = 'C:\xampp\htdocs\filemanager\files/' . "user-{$uid}";
    $fullpath = $main_path . $path;
    if (file_exists($fullpath)) {
        $files = scandir($fullpath);
        $output = "";
        array_splice($files, 0, 2);

        // return matched files
        foreach ($files as $file) {
            if (!preg_match("/$qry/", $file)) {
                continue;
            }


            $path = "$fullpath/$file";
            $pathinfo = pathinfo($path);
            $name = $pathinfo['basename'];
            $filescount_span = "";

            if (is_dir($path)) {
                $fileicon = "663341.png";
                $callback = "viewFileContents()";
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
                $callback = "viewFile()";


                $fileicon = "images-removebg-preview.png";
                if (in_array($ext, $img_formats)) {
                    $fileicon = "4503941.png";
                }

                // file is prigramming file
                else if (in_array($ext, $pg_formats)) {
                    $fileicon = "1090982.png";
                }
            }

            $output .= "<div class='file-item' onclick='$callback' data-path='$path'>
                            <div class='file-icon'>
                                <img src='images/$fileicon' alt='#'>
                            </div>
                            <h3>$name</h3>
                            $filescount_span
                            <input type='checkbox' id='wrapper-1'>
                        </div>";
        }

        if(empty($output)) $output = "no files found !";
        echo $output;
    }
}
