<?php


class User
{

    function __construct()
    {
        if (empty(session_id())) session_start();
    }

    function isUserLogedIn()
    {
        if (!empty($_SESSION['user_id'])) return true;
    }

    function setUserId($uid)
    {
        $_SESSION['user_id'] = $uid;
    }

    function getUserId()
    {
        if ($this->isUserLogedIn()) {
            return $_SESSION['user_id'];
        }
    }

    function addNewUserDir($uid)
    {
        $path = 'C:\xampp\htdocs\filemanager\files/';
        $dir = "user-{$uid}";

        if (!file_exists($dir)) {
            mkdir($path . $dir);
        }
    }


    function addUser($conn, $name, $email, $pass): bool
    {
        if (empty($email) or empty($pass)) return false;
        $enc_name = base64_encode($name);
        $enc_email = base64_encode($email);
        $enc_pass = base64_encode($pass);

        $qry = mysqli_query($conn, "INSERT INTO users (user_name,email, pass) VALUES ('{$enc_name}','{$enc_email}', '{$enc_pass}')");
        if ($qry) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $this->addNewUserDir(mysqli_insert_id($conn));
        }

        return $qry;
    }

    function getUser($conn, $email, $pass)
    {
        if (empty($email) or empty($pass)) return;
        $enc_email = base64_encode($email);
        $enc_pass = base64_encode($pass);

        $qry = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '{$enc_email}' && pass = '{$enc_pass}'");
        if ($qry && mysqli_num_rows($qry) > 0) {
            $data = mysqli_fetch_assoc($qry);
            $this->addNewUserDir($data['user_id']);
            return $data;
        }

        return "NO_USER_FOUND";
    }

    function getUserFiles($conn, $uid)
    {
        $qry = mysqli_query($conn, "SELECT * FROM uploads WHERE up_uid = '$uid'");
        if ($qry) {
            return mysqli_fetch_assoc($qry);
        }
        return $qry;
    }
}
