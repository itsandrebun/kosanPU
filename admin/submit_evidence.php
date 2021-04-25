<?php

    if(isset($_POST['confirmEvidenceButton'])){
        $invoice_id = $_POST['invoice_id'];
        include "../../DB_connection";
        $database = new Database();
        $con = $database->getConnection();
        $notification_msg = "Invoice with number: [invoice_code] was approved. Thank you!";
        $approve_payment_query = "CALL approve_evidence(".$invoice_id.", 3, '".$notification_msg."')";

        $approve_payment = $con->query($approve_payment_query);
        header("Location:invoice/list");
    }elseif(isset($_POST['rejectEvidenceButton'])){
        $rejected_reason = $_POST['rejected_reason'];
        $invoice_id = $_POST['invoice_id'];
        if(empty($rejected_reason)){
            session_start();
            $_SESSION['rejected_reason_validation'] = "Rejected Reason is required";

            $_SESSION['rejected_reason_error'] = $rejected_reason;

            header("Location:invoice/detail?id=".$invoice_id);
        }else{
            $notification_msg = "Invoice with number: [invoice_code] was rejected. [user] should re-submit the payment evidence.";
            $reject_payment_query = "CALL reject_evidence(".$invoice_id.", 4, '".$rejected_reason."', '".$notification_msg."')";

            $reject_payment = $con->query($reject_payment_query);

            header("Location:invoice/list");
        }
    }
?>