<?php
    session_start();
    $equipment_name_validation = "";
    $success = 0;
    $equipment_id = null;
    if(isset($_POST['submitEquipmentForm'])){
        $equipment_name = $_POST['equipment_name'];
        $equipment_id = !empty($_POST['equipment_id']) ? $_POST['equipment_id'] : null;
        if(empty($equipment_name)){
            $equipment_name_validation = "Equipment Name is required.";
        }else{
            if(strlen($equipment_name) < 5){
                $equipment_name_validation = "Equipment Name character length must be 5.";
                $_SESSION['equipment_name_error'] = $equipment_name;
            }else{
                include "../DB_connection.php";
                $modification_sql = "";
                if($equipment_id == null){
                    $modification_sql = "INSERT INTO equipment(equipment_name) VALUES ('".$equipment_name."')";
                }else{
                    $modification_sql = "UPDATE equipment SET equipment_name = '".$equipment_name."' WHERE equipment_id = ".$equipment_id."";
                }

                $modification_query = $con->query($modification_sql);

                if($modification_query){
                    $success = 1;
                }
            }
        }
    }

    if($equipment_name_validation != ""){
        $_SESSION['equipment_name_validation'] = $equipment_name_validation;
    }

    if($success == 0){
        $equipment_link = 'Location:master/equipment_form';
        if($equipment_id != null){
            $equipment_link .= "?id=".$equipment_id;
        }
        header($equipment_link);
    }else{
        header('Location:master/equipment');
    }
?>