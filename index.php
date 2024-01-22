<?php

include "db/db_config.php";
include "db/db_conn.php";
include "php/user.php";

$user = new User();
$logedIn  = $user->isUserLogedIn();
$filesCount = 0;
$filesTree = "";

function getFilesTree($dir)
{
    $files = scandir($dir);
    global $filesCount, $filesTree, $user;
    if ($filesCount == 0) $filesCount = count($files);

    foreach ($files as $file) {
        $path = "$dir/$file";
        $uid = $user->getUserId();

        if ($file != '.' && $file != '..') {
            $rel_path = substr($path, strpos($path, "user-{$uid}") + strlen("user-{$uid}") + 1);

            if (is_dir($path)) {
                $fileIcon = "663341.png";
                $filesTree .=
                    "
                <li>
                   <div class='file-wrapper' onclick='viewFileContents(this)' oncontextmenu='showFileMenu(this,event)' data-path='{$rel_path}'>
                      <img src='images/$fileIcon' alt='📁'>
                      <span data-path='{$rel_path}'>$file</span>
                      <button class='btn btn-toggle-file-wrapper' onclick='toggleFileTree(event,this)'><i class='ri-arrow-down-s-line'></i></button>

                      <div class='file-wrapper-menu' tabindex='-1'>
                         <div class='file-wrapper-menu-opt' onclick='rename_con(this,event)'>
                             <i class='ri-edit-box-fill'></i>
                             <span>Rename</span>
                         </div>
                         <div class='file-wrapper-menu-opt' onclick='del_file(this, event)'>
                             <i class='ri-delete-bin-3-line'></i>
                             <span>Delete Folder</span>
                         </div>
                      </div>
                   </div>

                   <ul style='margin-left:12px;' class='hide'>";
                getFilesTree($path);

                $filesTree .= "</ul>
                </li>
                ";
            } else {
                $fileIcon = 'images-removebg-preview.png';
                $pg_formats = ['html', 'css', 'js', 'php', 'python', 'java', 'c', 'c++', 'c#'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                if (in_array($ext, ['png', 'jpeg', 'jpg', 'webp', 'gif'])) {
                    $fileIcon = "4503941.png";
                }
                // file is prigramming file
                else if (in_array($ext, $pg_formats)) {
                    $fileIcon = "1090982.png";
                }

                $filesTree .=
                    "
                        <li>
                           <div class='file-wrapper' onclick='viewFile(this)' oncontextmenu='showFileMenu(this,event)' data-path='{$rel_path}'>
                              <img src='images/$fileIcon' alt='📄'>
                              <span data-path='{$rel_path}'>$file</span>

                              <div class='file-wrapper-menu' tabindex='-1'>
                                <div class='file-wrapper-menu-opt' onclick='rename_con(this,event)'>
                                    <i class='ri-edit-box-fill'></i>
                                    <span>Rename</span>
                                </div>
                                <div class='file-wrapper-menu-opt' onclick='del_file(this, event)'>
                                    <i class='ri-delete-bin-3-line'></i>
                                    <span>Delete File</span>
                                </div>
                              </div>
                           </div>
                        </li>
                    ";
            }
        }
    }
}

if ($logedIn) {
    $uid = $user->getUserId();
    getFilesTree('C:\xampp\htdocs\filemanager\files/' . "user-{$uid}");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- remixicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css">

    <!-- stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>

<body>

    <!-- header -->
    <?php include "includes/header.php" ?>

    <div class="del-acc-outer">
        <div class="del-acc-box">
            <div class="del-acc-header">
                <h3>Delete Account</h3>
            </div>

            <div class="del-acc-content">
                <img src="images/4677911.webp" alt="#">
                <h3>We Are Sad To See Yo Going !</h3>
                <p>this will delete your account and all files associate with us, you cannot retain your account !</p>
                <div class="del-acc-btns">
                    <a class="btn btn-del-acc" href="auth/del_user.php">delete</a>
                    <button class="btn btn-cancel" onclick="document.querySelector('.del-acc-outer').classList.remove('show')">cancel</button>
                </div>
            </div>
        </div>
    </div>

    <main>
        <div class="container main-container">
            <?php if ($logedIn) {
                if (isset($_GET['path'])) {
                    $addClass = 'hide';
                }
            ?>
                <div class="sidebar <?php echo $addClass ?>">
                    <div class="sidebar-header">
                        <h3>My Files</h3>
                        <button class="btn btn-create-file"><i class="ri-file-add-line"></i></button>
                        <button class="btn btn-create-folder"><i class="ri-folder-add-line"></i></button>

                        <div class="add-con add-dir-con">
                            <i class="ri-folder-3-line"></i>
                            <input type="text" class="inp" id="dirname" placeholder="directory name">
                        </div>

                        <div class="add-con add-file-con">
                            <i class="ri-file-add-line"></i>
                            <input type="text" class="inp" id="filename" placeholder="file name">
                        </div>
                    </div>

                    <ul class="sidebar-files">
                        <?php echo $filesTree; ?>
                    </ul>
                </div>
                <?php if ($filesCount > 0) { ?>
                    <div class="file-view">

                        <div class='file-search'>
                            <i class='ri-search-2-line'></i>
                            <input type='text' class='inp' id='_file_search' oninput="searchFile(this)" placeholder='Search Files...'>
                        </div>
                        <div class='file-view-list'>

                            <?php

                            $loadDefPath = true;
                            require "php/get-files.php";

                            ?>

                        </div>

                    </div>
                <?php } else { ?>
                    <div class="no-files-con">
                        <img src="images/118-1189137_folder-png-open-yellow-folder-icon-png-transparent-removebg-preview.png" alt="#">
                        <h3>No Files Yet !</h3>
                        <p>start uploading files securely (upto 1gb)</p>
                        <a href="upload.php" class="btn btn-upload">upload files !</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="login-info">
                    <img src="images/userlog.png" alt="#">
                    <h3>Hey, Login And Start Uploading <br> Your Files Securely</h3>
                    <a href="auth/login.php" class="btn btn-login">Login</a>
                </div>
            <?php } ?>
        </div>
    </main>

    <script src="js/view-file.js"></script>
    <script src="js/create-file.js"></script>
    <script src="js/serach.js"></script>

</body>

</html>