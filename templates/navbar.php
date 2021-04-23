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
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>

                        <span class="badge badge-danger badge-counter" id="totalUnreadNotifications" style="position: absolute;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" style="width:20rem!important" aria-labelledby="alertsDropdown" id="notificationDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" style="white-space: normal;padding-top: .5rem;padding-bottom: .5rem;border-left: 1px solid #e3e6f0;border-right: 1px solid #e3e6f0;border-bottom: 1px solid #e3e6f0;line-height: 1.3rem;">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary mykosan-icon-background-color" style="height: 2.5rem;width: 2.5rem;border-radius: 100%;display: flex;align-items: center;justify-content: center;">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div style="color: #b7b9cc !important;font-size: 80%;font-weight: 400;">
                                    <span>Hi, Sushi Estia. Aku harap agar kamu segera membayar tunggakan! Terima kasih</span>
                                    <span class="d-block" style="font-size:11px;">September 20, 2019</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, <?= $logged_in_user['first_name'].' '.$logged_in_user['last_name'];?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile">Profile</a></li>
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