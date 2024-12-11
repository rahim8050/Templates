<?php
        require_once('connection.php');
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        use PHPMailer\PHPMailer\SMTP;
        require '../vendor/autoload.php';
        //require 'PHPMailer/src/Exception.php';
        //require 'PHPMailer/src/Exception.php';
        //require 'PHPMailer/src/SMTP.php';

        if (isset($_POST['submit'])) {
            $email = $_POST["email"];
            if(empty($email)){
                $error = 'Email field is required';
                header("location: ../forgot-password.php?error=$error");
                die();
            }else{
                //mysql operations
                $sql ="SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if($stmt){
                    mysqli_stmt_bind_param($stmt, 's', $email);
                }
                // execute statement
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt)==1) {
                         //generate a token
                $length = 35;
                $token = bin2hex(random_bytes($length));
                //set expiration date
                $created = time();
                $expires = strtotime("5 minutes", $created);
                $url ="http://localhost/login_tuts/forgot-password.php";
                //send email
                //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
                //Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.hotmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'rahimranxx8050@hotmail.com';                     //SMTP username
                    $mail->Password   = 'wsniajmfmqzdgdhj';                               //SMTP password
                    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
                    //Recipients
                    $mail->From = 'rahimranxx8050@hotmail.com'; 
                    $mail->addAddress($email);

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Reset Password Link';
                    $body = '<b>Click on the link below to reset your password</b>';
                    $body .= '<span>Link expires in 5 mins</span>';
                    $body .= '<a href="'.$url.'">Click here</a>';
                    $mail->Body = $body;

            
    if ($mail->send()) {
        //update database
        $sql = "UPDATE users SET expiry_date = ?, token = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt, 'sss', $expires, $token, $email);
            if(mysqli_stmt_execute($stmt)){
                //redirect with a msg
                $msg = "A reset link has been sent to $email";
                header("location: ../forgot-password.php?msg=$msg");
                die();
            }else{
                $error = 'Something went wrong';
                header("location: ../forgot-password.php?error=$error");
                die();
            }
        }

    }
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
} else{
                        $error = 'invalid email';
                        header("location: ../forgot-password.php?error=$error");

                die();
                    }
                }
            }
        }
?>