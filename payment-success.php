<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title ="Coba";
        include "templates/header.php";
    ?>
</head>
<body>
<?php
    include "templates/navbar.php";
    $logged_in_user = $_SESSION['user'];
    if(empty($logged_in_user)){
        header("Location:index.php");
    }
?>
<div class="image-login-register d-flex justify-content-end">
    <img src="photo/kosan.jpg" class="card-img-top" alt="...">
    <div class="position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%)">
        <div>
            <img src="photo/checked.svg" alt="" srcset="" width="300" height="300">
        </div>
        <div>
          <a href="index.php" class="btn btn-primary d-block mt-2">Back To Home</a>
        </div>
    </div>
</body>
</html>