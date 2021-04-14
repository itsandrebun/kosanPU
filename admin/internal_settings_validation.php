<?php
    session_start();
    $company_name_validation = "";
    $company_address_validation = "";
    $rent_cost_validation = "";
    $internal_data_existence = "";
    $deposit_validation = "";
    $success = 0;

    if(isset($_POST['submitInternalForm'])){
        $company_name = $_POST['company_name'];
        $company_address = $_POST['company_address'];
        $rent_cost = $_POST['rent_cost'];
        $deposit = $_POST['deposit'];
        $rent_cost_temp = (int)str_replace('.','',$rent_cost);
        $deposit_temp = (int)str_replace('.','',$deposit);
        $internal_data_existence = $_POST['internal_data_existence'];

        if(!empty($company_name) && !empty($company_address) && (!empty($rent_cost) && $rent_cost_temp > 0) && (!empty($deposit) && $deposit > 0)){
            include "../DB_connection.php";
            $modification_sql = "";
            if(!empty($internal_data_existence)){
                $modification_sql = "UPDATE internal_parameter SET parameter_value = (CASE WHEN parameter_name = 'rent_cost' THEN ".$rent_cost_temp." WHEN parameter_name = 'deposit' THEN ".$deposit_temp." WHEN parameter_name = 'company_name' THEN '".$company_name."' WHEN parameter_name = 'company_address' THEN '".$company_address."' END)";
            }else{
                $modification_sql = "INSERT INTO internal_parameter(parameter_name, parameter_value) VALUES (rent_cost,".$rent_cost_temp."),(deposit,".$deposit."),(company_name,,'".$company_name."'),(company_address,'".$company_address."')";
            }

            $modification_query = $con->query($modification_sql);
            
            if($modification_query){
                $success = 1;
            }
            $con->close();
        }else{
            if(empty($company_name)){
                $company_name_validation = "Company Name is required";
            }

            if(empty($company_address)){
                $company_address_validation = "Company Address is required";
            }

            if(empty($rent_cost)){
                $rent_cost_validation = "Rent Cost is required";
            }

            if(empty($deposit)){
                $deposit_validation = "Deposit is required";
            }

            if($rent_cost_temp <= 0){
                $rent_cost_validation = "Rent Cost must be min 1";
            }

            if($deposit_temp <= 0){
                $deposit_validation = "Deposit must be min 1";
            }

            $_SESSION['company_name_error'] = $company_name;
            $_SESSION['company_address_error'] = $company_address;
            $_SESSION['rent_cost_error'] = $rent_cost;
            $_SESSION['deposit_error'] = $deposit_error;
        }
    }

    if($company_name_validation != ""){
        $_SESSION['company_name_validation'] = $company_name_validation;
    }

    if($company_address_validation != ""){
        $_SESSION['company_address_validation'] = $company_address_validation;
    }

    if($rent_cost_validation != ""){
        $_SESSION['rent_cost_validation'] = $rent_cost_validation;
    }

    if($deposit_validation != ""){
        $_SESSION['deposit_validation'] = $deposit_validation;
    }

    header('Location:settings/internal');
?>