<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Dashboard - Kosan Admin Panel";
    $dashboard_active = 1;
    session_start();
    $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    if(isset($_POST['logout_flag'])){
        session_destroy();
        header('Location:login');
    }
    if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] != null)){
        header('Location:login');
    }
    include "templates/header.php";
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "templates/sidebar.php";?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column mykosan-content-wrapper">

      <!-- Main Content -->
      <div id="content">

        <?php
          
          include "templates/navbar.php";
          include "../Helpers/Currency.php";
          $currency = new Currency();
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          </div>

          <?php
            $top_tenant_list_array = array();
            $top_booked_room_list_array = array();
            $chosen_month = date('m');
            if(isset($_GET['month'])){
              $chosen_month = $_GET['month'];
            }
            $chosen_year = date('Y');
            if(isset($_GET['year'])){
              $chosen_year = $_GET['year'];
            }
            $top_booked_room_sql = "SELECT rm.room_id, rm.room_name, (CASE WHEN COUNT(tr.room_id) IS NULL THEN 0 ELSE COUNT(tr.room_id) END) AS total_booked_room FROM room AS rm JOIN transaction AS tr ON tr.room_id = rm.room_id WHERE tr.transaction_type_id = 1 AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."')) GROUP BY rm.room_id ORDER BY total_booked_room DESC LIMIT 5";

            $top_tenant_list_sql = "SELECT us.user_id, us.first_name, us.last_name, us.email, us.phone_number, (CASE WHEN COUNT(inv.invoice_id) IS NULL THEN 0 ELSE COUNT(inv.invoice_id) END) AS total_transaction FROM user AS us JOIN tenant AS tnt ON tnt.user_id = us.user_id JOIN invoice AS inv ON inv.user_id = us.user_id WHERE (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."') GROUP BY us.user_id ORDER BY total_transaction DESC LIMIT 5";

            $booking_statistics_sql = "SELECT (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP < tr.booking_start_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_pending_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP BETWEEN tr.booking_start_date AND tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_active_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP > tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_expired_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND terminated_date IS NOT NULL AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_terminated_booking";

            $payment_statistics_sql = "SELECT (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status <= 2 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_pending_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 3 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_approved_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 4 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_rejected_payment";

            if($chosen_month == "all"){
              $booking_statistics_sql = "SELECT (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP < tr.booking_start_date AND ((YEAR(tr.booking_start_date) = '".$chosen_year."') OR (YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_pending_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP BETWEEN tr.booking_start_date AND tr.booking_end_date AND ((YEAR(tr.booking_start_date) = '".$chosen_year."') OR (YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_active_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP > tr.booking_end_date AND ((YEAR(tr.booking_start_date) = '".$chosen_year."') OR (YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_expired_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND terminated_date IS NOT NULL AND ((YEAR(tr.booking_start_date) = '".$chosen_year."') OR (YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_terminated_booking";

              $payment_statistics_sql = "SELECT (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status <= 2 AND (YEAR(inv.created_date) = '".$chosen_year."')) AS total_pending_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 3 AND (YEAR(inv.created_date) = '".$chosen_year."')) AS total_approved_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 4 AND (YEAR(inv.created_date) = '".$chosen_year."')) AS total_rejected_payment";

              $top_booked_room_sql = "SELECT rm.room_id, rm.room_name, (CASE WHEN COUNT(tr.room_id) IS NULL THEN 0 ELSE COUNT(tr.room_id) END) AS total_booked_room FROM room AS rm JOIN transaction AS tr ON tr.room_id = rm.room_id WHERE tr.transaction_type_id = 1 AND ((YEAR(tr.booking_start_date) = '".$chosen_year."') OR (YEAR(tr.booking_end_date) = '".$chosen_year."')) GROUP BY rm.room_id ORDER BY total_booked_room DESC LIMIT 5";

              $top_tenant_list_sql = "SELECT us.user_id, us.first_name, us.last_name, us.email, us.phone_number, (CASE WHEN COUNT(inv.invoice_id) IS NULL THEN 0 ELSE COUNT(inv.invoice_id) END) AS total_transaction FROM user AS us JOIN tenant AS tnt ON tnt.user_id = us.user_id JOIN invoice AS inv ON inv.user_id = us.user_id WHERE (YEAR(inv.created_date) = '".$chosen_year."') GROUP BY us.user_id ORDER BY total_transaction DESC LIMIT 5";
            }

            $booking_statistics = $con->query($booking_statistics_sql);
            $booking_statistics = $booking_statistics->fetch_assoc();
            $payment_statistics = $con->query($payment_statistics_sql);
            $payment_statistics = $payment_statistics->fetch_assoc();
            $top_booked_room = $con->query($top_booked_room_sql);
            if($top_booked_room->num_rows > 0){
              while($row = $top_booked_room->fetch_assoc()){
                array_push($top_booked_room_list_array,$row);
              }
            }
            $top_tenant_list = $con->query($top_tenant_list_sql);
            if($top_tenant_list->num_rows > 0){
              while($row = $top_tenant_list->fetch_assoc()){
                array_push($top_tenant_list_array,$row);
              }
            }
            $con->close();
          ?>
          <form method="GET" class="mb-4 p-3" style="border:0.5px solid #c5c3c3;border-radius:1%;">
            <h5>Filter</h5>
            <div class="row mb-2">
                <div class="col-md-6">
                    <select name="month" id="" class="form-control">
                      <option value="all">Choose Month</option>
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
                <div class="col-md-6">
                    <?php
                        $year = date('Y');
                    ?>
                    <select name="year" id="" class="form-control">
                      <!-- <option value="all">Choose Year</option> -->
                      <?php for($k = ($year - 4); $k < ($year + 5); $k++):?>
                          <option value="<?= $k ;?>" <?= !empty($chosen_year) && $chosen_year == $k ? 'selected' : '' ;?>><?= $k ;?></option>
                      <?php endfor;?>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
              <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group me-2" role="group" aria-label="First group">
                      <button type="button" class="btn btn-danger" onclick="window.location.href = 'dashboard'">Reset</button>
                  </div>
                  <div class="btn-group ml-2" role="group" aria-label="Second group">
                      <button type="submit" class="btn btn-primary mykosan-signature-button-color">Search</button>
                  </div>
              </div>
            </div>
          </form>
          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Booking (Pending)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $booking_statistics['total_pending_booking'];?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Booking (Active)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $booking_statistics['total_active_booking'];?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Booking (Expired)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $booking_statistics['total_expired_booking'];?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Booking (Terminated)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $booking_statistics['total_terminated_booking'];?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Payment (Pending)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp '.$currency->convert($payment_statistics['total_pending_payment']);?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Payment (Accepted)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp '.$currency->convert($payment_statistics['total_approved_payment']);?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Payment (Rejected)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp '.$currency->convert($payment_statistics['total_rejected_payment']);?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Content Row -->

          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">5 Most Booked Rooms</h6>
                </div>
                <div class="card-body<?= count($top_booked_room_list_array) == 0 ? 'text-center p-4' : '';?>">
                  <?php if(count($top_booked_room_list_array) == 0):?>
                    <span>No data found</span>
                  <?php else:?>
                  <ul class="list-group">
                    <?php for($k = 0; $k < count($top_booked_room_list_array); $k++):?>
                      <li class="list-group-item"><?= $top_booked_room_list_array[$k]['room_name'] . ' ('.$top_booked_room_list_array[$k]['total_booked_room'].' times)';?></li>
                    <?php endfor;?>
                  </ul>
                  <?php endif;?>
                </div>
              </div>

            </div>

            <div class="col-lg-6 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">5 Most Active Tenants</h6>
                </div>
                <div class="card-body<?= count($top_tenant_list_array) == 0 ? 'text-center p-4' : '';?>">
                  <?php if(count($top_tenant_list_array) == 0):?>
                    <span>No data found</span>
                  <?php else:?>
                  <ul class="list-group">
                    <?php for($k = 0; $k < count($top_tenant_list_array); $k++):?>
                      <li class="list-group-item"><?= $top_tenant_list_array[$k]['first_name'] . ' ' . $top_tenant_list_array[$k]['last_name'] . ' - ' .$top_tenant_list_array[$k]['email'].' ('.$top_tenant_list_array[$k]['total_transaction'].' times)';?></li>
                    <?php endfor;?>
                  </ul>
                  <?php endif;?>
                </div>
              </div>

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
  <?php include "logout_modal.php";?>

  <?php include "templates/js_list.php";?>

</body>

</html>
