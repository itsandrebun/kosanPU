<?php
    session_start();
    $room_name_validation = "";
    $room_floor_validation = "";
    $room_gender_validation = "";
    $success = 0;
    $room_id = null;
    if(isset($_POST['submitRoomForm'])){
        $room_name = $_POST['room_name'];
        $room_floor = $_POST['room_floor'];
        $room_gender = $_POST['gender'];
        $room_id = !empty($_POST['room_id']) ? $_POST['room_id'] : null;
        if(!empty($room_name) && (!empty($room_floor) && is_numeric($room_floor) && $room_floor > 0) && !empty($room_gender)){
            include "../DB_connection.php";

            $check_room_name_sql = "SELECT * FROM room WHERE room_name = '".$room_name."'";

            if($room_id != null){
                $check_room_name_sql .= " AND room_id <> ".$room_id;
            }
            
            $check_room_name = $con->query($check_room_name_sql);

            if($check_room_name->num_rows > 0){
                $room_name_validation = "Room name has been used! You can use different room name";
                $_SESSION['room_name_error'] = $room_name;
                $_SESSION['room_floor_error'] = $room_floor;
                $_SESSION['room_gender_error'] = $room_gender;
            }else{
                $modification_query = "";
                if($room_id == null){
                    $modification_query = "INSERT INTO room(room_name, room_floor, gender) VALUES ('".$room_name."','".$room_floor."','".$room_gender."')";
                }else{
                    $modification_query = "UPDATE room SET room_name = '".$room_name."', room_floor = '".$room_floor."', gender = '".$room_gender."' where room_id = ".$room_id;
                }
                $modification_execution = $con->query($modification_query);
                if($modification_execution){
                    $success = 1;
                }
            }

            $con->close();
        }else{
            if(empty($room_name)){
                $room_name_validation = "Room name is required";
            }
            $_SESSION['room_name_error'] = $room_name;
            if(empty($room_floor)){
                $room_floor_validation = "Room floor is required";
            }elseif(!is_numeric($room_floor)){
                $room_floor_validation = "Room floor must be numeric";
            }elseif($room_floor <= 0){
                $room_floor_validation = "Room floor must be min 1";
            }
            $_SESSION['room_floor_error'] = $room_floor;
            if(empty($room_gender)){
                $room_gender_validation = "Room gender is required";
            }
            $_SESSION['room_gender_error'] = $room_gender;
        }
    }

    if($room_name_validation != ""){
        $_SESSION['room_name_validation'] = $room_name_validation;
    }

    if($room_floor_validation != ""){
        $_SESSION['room_floor_validation'] = $room_floor_validation;
    }

    if($room_gender_validation != ""){
        $_SESSION['room_gender_validation'] = $room_gender_validation;
    }

    if($success == 0){
        $room_link = "Location:master/room_form";
        if($room_id != null){
            $room_link .= "?id=".$room_id;
        }
        header($room_link);
    }else{
        header('Location:master/room');
    }
?>