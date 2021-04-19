<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Transaction List - Kosan Admin Panel";
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
                <h1 class="h3 mb-0 text-gray-800">Booking List</h1>
            </div>
            <?php
                $booking_data = array();

                $booking_sql = "SELECT us.user_code, us.email, us.first_name, us.last_name, rm.room_name, rm.room_id, tr.transaction_id, tr.booking_start_date, tr.booking_end_date, tr.terminated_date FROM transaction AS tr JOIN user AS us ON us.user_id = tr.user_id JOIN room AS rm ON rm.room_id = tr.room_id WHERE tr.transaction_type_id = 1";

                $bookings = $con->query($booking_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($bookings->num_rows > 0){
                    while($row = $bookings->fetch_assoc()) {
                        array_push($booking_data, $row);
                    }
                }
                $con->close();
            ?>
            <div class="data-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User Fullname</th>
                            <th>Email</th>
                            <th>Room</th>
                            <th>Booking Start Date</th>
                            <th>Booking End Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($k = 0; $k < count($booking_data); $k++):?>
                          <tr>
                              <td><?= $booking_data[$k]['first_name'].' '.$booking_data[$k]['last_name'];?></td>
                              <td><?= $booking_data[$k]['email'];?></td>
                              <td><?= $booking_data[$k]['room_name'];?></td>
                              <td><?= $booking_data[$k]['booking_start_date'];?></td>
                              <td><?= $booking_data[$k]['booking_end_date'];?></td>
                              <td><?= ((strtotime('now') >= strtotime($booking_data[$k]['booking_start_date']) && strtotime('now') <= strtotime($booking_data[$k]['booking_end_date']) ) && $booking_data[$k]['terminated_date'] == null ? '<span class="badge bg-success" style="color:white">Active</span>' : '<span class="badge bg-secondary" style="color:white">Inactive</span>');?></td>
                              <td><a href="detail?id=<?= $booking_data[$k]['transaction_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">View</a></td>
                          </tr>
                        <?php endfor;?>
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

  <?php include "../logout_modal.php";?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
