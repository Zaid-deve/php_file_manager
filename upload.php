<?php






include "php/user.php";
$user = new User();
if (!$user->isUserLogedIn()) header("Location: auth/login.php?r=upload");
$uid = $user->getUserId();


$main_path = 'C:\xampp\htdocs\filemanager\files/';
$user_path = "user-{$uid}";

$paths = '';
function getPaths($dir)
{
    global $paths,$user_path;
    $files = scandir($dir);
    array_splice($files, 0,2);

    foreach($files as $file){
        $path = $dir.'/'.$file;
        $rel_path = substr($path, strpos($path,$user_path)+strlen($user_path)+1);

        if(is_dir($path)) {
            $paths .= "<option value='$rel_path'>$rel_path</option>";
            getPaths($path);
        }
    }
}
getPaths($main_path . $user_path);


$uploadErr = "";
$failedUploads = [];

if (isset($_POST['submit'])) {


    $files = $_FILES['upload'];
    $dir = "";
    if (isset($_POST['upload-path'])) {
        $dir = '/' . htmlentities($_POST['upload-path']);
    }
    $fullpath = $main_path . $user_path . $dir;

    if (file_exists($fullpath)) {
        foreach ($files['name'] as $val => $name) {
            $err = $files['error'][$val];
            $tmp_name = $files['tmp_name'][$val];
            $size = $files['size'][$val];

            if ($size <= 2097152) {
                if (!move_uploaded_file($tmp_name, $fullpath . '/' . $name)) $failedUploads[] = $name;
            } else {
                $failedUploads[] = $name;
                $uploadErr .= "$name cannot be uploaded due max size of 2 mb exeeded !<br><br>";
            }
        }

        if (count($failedUploads) == 0) header("Location: ./");
    } else $uploadErr = "directory does not exists !";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>upload</title>

    <!-- remixicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css">

    <!-- stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/upload.css">
</head>

<body>

    <!-- HEADER -->
    <?php include "includes/header.php" ?>
    <div class="upload-outer">
        <form action="#" method="POST" enctype="multipart/form-data" class="upload-box">
            <div class="upload-header">
                <h3>Upload File</h3>
                <button class="btn btn-info"><i class="ri-information-line"></i></button>
            </div>
            <div class="upload-path-sel">
                <label for="upload-path-inp">Upload Path</label>
                <select name="upload-path" id="upload-path-inp">
                    <?php echo $paths; ?>
                </select>
            </div>

            <?php

            if (!empty($uploadErr)) {
                echo "<div class='upload-err show'>
                         <p class='upload-err-text'>
                             $uploadErr
                         </p>
                     </div>";
            }

            ?>

            <label for="file">
                <div class="upload-file-select">
                    <i class="ri-upload-cloud-fill"></i>
                    <h1>Uplaod File...</h1>
                    <span>file size can not be greater than 2 mb</span>
                </div>
                <input type="file" id="file" name="upload[]" multiple hidden>
            </label>

            <button class="btn btn-upload" type="submit" name="submit">
                upload <i class="ri-arrow-right-line"></i>
            </button>
        </form>
    </div>

    <script src="js/upload.js"></script>

</body>

</html>