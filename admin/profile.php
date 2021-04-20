<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Profile - Kosan Admin Panel";
    include "templates/header.php";
    session_start();
    $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    if(isset($_POST['logout_flag'])){
        session_destroy();
        header('Location:login');
    }
    if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] != null)){
        session_destroy();
        header('Location:login');
    }
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "templates/sidebar.php";?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column mykosan-content-wrapper">

      <!-- Main Content -->
      <div id="content">

        <?php include "templates/navbar.php";?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Profile</h1>
            </div>
            <?php
                $admin_data = $logged_in_user;
            ?>
            <div class="data-list">
                <form action="profile_validation" method="POST">
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="tenant_first_name" class="col-form-label font-weight-bold">First Name</label>
                          <input class="form-control <?= (!empty($_SESSION['first_name_validation']) ? ('is-invalid') : '') ;?>" name="first_name" value="<?= (!empty($_SESSION['first_name_error']) ? $_SESSION['first_name_error'] : $admin_data['first_name']) ;?>">
                          <?= (!empty($_SESSION['first_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['first_name_validation'].'</div>') : '') ;?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="tenant_last_name" class="col-form-label font-weight-bold">Last Name</label>
                          <input class="form-control <?= (!empty($_SESSION['last_name_validation']) ? ('is-invalid') : '') ;?>" name="last_name" value="<?= (!empty($_SESSION['last_name_error']) ? $_SESSION['last_name_error'] : $admin_data['last_name']) ;?>">
                          <?= (!empty($_SESSION['last_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['last_name_validation'].'</div>') : '') ;?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="tenant_email" class="col-form-label font-weight-bold">Email</label>
                      <input class="form-control <?= (!empty($_SESSION['email_validation']) ? ('is-invalid') : '') ;?>" name="email" type="text" value="<?= (!empty($_SESSION['email_error']) ? $_SESSION['email_error'] : $admin_data['email']) ;?>">
                      <?= (!empty($_SESSION['email_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['email_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_phone_number" class="col-form-label font-weight-bold">Phone Number</label>
                      <input class="form-control <?= (!empty($_SESSION['phone_number_validation']) ? ('is-invalid') : '') ;?>" name="phone_number" value="<?= (!empty($_SESSION['phone_number_error']) ? $_SESSION['phone_number_error']: $admin_data['phone_number']) ;?>">
                      <?= (!empty($_SESSION['phone_number_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['phone_number_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_dob" class="col-form-label font-weight-bold">Date of Birth</label>
                      <input type="date" name="dob" class="form-control <?= (!empty($_SESSION['date_of_birth_validation']) ? ('is-invalid') : '') ;?>" value="<?= (!empty($_SESSION['user_dob_error']) ? $_SESSION['user_dob_error']: $admin_data['dob']) ;?>">
                      <?= (!empty($_SESSION['date_of_birth_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['date_of_birth_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_gender" class="col-form-label font-weight-bold">Gender</label>
                      <div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="male_gender" value="1" <?= (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 1 ? 'checked' : (($admin_data['gender'] == 1) ? 'checked' : '')) ;?>>
                              <label class="form-check-label" for="male">Male</label>
                          </div>
                          <div class="form-check form-check-inline">

                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="female_gender" value="2"  <?= (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 2 ? 'checked' : (($admin_data['gender'] == 2) ? 'checked' : '')) ;?>>
                              <label class="form-check-label" for="female">Female</label>
                          </div>
                          <?= (!empty($_SESSION['gender_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['gender_validation'].'</div>') : '') ;?>
                      </div>
                  </div>
                  <input type="submit" name="submitProfileForm" value="Submit" class="btn btn-primary mykosan-signature-button-color">
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

  <?php include "logout_modal.php";?>

  <?php include "templates/js_list.php";?>

</body>

</html>
