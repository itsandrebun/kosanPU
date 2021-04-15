<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Dashboard - Kosan Admin Panel";
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
                <h1 class="h3 mb-0 text-gray-800">Admin List</h1>
                <a href="user_form" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add User</a>
            </div>
            <?php
                $admin_data = array();

                $admin_sql = "SELECT us.* FROM user AS us LEFT JOIN tenant AS tn ON tn.user_id = us.user_id WHERE tn.tenant_id IS NULL";

                $admins = $con->query($admin_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($admins->num_rows > 0){
                    while($row = $admins->fetch_assoc()) {
                        array_push($admin_data, $row);
                    }
                }
                $con->close();
            ?>          

            <div class="data-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php if(count($admin_data) > 0):?>
                        <?php for($p = 0; $p < count($admin_data); $p++):?>
                        <tr>
                            <td><?= $admin_data[$p]['first_name'];?></td>
                            <td><?= $admin_data[$p]['last_name'];?></td>
                            <td><?= $admin_data[$p]['email'];?></td>
                            <td><?= $admin_data[$p]['phone_number'];?></td>
                            <td><a href="user_form?id=<?= $admin_data[$p]['user_id'];?>" class="ml-1 btn btn-primary">Edit</a></td>
                        </tr>
                        <?php endfor;?>
                      <?php else:?>
                        <tr>
                          <td colspan="5" style="font-size:12px">No data found</td>
                        </tr>
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
