<?php

require_once('connection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //VALIDATIONS 
    if (empty($email) || empty($password)) {
        $error = 'All fields are required';
        header("location: ../login.php?error=$error");
        exit();
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
        header("location: ../login.php?error=$error");
        exit();
    }else{
        //mysql operations
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt,"s", $email);
        }
        //attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            //store result
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                //bind result variables
                mysqli_stmt_bind_result($stmt, $id, $email, $hashPassword);
                if(mysqli_stmt_fetch($stmt)){
                    //verify password
                    if(password_verify($password, $hashPassword)){
                        session_start();
                        $_SESSION["email"] = $email;
                        header("location: ../home.php");
                        exit();
                    }else{
                        $error = 'Incorrect password';
                        header("location: ../login.php?error=$error");
                        exit();
                    }
                }
            }else{
                $error = 'Email does not exist';
                header("location: ../login.php?error=$error");
                exit();
            }
        }else{
            $error = 'Unexpected error';
            header("location: ../login.php?error=$error");
            exit();
        }
    }
}else{
    header("location: ../login.php");
    exit();
}

?>