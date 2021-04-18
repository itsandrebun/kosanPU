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
    <img src="photo/kosan.jpg" class="card-img-top" alt="...">
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
                                    <td>Diko</td>
                                    <td>Kintarenji</td>
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
                                    <td>diko.renji@gmail.com</td>
                                    <td>087867684328</td>
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
                                    <td>Male</td>
                                    <td>18/04/2002</td>
                                </tr>
                            </tbody>
                            <!-- <div>
                                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                            </div> -->
                    </table>
                    <div>
                        <button type="button" class="btn btn-primary d-block mt-2" data-toggle="modal" data-target="#exampleModalCenter">See Invoice</button>
                    </div>
            </div>
        </div>
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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