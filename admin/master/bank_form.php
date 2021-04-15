<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = (isset($_GET['id']) ? 'Edit Bank' : 'Create Bank')." - Kosan Admin Panel";
    $inside_folder = 1;
    $bank_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800"><?= isset($_GET['id']) ? 'Edit Bank' : 'Create Bank' ;?></h1>
            </div>
            <?php
                $admin_data = array();

                if(!empty($_GET['id'])){
                  $bank_sql = "SELECT bn.* FROM banks AS bn WHERE bn.bank_id = ".$_GET['id'];
  
                  $banks = $con->query($bank_sql);
                  $bank_data = $banks->fetch_assoc();
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
                <form action="../bank_validation" method="POST">
                  <?php if(!empty($_GET['id'])):?>
                      <input type="hidden" name="bank_id" value="<?= $_GET['id'];?>">
                  <?php endif;?>
                  <div class="form-group">
                      <label for="bank_name" class="col-form-label font-weight-bold">Bank Name</label>
                      <input class="form-control <?= (!empty($_SESSION['bank_name_validation']) ? ('is-invalid') : '') ;?>" name="bank_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['bank_name_error']) ? $_SESSION['bank_name_error'] : $bank_data['bank_name']) : (!empty($_SESSION['bank_name_error']) ? $_SESSION['bank_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['bank_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['bank_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="bank_account_number" class="col-form-label font-weight-bold">Account Number</label>
                      <input class="form-control <?= (!empty($_SESSION['bank_account_number_validation']) ? ('is-invalid') : '') ;?>" name="bank_account_number" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['bank_account_number_error']) ? $_SESSION['bank_account_number_error'] : $bank_data['bank_account_number']) : (!empty($_SESSION['bank_account_number_error']) ? $_SESSION['bank_account_number_error'] : '') ;?>">
                      <?= (!empty($_SESSION['bank_account_number_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['bank_account_number_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="owner_name" class="col-form-label font-weight-bold">Owner Name</label>
                      <input class="form-control <?= (!empty($_SESSION['owner_name_validation']) ? ('is-invalid') : '') ;?>" name="owner_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['owner_name_error']) ? $_SESSION['owner_name_error'] : $bank_data['owner_name']) : (!empty($_SESSION['owner_name_error']) ? $_SESSION['owner_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['owner_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['owner_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="bank_description" class="col-form-label font-weight-bold">Description</label>
                      <textarea class="form-control <?= (!empty($_SESSION['description_validation']) ? ('is-invalid') : '') ;?>" name="description" style="resize:none"><?= (!empty($_GET['id'])) ? (!empty($_SESSION['description_error']) ? $_SESSION['description_error'] : $bank_data['bank_description']) : (!empty($_SESSION['description_error']) ? $_SESSION['description_error'] : '') ;?></textarea>
                      <?= (!empty($_SESSION['description_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['description_validation'].'</div>') : '') ;?>
                  </div>
                  <input type="submit" name="submitBankForm" value="Submit" class="btn btn-primary mykosan-signature-button-color">
                </form>
                <?php
                  unset($_SESSION['bank_name_error']);
                  unset($_SESSION['bank_account_number_error']);
                  unset($_SESSION['description_error']);
                  unset($_SESSION['owner_name_error']);
                  unset($_SESSION['bank_name_validation']);
                  unset($_SESSION['bank_account_number_validation']);
                  unset($_SESSION['description_validation']);
                  unset($_SESSION['owner_name_validation']);
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
