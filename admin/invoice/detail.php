<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Invoice Detail - Kosan Admin Panel";
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
        header('Location:../login');
    }
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "../templates/sidebar.php";?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

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

                if(!empty($_GET['id'])){
                  $payment_history_master_sql = "SELECT inv.*, usr.user_id, usr.first_name, usr.last_name, usr.user_code from invoice AS inv JOIN user AS usr ON usr.user_id = inv.user_id where inv.invoice_id = ".$_GET['id'];
                  $payment_history_sql = "SELECT pys.payment_status_id,pys.payment_status_name, pyh.description, pys.created_date FROM payment_status AS pys JOIN payment_history AS pyh ON pyh.payment_status_id = pys.payment_status_id WHERE pyh.invoice_id = ".$_GET['id'];

                  $payment_history_master = $con->query($payment_history_master_sql);
                  $payment_history = $con->query($payment_history_sql);
                  $payment_history_master_data = $payment_history_master->fetch_assoc();
                  // echo $room_sql;
                  // print_r($rooms['num_rows']);
                  if($payment_history->num_rows > 0){
                      while($row = $payment_history->fetch_assoc()) {
                          array_push($payment_history_data, $row);
                      }
                  }
                }
                $con->close();
            ?>
            <div class="data-list">
                <form action="../submit_evidence" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="user_name" class="col-form-label font-weight-bold">User Name</label>
                        <input type="text" readonly name="user_name" class="form-control" value="<?= $payment_history_master_data['first_name'] . ' ' . $payment_history_master_data['last_name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="user_code" class="col-form-label font-weight-bold">User Code</label>
                        <input type="text" readonly name="user_code" class="form-control" value="<?= $payment_history_master_data['user_code'] ;?>" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="invoice_id" value="<?= $_GET['id'];?>">
                        <label for="invoice_number" class="col-form-label font-weight-bold">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" value="<?= $payment_history_master_data['invoice_number'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment_evidence" class="col-form-label font-weight-bold">Payment Evidence</label>
                        <?php if($payment_history_master_data['payment_status'] < 3):?>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('payment_evidence').click();">Upload</button>
                        <input type="file" name="payment_evidence" class="form-control d-none" id="payment_evidence">
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="payment_date" class="col-form-label font-weight-bold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?= $payment_history_master_data['payment_status'] >= 3 ? ($payment_history_master_data['payment_date'] != null ? date('Y-m-d',strtotime($payment_history_master_data['payment_date'])) : '') : '';?>" <?= $payment_history_master_data['payment_status'] >= 3 ? 'readonly' : '';?>>
                    </div>
                    <input type="submit" name="confirmEvidenceButton" class="btn btn-primary" value="Submit">
                </form>
                <hr>
                <div id="payment_history_div" class="mt-4">
                    <h1 class="h3 mb-0 text-gray-800">Payment History</h1>
                    <table class="table table-striped mt-3">
                      <thead>
                          <tr>
                            <th class="text-center">Payment Status Name</th>
                            <th class="text-center">Payment Status Description</th>
                            <th class="text-center">Payment Status Date</th>
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
