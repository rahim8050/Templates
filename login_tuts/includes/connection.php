<?php

$conn = mysqli_connect('localhost', 'root', '', 'login_tuts');

if($conn === false){
    die("ERROR:". mysqli_connect_error());
}

?>