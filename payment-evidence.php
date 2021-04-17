<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title ="Payment Evidence";
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
<div class="container login-container d-flex justify-content-center align-items-center position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%); background:white; width: 500px; height: 300px">
    <form class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label col-sm-2">Name:</label>
            <div class="col-xs-4">
            <input type="text" class="form-control" name="paymentprovename" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Upload:</label>
            <div class="col-xs-3">
            <input type="file" name="paymentprove" />
            </div>
        </div>
    </form>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>