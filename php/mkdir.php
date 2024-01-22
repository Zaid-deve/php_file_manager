
<?php 

if(isset($_GET['path'])){
    $dir = htmlentities($_GET['path']);
    require "user.php";
    $user = new User();
    if(!$user->isUserLogedIn()) die("please login to continue !");
    

    $uid = $user->getUserId();
    $main_path = 'C:\xampp\htdocs\filemanager\files/';
    $user_path = "user-{$uid}/";
    $fullpath = $main_path . $user_path . $dir;
    
    if(file_exists($fullpath)) echo "Folder Already Exists !";
    else {
        if(mkdir($fullpath, 0777)) echo 'success';
        else echo 'Something Went Wrong !';
    }


}

?>