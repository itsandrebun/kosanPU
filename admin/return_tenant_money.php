<?php

    $success = 0;
    $success_link = "";
    if(isset($_POST['return'])){
        $tenant_id = $_POST['tenant_id'];
        $invoice_id = $_POST['invoice_id'];
        $notification_msg = "Admin returned the deposit with the invoice number [invoice_code] on ".date("Y-m-d H:i:s")." by transferring to the tenant bank account. Please, check it out!";
        include "../DB_connection.php";

        $database = new Database();
        $con = $database->getConnection();
        $return_amount_query = "CALL return_money_to_tenant(".$tenant_id.",".$invoice_id.", '".$notification_msg."')";
        
        
        $return_amount = $con->query($return_amount_query);
        if($return_amount === TRUE){
            $success_link = "Location:invoice/list";
        }else{
            $success_link = "Location:invoice/detail?id=".$invoice_id;
        }
    }

    header($success_link);
?>