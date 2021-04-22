<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
        $page_title = "Payment Detail";
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
        $payment_data=array();
        $master_data=array();
        include "DB_connection.php";
        $database = new Database();
        $con = $database->getConnection();

        $payment_sql="SELECT trs.transaction_id, trs.transaction_code, trs.price, trs.room_id, roo.room_name, trs.transaction_type_id, typ.transaction_type_name, fin.fine_transaction_id, fin.price AS fine_price, equ.equipment_name FROM transaction AS trs JOIN transaction_type AS typ ON trs.transaction_type_id = typ.transaction_type_id LEFT JOIN room AS roo on trs.room_id=roo.room_id LEFT JOIN fine_transaction_detail AS fin on trs.transaction_id=fin.fine_transaction_id LEFT JOIN equipment as equ on equ.equipment_id=fin.equipment_id where trs.invoice_id =" .$_GET['invoice_id'];

        $master_sql="SELECT usr.first_name, usr.last_name, pys.payment_status_id, pys.payment_status_name, inv.invoice_number, inv.payment_date, inv.created_date, inv.total_payment, inv.due_start_date, inv.due_end_date FROM user as usr JOIN invoice AS inv ON usr.user_id=inv.user_id join payment_status AS pys ON pys.payment_status_id=inv.payment_status=" .$_GET['invoice_id'];

        $payments=$con->query($payment_sql);

        if($payments->num_rows > 0){
            while($row = $payments->fetch_assoc()) {
                array_push($payment_data, $row);
            }
        }
        
        $masters=$con->query($master_sql);
        $master_data=$masters->fetch_assoc();

    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 475px;overflow-y:auto">
                <h3 class="text-center"> Payment Detail</h3>
                    <table class="table table-dark table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">First name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Bill Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $master_data['first_name'] ?></td>
                                    <td><?= $master_data['last_name'] ?></td>
                                    <td><?= $master_data['invoice_number'] ?></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Total Payment</th>
                                    <th scope="col">Due Start Date</th>
                                    <th scope="col">Due End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $master_data['total_payment'] ?></td>
                                    <td><?= $master_data['due_start_date'] ?></td>
                                    <td><?= $master_data['due_end_date'] ?></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $master_data['payment_status_name'] ?></td>
                                    <td><?= $master_data['created_date'] ?></td>
                                    <td><?= $master_data['payment_date'] ?></td>
                                </tr>
                            </tbody>
                    </table>
                <div>
                    <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#transactionModalCenter" style="background:#aa6d5a!important;border-color:#aa6d5a!important;">Detail Transaction</button>
                </div>
                <div>
                <a href="payment-evidence.php?invoice_id=<?=$_GET['invoice_id'];?>" class="btn btn-primary d-block mt-2">Pay Now!</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="transactionModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <th scope="col">Transaction Code:</th>
                            <th scope="col">Transaction Type:</th>
                            <th scope="col">Total Price:</th>
                            <th scope="col">Room No:</th>
                            <th scope="col">Fine Items:</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for($d = 0; $d < count($payment_data); $d++):?>
                        <tr>
                            <td><?= $payment_data[$d]['transaction_code'];?></td>
                            <td><?= $payment_data[$d]['transaction_type_name'];?></td>
                            <td><?= $payment_data[$d]['price'];?></td>
                            <td><?= $payment_data[$d]['room_name'];?></td>
                            <td><?= $payment_data[$d]['equipment_name'];?>-</td>
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