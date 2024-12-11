
<?php 
	if(!(isset($_GET['User_name'])) || empty($_GET['User_name']))
	{
		
		//Redirect to login page if customer id is not available
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$query_string = "login.php";
		header("Location: http://$host$uri/$query_string");

	}
	else
	{
	if(isset($_GET['pName']) && isset($_GET['pPhone']) && isset($_GET['UnitNo']) && isset($_GET['StreetNo']) && isset($_GET['StreetName']) 
	 && isset($_GET['pDate']) && isset($_GET['pTime']))
	{
		//Get the value of customer ID from query string
		$Customer_ID = trim($_GET['User_name']);
		//Get the passenger details from form
		$Customer_Name = trim($_GET['pName']);
		$Passenger_Phone = trim($_GET['pPhone']);
		$Unit_No = trim($_GET['UnitNo']);
		$Street_No = trim($_GET['StreetNo']);
		$Street_Name = trim($_GET['StreetName']);
		$Pickup_Date = trim($_GET['pDate']);
		$Pickup_Time = trim($_GET['pTime']);
		//Check if any input is empty except for unit no
		if(empty($Customer_Name) || empty($Passenger_Phone) || empty($Street_No) || empty($Street_Name)
	    || empty($Pickup_Date) || empty($Pickup_Time))
		{
			echo "Please provide details for all the fields";
			exit();
		}
		else
		{
			//Call a function to validate the date and time inputs
			ValidateDateTime($Pickup_Date,$Pickup_Time);
			//Call a function to validate the pickup time, should be greater than 60 mins from current time
			if(ValidBookingTime($Pickup_Date,$Pickup_Time))
			{
				CreateBooking($User_name,$Customer_Name,$Passenger_Phone,$Unit_No,$Street_No,$Street_Name,$Pickup_Date,$Pickup_Time);
			}
			else
			{
				echo "Booking can be placed one hour after the current system time.";
				exit();
			}
		}
	}
	}
	
	//This function takes Date and Time provided by prison guard and checks if the ordering time is greater than 6hrs from current time or not. And returns true if valid else false
	function ValidBookingTime($Date,$Time)
	{
		//Concatenate the date and time provided
		$dt = $Date.":".$Time;
		//Create a date format using
		$Date = date_create_from_format('j/n/Y:H:i',$dt);
		//Compare the datetime provided with current time + 60 hours
		if(date_format($Date,'Y/m/j:H:i') < date('Y/m/j:H:i',strtotime('+6 hours')))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	//This function takes Date and Time as input parameters which are entered by the prison guard, and validates if they are in correct format or not by checking against regular expression
	function ValidateDateTime($Pickup_Date,$Pickup_Time)
	{
		//Regular expression for validating the date as DD/MM/YYYY format i.e eg.25/12/2013
		$DatePattern = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(20\d\d)$/";
		//Regular expression for validating the time as HH:MM format i.e. eg. 20:00
		$TimePattern = "/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/";
		
		$DateFlag = preg_match($DatePattern,$Pickup_Date);
		$TimeFlag = preg_match($TimePattern,$Pickup_Time);
		if($DateFlag != 1)
		{
			echo "Please enter the valid date in dd/mm/yyyy format";
				exit();
		}
		else
		{
			if($TimeFlag != 1)
			{
				echo "Please enter the valid time in HH:MM format";
				exit();
			}
		}
	}
	
	//This function takes all the parameters required for creating the ordering and creates the order record.
	function CreateBooking($User_name,$Customer_Name,$Passenger_Phone,$Unit_No,$Street_No,$Street_Name,$Suburb,$Destination_Suburn,$Pickup_Date,$Pickup_Time)
	{
		$dtnow = date('Y-m-j H:i:s');
		$status = "unassigned";
		$Pickup_Date = date_create_from_format('j/n/Y',$Pickup_Date);
		$Pickup_Date = date_format($Pickup_Date,'Y-m-j');
		$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
		mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
		
		
		$SQLstring = "INSERT INTO Booking values(null,'".$Customer_ID."','".$Passenger_Name."','".$Passenger_Phone."','".$Unit_No."','".$Street_No."','".$Street_Name."','".
				$Suburb."','".$Destination_Suburn."','".$Pickup_Date."','".$Pickup_Time."','".$dtnow."','".$status."')";
		//echo "<p>".$SQLstring."</p>";
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to insert data into booking table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		
		$SQLstring = "SELECT MAX(Booking_Number) FROM booking";		
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to insert data into booking table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		$row = mysqli_fetch_row($queryResult);
		$Pickup_Date = date_create_from_format('Y-m-j',$Pickup_Date);
		$Pickup_Date = date_format($Pickup_Date,'j/m/Y');
		echo "<p>Thank you! Your booking reference number is ".$row[0].". You will be picked up in front of your provided address at ".$Pickup_Time." on ".$Pickup_Date.".</p>";
		echo "<br><br><a href=login.php>Sign out</a>";
		exit();
    }
?>

  <head> 
    <title>Booking Page</title>
	<link rel="stylesheet" type="text/css" href="book.css"> 
  </head> 
	<html>
<H1>REALTECH</H1>
  <H2>Booking a Tent</H2>
	<H3>Please fill the fields below to book a tent</H3>
  <form name="bookingForm">
			<table>
			<tr><td>name:</td><td><input type="text" name="pName"></td></tr>
			<tr><td>Contact number:</td><td><input type="text" name="pPhone"></td></tr>
			<tr><td>address:</td><td>Unit number:<input type="text" name="UnitNo"></td></tr>
			<tr><td></td><td>Street number:<input type="text" name="StreetNo"></td></tr>
			<tr><td></td><td>Street name:<input type="text" name="StreetName"></td></tr>
			<tr><td>Pickup date:</td><td><input type="text" name="pDate">(dd/mm/yyyy)</td></tr>
			<tr><td>Pickup time:</td><td><input type="text" name="pTime">(HH:MM)</td></tr>
			<tr><td><input type="submit" value="Book" /></td><td></td></tr>
		</table>
	
		<input type="hidden" name="User_name" value=" <?php echo trim($_GET['User_name']); ?> "/>
  </form>
  </html>
  </body> 