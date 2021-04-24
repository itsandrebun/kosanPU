<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Booking Detail - Kosan Admin Panel";
    $inside_folder = 1;
    $transaction_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Booking Detail</h1>
            </div>
            <?php
                $booking_data = array();
                $equipment_data = array();
                $internal_parameter_data = array();

                if(!empty($_GET['id'])){
                    $booking_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.room_id, tr.user_id, rm.room_name, tr.price, tr.booking_start_date, tr.booking_end_date, tr.terminated_date, us.user_id, us.first_name, us.last_name, us.user_code, us.email, us.phone_number, tr.terminated_reason, tr.invoice_id, inv.deposit, (SELECT (CASE WHEN inv2.finalized_draft IS NULL THEN 0 ELSE inv2.finalized_draft END) FROM invoice AS inv2 WHERE inv2.parent_invoice_id = inv.invoice_id) AS finalized_draft FROM transaction AS tr JOIN user AS us ON us.user_id = tr.user_id JOIN room AS rm ON rm.room_id = tr.room_id JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id WHERE tr.transaction_id = ".$_GET['id'];
                    $bookings = $con->query($booking_sql);
                    $booking_data = $bookings->fetch_assoc();
                }
                
                $equipment_sql = "SELECT eq.equipment_id, eq.equipment_name, (select fn.price from fine AS fn WHERE fn.item_id = eq.equipment_id) AS fine_cost, (CASE WHEN eq.equipment_id IN (SELECT ftd.equipment_id FROM fine_transaction_detail AS ftd JOIN transaction AS trs ON trs.transaction_id = ftd.transaction_id JOIN invoice AS iv ON iv.invoice_id = trs.invoice_id WHERE iv.parent_invoice_id = tr.invoice_id) THEN 1 ELSE 0 END) AS fine_status from equipment as eq JOIN room_equipment_mapping AS rem ON rem.equipment_id = eq.equipment_id JOIN transaction AS tr ON tr.room_id = rem.room_id WHERE tr.transaction_id = ".$_GET['id']." ORDER BY fine_status DESC";
                $equipment = $con->query($equipment_sql);

                $internal_parameter_query = "SELECT * FROM internal_parameter AS itp WHERE itp.parameter_name IN ('company_name','company_address')";
                $internal_parameter = $con->query($internal_parameter_query);

                if($equipment->num_rows > 0){
                    while($row = $equipment->fetch_assoc()) {
                        array_push($equipment_data, $row);
                    }
                }

                if($internal_parameter->num_rows > 0){
                    while($row = $internal_parameter->fetch_assoc()) {
                        array_push($internal_parameter_data, $row);
                    }
                }
                $con->close();
            ?>
            <div class="data-list">
                <form action="../booking_termination" method="POST">
                    <div class="form-group">
                        <label for="transaction_code" class="col-form-label font-weight-bold">Code</label>
                        <input class="form-control" readonly name="transaction_code" type="text" value="<?= $booking_data['transaction_code'];?>">
                    </div>
                    <div class="form-group">
                        <label for="user_code" class="col-form-label font-weight-bold">User Code</label>
                        <input class="form-control" readonly name="user_code" type="text" value="<?= $booking_data['user_code'];?>">
                    </div>
                    <div class="row">
                        <input type="hidden" name="transaction_id" value="<?= $_GET['id'];?>">
                        <input type="hidden" name="tenant_id" value="<?= $booking_data['user_id'];?>">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenant_first_name" class="col-form-label font-weight-bold">First Name</label>
                                <input class="form-control" readonly name="first_name" value="<?= $booking_data['first_name'];?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenant_last_name" class="col-form-label font-weight-bold">Last Name</label>
                                <input class="form-control" readonly name="last_name" value="<?= $booking_data['last_name'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="room_name" class="col-form-label font-weight-bold">Room Name</label>
                                <input class="form-control" readonly name="room_name" type="text" value="<?= $booking_data['room_name'];?>">
                                <input type="hidden" name="room_id" value="<?= $booking_data['room_id'];?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="rent_cost" class="col-form-label font-weight-bold">Rent Cost</label>
                                <input class="form-control" readonly name="price" type="text" value="<?= $booking_data['price'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booking_start_date" class="col-form-label font-weight-bold">Booking Start Date</label>
                                <input class="form-control" readonly name="booking_start_date" type="date" value="<?= date("Y-m-d",strtotime($booking_data['booking_start_date']));?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booking_end_date" class="col-form-label font-weight-bold">Booking End Date</label>
                                <input class="form-control" readonly name="booking_end_date" type="date" value="<?= date("Y-m-d",strtotime($booking_data['booking_end_date']));?>">
                            </div>
                        </div>
                    </div>
                    <?php if($booking_data['terminated_date'] != null):?>
                    <div class="form-group">
                        <label for="terminated_date" class="col-form-label font-weight-bold">Terminated Date</label>
                        <input class="form-control" readonly name="terminated_date" type="date" value="<?= $booking_data['terminated_date'] == null ? '' : date("Y-m-d",strtotime($booking_data['terminated_date']));?>">
                    </div>
                    <?php endif;?>
                    <div class="form-group">
                        <label for="terminated_reason" class="col-form-label font-weight-bold">Terminated Reason</label>
                        <textarea class="form-control <?= (!empty($_SESSION['terminated_reason_validation']) ? ('is-invalid') : '') ;?>" style="resize:none" <?= (strtotime('now') >= strtotime($booking_data['booking_start_date']) && strtotime('now') <= strtotime($booking_data['booking_end_date'])) && $booking_data['terminated_date'] == null ? '' : 'readonly';?> name="terminated_reason"><?= $booking_data['terminated_reason'] == null ? (!empty($_SESSION['terminated_reason_error']) ? $_SESSION['terminated_reason_error'] : '') : $booking_data['terminated_reason'];?></textarea>
                        <?= (!empty($_SESSION['terminated_reason_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['terminated_reason_validation'].'</div>') : '') ;?>
                    </div>
                    <?php if((strtotime('now') >= strtotime($booking_data['booking_start_date']) && strtotime('now') <= strtotime($booking_data['booking_end_date'])) && $booking_data['terminated_date'] == null):?>
                        <input type="submit" name="terminateBookingForm" value="Terminate" class="btn btn-danger">
                    <?php else:?>
                        <input type="submit" name="terminateBookingForm" value="Terminate" class="btn btn-danger" disabled>
                    <?php endif;?>
                </form>
                <?php
                  unset($_SESSION['terminated_reason_error']);
                  unset($_SESSION['terminated_reason_validation']);
                  unset($_SESSION['fined_validation']);
                ?>
                <hr>
                <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-3">
                    <h4 class="h4 mb-0 text-gray-800">Equipment and Fine Status</h4>
                    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary mykosan-signature-button-color shadow-sm" data-toggle="modal" data-target="#fineStatusPopup"><i class="fas fa-edit fa-sm text-white-50"></i> Edit Fine Status</button>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th class="text-center">Fine Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_fine_cost = 0; ?>
                        <?php if(count($equipment_data) == 0):?>
                        <tr>
                            <td colspan="2" class="text-center" style="font-size:11px">No data found!</td>
                        </tr>
                        <?php else:?>
                            <?php for($b = 0; $b < count($equipment_data); $b++):?>
                            <?php
                                if($equipment_data[$b]['fine_status'] == 1){
                                    $total_fine_cost += $equipment_data[$b]['fine_cost'];
                                }    
                            ?>
                            <tr<?= $equipment_data[$b]['fine_status'] == 0 ? ' class="table-success"' : ' class="table-danger"';?>>
                                <td><?= $equipment_data[$b]['equipment_name'].' ('.$equipment_data[$b]['fine_cost'].')';?></td>
                                <td class="text-center"><?= $equipment_data[$b]['fine_status'] == 0 ? 'No' : 'Yes';?></td>
                            </tr>
                            <?php endfor;?>
                        <?php endif;?>
                    </tbody>
                </table>
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
    
    <!-- Modal -->
    <div class="modal fade" id="fineStatusPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="fineStatusPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fineStatusPopupLabel">Fined Item Status</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../submit_fine" id="fineStatusForm">
                        <input type="hidden" name="transaction_id" value="<?= $_GET['id'];?>">
                        <input type="hidden" name="parent_invoice_id" value="<?= $booking_data['invoice_id'];?>">
                        <input type="hidden" name="submitFineStatus" value="1" id="submitFineStatus">
                        <input type="hidden" name="tenant_id" value="<?= $booking_data['user_id'];?>">
                        <input type="hidden" name="room_id" value="<?= $booking_data['room_id'];?>">
                        <input type="hidden" name="deposit" value="<?= $booking_data['deposit'];?>">
                        <input type="hidden" name="first_name" value="<?= $booking_data['first_name'];?>">
                        <input type="hidden" name="last_name" value="<?= $booking_data['last_name'];?>">
                        <input type="hidden" name="email" value="<?= $booking_data['email'];?>">
                        <input type="hidden" name="phone_number" value="<?= $booking_data['phone_number'];?>">
                        <?php
                            $due_start_date = date("Y-m-01",strtotime($booking_data['booking_end_date']." +1 month"));
                            $due_end_date = date("Y-m-d", strtotime($due_start_date." +1 week"));
                        ?>
                        <input type="hidden" name="due_start_date" value="<?= $due_start_date;?>">
                        <input type="hidden" name="due_end_date" value="<?= $due_end_date;?>">
                        <input type="hidden" name="company_name" value="<?= $internal_parameter_data[0]['parameter_value'];?>">
                        <input type="hidden" name="company_address" value="<?= $internal_parameter_data[1]['parameter_value'];?>">
                        <ul class="list-group">
                            <?php for($k = 0; $k < count($equipment_data); $k++):?>
                                <li class="list-group-item"><input type="checkbox" class="mr-2" name="fined_equipment_status[]" value="<?= $equipment_data[$k]['equipment_id'];?>"<?= $equipment_data[$k]['fine_status'] == 0 ? ' ' : ' checked';?>><?= $equipment_data[$k]['equipment_name'].' ('.$equipment_data[$k]['fine_cost'].')';?></li>
                            <?php endfor;?>
                            <li class="list-group-item font-weight-bold">Total Fine Cost: <?= $total_fine_cost;?></li>
                        </ul>
                        
                    </form>
                </div>
                <?php if($booking_data['finalized_draft'] == 0):?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mykosan-signature-danger-button-color" onclick="document.getElementById('submitFineStatus').value = 2;document.getElementById('fineStatusForm').submit();">Finalize</button>
                    <button type="button" class="btn btn-primary mykosan-signature-button-color" onclick="document.getElementById('fineStatusForm').submit();">Submit</button>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
  <?php include "../logout_modal.php";?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
