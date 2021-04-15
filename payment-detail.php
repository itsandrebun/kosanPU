<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title = "Payment Detail";
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
            <div style="height: 500px;overflow-y:auto">
                <h3 class="text-center"> Payment Detail</h3>
                    <table class="table table-dark table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Bill Code</th>
                                    <th scope="col">Transaction Date</th>
                                    <th scope="col">Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01248u294</td>
                                    <td>01248u294</td>
                                    <td>01248u294</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">First name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">E-mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Diko</td>
                                    <td>Kintarenji</td>
                                    <td>diko.renji@gmail.com</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">DoB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0868768757656</td>
                                    <td>Male</td>
                                    <td>18/04/2002</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Total Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rp.2.000.000,00</td>
                                </tr>
                            </tbody>
                            <!-- <div>
                                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                            </div> -->
                    </table>
                <div>
                <a href="payment-evidence.php" class="btn btn-primary d-block mt-2">Pay Now!</a>
                </div>
            </div>
        </div>
    </div>
    <?php include "templates/js_list.php";?>
</body>
</html>