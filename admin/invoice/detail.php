<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Invoice Detail - Kosan Admin Panel";
    $inside_folder = 1;
    $invoice_active = 1;
    $payment_evidence_src = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $payment_evidence_src_part = explode("/",$payment_evidence_src);
    include "../templates/header.php";
    session_start();
    $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    if(isset($_POST['logout_flag'])){
        session_destroy();
        header('Location:../login');
    }
    if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] != null)){
        session_destroy();
        header('Location:../login');
    }
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "../templates/sidebar.php";?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column mykosan-content-wrapper">

      <!-- Main Content -->
      <div id="content">

        <?php include "../templates/navbar.php";?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Invoice Detail</h1>
            </div>
            <?php
                $payment_history_master_data = array();
                $payment_history_data = array();
                $transaction_data = array();
                $all_transaction_data = array();
                $bank_data = array();

                if(!empty($_GET['id'])){
                  $payment_history_master_sql = "SELECT inv.*, usr.user_id, usr.first_name, usr.last_name, usr.user_code from invoice AS inv JOIN user AS usr ON usr.user_id = inv.user_id where inv.invoice_id = ".$_GET['id'];
                  $payment_history_sql = "SELECT pys.payment_status_id,pys.payment_status_name, pyh.description, pys.created_date FROM payment_status AS pys LEFT JOIN payment_history AS pyh ON pyh.payment_status_id = pys.payment_status_id WHERE pyh.invoice_id = ".$_GET['id'];
                  $transaction_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.transaction_type_id, ttp.transaction_type_name, tr.booking_start_date, tr.booking_end_date, ftd.equipment_id, eq.equipment_name, rm.room_id, rm.room_name, tr.price AS transaction_cost, ftd.price AS fine_cost, inv.deposit, (SELECT tr2.booking_start_date FROM invoice AS inv2 JOIN transaction AS tr2 ON tr2.invoice_id = inv2.invoice_id WHERE inv2.invoice_id = inv.parent_invoice_id) AS previous_booking_start_date, (SELECT tr2.transaction_code FROM invoice AS inv2 JOIN transaction AS tr2 ON tr2.invoice_id = inv2.invoice_id WHERE inv2.invoice_id = inv.parent_invoice_id) AS previous_transaction_code, (SELECT tr2.booking_end_date FROM invoice AS inv2 JOIN transaction AS tr2 ON tr2.invoice_id = inv2.invoice_id WHERE inv2.invoice_id = inv.parent_invoice_id) AS previous_booking_end_date, (SELECT inv2.deposit FROM invoice AS inv2 JOIN transaction AS tr2 ON tr2.invoice_id = inv2.invoice_id WHERE inv2.invoice_id = inv.parent_invoice_id) AS previous_deposit FROM transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN transaction_type AS ttp ON ttp.transaction_type_id = tr.transaction_type_id LEFT JOIN room AS rm ON rm.room_id = tr.room_id LEFT JOIN fine_transaction_detail AS ftd ON ftd.transaction_id = tr.transaction_id LEFT JOIN equipment AS eq ON eq.equipment_id = ftd.equipment_id WHERE tr.invoice_id = ".$_GET['id'];
                  $bank_sql = "SELECT bn.bank_name, us.owner_name, us.bank_id, us.owner_account_number from banks AS bn JOIN user AS us ON us.bank_id = bn.bank_id JOIN invoice AS inv ON inv.user_id = us.user_id WHERE inv.invoice_id = ".$_GET['id'];

                  $payment_history_master = $con->query($payment_history_master_sql);
                  $payment_history = $con->query($payment_history_sql);
                  $payment_history_master_data = $payment_history_master->fetch_assoc();
                  $banks = $con->query($bank_sql);
                  $bank_data = $banks->fetch_assoc();

                  $transaction = $con->query($transaction_sql);
                  // echo $room_sql;
                  // print_r($rooms['num_rows']);
                  if($payment_history->num_rows > 0){
                      while($row = $payment_history->fetch_assoc()) {
                          array_push($payment_history_data, $row);
                      }
                  }

                  if($transaction->num_rows > 0){
                    while($row = $transaction->fetch_assoc()) {
                        array_push($transaction_data, $row);
                    }
                  }
                }
                $con->close();

                include "../../Helpers/Currency.php";

                $currency = new Currency();
            ?>
            <div class="data-list">
                <form action="../submit_evidence" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="user_code" class="col-form-label font-weight-bold">User Code</label>
                        <input type="text" readonly name="user_code" class="form-control" value="<?= $payment_history_master_data['user_code'] ;?>" readonly>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_name" class="col-form-label font-weight-bold">First Name</label>
                            <input type="text" readonly name="user_first_name" class="form-control" value="<?= $payment_history_master_data['first_name'];?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_name" class="col-form-label font-weight-bold">Last Name</label>
                            <input type="text" readonly name="user_last_name" class="form-control" value="<?= $payment_history_master_data['last_name'];?>">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="invoice_id" value="<?= $_GET['id'];?>">
                        <label for="invoice_number" class="col-form-label font-weight-bold">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" value="<?= $payment_history_master_data['invoice_number'];?>" readonly>
                    </div>
                    <?php
                      $payment_evidence_src = str_replace($payment_evidence_src_part[count($payment_evidence_src_part) - 3].'/'.$payment_evidence_src_part[count($payment_evidence_src_part) - 2].'/'.$payment_evidence_src_part[count($payment_evidence_src_part) - 1],$payment_history_master_data['payment_evidence'],$payment_evidence_src);
                    ?>
                    <div class="form-group">
                        <label for="payment_evidence" class="col-form-label font-weight-bold">Payment Evidence</label>
                        <img class="d-block payment_evidence_image" src="<?= '/../../'.$payment_history_master_data['payment_evidence'];?>" alt="" onError="this.onerror=null;this.src='../../assets/photo/invoice-icon-line-style-symbol-shopping-icon-collection-invoice-creative-element-logo-infographic-ux-ui-invoice-icon-169076566.jpg';">
                    </div>
                    <div class="form-group">
                        <label for="payment_date" class="col-form-label font-weight-bold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?= $payment_history_master_data['payment_status'] >= 3 ? ($payment_history_master_data['payment_date'] != null ? date('Y-m-d',strtotime($payment_history_master_data['payment_date'])) : '') : '';?>" <?= $payment_history_master_data['payment_status'] >= 3 || $payment_history_master_data['payment_status'] == 1 ? 'readonly' : '';?>>
                    </div>
                    <?php if($payment_history_master_data['payment_status'] == 4):?>
                    <div class="form-group">
                        <label for="rejected_reason" class="col-form-label font-weight-bold">Rejected Reason</label>
                        <textarea style="resize:none" class="form-control <?= (!empty($_SESSION['rejected_reason_validation']) ? ('is-invalid') : '') ;?>" name="rejected_reason"><?= (!empty($_SESSION['rejected_reason_error']) ? $_SESSION['rejected_reason_error'] : '') ;?></textarea>
                        <?= (!empty($_SESSION['rejected_reason_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['rejected_reason_validation'].'</div>') : '') ;?>
                    </div>
                    <?php endif;?>
                    <div class="btn-group d-flex justify-content-end" role="group" aria-label="Basic example">
                      <div>
                        <input type="submit" name="confirmEvidenceButton" class="btn btn-primary mykosan-signature-button-color" value="Approve"<?= ($payment_history_master_data['payment_status'] == 3 || $payment_history_master_data['payment_status'] == 1 ? ' disabled' : '');?>>
                        <input type="submit" name="rejectEvidenceButton" class="btn btn-danger ml-2 mykosan-signature-danger-button-color" value="Reject"<?= ($payment_history_master_data['payment_status'] == 3 || $payment_history_master_data['payment_status'] == 1 ? ' disabled' : '');?>>
                      </div>
                    </div>
                </form>
                <!-- <?php
                  unset($_SESSION['rejected_reason_validation']);
                  unset($_SESSION['rejected_reason_error']);
                ?> -->
                <hr>
                <div id="payment_history_div" class="mt-4">
                    <h1 class="h3 mb-0 text-gray-800">Payment History</h1>
                    <table class="table table-striped mt-3">
                      <thead>
                          <tr>
                            <th class="text-center">Payment Status Name</th>
                            <th class="text-center">Payment Status Description</th>
                            <th class="text-center">History Date</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php if(count($payment_history_data) == 0):?>
                          <tr>
                            <td colspan="3" class="text-center">No data found</td>
                          </tr>
                        <?php else:?>
                          <?php for($c = 0; $c < count($payment_history_data); $c++):?>
                              <tr>
                                <td class="text-center"><?= "<?></?>".$payment_history_data[$c]['payment_status_name'];?></td>
                                <td class="text-center"><?= ($payment_history_data[$c]['description'] == null ? '-' : $payment_history_data[$c]['description']);?></td>
                                <td class="text-center"><?= date("Y-m-d H:i:s",strtotime($payment_history_data[$c]['created_date']));?></td>
                              </tr>
                          <?php endfor;?>
                        <?php endif;?>
                      </tbody>
                    </table>
                </div>
                <?php foreach($transaction_data AS $transaction_per_index):?>
                  <?php
                    $transaction_obj = array();
                    $all_transaction_data[$transaction_per_index['transaction_id']]['transaction_id'] = $transaction_per_index['transaction_id'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['transaction_code'] = $transaction_per_index['transaction_code'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['transaction_type_id'] = $transaction_per_index['transaction_type_id'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['transaction_type_name'] = $transaction_per_index['transaction_type_name'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['equipment_id'] = $transaction_per_index['equipment_id'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['equipment_name'] = $transaction_per_index['equipment_name'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['booking_start_date'] = $transaction_per_index['booking_start_date'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['booking_end_date'] = $transaction_per_index['booking_end_date'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['previous_booking_start_date'] = $transaction_per_index['previous_booking_start_date'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['previous_booking_end_date'] = $transaction_per_index['previous_booking_end_date'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['previous_deposit'] = "-".$transaction_per_index['previous_deposit'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['previous_transaction_code'] = $transaction_per_index['previous_transaction_code'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['deposit'] = $transaction_per_index['deposit'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['transaction_cost'] = $transaction_per_index['transaction_cost'];
                    $all_transaction_data[$transaction_per_index['transaction_id']]['room_name'] = $transaction_per_index['room_name'];
                    if($transaction_per_index['transaction_type_id'] == 2){
                      $transaction_obj = $transaction_per_index;
                    }
                    $all_transaction_data[$transaction_per_index['transaction_id']]['fined_items_detail'][] = $transaction_obj;
                  ?>
                <?php endforeach;?>
                <?php
                  $all_transaction_data = array_values($all_transaction_data);
                  // echo "<pre>";
                  // print_r($all_transaction_data);
                ?>
                <hr>
                <h1 class="h3 mb-0 text-gray-800">Transaction List</h1>
                <?php if($payment_history_master_data['return_by_admin'] == 0 && $payment_history_master_data['finalized_draft'] == 1):?>
                <div class="d-flex justify-content-end">
                    <input type="button" value="Return amount to tenant" class="btn btn-primary mykosan-signature-button-color" data-toggle="modal" data-target="#returnTenantMoneyPopup">
                </div>
                <?php endif;?>
                <div id="transaction_list_div" class="mt-4">
                  <table id="transaction_list_table" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Transaction Code</th>
                        <th>Transaction Type</th>
                        <th>Booking Start Date</th>
                        <th>Booking End Date</th>
                        <th>Description</th>
                        <th>Room</th>
                        <th>Fined Items</th>
                        <th class="text-right">Price</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for($a = 0; $a < count($all_transaction_data); $a++):?>
                        <?php $fined_items_detail = $all_transaction_data[$a]['fined_items_detail'];?>
                          <tr<?= $all_transaction_data[$a]['transaction_type_id'] == 2 ? ' class="table-danger"' : '';?>>
                            <td<?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : '';?>><?= $all_transaction_data[$a]['transaction_type_id'] == 2 ? $all_transaction_data[$a]['previous_transaction_code'] : $all_transaction_data[$a]['transaction_code'];?></td>
                            <td><?= $all_transaction_data[$a]['transaction_type_id'] == 2 ? 'Deposit' : $all_transaction_data[$a]['transaction_type_name'];?></td>
                            <td <?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : ' rowspan="'.(count($fined_items_detail)+1).'" style="vertical-align:middle;text-align:center"';?>><?= $all_transaction_data[$a]['booking_start_date'] == null ? $all_transaction_data[$a]['previous_booking_start_date'] : $all_transaction_data[$a]['booking_start_date'];?></td>
                            <td <?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : ' rowspan="'.(count($fined_items_detail)+1).'" style="vertical-align:middle;text-align:center"';?>><?= $all_transaction_data[$a]['booking_end_date'] == null ? $all_transaction_data[$a]['previous_booking_end_date'] : $all_transaction_data[$a]['booking_end_date'];?></td>
                            <td <?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : ' rowspan="'.(count($fined_items_detail)+1).'" style="vertical-align:middle;text-align:center"';?>><?= $all_transaction_data[$a]['transaction_type_id'] == 2 ? 'Return deposit' : '';?></td>
                            <td <?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : ' rowspan="'.(count($fined_items_detail)+1).'" style="vertical-align:middle;text-align:center"';?>><?= $all_transaction_data[$a]['room_name'];?></td>
                            <td <?= $all_transaction_data[$a]['transaction_type_id'] == 1 ? ' rowspan="2" style="vertical-align:middle;text-align:center"' : '';?>>-</td>
                            <td class="text-right"><?= $all_transaction_data[$a]['transaction_type_id'] == 2 ? $currency->convert($all_transaction_data[$a]['previous_deposit']) : $currency->convert($all_transaction_data[$a]['transaction_cost']);?></td>
                          </tr>
                        <?php if($all_transaction_data[$a]['transaction_type_id'] == 1):?>
                          <tr>
                            <!-- <td></td> -->
                            <td>Deposit</td>
                            <!-- <td></td>
                            <td></td> -->
                            <!-- <td></td> -->
                            <!-- <td></td> -->
                            <!-- <td>-</td> -->
                            <td class="text-right"><?= $currency->convert($all_transaction_data[$a]['deposit']);?></td>
                          </tr>
                        <?php endif;?>
                        <?php if(isset($fined_items_detail[0]['transaction_id'])):?>
                        <?php for($b = 0; $b < count($fined_items_detail); $b++):?>
                          <tr <?= $fined_items_detail[$b]['transaction_type_id'] == 2 ? ' class="table-danger"' : '';?>>
                            <?php if($b == 0):?>
                              <td style="vertical-align:middle" rowspan="<?= count($fined_items_detail);?>"><?= $b != 0 ? '' : (isset($fined_items_detail[$b]['transaction_code']) ? $fined_items_detail[$b]['transaction_code'] : '');?></td>
                              <td style="vertical-align:middle" rowspan="<?= count($fined_items_detail);?>"><?= $b != 0 ? '' : (isset($fined_items_detail[$b]['transaction_type_name']) ? $fined_items_detail[$b]['transaction_type_name'] : '');?></td>
                            <?php endif;?>
                            <!-- <td></td> -->
                            <!-- <td></td> -->
                            <!-- <td></td> -->
                            <!-- <td>-</td> -->
                            <td><?= isset($fined_items_detail[$b]['equipment_name']) ? $fined_items_detail[$b]['equipment_name'] : '-';?></td>
                            <td class="text-right"><?= isset($fined_items_detail[$b]['fine_cost']) ? $currency->convert($fined_items_detail[$b]['fine_cost']) : '-';?></td>
                          </tr>
                        <?php endfor;?>
                        <?php endif;?>
                      <?php endfor;?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="7" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold"><?= $currency->convert($payment_history_master_data['total_payment']);?></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>My Kosan</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  <?php if($payment_history_master_data['return_by_admin'] == 0 && $payment_history_master_data['finalized_draft'] == 1):?>
  <!-- Modal -->
  <div class="modal fade" id="returnTenantMoneyPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="returnTenantMoneyPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnTenantMoneyPopupLabel">Return Amount to Tenant</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../return_tenant_money" id="returnTenantMoneyForm">
                        <input type="hidden" name="return" value="1">
                        <input type="hidden" name="invoice_id" value="<?= $_GET['id'];?>">
                        <input type="hidden" name="tenant_id" value="<?= $payment_history_master_data['user_id'];?>">
                        <div class="form-group">
                          <label for="total_payment" class="col-form-label font-weight-bold">Total Payment</label>
                          <input type="text" readonly name="total_payment" id="total_payment" class="form-control" value="<?= $payment_history_master_data['total_payment'];?>">
                        </div>
                        <div class="form-group">
                          <label for="destination_bank" class="col-form-label font-weight-bold">Transfer To</label>
                          <input type="hidden" name="bank_id" value="<?= $bank_data['bank_id'];?>">
                          <input type="text" readonly class="form-control" name="bank_detail" value="<?= $bank_data['bank_id'] == null ? '' : ($bank_data['owner_account_number'] . ' - ' . $bank_data['owner_name'] . ' (' .$bank_data['bank_name'] . ')');?>">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mykosan-signature-button-color" onclick="document.getElementById('returnTenantMoneyForm').submit();">Return The Amount</button>
                </div>
            </div>
        </div>
  </div>
  <?php endif;?>
  <?php include "../logout_modal.php";?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
