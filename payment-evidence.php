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
    <div class="container login-container d-flex justify-content-center align-items-center position-fixed" style="top: 50%;left: 50%;transform: translate(-50%, -50%); background:white; width: 500px;padding-top: 70px;padding-bottom: 30px;overflow-y: auto;">
        <form class="form-horizontal" enctype="multipart/form-data">
            <h3 style="text-align: center;">Payment Evidence</h3>
            <div class="form-group">
                <label class="control-label">Invoice Number</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" name="invoice_number" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Total Payment</label>
                <div class="col-xs-4">
                    <input type="text" readonly class="form-control" name="total_payment" />
                </div>
            </div>
            <?php
                $bank_data = array();
                include "DB_connection.php";
                $database = new Database();
                $con = $database->getConnection();

                $bank_sql = "SELECT bn.* from banks AS bn";

                $banks = $con->query($bank_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($banks->num_rows > 0){
                    while($row = $banks->fetch_assoc()) {
                        array_push($bank_data, $row);
                    }
                }
                $con->close();
            ?>   
            <div class="form-group">
                <label class="control-label">Transfer to</label>
                <?php for($k = 0; $k < count($bank_data); $k++):?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentmethod1" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1"><?= $bank_data[$k]['bank_account_number'].' - '.$bank_data[$k]['owner_name'].' ('.$bank_data[$k]['bank_name'].')' ;?></label>
                </div>
                <?php endfor;?>
            </div>
            <div class="form-group">
                <label class="control-label">Payment Date</label>
                <div class="col-xs-4">
                <input type="date" class="form-control" name="payment_date" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Upload</label>
                <div class="col-xs-3">
                    <input type="file" name="payment_evidence" />
                </div>
            </div>
            <div>
                <a href="payment-success.php" input type="submit" class="btn btn-primary d-block mt-2">Submit Evidence</a>
            </div>
        </form>
    </div>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>