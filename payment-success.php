<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title ="Payment Success";
        include "templates/header.php";
    ?>
</head>
<body>
<?php
    include "templates/navbar.php";
    $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] == null)){
        session_destroy();
        header("Location:index");
    }
?>
<div class="image-login-register d-flex justify-content-end">
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
    <div class="position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%)">
        <div>
            <img src="assets/photo/checked.svg" alt="" srcset="" width="300" height="300">
        </div>
        <div>
          <a href="index.php" class="btn btn-primary d-block mt-2">Back To Home</a>
        </div>
    </div>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>