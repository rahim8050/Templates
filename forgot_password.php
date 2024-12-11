<?php

            $error = array();
            session_start();
            if(!$con = mysqli_connect("localhost","root","","kamiti_prison")){

                die("could not connect");
            }
        $mode = "enter_email";
        if(isset($_GET['mode'])){
            $mode = $_GET['mode'];
        }
        //something being posted
        if(count($_POST) > 0){
            switch ($mode){
                case 'enter_email':
                //code..
                $email = ($_POST["email"]);
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					$error[] = "Please enter a valid email";
				}elseif(!valid_email($email)){
					$error[] = "That email was not found";
				}else{
					$_SESSION ['email'] = $email;
					send_email($email);
					header("Location: forgot_password.php?mode=enter_code");
					die;
				}
                break;
                case 'enter_code':
                // code ..
                $code = ($_POST["code"]);
                $result = code_correct($code);
                 if ($result == "the code is correct"){
                header("Location: forgot_password.php?mode=enter_password" );
                die;
                 } else{
                    $error [] = $result;
                 }
                break;
                case 'enter_password':
                // code..
                $password = ($_POST["password"]);
                $password_confirmation = ($_POST["password_confirmation"]);
                 if($password !== $password_confirmation){
                    $error [] = "password does not match";
                 } else{
                    save_password($password);
                header("Location: log_in.php" );
                die;
            }
                break;
                default:
                // code..
                break;

            }
        }
         function send_email($email){
            
            global $con;

           $expire = time() + (60 * 1);
           $code = rand(1000,99999);
           $email = addslashes($email);
           $query ="INSERT INTO codes (email,code,expire) value ('$email','$code','$expire')";
          mysqli_query($con,$query);
         // mail ($mail, 'kamiti prison: reset password', 'your code is' .$code);
        }
        function save_password($password){
            
            global $con;

            $password = password_hash($password,PASSWORD_DEFAULT);
            $email = addslashes($_SESSION['email']);
            $query ="UPDATE  user set password_hash = '$password' WHERE email = '$email' limit 1)";
           mysqli_query($con,$query);
        }
        function valid_email($email){
            
            global $con;

            $email = addslashes($email);
            $query = "SELECT  * FROM user WHERE email = '$email' limit 1";		
            $result = mysqli_query($con,$query);
            if($result){
                if(mysqli_num_rows($result) > 0)
                {
                    return true;
                 }
            }
    
            return false;
    
        }
          
           
         function code_correct($code){
            global $con;
            $code = addslashes($code);
            $expire = time();
            $email = addslashes($_SESSION['email']);
            $query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
            $result = mysqli_query($con,$query);
            if($result){
                if(mysqli_num_rows($result) > 0)
                {
                    $row = mysqli_fetch_assoc($result);
                    if($row['expire'] > $expire){
    
                        return "the code is correct";
                    }else{
                        return "the code is expired";
                    }
                }else{
                    return "the code is incorrect";
                }
            }
    
            return "the code is incorrect";
        }

         
?>
<!DOCTYPE html>
<head>
    <title>log in</title>
    <meta charset="UTF-8">
    <style>
        *{
            font-family: tahoma;
            font-size: 13px;
        }
        form{
            width: 100%;
            max-width: 200px;
            margin:auto;
            border: solid thin #ccc;
            padding: 10px;
        }
        .e{
                padding: 5px;
                width: 180px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<html>
    <body>
        <?php
        switch ($mode){
            case 'enter_email'
            //code..
            ?>
                <form action="forgot_password.php?mode=enter_email" method="post" >
            <div>
                <h2>please fill in your email</h2>
            </div>
            <span style="font-size: 12px;color:red;" >
            <?php
                foreach ($error as $err) {
                    # code...
                    echo $err . "<br>";
                }
            ?>
            </span>
            <div>
       <label for="email">email</label>
       <input  class="e" type="email" id="email" name="email" placeholder="email">
    <div>
    <button type="submit" value="next">Next</button>       
</div>
    <div>
    <a href="log_in.php">log in</a>
    </div>
    </form> 
       <?php
            break;
            case 'enter_code'
            // code ..
            ?>
             <form action="forgot_password.php?mode=enter_code" method="post" >
            <div>
            <h2>enter the code sent to  your email</h2>
            </div>
            <span style="font-size: 12px;color:red;" >
            <?php
                foreach ($error as $err) {
                    # code...
                    echo $err . "<br>";
                }
            ?>
            </span>
            <div>
             <label for="code">code</label>
             <input  class="e" type="text" id="code" name="code" placeholder="123...">
                <div>
                <button type="submit" value="next" style="float: right;">Next</button> 
                    </div>
                    <div>
                    <a href="forgot_password.php">
                    <button type="button" value="start over">Start over</button> 
                   </a>
                    </div>
                        <div>
                         <a href="log_in.php">log in</a>
                        </div>
                            </form> 
   <?php
            
            break;
            case 'enter_password'
            // code..
            ?>
            <form action="forgot_password.php?mode=enter_password" method="post" >
           <div>
           <h2>enter your new  password</h2>
           </div>
           <span style="font-size: 12px;color:red;" >
            <?php
                foreach ($error as $err) {
                    # code...
                    echo $err . "<br>";
                }
            ?>
            </span>
           <div>
            <label for="password">password</label>
            <input  class="e" type="password" id="password" name="password" placeholder="password">
               <div>
               <div>
            <label for="password_confirmation">password confirmation</label>
            <input  class="e" type="password" id="password_confirmation" name="password_confirmation" placeholder="password-confirmation">
               <div>
               <button type="submit" value="next" style="float: right;">Next</button> 
                   </div>
                   <div>
                   <a href="forgot_password.php">
                   <button type="button" value="start over">start over</button> 
                  </a>
                   </div>
                       <div>
                        <a href="log_in.php">log in</a>
                       </div>
                           </form> 
  <?php
            break;
            default:
            // code..
            break;

        }
        ?>
      
        
      
    </body>
</html>