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
                $admin_data = array();

                if(!empty($_GET['id'])){
                  $admin_sql = "SELECT us.* FROM user AS us WHERE us.user_id = ".$_GET['id'];
  
                  $admins = $con->query($admin_sql);
                  $admin_data = $admins->fetch_assoc();
                  // echo $room_sql;
                  // print_r($rooms['num_rows']);
                  // if($admins->num_rows > 0){
                  //     while($row = $admins->fetch_assoc()) {
                  //         array_push($admin_data, $row);
                  //     }
                  // }
                }
                $con->close();
            ?>
            <div class="data-list">
                <form action="../submit_evidence" method="POST">
                    <div class="form-group">
                        <label for="user_name" class="col-form-label font-weight-bold">User Name</label>
                        <input type="text" readonly name="user_name" class="form-control" name="invoice_number" value="">
                    </div>
                    <div class="form-group">
                        <label for="user_code" class="col-form-label font-weight-bold">User Code</label>
                        <input type="text" readonly name="user_code" class="form-control" name="invoice_number" value="">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="invoice_id" value="<?= $_GET['id'];?>">
                        <label for="invoice_number" class="col-form-label font-weight-bold">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" name="invoice_number" value="">
                    </div>
                    <div class="form-group">
                        <label for="payment_evidence" class="col-form-label font-weight-bold">Payment Evidence</label>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('payment_evidence').click();">Upload</button>
                        <input type="file" name="payment_evidence" class="form-control d-none" id="payment_evidence">
                    </div>
                    <div class="form-group">
                        <label for="payment_date" class="col-form-label font-weight-bold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="">
                    </div>
                    <input type="submit" name="confirmEvidenceButton" class="btn btn-primary" value="Submit">
                </form>
                
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
