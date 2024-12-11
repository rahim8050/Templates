 <?php
  session_start();
 session_destroy();
 header("Location: IndeX.php");
 exit;