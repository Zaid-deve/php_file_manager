<header>
    <div class="container header-main">
        <div class="header-left">
            <img src="http://localhost/filemanager/images/663341.png" alt="#">
            <div class="brand              ">
                <h3 class="logo">888</h3>
                <span>cloud</span>
            </div>
        </div>

        <div class="header-right">
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="http://localhost/filemanager/auth/register.php" class="btn btn-redir"><i class="ri-user-add-fill"></i> <span>Sign Up</span></a>
            <?php } else {  ?>
                <a href="http://localhost/filemanager/upload.php" class="btn btn-upload"><i class="ri-upload-cloud-2-fill"></i><span>upload</span></a>
                <div class="profile-con" tabindex="-1">
                    <img src="images/3135715.png" alt="#">
                    <ul class="profile-menu">
                        <li>
                            <button href="#" class="btn" onclick="document.querySelector('.del-acc-outer').classList.add('show')">
                                <i class="ri-file-shred-fill"></i>
                                <span>Delete Account</span>
                            </button>
                        </li>
                        <li>
                            <a href="auth/logout.php" class="btn">
                                <i class="ri-link-unlink-m"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</header>