<?php

require_once('connection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpass = $_POST['cpassword'];

    //VALIDATIONS 
    if (empty($email) || empty($password) || empty($cpass)) {
        $error = 'All fields are required';
        header("location: ../register.php?error=$error");
        exit();
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
        header("location: ../register.php?error=$error");
        exit();
    }elseif (strlen($password) < 6) {
        $error = 'short password';
        header("location: ../register.php?error=$error");
        exit();
    }elseif ($password != $cpass) {
        $error = 'Passwords do not match';
        header("location: ../register.php?error=$error");
        exit();
    }else{
        //mysql operations
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt,"s", $email);
        }
        //attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            //store result
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) === 1){
                $error = 'Email already exists';
                header("location: ../register.php?error=$error");
                exit();
            }else{
                $sql = "INSERT INTO users(email, password) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if($stmt){
                    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt,"ss", $email, $hashPassword);
                    if(mysqli_stmt_execute($stmt)){
                        $msg = 'Successfully registered';
                        header("location: ../login.php?msg=$msg");
                        exit();
                    }else{
                        $error = 'Unexpected error';
                        header("location: ../register.php?error=$error");
                        exit();
                    }

                    mysqli_stmt_close($stmt);
                }
            }
        }else{
            $error = 'Unexpected error';
            header("location: ../register.php?error=$error");
            exit();
        }
    }
}else{
    header("location: ../register.php");
    exit();
}


?>