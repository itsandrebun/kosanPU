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
        $logged_in_user = $_SESSION['user'];
        $user_id = $logged_in_user['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $user_dob = $_POST['user_dob'];
        $bank_id = $_POST['bank_id'];
        $owner_account_number = $_POST['owner_account_number'];
        $owner_name = $_POST['owner_name'];

        if((!empty($first_name) && preg_match('/^[\p{L} ]+$/u', $first_name)) && (!empty($last_name) && preg_match('/^[\p{L} ]+$/u', $last_name)) && !empty($user_dob) && !empty($gender) && (!empty($email) && strpos($email, "@")!== false) && (!empty($phone_number) && is_numeric($phone_number) && (strlen($phone_number)>=10 || ($phone_number)<=15)) && !empty($bank_id) && (!empty($owner_account_number) && is_numeric($owner_account_number) && (strlen($owner_account_number) >= 8 || strlen($owner_account_number) <= 15)) && !empty($owner_name)){
            include "DB_connection.php";
            
            $database = new Database();
            $con = $database->getConnection();
            
            $check_email_or_phone_number = "SELECT us.user_id, us.user_code, us.first_name, us.last_name, 
            us.password, us.email, us.phone_number, us.gender, tn.tenant_id FROM user AS us LEFT JOIN tenant AS tn ON tn.user_id = us.user_id WHERE (us.email = '".$email."' OR us.phone_number = '".$phone_number."') ";

            $check_email_or_phone_number .= " AND us.user_id <> ".$user_id;

            $username_data = $con->query($check_email_or_phone_number);

            if($username_data->num_rows > 0){
                
                $duplicate_email = 0;
                $duplicate_phone_number = 0;

                while($row = $username_data->fetch_assoc()) {
                    if(strtolower($email) == strtolower($row['email'])){
                        $duplicate_email = 1;
                    }
                    if(strtolower($phone_number) == $row['phone_number']){
                        $duplicate_phone_number = 1;
                    }
                }

                if($duplicate_email == 1){
                    $email_validation = "Please, use the different email!";
                }
                if($duplicate_phone_number == 1){
                    $phone_number_validation = "Please, use the different phone number!";
                }
                $_SESSION['first_name_error'] = $first_name;
                $_SESSION['last_name_error'] = $last_name;
                $_SESSION['email_error'] = $email;
                $_SESSION['phone_number_error'] = $phone_number;
                $_SESSION['user_dob_error'] = $user_dob;
                $_SESSION['gender_error'] = $gender;
            }else{
                // echo 1;
                // exit;
                // $password = password_hash('kosanPresident',PASSWORD_DEFAULT);
                $email = strtolower($email);
                $user_form_query = "";
                // $user_form_query = "CALL update_admin('".$user_id."','".$first_name."','".$last_name."','".$email."','".$phone_number."','".$gender."','".$user_dob."')";
                $user_form_query = "UPDATE user SET first_name = '".$first_name."', last_name = '".$last_name."', email = '".$email."', phone_number = '".$phone_number."', gender = '".$gender."', dob = '".$user_dob."', bank_id = ".$bank_id.", owner_name = '".$owner_name."', owner_account_number = '".$owner_account_number."' where user_id = ".$user_id;
                // if($user_id == null){
                //     $user_form_query = "CALL insert_admin('".$first_name."','".$last_name."','".$email."','".$phone_number."','".$gender."','".$user_dob."','".($password)."')";
                // }else{
                // }
                $modification_query = $con->query($user_form_query);

                if($modification_query === TRUE){
                    $success = 1;
                    $logged_in_user = array(
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "gender" => $gender,
                        "user_id" => $logged_in_user['user_id'],
                        "tenant_id" => $logged_in_user['tenant_id'],
                        "email" => $email,
                        "dob" => $user_dob,
                        "phone_number" => $phone_number,
                        "bank_id" => $bank_id,
                        "owner_name" => $owner_name,
                        "owner_account_number" => $owner_account_number
                    );
                    // print_r($logged_in_user);
                    // exit;
                    $_SESSION['user'] = $logged_in_user;
                }
            }
            $con->close();
        }else{
            if(empty($first_name)){
                $first_name_validation = "First Name is required";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $first_name)){
                $first_name_validation = "First name must contain alphabets and space";
            }

            if(empty($last_name)){
                $last_name_validation = "Last Name is required";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $last_name)){
                $last_name_validation = "First name must contain alphabets and space";
            }

            if(empty($email)){
                $email_validation = "Email is required";
            }elseif(strpos($email, "@")==false){
                $email_validation="Emai must contain @";
            }

            if(empty($phone_number)){
                $phone_number_validation = "Phone Number is required";
            }elseif(!is_numeric($phone_number)){
                $phone_number_validation="Phone number must contain only digit";
            }elseif(strlen($phone_number)<10 || strlen($phone_number)>15){
                $phone_number_validation="Phone number must contain min 10 characters and max 15 characters";
            }

            if(empty($bank_id)){
                $bank_validation = "Bank is required";
            }

            if(empty($owner_account_number)){
                $owner_account_number_validation = "Owner Account Number is required";
            }elseif(strlen($owner_account_number) < 8 || strlen($owner_account_number) > 15){
                $owner_account_number_validation = "Owner Account Number must contain min 10 characters and max 15 characters";
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