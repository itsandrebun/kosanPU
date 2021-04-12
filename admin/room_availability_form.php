<?php

    session_start();
    $mapping_validation = "";
    $equipment_id = 0;
    $success = 0;
    if(isset($_POST['submitRoomAvailability'])){
        $equipment_id = !empty($_POST['equipment_id']) ? $_POST['equipment_id'] : null;
        $checked_rooms = $_POST['room_availability_status'];
        if(count($checked_rooms) == 0){
            $mapping_validation = "You have to choose at least 1 room to connect with the equipment.";
        }else{
            $room_string = implode(",",$checked_rooms);
            // $_SESSION['room_string'] = $room_string;
            include "../DB_connection.php";

            $room_mapping_query = "CALL mapping_with_room(".$equipment_id.",'".$room_string."',".count($checked_rooms).")";

            $room_mapping = $con->query($room_mapping_query);

            $con->close();

            $success = 1;
        }
    }

    if($mapping_validation != ""){
        $_SESSION['mapping_validation'] = $mapping_validation;
    }

    if($success == 0){
        $equipment_link = "Location:master/equipment_form";
        if($equipment_id != null){
            $equipment_link .= "?id=".$equipment_id;
        }
        header($equipment_link);
    }else{
        header("Location:master/equipment");
    }
?>