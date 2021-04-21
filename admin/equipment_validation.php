<?php
    session_start();
    $equipment_name_validation = "";
    $fine_cost_validation = "";
    $success = 0;
    $equipment_id = null;
    if(isset($_POST['submitEquipmentForm'])){
        $equipment_name = $_POST['equipment_name'];
        $fine_cost = $_POST['fine_cost'];
        $equipment_id = !empty($_POST['equipment_id']) ? $_POST['equipment_id'] : null;
        if((!empty($equipment_name) && strlen($equipment_name) >= 5) && (!empty($fine_cost) && is_numeric($fine_cost))){
            include "../DB_connection.php";

            $database = new Database();
            $con = $database->getConnection();
            $modification_sql = "";
            if($equipment_id == null){
                $modification_sql = "CALL insert_equipment('".$equipment_name."',".$fine_cost.")";
            }else{
                $modification_sql = "CALL update_equipment(".$equipment_id.",'".$equipment_name."',".$fine_cost.")";
            }

            $modification_query = $con->query($modification_sql);

            if($modification_query){
                $success = 1;
            }
        }else{
            if(empty($equipment_name)){
                $equipment_name_validation = "Equipment Name is required."; 
            }elseif(strlen($equipment_name) < 5){
                $equipment_name_validation = "Equipment Name character length must be 5.";
                $_SESSION['equipment_name_error'] = $equipment_name;
            }

            if(empty($fine_cost)){
                $fine_cost_validation = "Fine cost is required."; 
            }elseif(!is_numeric($fine_cost)){
                $fine_cost_validation = "Fine cost must be numeric";
                $_SESSION['fine_cost_error'] = $fine_cost;
            }
            
        }
    }

    if($equipment_name_validation != ""){
        $_SESSION['equipment_name_validation'] = $equipment_name_validation;
    }
    if($fine_cost_validation != ""){
        $_SESSION['fine_cost_validation'] = $fine_cost_validation;
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