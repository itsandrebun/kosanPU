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
        $invoice_data=array();
        include "DB_connection.php";
        $database = new Database();
        $con = $database->getConnection();

        $invoice_sql="SELECT inv.invoice_id, inv.invoice_number, inv.due_start_date, inv.payment_status, pys.payment_status_name, inv.total_payment, inv.payment_date, inv.created_date FROM invoice AS inv JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE inv.user_id = '" . $logged_in_user['user_id'] . "'";

        $invoices=$con->query($invoice_sql);

        if($invoices->num_rows > 0){
            while($row = $invoices->fetch_assoc()) {
                array_push($invoice_data, $row);
            }
        }
    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 450px;overflow-y:auto">
                <h3 class="text-center">Profile</h3>
                <form>
                    <table class="table table-dark table-borderless">
                        <thead>
                            <tr>
                                <th scope="col" class="pt-3 pb-0">First name</th>
                                <th scope="col" class="pt-3 pb-0">Last Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="first_name" class="form-control" value="<?= $logged_in_user['first_name'];?>"></td>
                                <td><input type="text" name="last_name" class="form-control" value="<?= $logged_in_user['last_name'];?>"></td>
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
                                <td><input type="text" name="email" class="form-control" value="<?= $logged_in_user['email'];?>"></td>
                                <td><input type="text" name="phone_number" class="form-control" value="<?= $logged_in_user['phone_number'];?>"></td>
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
                                <td class="pb-3"><input type="text" name="gender" class="form-control" value="<?= $logged_in_user['gender'] == 1 ? 'Male' : 'Female';?>"></td>
                                <td class="pb-3"><input type="date" name="dob" class="form-control" value="<?= $logged_in_user['dob'];?>"></td>
                            </tr>
                        </tbody>
                            <!-- <div>
                                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                            </div> -->
                    </table>
                    <div>
                        <input type="button" name="changeProfileButton" class="btn btn-primary w-100" value="Submit">
                        <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#invoiceModalCenter" style="background:#aa6d5a!important;border-color:#aa6d5a!important;">See Invoice</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="invoiceModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <th scope="col">See Detail:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php for($p = 0; $p < count($invoice_data); $p++):?>
                            <td><?= $invoice_data[$p]['invoice_number'];?></td>
                            <td><?= $invoice_data[$p]['payment_status_name'];?></td>
                            <td><?= $invoice_data[$p]['payment_date'];?>-</td>
                            <td><?= $invoice_data[$p]['due_start_date'];?></td>
                            <td><a href="payment-detail.php?invoice_id=<?=$invoice_data[$p]['invoice_id'];?>">Click Here</a></td>
                        </tr>
                        <?php endfor;?>
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