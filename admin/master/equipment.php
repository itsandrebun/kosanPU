<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Equipment List - Kosan Admin Panel";
    $inside_folder = 1;
    $equipment_active = 1;
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
            <h1 class="h3 mb-0 text-gray-800">Equipment List</h1>
            <a href="equipment_form" class="d-none d-sm-inline-block btn btn-sm btn-primary mykosan-signature-button-color shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Equipment</a>
          </div>
          <?php
              $equipment_data = array();

              $equipment_sql = "SELECT * FROM equipment ORDER BY equipment_name ASC";

              $equipments = $con->query($equipment_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
              if($equipments->num_rows > 0){
                  while($row = $equipments->fetch_assoc()) {
                      array_push($equipment_data, $row);
                  }
              }
              $con->close();
          ?>
          <div class="data-list">
              <table class="table">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Equipment Name</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php for($r = 0; $r < count($equipment_data); $r++):?>
                      <tr>
                          <td><?= ($r+1) ;?></td>
                          <td><?= $equipment_data[$r]['equipment_name'];?></td>
                          <td><a href="equipment_form?id=<?= $equipment_data[$r]['equipment_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">Edit</a></td>
                      </tr>
                      <?php endfor;?>
                  </tbody>
              </table>
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
