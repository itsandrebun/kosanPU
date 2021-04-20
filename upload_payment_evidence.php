<?php

    $target_dir = "assets/photo/uploads/";
    $target_subdir = "assets/photo/uploads/payment_evidence";
    $target_file = $target_subdir . basename($_FILES["payment_evidence"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if(!file_exists($target_dir)){
        mkdir($target_dir,0777);
    }
    if(!file_exists($target_subdir)){
        mkdir($target_subdir,0777);
    }

    
    // Check if image file is a actual image or fake image
    if(isset($_POST["uploadButton"])) {
        $check = getimagesize($_FILES["payment_evidence"]["tmp_name"]);
        if($check !== false) {
            $newname = uniqid().'.'.$imageFileType; 

            $target = $target_subdir.'/'.$newname;
            move_uploaded_file( $_FILES['payment_evidence']['tmp_name'], $target);
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    header("Location:upload_image_testing_form");
?>