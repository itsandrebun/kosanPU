<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = (isset($_GET['id']) ? 'Edit Admin' : 'Create Admin')." - Kosan Admin Panel";
    $inside_folder = 1;
    $user_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800"><?= isset($_GET['id']) ? 'Edit Admin' : 'Create Admin' ;?></h1>
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
                <form action="../admin_validation" method="POST">
                  <?php if(!empty($_GET['id'])):?>
                      <input type="hidden" name="user_id" value="<?= $_GET['id'];?>">
                      <div class="form-group">
                          <label for="tenant_code" class="col-form-label font-weight-bold">Code</label>
                          <input readonly class="form-control" value="<?= (!empty($_GET['id'])) ? $admin_data['user_code'] : '' ;?>" >
                      </div>
                  <?php endif;?>
                  <div class="form-group">
                      <label for="tenant_first_name" class="col-form-label font-weight-bold">First Name</label>
                      <input class="form-control <?= (!empty($_SESSION['first_name_validation']) ? ('is-invalid') : '') ;?>" name="first_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['first_name_error']) ? $_SESSION['first_name_error'] : $admin_data['first_name']) : (!empty($_SESSION['first_name_error']) ? $_SESSION['first_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['first_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['first_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_last_name" class="col-form-label font-weight-bold">Last Name</label>
                      <input class="form-control <?= (!empty($_SESSION['last_name_validation']) ? ('is-invalid') : '') ;?>" name="last_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['last_name_error']) ? $_SESSION['last_name_error'] : $admin_data['last_name']) : (!empty($_SESSION['last_name_error']) ? $_SESSION['last_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['last_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['last_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_email" class="col-form-label font-weight-bold">Email</label>
                      <input class="form-control <?= (!empty($_SESSION['email_validation']) ? ('is-invalid') : '') ;?>" name="email" type="text" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['email_error']) ? $_SESSION['email_error'] : $admin_data['email']) : (!empty($_SESSION['email_error']) ? $_SESSION['email_error'] : '') ;?>">
                      <?= (!empty($_SESSION['email_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['email_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_phone_number" class="col-form-label font-weight-bold">Phone Number</label>
                      <input class="form-control <?= (!empty($_SESSION['phone_number_validation']) ? ('is-invalid') : '') ;?>" name="phone_number" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['phone_number_error']) ? $_SESSION['phone_number_error']: $admin_data['phone_number']) : (!empty($_SESSION['phone_number_error']) ? $_SESSION['phone_number_error']: '') ;?>">
                      <?= (!empty($_SESSION['phone_number_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['phone_number_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_dob" class="col-form-label font-weight-bold">Date of Birth</label>
                      <input type="date" name="dob" class="form-control <?= (!empty($_SESSION['date_of_birth_validation']) ? ('is-invalid') : '') ;?>" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['user_dob_error']) ? $_SESSION['user_dob_error']: $admin_data['dob']) : (!empty($_SESSION['user_dob_error']) ? $_SESSION['user_dob_error']: '') ;?>">
                      <?= (!empty($_SESSION['date_of_birth_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['date_of_birth_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_gender" class="col-form-label font-weight-bold">Gender</label>
                      <div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="male_gender" value="1" <?= (!empty($_GET['id']) && $admin_data['gender'] == 1) ? 'checked' : (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 1 ? 'checked' : '') ;?>>
                              <label class="form-check-label" for="male">Male</label>
                          </div>
                          <div class="form-check form-check-inline">

                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="female_gender" value="2" <?= (!empty($_GET['id']) && $admin_data['gender'] == 2) ? 'checked' : (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 2 ? 'checked' : '') ;?>>
                              <label class="form-check-label" for="female">Female</label>
                          </div>
                          <?= (!empty($_SESSION['gender_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['gender_validation'].'</div>') : '') ;?>
                      </div>
                  </div>
                  <input type="submit" name="submitUserForm" value="Submit" class="btn btn-primary">
                </form>
                <?php
                  unset($_SESSION['first_name_error']);
                  unset($_SESSION['last_name_error']);
                  unset($_SESSION['email_error']);
                  unset($_SESSION['phone_number_error']);
                  unset($_SESSION['user_dob_error']);
                  unset($_SESSION['gender_error']);
                  unset($_SESSION['first_name_validation']);
                  unset($_SESSION['last_name_validation']);
                  unset($_SESSION['email_validation']);
                  unset($_SESSION['phone_number_validation']);
                  unset($_SESSION['date_of_birth_validation']);
                  unset($_SESSION['gender_validation']);
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
