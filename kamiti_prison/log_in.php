<?php 
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] ==="POST"){
    
    $mysqli = require __DIR__ . "/database.php";
    $sql = sprintf("SELECT * FROM user
    WHERE email= '%s'",
    $mysqli->real_escape_string($_POST["email"]));
    $result =$mysqli->query($sql);
    $user = $result->fetch_assoc();

    if($user){
        if(password_verify($_POST["password"], $user["password_hash"])){
        session_start();
        $_SESSION["user_id"] = $user["id"];
        header("Location: IndexX.php");
        exit;
        }
    }
    $is_invalid = true;
   /* var_dump($user);
    exit;*/
}
?>

<!DOCTYPE html>
<head>
    <title>log in</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<html>
    <body>
        <h1>login</h1>
        <?php
        if ($is_invalid):
        ?>
        <em>Invalid login</em>
        <?php
            endif;
            ?>
        <form action="" method="post" >
            <div>
       <label for="email">email</label>
       <input type="email" name="email" id="email"
       value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
       </div>
       <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div>
    <button type="submit" value="log_in">submit</button> 
    </div>
    <div>
    <a href="forgot_password.php">Forgot Password?</a>
    </div>
    </form>
      
    </body>
</html>