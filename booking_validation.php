<?php

    session_start();
    $logged_in_user = $_SESSION['user'];
    $booking_validation = "";
    $booking_start_date_validation = "";
    $booking_end_date_validation = "";
    $success_booking_msg = "";
    $booked = 0;
    $room_parameter = 0;
    if(isset($_POST['bookingButton'])){
        $room_parameter = $_POST['room_id'];
        $booking_start_date = $_POST['booking_start_date'];
        $booking_end_date = $_POST['booking_end_date'];
        $rent_cost = $_POST['rent_cost'];
        $deposit = $_POST['deposit'];
        $total_price = $_POST['total_price'];
        $company_name = $_POST['company_name'];
        $company_address = $_POST['company_address'];
        $room_name = $_POST['room_name'];
        if(empty($booking_start_date) && empty($booking_end_date)){
            $booking_start_date_validation = "Booking Start Date is required";
            $booking_end_date_validation = "Booking End Date is required";
        }else{
            include "DB_connection.php";
            $database = new Database();
            $con = $database->getConnection();
            $booking_start_month = date('m',strtotime($booking_start_date));
            $booking_end_month = date('m',strtotime($booking_end_date));
            $booking_sql = "SELECT tr.transaction_id, tr.transaction_code, tr.booking_start_date, tr.booking_end_date, tr.room_id FROM transaction AS tr WHERE (tr.user_id = ".$logged_in_user['user_id']." AND (MONTH(tr.booking_start_date) = '".$booking_start_month."' OR MONTH(tr.booking_end_date) = '".$booking_end_month."') AND tr.transaction_type_id = 1) OR (tr.room_id = ".$room_parameter." AND (MONTH(tr.booking_start_date) = '".$booking_start_month."' OR MONTH(tr.booking_end_date) = '".$booking_end_month."') AND tr.transaction_type_id = 1) OR (tr.user_id = ".$logged_in_user['user_id']." AND (MONTH(tr.booking_start_date) = '".$booking_end_month."') AND tr.transaction_type_id = 1) OR (tr.room_id = ".$room_parameter." AND (MONTH(tr.booking_end_date) = '".$booking_start_month."') AND tr.transaction_type_id = 1) OR (tr.user_id = ".$logged_in_user['user_id']." AND (MONTH(tr.booking_end_date) = '".$booking_start_month."') AND tr.transaction_type_id = 1)";
            
            $booking = $con->query($booking_sql);
            $due_start_date = date("Y-m-01",strtotime($booking_end_date));
            $due_end_date = date("Y-m-d H:i:s", strtotime($due_start_date." +1 week"));
            
            if($booking->num_rows == 0){
                if(date("Y-m-d") >= $booking_start_date){
                    $booking_validation = "You cannot choose booking start date greater than equal to today";
                    $_SESSION['booking_start_date_error'] = $booking_start_date;
                    $_SESSION['booking_end_date_error'] = $booking_end_date;
                }else{
                    $notification_msg = "[user] booked room [room_name] for ".$booking_start_date." until ".$booking_end_date." on ".date('Y-m-d H:i:s');
                    $insert_transaction_query = "CALL insert_transaction(".$logged_in_user['user_id'].", '".$logged_in_user['first_name']."', '".$logged_in_user['last_name']."', '".$logged_in_user['email']."', '".$logged_in_user['phone_number']."', '".$company_name."', '".$company_address."', '".$due_start_date."','".$due_end_date."', ".$room_parameter.", ".$rent_cost.", ".$total_price.", ".$deposit.", '".$booking_start_date."', '".$booking_end_date."', '".$notification_msg."')";
                    $insert_transaction_query = $con->query($insert_transaction_query);
                    
                    if($insert_transaction_query == TRUE){
                        $success_booking_msg = "You have successfully booked room ".$room_name;
                        $booked = 1;
                    }
                }
            }else{
                
                $booking_validation = "Sorry, you cannot book this room between these dates because it has been booked by you or other tenant or you might have booked another room between these dates.";
            }
        }

    }

    if($booking_start_date_validation != ""){
        $_SESSION['booking_start_date_validation'] = $booking_start_date_validation;
        header("Location:calendar.php?room=".$room_parameter);
    }
    if($booking_end_date_validation != ""){
        $_SESSION['booking_end_date_validation'] = $booking_end_date_validation;
        header("Location:calendar.php?room=".$room_parameter);
    }
    if($booking_validation != ""){
        $_SESSION['booking_validation'] = $booking_validation;
        header("Location:calendar?room=".$room_parameter);
    }

    if($booked == 1){
        $_SESSION['success_booking'] = $success_booking_msg;
        header("Location:index");
    }else{
        header("Location:calendar?room=".$room_parameter);
    }
?>