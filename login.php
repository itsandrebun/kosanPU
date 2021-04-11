<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $page_title = "Login Page";
        include "templates/header.php";
    ?>
</head>
<body>
    <?php
      $login_navbar = "active";
      include "templates/navbar.php";
    ?>
    <div class="container login-container d-flex justify-content-center align-items-center">
        <div class="row">
            <div class="col-md-6 login-form-1">
                <div>                
                    <h3>Login Form</h3>
                    <form action="login_validation.php" id="login-form"  class="mt-4" method="POST">
                        <div class="form-group w-100">
                            <input type="text" class="form-control <?= (!empty($_SESSION['username_validation']) ? ('is-invalid') : '');?>" name="email" placeholder="Email/Phone Number*" value="" <?= (!empty($_SESSION['user_name']) ? ('value='.$_SESSION['user_name']) : '') ;?> >
                            <?= (!empty($_SESSION['username_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['username_validation'].'</div>') : '') ;?>
                        </div>
                        <div class="form-group w-100">
                            <input type="password" class="form-control <?= (!empty($_SESSION['password_validation']) ? ('is-invalid') : '');?>" name="password" placeholder="Password*" >
                            <?= (!empty($_SESSION['password_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['password_validation'].'</div>') : '') ;?>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btnSubmit btn btn-primary w-100" name="submitLogin" value="Login" />
                        </div>
                        <div class="form-group">
                            <a href="#" class="btnForgetPwd">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                <?php session_destroy();?>  
            </div>
            <div class="col-md-6 login-form-2 d-flex justify-content-center align-items-center">
                <div class="login-logo">
                    <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
                </div>
            </div>
        </div>
    </div>
    <?php include "templates/js_list.php";?>
</body>
</html>