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
        $all_payment_data = array();
        $master_data=array();
        include "DB_connection.php";
        $database = new Database();
        $con = $database->getConnection();

        $payment_sql="SELECT trs.transaction_id, trs.transaction_code, trs.room_id, trs.booking_start_date, trs.booking_end_date, roo.room_name, trs.transaction_type_id, typ.transaction_type_name, fin.fine_transaction_id, inv.deposit, fin.price AS fine_price, trs.price AS transaction_price, equ.equipment_name, (SELECT inv2.deposit FROM invoice AS inv2 where inv2.invoice_id = inv.parent_invoice_id) AS previous_deposit, (SELECT rm2.room_name FROM room AS rm2 JOIN transaction AS tr2 ON tr2.room_id = rm2.room_id JOIN invoice AS inv2 ON inv2.invoice_id = tr2.invoice_id where inv2.invoice_id = inv.parent_invoice_id) AS previous_room_name FROM transaction AS trs JOIN transaction_type AS typ ON trs.transaction_type_id = typ.transaction_type_id JOIN invoice AS inv ON inv.invoice_id = trs.invoice_id LEFT JOIN room AS roo on trs.room_id=roo.room_id LEFT JOIN fine_transaction_detail AS fin on trs.transaction_id=fin.transaction_id LEFT JOIN equipment as equ on equ.equipment_id=fin.equipment_id where trs.invoice_id =" .$_GET['invoice_id']." ORDER BY trs.transaction_type_id DESC";

        $master_sql = "SELECT usr.first_name, usr.last_name, pys.payment_status_id, pys.payment_status_name, inv.invoice_number, inv.payment_date, inv.created_date, inv.total_payment, inv.due_start_date, inv.due_end_date FROM user as usr JOIN invoice AS inv ON usr.user_id=inv.user_id join payment_status AS pys ON pys.payment_status_id=inv.payment_status WHERE inv.invoice_id=" .$_GET['invoice_id'];

        $payments=$con->query($payment_sql);

        if($payments->num_rows > 0){
            while($row = $payments->fetch_assoc()) {
                array_push($payment_data, $row);
            }
        }
        
        $masters=$con->query($master_sql);
        $master_data=$masters->fetch_assoc();

        $con->close();

        include "Helpers/Currency.php";
        $currency = new Currency();

    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 475px;overflow-y:auto">
                <h3 class="text-center"> Payment Detail</h3>
                    <table class="table table-dark table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Bill Code</th>
                                    <th scope="col">First name</th>
                                    <th scope="col">Last Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $master_data['invoice_number']; ?></td>
                                    <td><?= $master_data['first_name']; ?></td>
                                    <td><?= $master_data['last_name']; ?></td>
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
                                    <td><?= $currency->convert($master_data['total_payment']); ?></td>
                                    <td><?= $master_data['due_start_date']; ?></td>
                                    <td><?= $master_data['due_end_date']; ?></td>
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
                                    <td><?= $master_data['payment_status_name']; ?></td>
                                    <td><?= $master_data['created_date']; ?></td>
                                    <td><?= $master_data['payment_date'] == null ? '-' : $master_data['payment_date']; ?></td>
                                </tr>
                            </tbody>
                    </table>
                <div>
                    <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#transactionModalCenter" style="background:#aa6d5a!important;border-color:#aa6d5a!important;"> Transaction Detail</button>
                </div>
                <div>
                <a href="payment-evidence?invoice_id=<?=$_GET['invoice_id'];?>" class="btn btn-primary d-block mt-2">Pay Now!</a>
                </div>
            </div>
        </div>
        <?php foreach($payment_data AS $payment_per_index):?>
            <?php
                    // echo $payment_per_index['transaction_type_id'];
                    // // exit;
                    $payment_obj = array();
                    $all_payment_data[$payment_per_index['transaction_id']]['transaction_id'] = $payment_per_index['transaction_id'];
                    $all_payment_data[$payment_per_index['transaction_id']]['transaction_code'] = $payment_per_index['transaction_code'];
                    $all_payment_data[$payment_per_index['transaction_id']]['transaction_type_id'] = $payment_per_index['transaction_type_id'];
                    $all_payment_data[$payment_per_index['transaction_id']]['transaction_type_name'] = $payment_per_index['transaction_type_name'];
                    // $payment_per_index[$payment_per_index['transaction_id']]['equipment_id'] = $payment_per_index['equipment_id'];
                    $all_payment_data[$payment_per_index['transaction_id']]['equipment_name'] = $payment_per_index['equipment_name'];
                    $all_payment_data[$payment_per_index['transaction_id']]['booking_start_date'] = $payment_per_index['booking_start_date'];
                    $all_payment_data[$payment_per_index['transaction_id']]['previous_deposit'] = "-".$payment_per_index['previous_deposit'];
                    $all_payment_data[$payment_per_index['transaction_id']]['booking_end_date'] = $payment_per_index['booking_end_date'];
                    $all_payment_data[$payment_per_index['transaction_id']]['deposit'] = $payment_per_index['deposit'];
                    $all_payment_data[$payment_per_index['transaction_id']]['transaction_cost'] = $payment_per_index['transaction_price'];
                    $all_payment_data[$payment_per_index['transaction_id']]['room_name'] = $payment_per_index['room_name'];
                    $all_payment_data[$payment_per_index['transaction_id']]['fine_cost'] = $payment_per_index['fine_price'];
                    $all_payment_data[$payment_per_index['transaction_id']]['previous_room_name'] = $payment_per_index['previous_room_name'];
                    if($payment_per_index['transaction_type_id'] == 2){
                      $payment_obj = $payment_per_index;
                    }
                    $all_payment_data[$payment_per_index['transaction_id']]['fined_items_detail'][] = $payment_obj;
            ?>
        <?php endforeach;?>
        <?php $all_payment_data = array_values($all_payment_data);?>
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
                            <th scope="col">Room No:</th>
                            <th scope="col">Fine Items:</th>
                            <th scope="col">Price:</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(count($all_payment_data) > 0):?>
                    <?php for($d = 0; $d < count($all_payment_data); $d++):?>
                        <?php if($all_payment_data[$d]['transaction_type_id'] == 1):?>
                        <tr>
                            <td rowspan="2" style="vertical-align:middle"><?= $all_payment_data[$d]['transaction_code'];?></td>
                            <td><?= $all_payment_data[$d]['transaction_type_name'];?></td>
                            <td><?= $all_payment_data[$d]['room_name'];?></td>
                            <td><?= $all_payment_data[$d]['equipment_name'];?></td>
                            <td class="text-right"><?= $all_payment_data[$d]['fine_cost'] == null ? $currency->convert($all_payment_data[$d]['transaction_cost']) : $currency->convert($all_payment_data[$d]['fine_cost']);?></td>
                        </tr>
                        <tr>
                            <!-- <td></td> -->
                            <td>Deposit</td>
                            <td><?= $all_payment_data[$d]['room_name'];?></td>
                            <td><?= $all_payment_data[$d]['equipment_name'];?></td>
                            <td class="text-right"><?= $currency->convert($all_payment_data[$d]['deposit']) ;?></td>
                        </tr>
                        <?php else:?>
                        <tr>
                            <td>Previous Month</td>
                            <td>Deposit</td>
                            <td><?= $all_payment_data[$d]['previous_room_name'];?></td>
                            <td></td>
                            <td class="text-right"><?= $currency->convert($all_payment_data[$d]['previous_deposit']);?></td>
                        </tr>
                        <?php $fined_items_detail = $all_payment_data[$d]['fined_items_detail'];?>
                            <?php for($h = 0; $h < count($fined_items_detail); $h++):?>
                            <tr>
                                <?php if($h == 0):?>
                                <td rowspan="<?= count($fined_items_detail);?>" style="vertical-align:middle"><?= $h == 0 ? $fined_items_detail[$h]['transaction_code'] : '';?></td>
                                <td rowspan="<?= count($fined_items_detail);?>" style="vertical-align:middle"><?= $h == 0 ? $fined_items_detail[$h]['transaction_type_name'] : '';?></td>
                                <?php endif;?>
                                <td><?= $h == 0 ? $fined_items_detail[$h]['room_name'] : '';?></td>
                                <td><?= $fined_items_detail[$h]['equipment_name'];?></td>
                                <td class="text-right"><?= $currency->convert($fined_items_detail[$h]['fine_price']) ;?></td>
                            </tr>
                            <?php endfor;?>
                        <?php endif;?>
                    <?php endfor;?>
                    <?php else:?>
                        <tr>
                            <td colspan="5" class="text-center" style="font-size:11px">No data found!</td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-center font-weight-bold">Total</td>
                            <td class="text-right font-weight-bold"><?= $currency->convert($master_data['total_payment']);?></td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php include "templates/js_list.php";?>
</body>
</html>