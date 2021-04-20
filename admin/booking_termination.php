<?php
    
    session_start();
    if(isset($_POST['terminateBookingForm'])){
        $tenant_id = $_POST['tenant_id'];
        $transaction_id = $_POST['transaction_id'];
        $terminated_reason = $_POST['terminated_reason'];
        $terminated_reason_validation = "";
        $notification_msg = "The booking with the code [transaction_code] has been terminated and the tenant should leave the room.";
        if(!empty($terminated_reason) && (strlen($terminated_reason) > 5)){
            include "../DB_connection.php";

            $database = new Database();
            $con = $database->getConnection();

            $terminate_booking_query = "CALL terminate_booking(".$tenant_id.", ".$transaction_id.", '".$terminated_reason."', '".$notification_msg."')";
            $terminate_booking = $con->query($terminate_booking_query);
            header("Location:booking/list");
        }else{
            if(empty($terminated_reason)){
                $terminated_reason_validation = "Terminated reason is required";
            }elseif(strlen($terminated_reason) <= 6){
                $terminated_reason_validation = "Terminated reason length must be min 5 characters";
            }
            $_SESSION['terminated_reason_validation'] = $terminated_reason_validation;
            $_SESSION['terminated_reason_error'] = $terminated_reason;
            header("Location:booking/detail?id=".$transaction_id);
        }
    }
?>