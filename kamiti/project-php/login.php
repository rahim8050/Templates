<?php
    include_once 'header.php';
?>
              <head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"></head>
                <section class="signup-form"> 
                    <h2>log in</h2>
                      <div class="signup-form-form">
                    <form action="login.inc.php" method="post">
                      
                        <input type="text" name="name" placeholder="Full name...">
                         
                          <input type="password" name="pwd" placeholder="password...">
              
                            <button type="submit" name="submit"> log in </button>
                        </div>
                    </form>
                </section>

<?php
    include_once 'footer.php';
?>