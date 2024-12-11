<! DOCTYPE html>
<html lang="en">
<head>
         <meta charset="UFT-8">
         <meta http-equiv="X-UA-Compatible" content="IE-edge">
         <meta name="viewport" content="width=device-width,initial-scale=1.0>
         <title>otp verification form</title>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
            <div id="container">
                <h2>Sign up</h2>
                <div id="line">
                    <form action="otp.php" method="POST" autocomplete="off">
                       <?php
                    if(isset($_SESSION['message'])){
                        ?>
                            <div id="alert"> <?php echo $_SESSION['message']; ?> </div>
                            <?php
                        }
                        ?>
                        <div>
                        <input type="number" name="otp" placeholder="verification code" required>
                        </div>
                        <div>
                            <input type="submit" name="verifyEmail" value="verify">
                        </div>
                    </form>
                </div>