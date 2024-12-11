<?php
if (empty($_POST["dates"])){
	die ("time is required");
}
if (empty($_POST["times"])){
	die ("time is required");
}
$mysqli = require __DIR__ . "/database.php";
$sql = "INSERT INTO dates(dates,times) VALUES (?,?)";            
$stmt = $mysqli->stmt_init();
if ( ! $stmt->prepare($sql)){
	die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ss",
					$_POST["dates"],
                    $_POST["times"]
					);
                    if ($stmt->execute()){
                        echo("date and time  submitted successfully");
                        exit;
                    }
                    
                        else{
                        die ($mysqli->error . " " . $mysqli->errno);
                    }
                    
                                    
?>