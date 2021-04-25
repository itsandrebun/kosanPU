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
            // $tenant_data = array();

            // $tenant_sql = "SELECT us.* FROM user AS us JOIN tenant AS tn ON tn.user_id = us.user_id";

            // $tenants = $con->query($tenant_sql);

            //     // echo $room_sql;
            //     // print_r($rooms['num_rows']);
            // if($tenants->num_rows > 0){
            //     while($row = $tenants->fetch_assoc()) {
            //         array_push($tenant_data, $row);
            //     }
            // }
            // $con->close();
            $chosen_month = date('m');
            if(isset($_GET['month'])){
              $chosen_month = $_GET['month'];
            }
            $chosen_year = date('Y');
            if(isset($_GET['year'])){
              $chosen_year = $_GET['year'];
            }
            $booking_statistics_sql = "SELECT (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP < tr.booking_start_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_pending_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP BETWEEN tr.booking_start_date AND tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_active_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP > tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_expired_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND terminated_date IS NOT NULL AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_terminated_booking";

            $payment_statistics_sql = "SELECT (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status <= 2 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_pending_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 3 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_approved_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 4 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_rejected_payment";

            if($chosen_month == "all"){
              $booking_statistics_sql = "SELECT (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP < tr.booking_start_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_pending_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP BETWEEN tr.booking_start_date AND tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_active_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND CURRENT_TIMESTAMP > tr.booking_end_date AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_expired_booking, (SELECT (CASE WHEN COUNT(tr.transaction_id) IS NULL THEN 0 ELSE COUNT(tr.transaction_id) END) FROM transaction AS tr WHERE tr.transaction_type_id = 1 AND terminated_date IS NOT NULL AND ((MONTH(tr.booking_start_date) = '".$chosen_month."' AND YEAR(tr.booking_start_date) = '".$chosen_year."') OR (MONTH(tr.booking_end_date) = '".$chosen_month."' AND YEAR(tr.booking_end_date) = '".$chosen_year."'))) AS total_terminated_booking";

              $payment_statistics_sql = "SELECT (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status <= 2 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_pending_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 3 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_approved_payment, (SELECT (CASE WHEN SUM(inv.total_payment) IS NULL THEN 0 ELSE SUM(inv.total_payment) END) FROM invoice AS inv WHERE inv.payment_status = 4 AND (MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."')) AS total_rejected_payment";
            }

            $booking_statistics = $con->query($booking_statistics_sql);
            $booking_statistics = $booking_statistics->fetch_assoc();
            $payment_statistics = $con->query($payment_statistics_sql);
            $payment_statistics = $payment_statistics->fetch_assoc();
          ?>
          <form method="GET" class="mb-4 p-3" style="border:0.5px solid #c5c3c3;border-radius:1%;">
            <h5>Filter</h5>
            <div class="row mb-2">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                  <h6 class="m-0 font-weight-bold text-primary">Most Booked Rooms</h6>
                </div>
                <div class="card-body">
                  <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">tenant Database <span class="float-right">60%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
                  <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>

              <!-- Color System -->
              <div class="row">
                <div class="col-lg-6 mb-4">
                  <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                      Primary
                      <div class="text-white-50 small">#4e73df</div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-4">
                  <div class="card bg-success text-white shadow">
                    <div class="card-body">
                      Success
                      <div class="text-white-50 small">#1cc88a</div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-4">
                  <div class="card bg-info text-white shadow">
                    <div class="card-body">
                      Info
                      <div class="text-white-50 small">#36b9cc</div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-4">
                  <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                      Warning
                      <div class="text-white-50 small">#f6c23e</div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-4">
                  <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                      Danger
                      <div class="text-white-50 small">#e74a3b</div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-4">
                  <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                      Secondary
                      <div class="text-white-50 small">#858796</div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-lg-6 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Most Active Tenants</h6>
                </div>
                <div class="card-body">
                  <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="../assets/img/undraw_posting_photo.svg" alt="">
                  </div>
                  <p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
                  <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
                </div>
              </div>

              <!-- Approach -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                </div>
                <div class="card-body">
                  <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce CSS bloat and poor page performance. Custom CSS classes are used to create custom components and custom utility classes.</p>
                  <p class="mb-0">Before working with this theme, you should become familiar with the Bootstrap framework, especially the utility classes.</p>
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
