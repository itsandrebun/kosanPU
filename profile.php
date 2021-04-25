<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
        $page_title = "Profile";
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
    <?php
        $invoice_data = array();
        $bank_data = array();
        include "DB_connection.php";
        $database = new Database();
        $con = $database->getConnection();

        $invoice_sql="SELECT inv.invoice_id, inv.invoice_number, inv.due_start_date, inv.payment_status, pys.payment_status_name, inv.total_payment, inv.payment_date, inv.created_date FROM invoice AS inv JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE inv.finalized_draft = 1 AND inv.user_id = '" . $logged_in_user['user_id'] . "'";

        $bank_sql = "SELECT * FROM banks AS bn";
        $banks = $con->query($bank_sql);
        $invoices=$con->query($invoice_sql);

        if($invoices->num_rows > 0){
            while($row = $invoices->fetch_assoc()) {
                array_push($invoice_data, $row);
            }
        }

        if($banks->num_rows > 0){
            while($row = $banks->fetch_assoc()) {
                array_push($bank_data, $row);
            }
        }

        $con->close();

        include "Helpers/Currency.php";
        $currency = new Currency();
    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 450px;overflow-y:auto">
                <h3 class="text-center">Profile</h3>
                <form method="POST" action="profile_validation" id="tenantProfileForm">
                    <input type="hidden" name="change_profile_status" value="1">
                    <table class="table table-dark table-borderless">
                        <thead>
                            <tr>
                                <th scope="col" class="pt-3 pb-0">First name</th>
                                <th scope="col" class="pt-3 pb-0">Last Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="first_name" class="form-control <?= (!empty($_SESSION['first_name_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['first_name'];?>">
                                    <?= (!empty($_SESSION['first_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['first_name_validation'].'</div>') : '') ;?>
                                </td>
                                <td>
                                    <input type="text" name="last_name" class="form-control <?= (!empty($_SESSION['last_name_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['last_name'];?>">
                                    <?= (!empty($_SESSION['last_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['last_name_validation'].'</div>') : '') ;?>
                                </td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th scope="col" class="pt-0 pb-0">E-Mail</th>
                                <th scope="col" class="pt-0 pb-0">Phone Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="email" class="form-control <?= (!empty($_SESSION['email_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['email'];?>">
                                    <?= (!empty($_SESSION['email_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['email_validation'].'</div>') : '') ;?>
                                </td>
                                <td>
                                    <input type="text" name="phone_number" class="form-control <?= (!empty($_SESSION['phone_number_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['phone_number'];?>">
                                    <?= (!empty($_SESSION['phone_number_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['phone_number_validation'].'</div>') : '') ;?>
                                </td>
                                </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th scope="col" class="pt-0 pb-0">Gender</th>
                                <th scope="col" class="pt-0 pb-0">Date of Birth</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="pb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input <?= (!empty($_SESSION['gender_validation']) ? ('is-invalid') : '');?>" type="radio" name="gender" id="male_gender" value="1" <?= (!empty($_SESSION['user_gender_error']) && $_SESSION['user_gender_error'] == 1 ? ('checked') : ($logged_in_user['gender'] == 1 ? 'checked' : '')) ;?>>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                        Male
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input <?= (!empty($_SESSION['gender_validation']) ? ('is-invalid') : '');?>" type="radio" name="gender" id="female_gender" value="2" <?= (!empty($_SESSION['user_gender_error']) && $_SESSION['user_gender_error'] == 2 ? ('checked') : ($logged_in_user['gender'] == 2 ? 'checked' : '')) ;?>>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                        Female
                                        </label>
                                    </div>
                                    <?= (!empty($_SESSION['gender_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['gender_validation'].'</div>') : '') ;?>
                                </td>
                                <td class="pb-3">
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" autocomplete="off" name="user_dob" class="datepicker form-control <?= (!empty($_SESSION['user_dob_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['dob'];?>">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <!-- <input type="date" > -->
                                    <?= (!empty($_SESSION['user_dob_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['user_dob_validation'].'</div>') : '') ;?>
                                </td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th scope="col" class="pt-0 pb-0">Bank</th>
                                <th scope="col" class="pt-0 pb-0">Account Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="pb-3">
                                    <select name="bank_id" id="bank_id" class="form-control <?= (!empty($_SESSION['bank_validation']) ? ('is-invalid') : '') ;?>">
                                        <option value="">Choose Bank</option>
                                        <?php for($a = 0; $a < count($bank_data); $a++):?>
                                            <option value="<?= $bank_data[$a]['bank_id'];?>"<?= $logged_in_user['bank_id'] == $bank_data[$a]['bank_id'] ? ' selected' : '';?>><?= $bank_data[$a]['bank_name'];?></option>
                                        <?php endfor;?>
                                    </select>
                                    <?= (!empty($_SESSION['bank_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['bank_validation'].'</div>') : '') ;?>
                                </td>
                                <td class="pb-3">
                                    <input type="text" name="owner_account_number" class="form-control <?= (!empty($_SESSION['owner_account_number_validation']) ? ('is-invalid') : '') ;?>" value="<?= $logged_in_user['owner_account_number'];?>">
                                    <?= (!empty($_SESSION['owner_account_number_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['owner_account_number_validation'].'</div>') : '') ;?>
                                </td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th scope="col" class="pt-0 pb-0">Owner Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="pb-3">
                                    <input type="text" class="form-control <?= (!empty($_SESSION['owner_name_validation']) ? ('is-invalid') : '') ;?>" name="owner_name" id="owner_name" value="<?= $logged_in_user['owner_name'];?>">
                                    <?= (!empty($_SESSION['owner_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['owner_name_validation'].'</div>') : '') ;?>
                                </td>
                            </tr>
                        </tbody>
                            <!-- <div>
                                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                            </div> -->
                    </table>
                    <?php
                        unset($_SESSION['first_name_validation']);
                        unset($_SESSION['last_name_validation']);
                        unset($_SESSION['bank_validation']);
                        unset($_SESSION['owner_name_validation']);
                        unset($_SESSION['owner_account_number_validation']);
                        unset($_SESSION['gender_validation']);
                        unset($_SESSION['phone_number_validation']);
                        unset($_SESSION['email_validation']);
                        unset($_SESSION['user_dob_validation']);
                    ?>
                    <div>
                        <input type="button" name="changeProfileButton" class="btn btn-primary w-100" value="Submit" onclick="document.getElementById('tenantProfileForm').submit();">
                        <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#invoiceModalCenter" style="background:#aa6d5a!important;border-color:#aa6d5a!important;">See Invoice</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="invoiceModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Bill No:</th>
                            <th scope="col">Payment Status:</th>
                            <th scope="col">Payment Date:</th>
                            <th scope="col">Date In:</th>
                            <th scope="col" class="text-right">Total Payment</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($invoice_data) > 0):?>
                            <?php for($p = 0; $p < count($invoice_data); $p++):?>
                                <tr>
                                    <td><?= $invoice_data[$p]['invoice_number'];?></td>
                                    <td><?= $invoice_data[$p]['payment_status_name'];?></td>
                                    <td><?= $invoice_data[$p]['payment_date'];?>-</td>
                                    <td><?= $invoice_data[$p]['created_date'];?></td>
                                    <td class="text-right"><?= $currency->convert($invoice_data[$p]['total_payment']);?></td>
                                    <td><a href="payment-detail?invoice_id=<?=$invoice_data[$p]['invoice_id'];?>">Click Here</a></td>
                                </tr>
                            <?php endfor;?>
                        <?php else:?>
                            <tr>
                                <td colspan="5" class="text-center" style="font-size:11px">No data found!</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
<?php include "templates/js_list.php";?>
</body>
</html>