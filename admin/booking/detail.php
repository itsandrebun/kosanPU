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
        header('Location:login');
    }
    if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] != null)){
        session_destroy();
        header('Location:login');
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
                $booking_data = $logged_in_user;

                if(!empty($_GET['id'])){
                    $booking_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.room_id, rm.room_name, tr.booking_start_date, tr.booking_end_date, tr.terminated_date, us.user_id, us.first_name, us.last_name, us.user_code, us.email, tr.terminated_reason FROM transaction AS tr JOIN user AS us ON us.user_id = tr.user_id JOIN room AS rm ON rm.room_id = tr.room_id WHERE tr.transaction_id = ".$_GET['id'];
    
                    $bookings = $con->query($booking_sql);
                    $booking_data = $bookings->fetch_assoc();
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
                    <div class="form-group">
                        <label for="room_name" class="col-form-label font-weight-bold">Room Name</label>
                        <input class="form-control" readonly name="room_name" type="text" value="<?= $booking_data['room_name'];?>">
                        <input type="hidden" name="room_id" value="<?= $booking_data['room_id'];?>">
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
                        <textarea class="form-control <?= (!empty($_SESSION['terminated_reason_validation']) ? ('is-invalid') : '') ;?>" style="resize:none" <?= $booking_data['terminated_date'] == null ? (!empty($_SESSION['terminated_reason_error']) ? $_SESSION['terminated_reason_error'] : '') : 'readonly';?> name="terminated_reason"><?= $booking_data['terminated_reason'] == null ? (!empty($_SESSION['terminated_reason_error']) ? $_SESSION['terminated_reason_error'] : '') : $booking_data['terminated_reason'];?></textarea>
                        <?= (!empty($_SESSION['terminated_reason_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['terminated_reason_validation'].'</div>') : '') ;?>
                    </div>
                    <?php if((strtotime('now') >= strtotime($booking_data['booking_start_date']) && strtotime('now') <= strtotime($booking_data['booking_end_date'])) && $booking_data['terminated_date'] == null):?>
                        <input type="submit" name="terminateBookingForm" value="Terminate" class="btn btn-danger">
                    <?php endif;?>
                </form>
                <?php
                  unset($_SESSION['terminated_reason_error']);
                  unset($_SESSION['terminated_reason_validation']);
                ?>
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
