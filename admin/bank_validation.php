<?php
    session_start();
    $bank_name_validation = "";
    $bank_account_number_validation = "";
    $description_validation = "";
    $owner_name_validation = "";
    $success = 0;
    $bank_id = 0;
    
    if(isset($_POST['submitBankForm'])){
        $bank_name = $_POST['bank_name'];
        $bank_account_number = $_POST['bank_account_number'];
        $bank_description = $_POST['description'];
        $owner_name = $_POST['owner_name'];
        $bank_id = empty($_POST['bank_id']) ? 0 : $_POST['bank_id'];
        if(!empty($bank_name) && !(empty($bank_account_number) && is_numeric($bank_account_number) && (strlen($bank_account_number) >= 10 || strlen($bank_account_number) <= 15)) && !empty($bank_description) && !empty($owner_name)){
            include "../DB_connection.php";

            $database = new Database();
            $con = $database->getConnection();
            
            $modification_sql = "";
            if($bank_id == 0){
                $modification_sql = "INSERT INTO banks (bank_name, bank_account_number, bank_description, owner_name) VALUES ('".$bank_name."','".$bank_account_number."','".$bank_description."','".$owner_name."')";
            }else{
                $modification_sql = "UPDATE banks SET bank_name = '".$bank_name."', bank_account_number = '".$bank_account_number."', bank_description = '".$bank_description."', owner_name = '".$owner_name."' WHERE bank_id = ".$bank_id; 
            }

            $modification_query = $con->query($modification_sql);

            if($modification_query){
                $success = 1;
            }
            $con->close();
        }else{
            if(empty($bank_name)){
                $bank_name_validation = "Bank Name is required";
            }
            $_SESSION['bank_name_error'] = $bank_name;
            if(empty($bank_account_number)){
                $bank_account_number_validation = "Bank Account Number is required";
            }elseif(!is_numeric($bank_account_number)){
                $bank_account_number_validation =  "Bank Account Number must be numeric";
            }elseif(strlen($bank_account_number) < 10 || strlen($bank_account_number) > 15){
                $bank_account_number_validation =  "Bank Account Number Length must be min 10 or max 15 characters";
            }
            $_SESSION['bank_account_number_error'] = $bank_account_number;
            if(empty($bank_description)){
                $description_validation = "Description is required";
            }
            $_SESSION['description_error'] = $bank_description;
            if(empty($owner_name)){
                $owner_name_validation = "Owner Name is required";
            }
            $_SESSION['owner_name_error'] = $owner_name;
        }
    }

    if($bank_name_validation != ""){
        $_SESSION['bank_name_validation'] = $bank_name_validation;
    }
    if($bank_account_number_validation != ""){
        $_SESSION['bank_account_number_validation'] = $bank_account_number_validation;
    }
    if($description_validation != ""){
        $_SESSION['description_validation'] = $description_validation;
    }
    if($owner_name_validation != ""){
        $_SESSION['owner_name_validation'] = $owner_name_validation;
    }
    
    if($success == 0){
        $bank_link = 'Location:master/bank_form';
        if($bank_id != 0){
            $bank_link .= "?id=".$bank_id;
        }
        header($bank_link);
    }else{
        header("Location:master/bank");
    }
?>