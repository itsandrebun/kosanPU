<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = (isset($_GET['id']) ? 'Edit Room' : 'Create Room')." - Kosan Admin Panel";
    $inside_folder = 1;
    $room_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800"><?= isset($_GET['id']) ? 'Edit Room' : 'Create Room' ;?></h1>
            </div>
            <?php
                $room_data = array();

                if(!empty($_GET['id'])){
                  $room_sql = "SELECT rm.* FROM room AS rm WHERE rm.room_id = ".$_GET['id'];
  
                  $rooms = $con->query($room_sql);
                  $room_data = $rooms->fetch_assoc();
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
                      <input type="hidden" name="room_id" value="<?= $_GET['id'];?>">
                  <?php endif;?>
                  <div class="form-group">
                      <label for="room_name" class="col-form-label font-weight-bold">Room Name</label>
                      <input class="form-control <?= (!empty($_SESSION['room_name_validation']) ? ('is-invalid') : '') ;?>" name="room_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['room_name_error']) ? $_SESSION['room_name_error'] : $room_data['room_name']) : (!empty($_SESSION['room_name_error']) ? $_SESSION['room_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['room_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['room_name_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="room_name" class="col-form-label font-weight-bold">Room Floor</label>
                      <input class="form-control <?= (!empty($_SESSION['room_floor_validation']) ? ('is-invalid') : '') ;?>" name="room_floor" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['room_floor_error']) ? $_SESSION['room_floor_error'] : $room_data['room_floor']) : (!empty($_SESSION['room_floor_error']) ? $_SESSION['room_floor_error'] : '') ;?>">
                      <?= (!empty($_SESSION['room_floor_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['room_floor_validation'].'</div>') : '') ;?>
                  </div>
                  <div class="form-group">
                      <label for="tenant_gender" class="col-form-label font-weight-bold">Gender</label>
                      <div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="male_gender" value="1" <?= (!empty($_GET['id']) && $room_data['gender'] == 1) ? 'checked' : (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 1 ? 'checked' : '') ;?>>
                              <label class="form-check-label" for="male">Male</label>
                          </div>
                          <div class="form-check form-check-inline">

                              <input class="form-check-input <?= !empty($_SESSION['gender_validation']) ? 'is-invalid' : '';?>" type="radio" name="gender" id="female_gender" value="2" <?= (!empty($_GET['id']) && $room_data['gender'] == 2) ? 'checked' : (!empty($_SESSION['gender_error']) && $_SESSION['gender_error'] == 2 ? 'checked' : '') ;?>>
                              <label class="form-check-label" for="female">Female</label>
                          </div>
                          <?= (!empty($_SESSION['gender_validation']) ? ('<div class="invalid-feedback d-block">'.$_SESSION['gender_validation'].'</div>') : '') ;?>
                      </div>
                  </div>
                  <input type="submit" name="submitRoomForm" value="Submit" class="btn btn-primary">
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
