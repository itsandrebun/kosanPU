<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Invoice List - Kosan Admin Panel";
    $inside_folder = 1;
    $invoice_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Invoice List</h1>
            </div>
            <?php
                $invoice_data = array();
                $payment_status_data = array();
                $chosen_month = date("m");
                $chosen_year = date("Y");

                if(!empty($_GET['month'])){
                  $chosen_month = $_GET['month'];
                }

                if(!empty($_GET['year'])){
                  $chosen_year = $_GET['year'];
                }

                $invoice_sql = "SELECT inv.invoice_id, inv.invoice_number, inv.user_id, inv.phone_number, inv.first_name, inv.last_name, inv.total_payment, inv.deposit, inv.due_start_date, inv.due_end_date, inv.confirmed_date, inv.submitted_date, inv.payment_date, pst.payment_status_name FROM invoice AS inv JOIN user AS us ON us.user_id = inv.user_id JOIN payment_status AS pst ON pst.payment_status_id = inv.payment_status WHERE MONTH(inv.created_date) = '".$chosen_month."' AND year(inv.created_date) = '".$chosen_year."'";
                if(!empty($_GET['payment_status'])){
                  $invoice_sql .= " AND inv.payment_status = ".$_GET['payment_status'];
                }
                $invoices = $con->query($invoice_sql);
                $payment_status_sql = "SELECT * FROM payment_status";
                $payment_status = $con->query($payment_status_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($invoices->num_rows > 0){
                    while($row = $invoices->fetch_assoc()) {
                        array_push($invoice_data, $row);
                    }
                }

                if($payment_status->num_rows > 0){
                  while($row = $payment_status->fetch_assoc()) {
                      array_push($payment_status_data, $row);
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
                          <select name="payment_status" class="form-control">
                            <option value="">Choose Payment Status</option>
                            <?php for($b = 0; $b < count($payment_status_data); $b++):?>
                              <option value="<?= $payment_status_data[$b]['payment_status_id'];?>"<?= (!empty($_GET['payment_status']) && $_GET['payment_status'] == $payment_status_data[$b]['payment_status_id']) ? ' selected' : '';?>><?= $payment_status_data[$b]['payment_status_name'];?></option>
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
                            <th>Number</th>
                            <th>User Name</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Total Payment</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($invoice_data) == 0):?>
                        <tr>
                            <td colspan="6" class="text-center">No data found!</td>
                        </tr>
                        <?php else:?>
                          <?php for($k = 0; $k < count($invoice_data); $k++):?>
                            <tr>
                                <td><?= $invoice_data[$k]['invoice_number'];?></td>
                                <td><?= $invoice_data[$k]['first_name'].' '.$invoice_data[$k]['last_name'];?></td>
                                <td><?= $invoice_data[$k]['payment_status_name'];?></td>
                                <td><?= $invoice_data[$k]['payment_date'] == null ? "-" : date("Y-m-d H:i:s",strtotime($invoice_data[$k]['payment_date']));?></td>
                                <td><?= $invoice_data[$k]['total_payment'];?></td>
                                <td><a href="detail?id=<?= $invoice_data[$k]['invoice_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">View</a></td>
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
