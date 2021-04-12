<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Room List - Kosan Admin Panel";
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
            <h1 class="h3 mb-0 text-gray-800">Room List</h1>
            <a href="room_form" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Room</a>
          </div>
          
          <?php
              $room_data = array();

              $room_sql = "SELECT * FROM room ORDER BY room_floor ASC";

              $rooms = $con->query($room_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
              if($rooms->num_rows > 0){
                  while($row = $rooms->fetch_assoc()) {
                      array_push($room_data, $row);
                  }
              }
              $con->close();
          ?>

          <div class="data-list">
              <table class="table">
                  <thead>
                      <tr>
                          <th class="text-center">Room Name</th>
                          <th class="text-center">Room Floor</th>
                          <th class="text-center">Gender</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if(count($room_data) == 0):?>
                          <tr>
                            <td colspan="4" style="text-align:center">No data found!</td>
                          </tr>
                      <?php else:?>
                        <?php for($k = 0; $k < count($room_data); $k++):?>
                          <tr>
                              <td class="text-center"><?= $room_data[$k]['room_name'];?></td>
                              <td class="text-center"><?= $room_data[$k]['room_floor'];?></td>
                              <td class="text-center"><?= $room_data[$k]['gender'] == 1 ? 'Male' : 'Female';?></td>
                              <td><a href="room_form?id=<?= $room_data[$k]['room_id']?>" class="ml-1 btn btn-primary">Edit</a></td>
                          </tr>
                        <?php endfor;?>
                      <?php endif;?>
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
