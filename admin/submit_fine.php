<?php

    session_start();
    $fined_validation = "";
    $transaction_id = 0;
    $success = 0;
    if(isset($_POST['submitFineStatus'])){
        $transaction_id = !empty($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
        $checked_items = $_POST['fined_equipment_status'];
        $tenant_id = $_POST['tenant_id'];
        $room_id = $_POST['room_id'];
        $parent_invoice_id = $_POST['parent_invoice_id'];
        $logged_in_user = $_SESSION['user'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $company_name = $_POST['company_name'];
        $company_address = $_POST['company_address'];
        $notification_msg = "";
        $due_start_date = $_POST['due_start_date'];
        $due_end_date = $_POST['due_end_date'];
        $fined_status = $_POST['submitFineStatus'];
        if(count($checked_items) == 0){
            $fined_validation = "You have to choose at least 1 room to connect with the equipment.";
        }else{
            $fined_equipment_string = implode(",",$checked_items);
            include "../DB_connection.php";

            $database = new Database();
            $con = $database->getConnection();
            
            // $equipment_mapping_query = "CALL submit_fine(".$equipment_id.",'".$equipment_string."',".count($checked_items).")";
            $fined_equipment_query = "CALL submit_fine(".$tenant_id.", ".$parent_invoice_id.", '".$first_name."', '".$last_name."', '".$email."', '".$phone_number."', '".$company_name."', '".$company_address."', ".$room_id.", '".$fined_equipment_string."', ".count($checked_items).", '".$notification_msg."','".$due_start_date."','".$due_end_date."')";
            if($fined_status == 2){
                $fined_equipment_query = "CALL finalize_fine(".$tenant_id.", ".$parent_invoice_id.", '".$first_name."', '".$last_name."', '".$email."', '".$phone_number."', '".$company_name."', '".$company_address."', ".$room_id.", '".$fined_equipment_string."', ".count($checked_items).", '".$notification_msg."','".$due_start_date."','".$due_end_date."')";
            }

            // echo $fined_equipment_query;
            // exit;

            $fined_equipment = $con->query($fined_equipment_query);

            $con->close();

            if($fined_equipment === TRUE){
                $success = 1;
            }
        }
    }

    if($fined_validation != ""){
        $_SESSION['fined_validation'] = $fined_validation;
    }

    if($success == 0){
        $fined_equipment_link = "Location:booking/detail";
        if($transaction_id != null){
            $fined_equipment_link .= "?id=".$transaction_id;
        }
        header($fined_equipment_link);
    }else{
        header("Location:booking/list");
    }
?>