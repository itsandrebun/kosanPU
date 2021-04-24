<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Tenant Detail - Kosan Admin Panel";
    $inside_folder = 1;
    $tenant_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Tenant Detail</h1>
            </div>
            <?php
                $tenant_data = array();
                $invoice_array = array();
                $chosen_month = date("m");
                $chosen_year = date("Y");
                
                if(!empty($_GET['month'])){
                  $chosen_month = $_GET['month'];
                }

                if(!empty($_GET['year'])){
                  $chosen_year = $_GET['year'];
                }

                $tenant_sql = "SELECT us.* FROM user AS us JOIN tenant AS tn ON tn.user_id = us.user_id WHERE us.user_id = ".$_GET['id'];
                
                $invoice_sql = "SELECT DISTINCT inv.*, COUNT(tr.transaction_id) AS total_transaction, pys.payment_status_name, pys.payment_status_description from transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE tr.user_id = ".$_GET['id']." AND MONTH(inv.created_date) = '".$chosen_month."' AND YEAR(inv.created_date) = '".$chosen_year."' GROUP BY tr.invoice_id";

                if($chosen_month == "all"){
                  $invoice_sql = "SELECT DISTINCT inv.*, COUNT(tr.transaction_id) AS total_transaction, pys.payment_status_name, pys.payment_status_description from transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE tr.user_id = ".$_GET['id']." AND YEAR(inv.created_date) = '".$chosen_year."' GROUP BY tr.invoice_id";

                  if($chosen_year == "all"){
                    $invoice_sql = "SELECT DISTINCT inv.*, COUNT(tr.transaction_id) AS total_transaction, pys.payment_status_name, pys.payment_status_description from transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE tr.user_id = ".$_GET['id']." GROUP BY tr.invoice_id";
                  }
                }elseif($chosen_year == "all"){
                  $invoice_sql = "SELECT DISTINCT inv.*, COUNT(tr.transaction_id) AS total_transaction, pys.payment_status_name, pys.payment_status_description from transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE tr.user_id = ".$_GET['id']." AND MONTH(inv.created_date) = '".$chosen_month."' GROUP BY tr.invoice_id";
                  if($chosen_month == "all"){
                    $invoice_sql = "SELECT DISTINCT inv.*, COUNT(tr.transaction_id) AS total_transaction, pys.payment_status_name, pys.payment_status_description from transaction AS tr JOIN invoice AS inv ON inv.invoice_id = tr.invoice_id JOIN payment_status AS pys ON pys.payment_status_id = inv.payment_status WHERE tr.user_id = ".$_GET['id']." GROUP BY tr.invoice_id";
                  }
                }

                $tenants = $con->query($tenant_sql);
                $tenant_data = $tenants->fetch_assoc();
                $invoice_data = $con->query($invoice_sql);

                if($invoice_data->num_rows > 0){
                    while($row = $invoice_data->fetch_assoc()) {
                        array_push($invoice_array, $row);
                    }
                }

                $con->close();

                include "../../Helpers/Currency.php";
                $currency = new Currency();
            ?>
            <div class="data-list">
                <div class="form-group">
                    <label for="tenant_code" class="col-form-label font-weight-bold">Code</label>
                    <input class="form-control-plaintext" value="<?= $tenant_data['user_code'];?>">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="tenant_first_name" class="col-form-label font-weight-bold">First Name</label>
                        <input class="form-control-plaintext" value="<?= $tenant_data['first_name'];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="tenant_last_name" class="col-form-label font-weight-bold">Last Name</label>
                        <input class="form-control-plaintext" value="<?= $tenant_data['last_name'];?>">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label for="tenant_email" class="col-form-label font-weight-bold">Email</label>
                    <input class="form-control-plaintext" value="<?= $tenant_data['email'];?>">
                </div>
                <div class="form-group">
                    <label for="tenant_phone_number" class="col-form-label font-weight-bold">Phone Number</label>
                    <input class="form-control-plaintext" value="<?= $tenant_data['phone_number'];?>">
                </div>
                <div class="form-group">
                    <label for="tenant_gender" class="col-form-label font-weight-bold">Gender</label>
                    <input class="form-control-plaintext" value="<?= $tenant_data['gender'] == 1 ? 'Male' : 'Female' ;?>">
                </div>
                <hr>
                <h3>Invoice</h3>
                <form method="GET" class="mb-2 p-3" style="border:0.5px solid #c5c3c3;border-radius:1%;">
                  <h5>Filter</h5>
                  <input type="hidden" name="id" value="<?= $_GET['id'];?>">
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
                          <option value="all">Choose Year</option>
                          <?php for($k = ($year - 4); $k < ($year + 5); $k++):?>
                              <option value="<?= $k ;?>" <?= !empty($chosen_year) && $chosen_year == $k ? 'selected' : '' ;?>><?= $k ;?></option>
                          <?php endfor;?>
                        </select>
                    
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group me-2" role="group" aria-label="First group">
                            <button type="button" class="btn btn-danger" onclick="window.location.href = 'tenant_detail?id=<?= $_GET['id'];?>'">Reset</button>
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
                            <th>Payment Status</th>
                            <th>Total Transaction</th>
                            <th>Total Payment</th>
                            <th>Payment Date</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($invoice_array) == 0):?>
                          <tr>
                            <td colspan="4" style="font-size:13px;text-align:center">No data found!</td>
                          </tr>
                        <?php else:?>
                          <?php for($k = 0; $k < count($invoice_array); $k++):?>
                            <tr>
                                <td><?= $invoice_array[$k]['invoice_number'];?></td>
                                <td><?= $invoice_array[$k]['payment_status_name'];?></td>
                                <td class="text-center"><?= $invoice_array[$k]['total_transaction'];?></td>
                                <td class="text-center"><?= $currency->convert($invoice_array[$k]['total_payment']);?></td>
                                <td class="text-center"><?= $invoice_array[$k]['payment_date'] == null ? '-' : date("Y-m-d H:i:s",strtotime($invoice_array[$k]['payment_date'])) ;?></td>
                                <td><?= date("F d, Y",strtotime($invoice_array[$k]['created_date']));?></td>
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
