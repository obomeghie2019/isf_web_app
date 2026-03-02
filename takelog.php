<?php
session_start();
require 'db_params.php';

	if (isset($_POST['takesubmit'])){
		$take = $_POST['take'];
	
		
	//Create query
	$qry199="SELECT * FROM studentreg WHERE jamb='$take' AND time='active'";
	$result199=mysql_query($qry199);
	
	//Check whether the query was successful or not
	if($result199) {
		if(mysql_num_rows($result199) > 0) {
			//Login Successful
			session_regenerate_id();
			$reger = mysql_fetch_assoc($result199);
			$_SESSION['STUDENT_ID'] = $reger['id'];
			$_SESSION['SUR_NAME'] = $reger['surname'];
			$_SESSION['OTHER_NAME'] = $reger['othernames'];
			$_SESSION['LEVEL'] = $reger['level'];

			session_write_close();
			//if ($level="admin"){
			header("location: select.php");
		}else {
			//Login failed

		$st= mysql_query ("SELECT * FROM studentreg WHERE jamb='$take'");
		$later = mysql_fetch_array($st);
		if ($later['time']=="inactive"){
				
				echo '<script type="text/javascript">
alert("Sorry! This Jamb Number is awaiting activation. Kindly check back");
</script>';

			}elseif ($later['time']=="re-register"){
echo '<script type="text/javascript">
alert("Sorry! This Jamb Number has been blocked. Kindly purchase another scratch card and Re-register");
</script>';

				}else{
					
				echo '<script type="text/javascript">
alert("The JAMB NUMBER you entered is wrong or does not exist. Kindly re-check your Stratch Card and try again later");
</script>';
				
			
		}
	}
	}
		}
?>
