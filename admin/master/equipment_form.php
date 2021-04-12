<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = (isset($_GET['id']) ? 'Edit Equipment' : 'Create Equipment')." - Kosan Admin Panel";
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
                <h1 class="h3 mb-0 text-gray-800"><?= isset($_GET['id']) ? 'Edit Equipment' : 'Create Equipment' ;?></h1>
            </div>
            <?php
                $equipment_data = array();
                $room_mapping_array = array();

                if(!empty($_GET['id'])){
                    $equipment_sql = "SELECT eq.* FROM equipment AS eq WHERE eq.equipment_id = ".$_GET['id'];
                    $room_mapping_sql = "SELECT rm.* from room as rm JOIN room_equipment_mapping AS rem ON rem.room_id = rm.room_id WHERE rem.equipment_id = ".$_GET['id'];

                    $equipments = $con->query($equipment_sql);
                    $room_mapping_data = $con->query($room_mapping_sql);

                    if($room_mapping_data->num_rows > 0){
                        while($row = $room_mapping_data->fetch_assoc()) {
                            array_push($room_mapping_array, $row);
                        }
                    }

                    $equipment_data = $equipments->fetch_assoc();
                }
                $con->close();
            ?>
            <div class="data-list">
                <form action="../equipment_validation" method="POST">
                  <?php if(!empty($_GET['id'])):?>
                      <input type="hidden" name="equipment_id" value="<?= $_GET['id'];?>">
                  <?php endif;?>
                  <div class="form-group">
                      <label for="equipment_name" class="col-form-label font-weight-bold">Equipment Name</label>
                      <input class="form-control <?= (!empty($_SESSION['room_name_validation']) ? ('is-invalid') : '') ;?>" name="equipment_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['equipment_name_error']) ? $_SESSION['equipment_name_error'] : $equipment_data['equipment_name']) : (!empty($_SESSION['equipment_name_error']) ? $_SESSION['equipment_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['equipment_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['equipment_name_validation'].'</div>') : '') ;?>
                  </div>
                  <input type="submit" name="submitEquipmentForm" value="Submit" class="btn btn-primary">
                </form>
                <?php if(isset($_GET['id'])):?>
                    <h5 class="mt-4 font-weight-bold">Available in</h5>
                    <?php if(count($room_mapping_array) == 0):?>
                        <span class="font-size:11px">No data found!</span>
                    <?php else:?>
                    <ul class="pl-4">
                        <?php for($k = 0; $k < count($room_mapping_array); $k++):?>
                            <li ><?= 'Room '.$room_mapping_array[$k]['room_name'];?></li>
                        <?php endfor;?>
                    </ul>
                    <?php endif;?>
                <?php endif;?>
                <?php
                  unset($_SESSION['equipment_name_error']);
                  unset($_SESSION['equipment_name_validation']);
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
