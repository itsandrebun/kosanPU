<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Fine Detail - Kosan Admin Panel";
    $inside_folder = 1;
    $fine_active = 1;
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
                    $booking_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.room_id, tr.user_id, rm.room_name, tr.price, tr.booking_start_date, tr.booking_end_date, tr.terminated_date, us.user_id, us.first_name, us.last_name, us.user_code, us.email, tr.terminated_reason, tr.invoice_id, inv.deposit, inv.finalized_draft FROM transaction AS tr JOIN user AS us ON us.user_id = tr.user_id JOIN room AS rm ON rm.room_id = tr.room_id JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id WHERE tr.transaction_id = ".$_GET['id'];
                    $bookings = $con->query($booking_sql);
                    $booking_data = $bookings->fetch_assoc();
                }
                
                $equipment_sql = "SELECT eq.equipment_id, eq.equipment_name, ftd.price AS fine_cost FROM equipment AS eq JOIN fine_transaction_detail AS ftd ON ftd.equipment_id = eq.equipment_id JOIN transaction AS tr ON tr.transaction_id = ftd.transaction_id WHERE tr.transaction_id = ".$_GET['id'];
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
                                <label for="finalized_draft" class="col-form-label font-weight-bold">Status</label>
                                <input class="form-control" readonly name="finalized_draft" type="text"<?= $booking_data['finalized_draft'] == 0 ? ' style="color: #ff0000!important;"' : ' style="color: #4caf50!important;"';?> value="<?= $booking_data['finalized_draft'] == 0 ? 'Not finalized' : 'Finalized';?>">
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-3">
                    <h4 class="h4 mb-0 text-gray-800">Fined Equipment</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th class="text-center">Fine Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_fine_cost = 0;?>
                        <?php if(count($equipment_data) == 0):?>
                        <tr>
                            <td colspan="2" class="text-center" style="font-size:11px">No data found!</td>
                        </tr>
                        <?php else:?>
                            <?php for($b = 0; $b < count($equipment_data); $b++):?>
                            <tr>
                                <?php $total_fine_cost += $equipment_data[$b]['fine_cost'];?>
                                <td><?= $equipment_data[$b]['equipment_name'];?></td>
                                <td class="text-right"><?= $equipment_data[$b]['fine_cost'];?></td>
                            </tr>
                            <?php endfor;?>
                        <?php endif;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="font-weight-bold">Total</td>
                            <td class="font-weight-bold text-right"><?= $total_fine_cost;?></td>
                        </tr>
                    </tfoot>
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
  <?php include "../logout_modal.php";?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
