<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
        $page_title = "Profile";
        include "templates/header.php";
    ?>
</head>
<body>
    <?php
        include "templates/navbar.php";
        $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
        if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] == null)){
            session_destroy();
            header("Location:index");
        }
    ?>
    <div class="image-login-register d-flex justify-content-end">
    <?php
       include "DB_connection.php";
    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 400px;overflow-y:auto">
                <h3 class="text-center">Profile</h3>
                    <table class="table table-dark table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">First name</th>
                                    <th scope="col">Last Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="first_name" class="form-control" value="<?= $logged_in_user['first_name'];?>"></td>
                                    <td><input type="text" name="last_name" class="form-control" value="<?= $logged_in_user['last_name'];?>"></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">E-Mail</th>
                                    <th scope="col">Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="email" class="form-control" value="<?= $logged_in_user['email'];?>"></td>
                                    <td><input type="text" name="phone_number" class="form-control" value="<?= $logged_in_user['phone_number'];?>"></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Date of Birth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="gender" class="form-control" value="<?= $logged_in_user['gender'] == 1 ? 'Male' : 'Female';?>"></td>
                                    <td><input type="date" name="dob" class="form-control" value="<?= $logged_in_user['dob'];?>"></td>
                                </tr>
                            </tbody>
                            <!-- <div>
                                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                            </div> -->
                    </table>
                    <div>
                        <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#invoiceModalCenter">See Invoice</button>
                    </div>
            </div>
        </div>
        <div class="modal fade" id="invoiceModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Bill No:</th>
                            <th scope="col">Payment Status:</th>
                            <th scope="col">Payment Date:</th>
                            <th scope="col">Date In:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>KSINV21041100001</td>
                            <td>Done</td>
                            <td>21 March 2021</td>
                            <td>21 March 2021</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="payment-detail.php" class="btn btn-primary">See Detail</a>
                </div>
            </div>
            </div>
        </div>
    </div>
<?php include "templates/js_list.php";?>
</body>
</html>