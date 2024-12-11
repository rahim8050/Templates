
<HTML XMLns=""> 
<!--
	Student ID : 1784765
	Name: Vivek S Patil
	Functonality : This page is provided to customers to book a cab online.
			 This page will take the booking details like Passenger name, phone, address, pickup point, destination address, pickup date and time.
			A booking record is created after the data provided is validated, and the reference number i provided to the customer.
--> 
  <head> 
    <title>order gun </title>
	
  </head> 
	
  <body>
<H1>Kamiti prison gun ordering</H1>
  <H2>order a gun from our armoury</H2>
	<H3>Please fill the fields below to order a gun</H3>
  <form name="orderingform">
			<table>
			<tr><td>Guard Name</td><td><input type="text" name="GName"></td></tr>
			<tr><td>Contact number:</td><td><input type="text" name="GPhone"></td></tr>
			<tr><td>address:</td><td>Unit number:<input type="text" name="UnitNo"></td></tr>
			<tr><td></td><td>Street Adress:<input type="text" name="StreetA"></td></tr>
			<tr><td></td><td>Service Number:<input type="text" name="SNo"></td></tr>
			<tr><td>Pickup date:</td><td><input type="text" name="pDate">(dd/mm/yyyy)</td></tr>
			<tr><td>Pickup time:</td><td><input type="text" name="pTime">(HH:MM)</td></tr>
			<tr><td><input type="submit" value="Order" /></td><td></td></tr>
		</table>
	
		<input type="hidden" name="Username" value=" <?php echo trim($_GET['Username']); ?> "/>
  </form>
	
  </body> 

<?php 
	if(!(isset($_GET['Username'])) || empty($_GET['Username']))
	{
		
		//Redirect to login page if customer id is not available
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$query_string = "login_php";
		header("Location: http://$host$uri/$query_string");

	}
	else
	{
	if(isset($_GET['GName']) && isset($_GET['GPhone']) && isset($_GET['UnitNo'])  && isset($_GET['StreetA']) && isset($_GET['SNo']) 
	 && isset($_GET['pDate']) && isset($_GET['pTime']))
	{
		//Get the value of customer ID from query string
		$Customer_ID = trim($_GET['Username']);
		//Get the passenger details from form
		$Guard_Name = trim($_GET['GName']);
		$Guard_Phone = trim($_GET['GPhone']);
		$Unit_No = trim($_GET['UnitNo']);
		$StreetA = trim($_GET['StreetA']);
		$SNo = trim($_GET['SNo']);
		$Pickup_Date = trim($_GET['pDate']);
		$Pickup_Time = trim($_GET['pTime']);
		//Check if any input is empty except for unit no
		if(empty($Guard_Name) || empty($Guard_Phone) || empty($StreetA) || empty($SNo)
	    || empty($Pickup_Date) || empty($Pickup_Time))
		{
			echo "Please provide details for all the fields";
			exit();
		}
		else
		{
			//Call a function to validate the date and time inputs
			ValidateDateTime($Pickup_Date,$Pickup_Time);
			//Call a function to validate the pickup time, should be greater than 6 hrs from current time
			if(Validordering($Pickup_Date,$Pickup_Time))
			{
				CreateBooking($Username,$G_Name,$G_Phone,$Unit_No,$Street_A,$SNo,$Pickup_Date,$Pickup_Time);
			}
			else
			{
				echo "ordering  can be placed one hour after the current system time.";
				exit();
			}
		}
	}
	}
	
	//This function takes Date and Time provided by customer and checks if the booking time is greater than 6 hours from current time or not. And returns true if valid else false
	function Validordering($Date,$Time)
	{
		//Concatenate the date and time provided
		$dt = $Date.":".$Time;
		//Create a date format using
		$Date = date_create_from_format('j/n/Y:H:i',$dt);
		//Compare the datetime provided with current time + 6 hours
		if(date_format($Date,'Y/m/j:H:i') < date('Y/m/j:H:i',strtotime('+6 hours')))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	//This function takes Date and Time as input parameters which are entered by the prison guards, and validates if they are in correct format or not by checking against regular expression
	function ValidateDateTime($Pickup_Date,$Pickup_Time)
	{
		//Regular expression for validating the date as DD/MM/YYYY format i.e eg.25/12/2013
		$DatePattern = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(20\d\d)$/";
		//Regular expression for validating the time as HH:MM format i.e. eg. 18:00
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
	
	//This function takes all the parameters required for creating the booking and creates the book record.
	function CreateBooking($Username,$Guard_Name,$Guard_Phone,$Unit_No,$StreetA,$SNo,$Pickup_Date,$Pickup_Time)
	{
		$dtnow = date('Y-m-j H:i:s');
		$status = "unassigned";
		$Pickup_Date = date_create_from_format('j/n/Y',$Pickup_Date);
		$Pickup_Date = date_format($Pickup_Date,'Y-m-j');
		$DBConnect = @mysqli_connect("mysql.ict.swin.edu.au", "s1784765","200385", "s1784765_db")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ".
		mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";
		
		
		$SQLstring = "INSERT INTO ordering values(null,'".$Guard_ID."','".$Guard_Name."','".$Guard_Phone."','".$Unit_No."','".$StreetA."','".$SNo."',
				'".$Pickup_Date."','".$Pickup_Time."','".$dtnow."','".$status."')";
		//echo "<p>".$SQLstring."</p>";
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to insert data into ordering table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		
		$SQLstring = "SELECT MAX(Booking_Number) FROM ordering";		
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to insert data into booking table.</p>"."<p>Error code ".
		mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
		$row = mysqli_fetch_row($queryResult);
		$Pickup_Date = date_create_from_format('Y-m-j',$Pickup_Date);
		$Pickup_Date = date_format($Pickup_Date,'j/m/Y');
		echo "<p>Thank you! Your booking reference number is ".$row[0].". You will be picked up in front of your provided address at ".$Pickup_Time." on ".$Pickup_Date.".</p>";
		echo "<br><br><a href=login_php>Sign out</a>";
		exit();
    }
?>

</HTML>
