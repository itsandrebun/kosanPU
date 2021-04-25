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
        <form class="form-horizontal" enctype="multipart/form-data" action="submit_evidence" method="POST">
            <h3 style="text-align: center;">Payment Evidence</h3>
            <?php
                $bank_data = array();
                $payment_data = array();
                include "DB_connection.php";
                $database = new Database();
                $con = $database->getConnection();

                $bank_sql = "SELECT bn.* from banks AS bn";

                $payment_sql = "SELECT * FROM invoice where invoice_id=".$_GET["invoice_id"];

                $banks = $con->query($bank_sql);
                $payments= $con->query($payment_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($banks->num_rows > 0){
                    while($row = $banks->fetch_assoc()) {
                        array_push($bank_data, $row);
                    }
                }
                $payment_data=$payments->fetch_assoc();
                $con->close();

                include "Helpers/Currency.php";
                $currency = new Currency();
            ?>   
            <div class="form-group">
                <label class="control-label">Invoice Number</label>
                <div class="col-xs-4">
                    <input type="text" readonly class="form-control" name="invoice_number" value="<?= $payment_data['invoice_number'] ?>" />
                </div>
            </div>
            <input type="hidden" name="invoice_id" value="<?= $_GET['invoice_id'] ?>"/>
            <div class="form-group">
                <label class="control-label">Total Payment</label>
                <div class="col-xs-4">
                    <input type="text" readonly class="form-control" name="total_payment" value="<?= $currency->convert($payment_data['total_payment']); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Transfer to</label>
                <?php for($k = 0; $k < count($bank_data); $k++):?>
                <div class="form-check">
                    <input class="form-check-input <?= (!empty($_SESSION['bank_validation']) ? ('is-invalid') : '') ;?>" type="radio" name="bank_id" value="<?= $bank_data[$k]['bank_id']?>" id="flexRadioDefault1" <?= (!empty($_SESSION['bank_id']) ? ($_SESSION['bank_id'] == $bank_data[$k]['bank_id'] ? 'checked' : '') : '') ;?>>
                    <label class="form-check-label" for="flexRadioDefault1"><?= $bank_data[$k]['bank_account_number'].' - '.$bank_data[$k]['owner_name'].' ('.$bank_data[$k]['bank_name'].')' ;?></label>
                </div>
                <?php endfor;?>
                <?= (!empty($_SESSION['bank_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['bank_validation'].'</div>') : '') ;?>
            </div>
            <div class="form-group">
                <label class="control-label">Payment Date</label>
                <div class="col-xs-4">
                    <!-- <input type="date" class="form-control <?= (!empty($_SESSION['payment_date_validation']) ? ('is-invalid') : '') ;?>" name="payment_date" <?= (!empty($_SESSION['payment_date']) ? ('value="'.$_SESSION['payment_date'].'"') : '') ;?>/> -->
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" autocomplete="off" name="payment_date" class="datepicker form-control <?= (!empty($_SESSION['user_dob_validation']) ? ('is-invalid') : '') ;?>" <?= (!empty($_SESSION['payment_date']) ? ('value="'.$_SESSION['payment_date'].'"') : '') ;?> data-date-format="dd/mm/yyyy">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                    <?= (!empty($_SESSION['payment_date_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['payment_date_validation'].'</div>') : '') ;?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Upload</label>
                <div class="col-xs-3">
                    <input type="file" name="evidence" class="<?= (!empty($_SESSION['evidence_file_validation']) ? ('is-invalid') : '') ;?>" />
                    <?= (!empty($_SESSION['evidence_file_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['evidence_file_validation'].'</div>') : '') ;?>
                </div>
            </div>
            <div>
            <input type="submit" class="btn btn-primary d-block mt-2" value="Submit Evidence" name="submitEvidenceButton"> 
            </div>
        </form>
        <?php
            unset($_SESSION['bank_id']);
            unset($_SESSION['payment_date']);
            unset($_SESSION['bank_validation']);
            unset($_SESSION['payment_date_validation']);
            unset($_SESSION['evidence_file_validation']);
        ?>
    </div>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>