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

    include "../../Helpers/Currency.php";
    $currency = new Currency();
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
                <h1 class="h3 mb-0 text-gray-800">Fine List</h1>
            </div>
            <?php
                $fine_data = array();

                $fine_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.room_id, tr.price, rm.room_name, us.user_id, us.first_name, us.last_name, us.email, (SELECT COUNT(fed.equipment_id) FROM fine_transaction_detail AS fed WHERE fed.transaction_id = tr.transaction_id) AS total_fine from transaction AS tr JOIN room AS rm ON rm.room_id = tr.room_id JOIN user AS us ON us.user_id = tr.user_id WHERE tr.transaction_type_id = 2";

                $fines = $con->query($fine_sql);

                // echo $room_sql;
                // print_r($rooms['num_rows']);
                if($fines->num_rows > 0){
                    while($row = $fines->fetch_assoc()) {
                        array_push($fine_data, $row);
                    }
                }
                $con->close();
            ?>
            <div class="data-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Room</th>
                            <th>Total Fine</th>
                            <th class="text-right">Fine Cost</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($fine_data) == 0):?>
                          <tr>
                            <td colspan="6" class="text-center" style="font-size:12px">No data found!</td>
                          </tr>
                        <?php else:?>
                        <?php for($k = 0; $k < count($fine_data); $k++):?>
                          <tr>
                              <td><?= $fine_data[$k]['first_name'] . ' '.$fine_data[$k]['last_name'] ;?></td>
                              <td><?= $fine_data[$k]['email'];?></td>
                              <td><?= $fine_data[$k]['room_name'];?></td>
                              <td><?= $fine_data[$k]['total_fine'];?></td>
                              <td class="text-right"><?= $currency->convert($fine_data[$k]['price']);?></td>
                              <td><a href="detail?id=<?= $fine_data[$k]['transaction_id'];?>" class="ml-1 btn btn-primary mykosan-signature-button-color">View</a></td>
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
