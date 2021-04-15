<!DOCTYPE html>
<html lang="en">
<?php
    $page_title = "Bank List - Kosan Admin Panel";
    $inside_folder = 1;
    $bank_active = 1;
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
                <h1 class="h3 mb-0 text-gray-800">Bank List</h1>
                <a href="bank_form" class="d-none d-sm-inline-block btn btn-sm btn-primary mykosan-signature-button-color shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Bank</a>
            </div>
            <?php
                $bank_data = array();

                $bank_sql = "SELECT bn.* from banks as bn";

                $banks = $con->query($bank_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($banks->num_rows > 0){
                    while($row = $banks->fetch_assoc()) {
                        array_push($bank_data, $row);
                    }
                }
                $con->close();
            ?>          

            <div class="data-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Description</th>
                            <th>Owner Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php if(count($bank_data) > 0):?>
                        <?php for($p = 0; $p < count($bank_data); $p++):?>
                        <tr>
                            <td><?= $bank_data[$p]['bank_name'];?></td>
                            <td><?= $bank_data[$p]['bank_account_number'];?></td>
                            <td><?= $bank_data[$p]['bank_description'];?></td>
                            <td><?= $bank_data[$p]['owner_name'];?></td>
                            <td><a href="bank_form?id=<?= $bank_data[$p]['bank_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">Edit</a></td>
                        </tr>
                        <?php endfor;?>
                      <?php else:?>
                        <tr>
                          <td colspan="4" style="font-size:12px" class="text-center">No data found</td>
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
