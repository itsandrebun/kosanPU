<?php
    session_start();
    // echo 0;
    // exit;
    $success = 0;
    $first_name_validation="";
    $last_name_validation="";
    $phone_number_validation="";
    $email_validation="";
    $gender_validation="";
    $date_of_birth_validation = "";
    if(isset($_POST['submitUserForm'])){
        $user_id = empty($_POST['user_id']) ? null : $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $user_dob = $_POST['dob'];
        if((!empty($first_name) && strlen($first_name) >= 5) && !empty($last_name) && (!empty($email) && strpos($email, "@")!== false) && (!empty($phone_number) && (strlen($phone_number) >= 10 || strlen($phone_number) <= 15)) && isset($gender)){
            include "../DB_connection.php";
            
            // echo 11;
            // exit;
            $check_email_or_phone_number = "SELECT us.user_id, us.user_code, us.first_name, us.last_name, 
            us.password, us.email, us.phone_number, us.gender, tn.tenant_id FROM user AS us LEFT JOIN tenant AS tn ON tn.user_id = us.user_id WHERE (us.email = '".$email."' OR us.phone_number = '".$phone_number."') ";

            if($user_id != null){
                $check_email_or_phone_number .= " AND us.user_id <> ".$user_id;
            }

            $username_data = $con->query($check_email_or_phone_number);

            if($username_data->num_rows > 0){
                
                $duplicate_email = 0;
                $duplicate_phone_number = 0;

                while($row = $username_data->fetch_assoc()) {
                    // array_push($tenant_data, $row);
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
                $password = password_hash('kosanPresident',PASSWORD_DEFAULT);
                $email = strtolower($email);
                $user_form_query = "";
                if($user_id == null){
                    $user_form_query = "INSERT INTO `user`(`first_name`, `last_name`, `password`, `email`, `phone_number`, `dob`, `gender`) VALUES ('".$first_name."','".$last_name."','".$password."','".$email."','".$phone_number."','".$user_dob."',".$gender.")";
                }else{
                    $user_form_query = "UPDATE user SET first_name = '".$first_name."', last_name = '".$last_name."', password = '".$password."', email = '".$email."', phone_number = '".$phone_number."', dob = '".$user_dob."', gender = '".$gender."' WHERE user_id = ".$user_id;
                }
                $modification_query = $con->query($user_form_query);

                if($modification_query){
                    $success = 1;
                }
            }
            $con->close();
        }else{
            // echo 'test';
            //     exit;
            if(empty($first_name)){
                $first_name_validation= "First name is required!";
            }elseif(strlen($first_name) < 5){
                $first_name_validation = "First name min. 5 characters";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $first_name)){
                $first_name_validation = "First name must contain alphabets and space";
            }
            $_SESSION['first_name_error'] = $first_name;
            // echo $first_name_validation;
            // exit;
            if(empty($last_name)){
                $last_name_validation= "Last name is required!";
            }elseif(!preg_match('/^[\p{L} ]+$/u', $last_name)){
                $first_name_validation = "First name must contain alphabets and space";
            }
            $_SESSION['last_name_error'] = $last_name;
            if(empty($email)){
                $email_validation="E-mail is required";
            }elseif(strpos($email, "@")==false){
                $email_validation="Emai must contain @";
            }
            $_SESSION['email_error'] = $email;   
            if(empty($phone_number)){
                $phone_number_validation="Phone number is required!";
            }elseif(!is_numeric($phone_number)){
                $phone_number_validation="Phone number must contain only digit";
            }elseif(strlen($phone_number)<10 || strlen($phone_number)>15){
                $phone_number_validation="Phone number must contain min 10 characters and max 15 characters";
            }
            $_SESSION['phone_number_error'] = $phone_number;
    
            if(empty($user_dob)){
                $date_of_birth_validation= "Date of Birth is required!";
            }else{
                $_SESSION['user_dob_error'] = $user_dob;
            }
    
            if(!isset($gender)){
                $gender_validation="Gender is required!";
            }else{
                $_SESSION['gender_error'] = $gender;
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
    if($phone_number_validation != ""){
        $_SESSION['phone_number_validation'] = $phone_number_validation;
    }
    if($gender_validation != ""){
        $_SESSION['gender_validation'] = $gender_validation;
    }
    if($date_of_birth_validation != ""){
        $_SESSION['date_of_birth_validation'] = $date_of_birth_validation;
    }

    if($success == 0){
        $user_link = $user_id == null ? "" : ("?id=".$user_id);
        header("Location:master/user_form".$user_link);
    }else{
        header("Location:master/user");
    }
?>