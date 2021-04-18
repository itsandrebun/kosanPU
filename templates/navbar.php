<!-- Nav Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mykosan-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="index">MyKosan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
                session_start();
                $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
            ?>
            <?php if($logged_in_user == null):?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($login_navbar) ? $login_navbar : '') ;?>" aria-current="page" href="login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($register_navbar) ? $register_navbar : '') ;?>" href="register">Register</a>
                </li>
            </ul>
            <?php else:?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, <?= $logged_in_user['first_name'].' '.$logged_in_user['last_name'];?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li>
                            <?php
                                if(isset($_POST['logout_flag'])){
                                    session_destroy();
                                    header("Location:index");
                                }
                            ?>
                            <form method="POST" id="logoutForm">
                                <input type="hidden" name="logout_flag" value="1">
                            </form>
                            <a class="dropdown-item" href="#" onclick="document.getElementById('logoutForm').submit();">Logout</a>

                        </li>
                    </ul>
                </li>
            </ul>
            <?php endif;?>
        </div>
    </div>
</nav>