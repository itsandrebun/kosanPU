<!DOCTYPE html>
<html lang="en">
<head>
    <?php
      $page_title = "Register Page";
      include "templates/header.php";
    ?>
</head>
<body>
    <?php
      $register_navbar = "active";
      include "templates/navbar.php";
    ?>
    <?php if(!empty($_SESSION['success_registration'])):?>
    <div class="alert alert-success alert-dismissible fade show position-absolute w-100 auth_alert" role="alert" style="top:0%;left:0%">
      <strong><?= $_SESSION['success_registration'];?></strong>
      <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <?php endif;?>
    <div class="container register-container d-flex justify-content-center align-items-center">
      <div class="row">
        <div class="col-md-6 register-form-1">
            <div>
              <h3>Register Form</h3>
              <form action="register_validation.php" id="register-form" class="mt-4" method="post">
                  <div class="form-group">
                    <input type="text" name="firstName" class="form-control <?= (!empty($_SESSION['first_name_validation']) ? ('is-invalid') : '');?>" placeholder="First Name" id="firstName" <?= (!empty($_SESSION['user_first_name']) ? ('value='.$_SESSION['user_first_name']) : '') ;?> />
                    <?= (!empty($_SESSION['first_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['first_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="text" name="lastName" class="form-control <?= (!empty($_SESSION['last_name_validation']) ? ('is-invalid') : '');?>" placeholder="Last Name" id="lastName" <?= (!empty($_SESSION['user_last_name']) ? ('value='.$_SESSION['user_last_name']) : '') ;?> />
                    <?= (!empty($_SESSION['last_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['last_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control <?= (!empty($_SESSION['email_validation']) ? ('is-invalid') : '');?>" placeholder="Email*" name="email" id="email"  <?= (!empty($_SESSION['user_email']) ? ('value='.$_SESSION['user_email']) : '') ;?> />
                    <?= (!empty($_SESSION['email_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['email_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control <?= (!empty($_SESSION['phonenumber_validation']) ? ('is-invalid') : '');?>" placeholder="Phone Number*" name="phoneNumber" id="phoneNumber"  <?= (!empty($_SESSION['user_phonenumber']) ? ('value='.$_SESSION['user_phonenumber']) : '') ;?> />
                    <?= (!empty($_SESSION['phonenumber_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['phonenumber_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="date" class="form-control <?= (!empty($_SESSION['date_of_birth_validation']) ? ('is-invalid') : '');?>" name="date_of_birth" id="date_of_birth" <?= (!empty($_SESSION['user_dob']) ? ('value='.$_SESSION['user_dob']) : '') ;?> />
                    <?= (!empty($_SESSION['date_of_birth_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['date_of_birth_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control <?= (!empty($_SESSION['password_error']) ? ('is-invalid') : '');?>" placeholder="Password*" name="password" id="password" value="" />
                    <?= (!empty($_SESSION['password_error']) ? ('<div class="invalid-feedback">'.$_SESSION['password_error'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control <?= (!empty($_SESSION['confirm_password_error']) ? ('is-invalid') : '');?>" placeholder="Confirm Password*" name="confirm_password" id="confirm_password" value="" />
                    <?= (!empty($_SESSION['confirm_password_error']) ? ('<div class="invalid-feedback">'.$_SESSION['confirm_password_error'].'</div>') : '') ;?>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input <?= (!empty($_SESSION['gender_validation']) ? ('is-invalid') : '');?>" type="radio" name="gender" id="male_gender" value="1" <?= (!empty($_SESSION['user_gender']) && $_SESSION['user_gender'] == 1 ? ('checked') : '') ;?>>
                    <label class="form-check-label" for="flexRadioDefault1">
                      Male
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input <?= (!empty($_SESSION['gender_validation']) ? ('is-invalid') : '');?>" type="radio" name="gender" id="female_gender" value="2" <?= (!empty($_SESSION['user_gender']) && $_SESSION['user_gender'] == 2 ? ('checked') : '') ;?>>
                    <label class="form-check-label" for="flexRadioDefault2">
                      Female
                    </label>
                  </div>
                  <?= (!empty($_SESSION['gender_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['gender_validation'].'</div>') : '') ;?>
                  <div class="form-check mt-3">
                    <input class="form-check-input <?= (!empty($_SESSION['checkbox_validation']) ? ('is-invalid') : '');?>" type="checkbox" value="true" name="agree" <?= (!empty($_SESSION['user_checkbox']) ? ('checked') : '') ;?>>
                    <label class="form-check-label privacy-label" for="flexCheckChecked">
                        I agree to accept our <a class="redirect-to-privacy-popup" data-bs-toggle="modal" data-bs-target="#privacyPolicyPopup">Privacy Policy</a>. Terms of Service and notification settings 
                    </label>
                  </div>
                  <?= (!empty($_SESSION['checkbox_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['checkbox_validation'].'</div>') : '') ;?>
                  <div class="form-group">
                    <input type="submit" name="submitRegister" class="btnSubmit btn btn-primary w-100" value="Register" />
                  </div>  
              </form>
            </div>
        </div>
        <div class="col-md-6 register-form-2 d-flex justify-content-center align-items-center">
            <div class="login-logo">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
            </div>
          </div>
      </div>
    </div>
    <?php
      session_destroy();
    ?>
    <!-- Vertically centered scrollable modal -->
    <div class="modal fade" id="privacyPolicyPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
      </div>
    </div>
    <?php include "templates/js_list.php";?>
    <script>
      $(".auth_alert").fadeTo(2000, 500).slideUp(500, function(){
          $(".auth_alert").slideUp(500);
      });
    </script>
</body>
</html>