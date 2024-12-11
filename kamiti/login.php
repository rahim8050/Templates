<?php
    include_once 'header.php';
?>
                <section class="signup-form">
                    <h2>sign up form</h2>
                    <form action="signup.inc.php" method="post">
                        <div class="signup-form-form">
                        <input type="text" name="name" placeholder="Full name...">
                         <input type="text" name="email" placeholder="email...">
                          <input type="text" name="uid" placeholder="username...">
                          <input type="password" name="pwd" placeholder="password...">
                          <input type="password" name="pwdrepeat" placeholder="repeat password...">
                            <button type="submit" name="submit"> sign up </button>
                        </div>
                    </form>
                </section>

<?php
    include_once 'footer.php';
?>