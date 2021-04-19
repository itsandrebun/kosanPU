<?php
session_start();
$username_validation="";
$password_validation="";
$login = 0;
if (isset($_POST['submitLogin'])){
    $user_name=$_POST['email'];
    $password=$_POST['password'];
    if(!empty($user_name) && !empty($password)){
        include "DB_connection.php";

        $database = new Database();
        $con = $database->getConnection();

        $check_email_or_phone_number = "SELECT us.user_id, us.user_code, us.first_name, us.last_name, 
        us.password, us.email, us.phone_number, us.gender, tn.tenant_id, us.dob FROM user AS us LEFT JOIN tenant AS tn ON tn.user_id = us.user_id WHERE us.email = '".$user_name."' OR us.phone_number = '".$user_name."'";

        $check_email_or_phone_number = $con->query($check_email_or_phone_number);
        
        if($check_email_or_phone_number->num_rows > 0){
            $check_email_or_phone_number = $check_email_or_phone_number->fetch_assoc();
            // echo $check_email_or_phone_number['email'];
            // exit;
            if(password_verify($password, $check_email_or_phone_number['password']) == true){
                if($check_email_or_phone_number['tenant_id'] == null){
                    $username_validation = "You're administrator and you cannot access this web!";
                }else{
                    $logged_in_user = array(
                        "first_name" => $check_email_or_phone_number['first_name'],
                        "last_name" => $check_email_or_phone_number['last_name'],
                        "gender" => $check_email_or_phone_number['gender'],
                        "user_id" => $check_email_or_phone_number['user_id'],
                        "email" => $check_email_or_phone_number['email'],
                        "phone_number" => $check_email_or_phone_number['phone_number'],
                        "tenant_id" => $check_email_or_phone_number['tenant_id'],
                        "dob" => $check_email_or_phone_number['dob']
                    );

                    $_SESSION['user'] = $logged_in_user;
                    $login = 1;
                }
                
            }
        }else{
            $username_validation = "No user found!";
            $_SESSION['user_name'] = $user_name;
        }

        
    }else{
        if(empty($user_name)){
            $username_validation="Username is required";
        }
        $_SESSION['user_name'] = $user_name;

        if(empty($password)){
            $password_validation="Password is required";
        }
    }

}

if($username_validation != ""){
    $_SESSION["username_validation"] = $username_validation;
}

if($password_validation != ""){
    $_SESSION["password_validation"] = $password_validation;
}

if($login == 0){
    header('Location:login');
}else{
    header('Location:index');  

}

?>