<!DOCTYPE html>
<html lang="en">

<?php
    $page_title = "Login - Kosan Admin Panel";
    include "templates/header.php";
    session_start();
?>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Kosan</h1>
                  </div>
                  <form class="user" action="login_validation" method="POST">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user <?= (!empty($_SESSION['username_validation']) ? ('is-invalid') : '') ;?>" name="username" id="email" aria-describedby="emailHelp" placeholder="Email/Username" <?= (!empty($_SESSION['user_name']) ? ('value="'.$_SESSION['user_name'].'"') : '') ;?>>
                      <?= (!empty($_SESSION['username_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['username_validation'].'</div>') : '') ;?>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user <?= (!empty($_SESSION['password_validation']) ? ('is-invalid') : '') ;?>" name="password" id="password" placeholder="Password">
                      <?= (!empty($_SESSION['password_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['password_validation'].'</div>') : '') ;?>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary btn-user btn-block" id="loginButton" name="loginButton" value="Login">
                    <!-- <hr> -->
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="#">Forgot Password?</a>
                  </div>
                  <?php session_destroy();?>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <?php include "templates/js_list.php";?>

</body>

</html>
