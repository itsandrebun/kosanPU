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
<div class="container login-container d-flex justify-content-center align-items-center position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%); background:white; width: 500px; height: 500px">
    <form class="form-horizontal" enctype="multipart/form-data">
    <h3 style="text-decoration:underline; text-align: center;">Payment Evidence</h3>
        <div class="form-group">
            <label class="control-label col-sm-2">Name:</label>
            <div class="col-xs-4">
            <input type="text" class="form-control" name="paymentevidencename" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Payment Date:</label>
            <div class="col-xs-4">
            <input type="date" class="form-control" name="paymentdate" />
            </div>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="paymentmethod1" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">BCA(Owner Name: Bambang Subambang, Account Number: 1222898409)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="paymentmethod2" id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">Mandiri(Owner Name: John Cena, Account Number: 1238910209123</label>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Upload:</label>
            <div class="col-xs-3">
            <input type="file" name="paymentevidence" />
            </div>
        </div>
    <div>
        <a href="payment-success.php" input type="submit" class="btn btn-primary d-block mt-2">Submit Evidence</a>
    </div>
    </form>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>