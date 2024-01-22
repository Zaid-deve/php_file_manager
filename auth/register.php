<?php

include "../db/db_config.php";
include "../db/db_conn.php";
include "../php/user.php";

$user = new User();
if ($user->isUserLogedIn()) header("Location: ../") or die("");

$email_err = $name_err = $pass_err = $output = "";
$email = $pass = $name = "";

if (isset($_POST['_submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['_name']);
    $email = mysqli_real_escape_string($conn, $_POST['_email']);
    $pass = mysqli_real_escape_string($conn, $_POST['_pass']);

    // validate 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "please enter a valid email address";
    }

    if (empty($name)) $name_err = "please enter your name !";
    if (empty($pass)) $pass_err = "please set your account password";

    if ($email_err == "" && $pass_err == "" && $name_err == "") {
        if ($user->addUser($conn, $name, $email, $pass)) {
            $output = 'Success';
            header("Location: ../");
        } else $output = "Something Went Wrong !";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- remixicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css">

    <!-- stylesheets -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body>

    <!-- header -->
    <?php include "../includes/header.php"; ?>

    <div class="login-container">
        <div class="container login-flex">
            <div class="login-box">
                <h3>Sign Up</h3>
                <form class="form" action="#" method="POST">
                    <?php if (!empty($output)) {
                        echo "<div class='alert-con'>
                                <p class='alert-text'>$output</p>
                              </div>";
                    } ?>
                    <div class="field">
                        <input type="text" name="_name" class="inp" placeholder="Your Full Name" value="<?php echo $name ?>">
                        <i class="ri-user-line"></i>
                        <div class="err"><?php echo $name_err ?></div>
                    </div>

                    <div class="field">
                        <input type="text" name="_email" class="inp" placeholder="Your Email Address" value="<?php echo $email ?>">
                        <i class="ri-mail-line"></i>
                        <div class="err"><?php echo $email_err ?></div>
                    </div>

                    <div class="field">
                        <input type="text" name="_pass" class="inp" placeholder="Set Account Password" value="<?php echo $pass ?>">
                        <i class="ri-key-line"></i>
                        <div class="err"><?php echo $pass_err ?></div>
                    </div>

                    <button name="_submit" type="submit" class="btn btn-submit btn-signup">continue</button>
                </form>
            </div>
            <div class="login-info">
                <h3>Welcome To Drive !</h3>
                <p>Login, and start saving your files securely with open ssl 255 encoding file procedure.</p>
                <div><input type="checkbox" id="privacy_check"> <a href="#" class="btn">read privacy papers</a></div>
            </div>
        </div>
    </div>

</body>

</html>