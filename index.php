<!doctype html>
<html lang="en">
  <head>
    <?php
      $page_title = "Landing Page";
      // session_start();
      
      include "templates/header.php";
    ?>
  </head>
  <body style="height:100vh">
    <?php
      include "templates/navbar.php";
      $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
      if($logged_in_user != null && $logged_in_user['tenant_id'] == null){
        session_destroy();
        header("Location:index.php");
      }
    ?>
    <div class="image-login-register d-flex justify-content-end">
      <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
      <?php if($logged_in_user == null):?>
        <div class="position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%)">
          <a href="login" class="btn btn-primary">Find Dormitory</a>
        </div>
      <?php else:?>
      <?php $page_title="Home" ?>
        <?php if(!empty($_SESSION['success_booking'])):?>
          <div class="alert alert-success alert-dismissible fade show position-absolute w-100 auth_alert" role="alert" style="top:0%;left:0%">
            <strong><?= $_SESSION['success_booking'];?></strong>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
        <?php endif;?>
        <div class="position-fixed justify-content" style="top: 50%;left: 25%;transform: translate(-50%, -50%)">
          <div>
            <img src="assets/photo/man.svg" alt="" srcset="" width="300" height="300">
          </div>
          <a href="room.php?gender=1" class="btn btn-primary d-block mt-3">Book Now!</a>
        </div>
        <div class="position-fixed justify-content" style="top: 50%;left: 75%;transform: translate(-50%, -50%)">
          <div>
            <img src="assets/photo/woman.svg" alt="" srcset="" width="300" height="300">
          </div>
          <a href="room.php?gender=2" class="btn btn-primary d-block mt-3">Book Now!</a>
        </div>
        <?php
          unset($_SESSION['success_booking']);
        ?>
      <?php endif;?>
    </div>

    <?php include "templates/js_list.php";?>
    <script>
      $(".auth_alert").fadeTo(2000, 500).slideUp(500, function(){
          $(".auth_alert").slideUp(500);
      });
    </script>
  </body>
</html>