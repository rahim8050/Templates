<?php
    include_once("connection.php");
    // Connection Created Successfully

    session_start();

    // Store All Errors
    $errors = [];

    // When Sign Up Button Clicked
    if (isset($_POST['signup'])) {
        $Username = mysqli_real_escape_string($conn, $_POST['Username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
       $password_hash = mysqli_real_escape_string($conn, $_POST['password_hash']);

        // check password length if password is less then 8 character so
        if (strlen(trim($_POST['password'])) < 8) {
            $errors['password'] = 'Use 8 or more characters with a mix of letters, numbers & symbols';
        } else {
            // if password not matched so
            if ($_POST['password'] != $_POST['Password_confirmation']) {
                $errors['password'] = 'Password not matched';
            } else {
                $password = md5($_POST['password']);
            }
        }
        // generate a random Code
        $code = rand(999999, 111111);
        // set Status
        $status = "Not Verified";

        // echo 'first name = ' .$fname . "<br> last name = " .$lname . "<br> email = " .$email . "<br> password = " .$password . "<br> gender = " .$gender . "<br>";

        // check email validation and save information
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $res = mysqli_query($conn, $sql) or die('query failed');
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Email is Already Taken';
        }

        // count erros
        if (count($errors) === 0) {
            $insertQuery = "INSERT INTO user (Username,email,password_hash,code,status)
            VALUES ('$Username','$lname','$email','$password_hash',$code,'$status')";
            $insertInfo = mysqli_query($conn, $insertQuery);

            // Send Varification Code In Gmail
            if ($insertInfo) {
                // Configure Your Server To Send Mail From Local Host Link In Video Description (How To Config LocalHost Server)
                $subject = 'Email Verification Code';
                $message = "our verification code is $code";
                $sender = 'From: ma382793@gmail.com';

                if (mail($email, $subject, $message, $sender)) {
                    $message = "We've sent a verification code to your Email <br> $email";

                    $_SESSION['message'] = $message;
                    header('location: otp.php');
                } else {
                    $errors['otp_errors'] = 'Failed while sending code!';
                }
            } else {
                $errors['db_errors'] = "Failed while inserting data into database!";
            }
        }
    }

    // if Verify Button Clicked
    if (isset($_POST['verify'])) {
        $_SESSION['message'] = "";
        $otp = mysqli_real_escape_string($conn, $_POST['otp']);
        $otp_query = "SELECT * FROM user WHERE code = $otp";
        $otp_result = mysqli_query($conn, $otp_query);

        if (mysqli_num_rows($otp_result) > 0) {
            $fetch_data = mysqli_fetch_assoc($otp_result);
            $fetch_code = $fetch_data['code'];

            $update_status = "Verified";
            $update_code = 0;

            $update_query = "UPDATE usersInfo SET status = '$update_status' , code = $update_code WHERE code = $fetch_code";
            $update_result = mysqli_query($conn, $update_query);

            if ($update_result) {
                header('location: log_in.php');
            } else {
                $errors['db_error'] = "Failed To Insering Data In Database!";
            }
        } else {
            $errors['otp_error'] = "You enter invalid verification code!";
        }
    }

    // if login Button clicked so

    if (isset($_POST['log_in'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = md5($_POST['password']);

        $emailQuery = "SELECT * FROM usersInfo WHERE email = '$email'";
        $email_check = mysqli_query($conn, $emailQuery);

        if (mysqli_num_rows($email_check) > 0) {
            $passwordQuery = "SELECT * FROM user WHERE email = '$email' AND password = '$password_hash'";
            $password_check = mysqli_query($conn, $passwordQuery);
            if (mysqli_num_rows($password_check) > 0) {
                $fetchInfo = mysqli_fetch_assoc($password_check);
                $status = $fetchInfo['status'];
                $Username = $fetchInfo['Username'];
                $_SESSION['Username'] = $Username;
                $_SESSION['email'] = $fetchInfo['email'];
                $_SESSION['password_hash'] = $fetchInfo['password_hash'];
                if ($status === 'Verified') {
                    header('location: IndexX.php');
                } else {
                    $info = "It's look like you haven't still verify your email $email";
                    $_SESSION['message'] = $info;
                    header('location: otp.php');
                }
            } else {
                $errors['email'] = 'Password did not matched';
            }
        } else {
            $errors['email'] = 'Invalid Email Address';
        }
    }

    // if forgot button will clicked
    if (isset($_POST['forgot_password'])) {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;

        $emailCheckQuery = "SELECT * FROM User WHERE email = '$email'";
        $emailCheckResult = mysqli_query($conn, $emailCheckQuery);

        // if query run
        if ($emailCheckResult) {
            // if email matched
            if (mysqli_num_rows($emailCheckResult) > 0) {
                $code = rand(999999, 111111);
                $updateQuery = "UPDATE User SET code = $code WHERE email = '$email'";
                $updateResult = mysqli_query($conn, $updateQuery);
                if ($updateResult) {
                    $subject = 'Email Verification Code';
                    $message = "our verification code is $code";
                    $sender = 'From: ma382793@gmail.com';

                    if (mail($email, $subject, $message, $sender)) {
                        $message = "We've sent a verification code to your Email <br> $email";

                        $_SESSION['message'] = $message;
                        header('location: verifyEmail.php');
                    } else {
                        $errors['otp_errors'] = 'Failed while sending code!';
                    }
                } else {
                    $errors['db_errors'] = "Failed while inserting data into database!";
                }
            }else{
                $errors['invalidEmail'] = "Invalid Email Address";
            }
        }else {
            $errors['db_error'] = "Failed while checking email from database!";
        }
    }
if(isset($_POST['verifyEmail'])){
    $_SESSION['message'] = "";
    $OTPverify = mysqli_real_escape_string($conn, $_POST['OTPverify']);
    $verifyQuery = "SELECT * FROM User WHERE code = $OTPverify";
    $runVerifyQuery = mysqli_query($conn, $verifyQuery);
    if($runVerifyQuery){
        if(mysqli_num_rows($runVerifyQuery) > 0){
            $newQuery = "UPDATE User SET code = 0";
            $run = mysqli_query($conn,$newQuery);
            header("location: setnewPassword.php");
        }else{
            $errors['verification_error'] = "Invalid Verification Code";
        }
    }else{
        $errors['db_error'] = "Failed while checking Verification Code from database!";
    }
}

// change Password
if(isset($_POST['changePassword'])){
    $password = md5($_POST['password']);
    $confirmPassword = md5($_POST['Password_confirmation']);
    
    if (strlen($_POST['password']) < 8) {
        $errors['password_error'] = 'Use 8 or more characters with a mix of letters, numbers & symbols';
    } else {
        // if password not matched so
        if ($_POST['password'] != $_POST['confirmPassword']) {
            $errors['password_error'] = 'Password not matched';
        } else {
            $email = $_SESSION['email'];
            $updatePassword = "UPDATE User SET password = '$password' WHERE email = '$email'";
            $updatePass = mysqli_query($conn, $updatePassword) or die("Query Failed");
            session_unset($email);
            session_destroy();
            header('location: log_in.php');
        }
    }
}