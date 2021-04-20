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
                $room_data = array();
                $chosen_month = date('m');
                if(!empty($_GET['month'])){
                  $chosen_month = $_GET['month'];
                }
                $chosen_year = date('Y');
                if(!empty($_GET['year'])){
                  $chosen_year = $_GET['year'];
                }
                

                $booking_sql = "SELECT us.user_code, us.email, us.first_name, us.last_name, rm.room_name, rm.room_id, tr.transaction_id, tr.booking_start_date, tr.booking_end_date, tr.terminated_date FROM transaction AS tr JOIN user AS us ON us.user_id = tr.user_id JOIN room AS rm ON rm.room_id = tr.room_id WHERE tr.transaction_type_id = 1 AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))";

                if(!empty($_GET['room'])){
                  $booking_sql .= " AND tr.room_id = ".$_GET['room'];
                }
                $room_sql = "SELECT rm.* from room AS rm";
                $bookings = $con->query($booking_sql);
                $rooms = $con->query($room_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($bookings->num_rows > 0){
                    while($row = $bookings->fetch_assoc()) {
                        array_push($booking_data, $row);
                    }
                }
                if($rooms->num_rows > 0){
                  while($row = $rooms->fetch_assoc()) {
                      array_push($room_data, $row);
                  }
                }
                $con->close();
            ?>
            <div class="data-list">
                <form method="GET" class="mb-2 p-3" style="border:0.5px solid #c5c3c3;border-radius:1%;">
                  <h5>Filter</h5>
                  <div class="row mb-2">
                    <div class="col-md-4">
                        <select name="month" id="" class="form-control">
                            <option value="">Choose Month</option>
                            <option value="01" <?= !empty($chosen_month) && $chosen_month == '01' ? 'selected' : '' ;?>>January</option>
                            <option value="02" <?= !empty($chosen_month) && $chosen_month == '02' ? 'selected' : '' ;?>>February</option>
                            <option value="03" <?= !empty($chosen_month) && $chosen_month == '03' ? 'selected' : '' ;?>>March</option>
                            <option value="04" <?= !empty($chosen_month) && $chosen_month == '04' ? 'selected' : '' ;?>>April</option>
                            <option value="05" <?= !empty($chosen_month) && $chosen_month == '05' ? 'selected' : '' ;?>>May</option>
                            <option value="06" <?= !empty($chosen_month) && $chosen_month == '06' ? 'selected' : '' ;?>>June</option>
                            <option value="07" <?= !empty($chosen_month) && $chosen_month == '07' ? 'selected' : '' ;?>>July</option>
                            <option value="08" <?= !empty($chosen_month) && $chosen_month == '08' ? 'selected' : '' ;?>>August</option>
                            <option value="09" <?= !empty($chosen_month) && $chosen_month == '09' ? 'selected' : '' ;?>>September</option>
                            <option value="10" <?= !empty($chosen_month) && $chosen_month == '10' ? 'selected' : '' ;?>>October</option>
                            <option value="11" <?= !empty($chosen_month) && $chosen_month == '11' ? 'selected' : '' ;?>>November</option>
                            <option value="12" <?= !empty($chosen_month) && $chosen_month == '12' ? 'selected' : '' ;?>>December</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <?php
                            $year = date('Y');
                        ?>
                        <select name="year" id="" class="form-control">
                          <option value="">Choose Year</option>
                          <?php for($k = ($year - 4); $k < ($year + 5); $k++):?>
                              <option value="<?= $k ;?>" <?= !empty($chosen_year) && $chosen_year == $k ? 'selected' : '' ;?>><?= $k ;?></option>
                          <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-4">
                          <select name="room" class="form-control">
                            <option value="">Choose Room</option>
                            <?php for($k = 0; $k < count($room_data); $k++):?>
                              <option value="<?= $room_data[$k]['room_id'] ;?>" <?= !empty($_GET['room']) && $_GET['room'] == $room_data[$k]['room_id'] ? 'selected' : '' ;?>><?= $room_data[$k]['room_name'] ;?></option>
                          <?php endfor;?>
                          </select>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group me-2" role="group" aria-label="First group">
                            <button type="button" class="btn btn-danger" onclick="window.location.href = 'list'">Reset</button>
                        </div>
                        <div class="btn-group ml-2" role="group" aria-label="Second group">
                            <button type="submit" class="btn btn-primary mykosan-signature-button-color">Search</button>
                        </div>
                    </div>
                  </div>
                </form>
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
                        <?php if(count($booking_data) == 0):?>
                        <tr>
                          <td colspan="7" class="text-center" style="font-size:12px">No data found!</td>
                        </tr>
                        <?php else:?>
                        <?php for($k = 0; $k < count($booking_data); $k++):?>
                          <tr>
                              <td><?= $booking_data[$k]['first_name'].' '.$booking_data[$k]['last_name'];?></td>
                              <td><?= $booking_data[$k]['email'];?></td>
                              <td><?= $booking_data[$k]['room_name'];?></td>
                              <td><?= $booking_data[$k]['booking_start_date'];?></td>
                              <td><?= $booking_data[$k]['booking_end_date'];?></td>
                              <td><?= ((strtotime('now') >= strtotime($booking_data[$k]['booking_start_date']) && strtotime('now') <= strtotime($booking_data[$k]['booking_end_date']) ) && $booking_data[$k]['terminated_date'] == null ? '<span class="badge bg-success" style="color:white">Active</span>' : (strtotime('now') < strtotime($booking_data[$k]['booking_start_date']) ? '<span class="badge bg-secondary" style="color:white">Yet Inactive</span>' : '<span class="badge bg-secondary" style="color:white">Inactive</span>'));?></td>
                              <td><a href="detail?id=<?= $booking_data[$k]['transaction_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">View</a></td>
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

  <?php include "../logout_modal.php";?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
