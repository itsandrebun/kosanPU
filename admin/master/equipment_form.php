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
        session_destroy();
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
                    $room_mapping_sql = "SELECT rm.*,(CASE WHEN rm.room_id IN (SELECT rem.room_id FROM room_equipment_mapping AS rem WHERE rem.equipment_id = ".$_GET['id'].") THEN 1 ELSE 0 END) AS room_availability from room as rm";

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
                      <input class="form-control <?= (!empty($_SESSION['equipment_name_validation']) ? ('is-invalid') : '') ;?>" name="equipment_name" value="<?= (!empty($_GET['id'])) ? (!empty($_SESSION['equipment_name_error']) ? $_SESSION['equipment_name_error'] : $equipment_data['equipment_name']) : (!empty($_SESSION['equipment_name_error']) ? $_SESSION['equipment_name_error'] : '') ;?>">
                      <?= (!empty($_SESSION['equipment_name_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['equipment_name_validation'].'</div>') : '') ;?>
                  </div>
                  <input type="submit" name="submitEquipmentForm" value="Submit" class="btn btn-primary">
                </form>
                <?php if(isset($_GET['id'])):?>
                    <hr>
                    <!-- <h5 class="mt-4 font-weight-bold">Available in</h5> -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-3">
                        <h4 class="h4 mb-0 text-gray-800">Dashboard</h4>
                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#equipmentAvailabilityPopup"><i class="fas fa-edit fa-sm text-white-50"></i> Edit Available Rooms</button>
                    </div>
                    <?php if(count($room_mapping_array) == 0):?>
                        <span class="font-size:11px">No data found!</span>
                    <?php else:?>
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Room Name</th>
                                <th>Available Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($k = 0; $k < count($room_mapping_array); $k++):?>
                                <tr class="text-center<?= $room_mapping_array[$k]['room_availability'] == 0 ? ' table-danger' : ' table-success';?>">
                                    <td><?= 'Room '.$room_mapping_array[$k]['room_name'];?></td>
                                    <td><?= $room_mapping_array[$k]['room_availability'] == 1 ? 'Yes' : 'No';?></td>
                                </tr>
                            <?php endfor;?>
                        </tbody>
                    </table>
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
    
    <?php if(isset($_GET['id'])):?>
    <!-- Modal -->
    <div class="modal fade" id="equipmentAvailabilityPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby=equipmentAvailabilityPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=equipmentAvailabilityPopupLabel">Room Availability</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../room_availability_form" id="mappingWithRoomForm">
                        <input type="hidden" name="equipment_id" value="<?= $_GET['id'];?>">
                        <input type="hidden" name="submitRoomAvailability" value="1">
                        <ul class="list-group">
                            <?php for($k = 0; $k < count($room_mapping_array); $k++):?>
                                <li class="list-group-item"><input type="checkbox" class="mr-2" name="room_availability_status[]" value="<?= $room_mapping_array[$k]['room_id'];?>"<?= $room_mapping_array[$k]['room_availability'] == 0 ? ' ' : ' checked';?>><?= 'Room '.$room_mapping_array[$k]['room_name'];?></li>
                            <?php endfor;?>
                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('mappingWithRoomForm').submit();">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>

  <?php include "../templates/js_list.php";?>

</body>

</html>
