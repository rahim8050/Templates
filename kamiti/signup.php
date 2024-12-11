<?php
    include_once 'header.php';
?>
<head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"></head>
<style type="text/css">
   .signup-form-form{
    display: block;
   } 
</style>
                <section class="signup-form">
                    <h2>sign up form</h2>
                    <div class="signup-form-form">
                    <form action="signup.inc.php" method="post">
                        <input type="text" name="name" placeholder="Full name..."><br><br>
                         <input type="text" name="email" placeholder="email..."><br><br>
                          <input type="text" name="uid" placeholder="username..."><br><br>
                          <input type="password" name="pwd" placeholder="password..."><br><br>
                          <input type="password" name="pwdrepeat" placeholder="repeat password..."><br><br>
                            <button type="submit" name="submit"> sign up </button>
                        </div>
                    </form>
                </section>

<?php
    include_once 'footer.php';
?>