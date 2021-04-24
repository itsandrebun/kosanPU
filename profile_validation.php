<?php

    session_start();
    $first_name_validation = "";
    $last_name_validation = "";
    $email_validation = "";
    $phone_number_validation = "";
    $gender_validation = "";
    $user_dob_validation = "";
    $bank_validation = "";
    $owner_account_number_validation = "";
    $owner_name_validation = "";
    $success = 0;
    if(isset($_POST['change_profile_status'])){
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $user_dob = $_POST['user_dob'];
        $bank_id = $_POST['bank_id'];
        $owner_account_number = $_POST['owner_account_number'];
        $owner_name = $_POST['owner_name'];

        if((!empty($first_name) && preg_match('/^[\p{L} ]+$/u', $first_name)) && (!empty($last_name) && preg_match('/^[\p{L} ]+$/u', $last_name)) && !empty($user_dob) && !empty($gender) && (!empty($email) && strpos($email, "@")!== false) && (!empty($phone_number) && is_numeric($phone_number) && (strlen($phone_number)>=10 && ($phone_number)<=15)) && !empty($bank_id) && (!empty($owner_account_number) && is_numeric($owner_account_number) && (strlen($owner_account_number) >= 8 && strlen($owner_account_number <= 15))) && !empty($owner_name)){

        }else{
            if(empty($first_name)){
                $first_name_validation = "First Name is required";
            }

            if(empty($last_name)){
                $last_name_validation = "Last Name is required";
            }

            if(empty($email)){
                $email_validation = "Email is required";
            }

            if(empty($phone_number)){
                $phone_number_validation = "Phone Number is required";
            }

            if(empty($bank_id)){
                $bank_validation = "Bank is required";
            }

            if(empty($owner_account_number)){
                $owner_account_number_validation = "Owner Account is required";
            }

            if(empty($owner_name)){
                $owner_name_validation = "Owner Name is required";
            }
        }
    }

    if($first_name_validation != ""){
        $_SESSION['first_name_validation'] = $first_name_validation;
    }

    if($last_name_validation != ""){
        $_SESSION['last_name_validation'] = $last_name_validation;
    }

    if($email_validation != ""){
        $_SESSION['email_validation'] = $email_validation;
    }

    if($bank_validation != ""){
        $_SESSION['bank_validation'] = $bank_validation;
    }

    if($phone_number_validation != ""){
        $_SESSION['phone_number_validation'] = $phone_number_validation;
    }

    if($owner_account_number_validation != ""){
        $_SESSION['owner_account_number_validation'] = $owner_account_number_validation;
    }

    if($owner_name_validation != ""){
        $_SESSION['owner_name_validation'] = $owner_name_validation;
    }

    header("Location:profile");
?>