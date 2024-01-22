<?php 


$conn = mysqli_connect(host,uname,pass,db);
if(mysqli_connect_error()) {
    $dbErr = mysqli_connect_errno();
    die("something went wrong !");
}


?>