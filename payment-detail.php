<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        $database = new Database();
        $con = $database->getConnection();
    ?>
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
        <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
            <div style="height: 475px;overflow-y:auto">
                <h3 class="text-center"> Payment Detail</h3>
                    <table class="table table-dark table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">First name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Bill Code</th>
                                    <th scope="col">Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Diko</td>
                                    <td>Kintarenji</td>
                                    <td>KSINV21041100001</td>
                                    <td>Done</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Fine</th>
                                    <th scope="col">Rent Cost</th>
                                    <th scope="col">Deposit</th>
                                    <th scope="col">Total Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>Rp.1.000.000,00</td>
                                    <td>Rp.1.000.000,00</td>
                                    <td>Rp.2.000.000,00</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Transaction Code</th>
                                    <th scope="col">Transaction Type</th>
                                    <th scope="col">Transaction Date</th>
                                    <th scope="col">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>KSTRS21041100001</td>
                                    <td>Rent Cost & Deposit</td>
                                    <td>DD/MM/YY</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                    </table>
                <div>
                    <button type="button" class="btn btn-primary d-block mt-2 w-100" data-toggle="modal" data-target="#transactionModalCenter" style="background:#aa6d5a!important;border-color:#aa6d5a!important;">Detail Transaction</button>
                </div>
                <div>
                <a href="payment-evidence" class="btn btn-primary d-block mt-2">Pay Now!</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="transactionModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <th scope="col">Transaction Code:</th>
                            <th scope="col">Transaction Type:</th>
                            <th scope="col">Total Price:</th>
                            <th scope="col">Room No:</th>
                            <th scope="col">Fine Items:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>KSINV21041100001</td>
                            <td>Rent Cost</td>
                            <td>Rp.1.000.000,00</td>
                            <td>M1</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th scope="col">Transaction Code:</th>
                            <th scope="col">Transaction Type:</th>
                            <th scope="col">Total Price:</th>
                            <th scope="col">Room No:</th>
                            <th scope="col">Fine Items:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>KSINV21041100001</td>
                            <td>Fine</td>
                            <td>-</td>
                            <td>M1</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php include "templates/js_list.php";?>
</body>
</html>