<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title = 'Upload Image';
        include "templates/header.php";
    ?>
</head>
<body>
<?php
    include "templates/navbar.php";
?>
<div class="image-login-register d-flex justify-content-end">
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
    <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
        <div style="height:400px;overflow-y:auto">
            <form action="upload_payment_evidence.php" enctype="multipart/form-data" method="POST">
                <input type="file" name="payment_evidence" id="payment_evidence" class="form-control">
                <input type="submit" class="btn btn-primary mt-4" name="uploadButton" value="Submit">
            </form>
        </div>
    </div>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>