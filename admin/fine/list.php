<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Fine List - Kosan Admin Panel";
    $inside_folder = 1;
    $fine_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Fine List</h1>
            </div>
            <?php
                $fine = array();

                $tenant_sql = "SELECT us.* FROM user AS us JOIN tenant AS tn ON tn.user_id = us.user_id";

                $tenants = $con->query($tenant_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($tenants->num_rows > 0){
                    while($row = $tenants->fetch_assoc()) {
                        array_push($fine, $row);
                    }
                }
                $con->close();
            ?>
            <div class="data-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tenant Code</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($k = 0; $k < count($fine); $k++):?>
                          <tr>
                              <td><?= $fine[$k]['user_code'];?></td>
                              <td><?= $fine[$k]['first_name'];?></td>
                              <td><?= $fine[$k]['last_name'];?></td>
                              <td><?= $fine[$k]['email'];?></td>
                              <td><?= $fine[$k]['phone_number'];?></td>
                              <td><a href="tenant_detail?id=<?= $fine[$k]['user_id'];?>" class="ml-1 btn btn-primary">View</a></td>
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
