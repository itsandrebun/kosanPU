<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $page_title = ($_GET['gender'] == 1 ? 'Male' : 'Female').' Dorm';
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
    <img src="assets/photo/kosan.jpg" class="card-img-top" alt="...">
    <div class="position-fixed d-flex justify-content-center p-3" style="top: 50%;left: 50%;transform: translate(-50%, -50%);background:white">
        <?php
            $room_data = array();
            include "DB_connection.php";

            $room_sql = "SELECT * FROM room WHERE gender = ".$_GET['gender'];

            $rooms = $con->query($room_sql);

            // echo $room_sql;
            // print_r($rooms['num_rows']);
            if($rooms->num_rows > 0){
                while($row = $rooms->fetch_assoc()) {
                    array_push($room_data, $row);
                }
            }
        ?>
        <div style="height:400px;overflow-y:auto">
            <h3 class="text-center"><?= $_GET['gender'] == 1 ? 'Male' : 'Female' ;?> Dorm</h3>
            <table class="table table-dark table-borderless">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Room</th>
                        <th scope="col">Floor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($b = 0; $b < count($room_data); $b++):?>
                        <tr>
                            <th scope="row"><?= ($b+1);?></th>
                            <td><?= $room_data[$b]['room_name'];?></td>
                            <td><?= $room_data[$b]['room_floor'];?></td>
                            <td><a href="calendar.php?room=<?=$room_data[$b]['room_id'];?>" class="btn btn-primary">Book</a></td>
                        </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "templates/js_list.php";?>
</body>
</html>