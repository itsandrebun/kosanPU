<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Internal Parameter - Kosan Admin Panel";
    $inside_folder = 1;
    $internal_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Internal Settings</h1>
            </div>
            <?php
                $internal_settings_data = array();
                
                $internal_settings_sql = "SELECT * FROM internal_parameter";
                $internal_settings = $con->query($internal_settings_sql);
                
                if($internal_settings->num_rows > 0){
                    while($row = $internal_settings->fetch_assoc()) {
                        array_push($internal_settings_data, $row);
                    }
                }
                $con->close();
            ?>
            <div class="data-list">
                <form action="../internal_settings_validation" method="POST">
                    <?php if(count($internal_settings_data) > 0):?>
                        <input type="hidden" name="internal_data_existence" value="1">
                    <?php endif;?>
                    <div class="form-group">
                        <label for="company_name" class="col-form-label font-weight-bold">Company Name</label>
                        <input class="form-control <?= (!empty($_SESSION['company_name_validation']) ? ('is-invalid') : '') ;?>" name="company_name" value="<?= empty($internal_settings) ? '' : $internal_settings_data[2]['parameter_value'];?>">
                        <?= (!empty($_SESSION['company_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['company_name_validation'].'</div>') : '') ;?>
                    </div>
                    <div class="form-group">
                        <label for="company_address" class="col-form-label font-weight-bold">Company Address</label>
                        <textarea class="form-control <?= (!empty($_SESSION['company_address_validation']) ? ('is-invalid') : '') ;?>" name="company_address" style="resize:none"><?= empty($internal_settings) ? '' : $internal_settings_data[3]['parameter_value'];?></textarea>
                        <?= (!empty($_SESSION['company_address_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['company_address_validation'].'</div>') : '') ;?>
                    </div>
                    <div class="form-group">
                        <label for="rent_cost" class="col-form-label font-weight-bold">Rent Cost Per Room</label>
                        <input class="form-control <?= (!empty($_SESSION['rent_cost_validation']) ? ('is-invalid') : '') ;?>" name="rent_cost" value="<?= empty($internal_settings) ? '' : $internal_settings_data[0]['parameter_value'];?>">
                        <?= (!empty($_SESSION['rent_cost_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['rent_cost_validation'].'</div>') : '') ;?>
                    </div>
                    <div class="form-group">
                        <label for="deposit" class="col-form-label font-weight-bold">Deposit</label>
                        <input class="form-control <?= (!empty($_SESSION['deposit_validation']) ? ('is-invalid') : '') ;?>" name="deposit" value="<?= empty($internal_settings) ? '' : $internal_settings_data[1]['parameter_value'];?>">
                        <?= (!empty($_SESSION['deposit_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['deposit_validation'].'</div>') : '') ;?>
                    </div>
                    <input type="submit" name="submitInternalForm" value="Submit" class="btn btn-primary mykosan-signature-button-color">
                </form>
                <?php
                    unset($_SESSION['company_name_error']);
                    unset($_SESSION['company_name_validation']);
                    unset($_SESSION['company_address_error']);
                    unset($_SESSION['company_address_validation']);
                    unset($_SESSION['rent_cost_error']);
                    unset($_SESSION['rent_cost_validation']);
                    unset($_SESSION['deposit_error']);
                    unset($_SESSION['deposit_validation']);
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
