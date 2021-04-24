<?php
    session_start();
    $first_name_validation="";
    $last_name_validation="";
    $password_validation="";
    $phonenumber_validation="";
    $email_validation="";
    $gender_validation="";
    $checkbox_validation="";
    $date_of_birth_validation = "";
    $password_error = "";
    $confirm_password_error = "";
    $is_checked="";
    if(isset($_POST['submitRegister'])){
        // print_R($_POST['date_of_birth']);exit;
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $user_gender=$_POST['gender'];
        $user_first_name=$_POST['firstName'];
        $user_last_name=$_POST['lastName'];
        $user_email=$_POST['email'];
        $user_phonenumber=$_POST['phoneNumber'];
        $user_checkbox=$_POST['agree'];
        $user_dob = $_POST['date_of_birth'];

        if((!empty($user_first_name) && preg_match('/^[\p{L} ]+$/u', $user_first_name)) && (!empty($user_last_name) && preg_match('/^[\p{L} ]+$/u', $user_last_name)) && (!empty($password) && strlen($password) >= 8) && (!empty($confirm_password) && $password == $confirm_password) && !empty($user_dob) && !empty($user_gender) && !empty($user_checkbox) && (!empty($user_email) && strpos($user_email, "@")!== false) && (!empty($user_phonenumber) && is_numeric($user_phonenumber) && (strlen($user_phonenumber)>=10||($user_phonenumber)<=15))){
            include "DB_connection.php";

            $database = new Database();
            $con = $database->getConnection();
            
            $check_email_or_phone_number = "SELECT * FROM user FROM email = '".strtolower($user_email)."' OR phone_number = '".$user_phonenumber."'";

            $check_email_or_phone_number = $con->query($check_email_or_phone_number);
            if($check_email_or_phone_number->num_rows > 0){
                $check_email_or_phone_number = $check_email_or_phone_number->fetch_assoc();
                if($check_email_or_phone_number['email'] == strtolower($user_email)){
                    $email_validation = "Email has been used!";
                }
                if($check_email_or_phone_number['phone_number'] == strtolower($user_phonenumber)){
                    $phonenumber_validation = "Phone number has been used!";
                }
            }else{
                $password = password_hash($password, PASSWORD_DEFAULT);
                $sql="CALL insert_tenant('".$user_first_name."','".$user_last_name."','".$user_email."','".$user_phonenumber."','".$user_gender."','".$user_dob."','".($password)."')";
    
                $result= $con->query($sql);

                $_SESSION['success_registration'] = "You have successfully registered.";
            }
            $con->close();
        }else{
            if(empty($password)){
                $password_error = "Password is required";
            }elseif(strlen($password) < 8){
                $password_error = "Password must contain min 8";
            }
    
            if(empty($confirm_password)){
                $confirm_password_error = "Confirm password is required";
            }elseif($password != $confirm_password){
                $confirm_password_error = "Confirm password must be exactly same as password";
            }
    
            if(empty($user_first_name)){
                $first_name_validation= "First name is required!";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $user_first_name)){
                $first_name_validation = "First name must contain alphabets and space";
            }
            $_SESSION['user_first_name'] = $user_first_name;
            // echo $first_name_validation;
            // exit;
            if(empty($user_last_name)){
                $last_name_validation= "Last name is required!";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $user_last_name)){
                $last_name_validation = "First name must contain alphabets and space";
            }
            $_SESSION['user_last_name'] = $user_last_name;
            if(empty($user_email)){
                $email_validation="E-mail is required";
            }elseif(strpos($user_email, "@")==false){
                $email_validation="Emai must contain @";
            }
            $_SESSION['user_email'] = $user_email;   
            if(empty($user_phonenumber)){
                $phonenumber_validation="Phone number is required!";
            }elseif(!is_numeric($user_phonenumber)){
                $phonenumber_validation="Phone number must contain only digit";
            }elseif(strlen($user_phonenumber)<10 || strlen($user_phonenumber)>15){
                $phonenumber_validation="Phone number must contain min 10 characters and max 15 characters";
            }
            $_SESSION['user_phonenumber'] = $user_phonenumber;
    
            if(empty($user_dob)){
                $date_of_birth_validation= "Date of Birth is required!";
            }else{
                $_SESSION['user_dob'] = $user_dob;
            }
    
            if(!isset($user_gender)){
                $gender_validation="Gender is required!";
            }else{
                $_SESSION['user_gender'] = $user_gender;
            }
            if(!isset($user_checkbox)){
                $checkbox_validation="You should check the box to agree with this terms and conditions";
            }else{
                $_SESSION['user_checkbox'] = $user_checkbox;
            }
        }
    }

    if($password_error != ""){
        $_SESSION['password_error'] = $password_error;
    }
    if($confirm_password_error != ""){
        $_SESSION['confirm_password_error'] = $confirm_password_error;
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
    if($phonenumber_validation != ""){
        $_SESSION['phonenumber_validation'] = $phonenumber_validation;
    }
    if($gender_validation != ""){
        $_SESSION['gender_validation'] = $gender_validation;
    }
    if($checkbox_validation != ""){
        $_SESSION['checkbox_validation'] = $checkbox_validation;
    }
    if($date_of_birth_validation != ""){
        $_SESSION['date_of_birth_validation'] = $date_of_birth_validation;
    }

    header('Location:register');
?>
