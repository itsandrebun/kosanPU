<?php

    session_start();
    $evidence_file_validation = "";
    $bank_validation = "";
    $payment_date_validation = "";
    $evidence_link = "";
    if(isset($_POST['submitEvidenceButton'])){
        $evidence_file = $_FILES['evidence'];
        $invoice_id = $_POST['invoice_id'];
        $bank_id = $_POST['bank_id'];
        $payment_date = $_POST['payment_date'];
        $target_dir = "assets/photo/uploads/";
        $target_subdir = "assets/photo/uploads/payment_evidence";
        
        if($evidence_file != null && !empty($bank_id) && !empty($payment_date)){
            $check = getimagesize($evidence_file["tmp_name"]);
            if($check !== false){
                $evidence_filename = uniqid().'.'.$imageFileType; 
    
                $evidence_filename = $target_subdir.'/'.$evidence_filename;
                move_uploaded_file( $evidence_file['tmp_name'], $evidence_filename);
                    
                include "DB_connection.php";
    
                $database = new Database();
                $con = $database->getConnection();

                $notification_msg = "[user] submitted the payment evidence with invoice number [invoice_number] on ".date('Y-m-d H:i:s');
    
                $submit_evidence_query = "CALL insert_evidence(".$invoice_id.", 2, '".date("Y-m-d H:i:s")."', '".$payment_date."', '".$move_uploaded_file."', ".$bank_id.", '".$notification_msg."')";

                $submit_evidence = $con->query($submit_evidence_query);
                $evidence_link = "Location:index";
            }else{
                $evidence_file_validation = "Evidence file must be an image";
                $evidence_link = "Location:payment-detail?id=".$invoice_id;
            }
        }else{
            if($evidence_file == null){
                $evidence_file_validation = "Evidence file is required";
            }
            
            if($empty($bank_id)){
                $bank_validation = "Bank is required";
            }

            if($empty($payment_date)){
                $payment_date_validation = "Payment Date is required";
            }
            $evidence_link = "Location:payment-detail?id=".$invoice_id;
        }
    }

    if($evidence_file_validation != ""){
        $_SESSION['evidence_file_validation'] = $evidence_file_validation;
    }
    if($bank_validation != ""){
        $_SESSION['bank_validation'] = $bank_validation;
    }
    if($payment_date_validation != ""){
        $_SESSION['payment_date_validation'] = $payment_date_validation;

    }
    header($evidence_link);
?>