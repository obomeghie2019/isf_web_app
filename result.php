<?php include 'inc/config.php'; ?>
<?php
session_start();
$loginid = $_SESSION['STUDENT_ID'];		
 require 'db_params.php';
$check = 'done';
 $timi = mysql_query ("SELECT * FROM studentreg WHERE id = '$loginid'");
$rowtimer = mysql_fetch_assoc($timi);
if ($rowtimer['status'] != $check){
echo '<script type="text/javascript">
alert("You have not taken any Exam. No result for you Yet");
</script>';
?>
	<script type="text/javascript">
				
					window.location='select.php';
				</script>
				<?php
}else{	

			  
          $sql=mysql_query("SELECT * FROM studentreg WHERE id='$loginid'");
						$rol = mysql_num_rows($sql);
						
				
					while($appost=mysql_fetch_assoc($sql))
					{
					    $surname = $appost['surname'];
						 $othernames = $appost['othernames'];
						  $level = $appost['level'];
						   $jamb = $appost['jamb'];
						    $appno = $appost['appno'];
							 $sub1 = $appost['sub1'];
							  $grade1 = $appost['grade1'];
							  $sub2 = $appost['sub2'];
							  $grade2 = $appost['grade2'];
							  $sub3 = $appost['sub3'];
							  $grade3 = $appost['grade3'];
							  $sub4 = $appost['sub4'];
							  $grade4 = $appost['grade4'];
							  $total = $appost['total'];
							  $school = $appost['school'];
							  $faculty = $appost['faculty'];
							  $course = $appost['course'];
							 
					
					
					
					
					
					
					
						$pic = "<img border=\"0\" src=\"".$appost['photo']. "\" width=\"85px\" height=\"100px\" alt=\"Your Name\" height\"20px\">";
          }
		  
				}
		  ?>
		  <?php
		  
		    $sk=mysql_query("SELECT * FROM mst_result WHERE login='$loginid'");
						$rolk = mysql_num_rows($sk);
						
				
					while($appostk=mysql_fetch_assoc($sk))
					{
					    $score = $appostk['score'];
						$score1 = $score * 4;
					
		
		}  
		  ?>
        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $surname.' '.$othernames ;?></title>
<style type="text/css">

table tr td a{color:blue; text-decoration:none;}
table tr td a:hover{ text-decoration:underline;}

</style>
</head>

<body style="width:600px; margin:auto; height:auto; font-family:tahoma; font-size:11px">
<br /><br />
<table border="0px" width="100%">
<tr><td align="right"><img height="40px" width="30px" src="logo.png" /></td><td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $template['title'] ?></b></td><td><a style="color:red" href="logout.php"><b>Logout</b></a></td></tr>

<table border="0px" width="100%"><br />
<tr><td width="100px" ><img height="110px" width="95px" src="logo.png" /></td><td valign="top" style="padding:10px; font-size:14px; text-align:center"><b  style="font-size:17px"><?php echo $template['title'] ?></b><br />
<span style=" font-size:15px;">(DIRECTORATE OF ACADEMIC AFFAIRS)</span><BR />
<b style="font-family:'Times New Roman', Times, serif;">Photo Card for Screening</b><br /><br />
<span style="font-family:'Times New Roman', Times, serif; font-size:11px; font-style:italic;">You Secondary School Level is:</span> <b><?php echo $level; ?></b>

</td><td width="100px"><?php echo $pic; ?></b></td></tr>


<table border="0px" width="100%"><br /><br /><br /><br />
<tr style="font-size:13px; font-weight:bold"><td>EXAM NUMBER</td><td>JAMB REGISTRATION NUMBER</td><td>APPLICATION NUMBER</td></tr>
<tr style="font-size:13px;"><td><?php echo $jamb; ?></td><td><?php echo $jamb; ?></td><td><?php echo $appno; ?></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>SURNAME</td><td>OTHER NAMES</td></tr>
<tr style="font-size:13px; text-transform:uppercase"><td><?php echo $surname; ?></td><td><?php echo $othernames; ?></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>SUBJECTS</td><td>SCORES</td></tr>
<tr style="font-size:13px;"><td width="270px"><?php echo $sub1; ?></td><td><?php echo $grade1; ?></td></tr>
<tr style="font-size:13px;"><td width="270px"><?php echo $sub2; ?></td><td><?php echo $grade2; ?></td></tr>
<tr style="font-size:13px;"><td width="270px"><?php echo $sub3; ?></td><td><?php echo $grade3; ?></td></tr>
<tr style="font-size:13px;"><td width="270px"><?php echo $sub4; ?></td><td><?php echo $grade4; ?></td></tr>
<tr style="font-size:13px;"><td width="270px" style="border-top:2px solid grey;" ><b>TOTAL</b></td><td style="border-top:2px solid grey;"><b><?php echo $total; ?></b></td></tr>


<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td colspan="2">SCHOOL OF CHOICE</td></tr>
<tr style="font-size:13px;"><td width="160px"><b>SCHOOL</b></td><td colspan="2"><?php echo $school; ?></td></tr>
<tr style="font-size:13px;"><td width="160px"><b>FACULTY</b></td><td><?php echo $faculty; ?></td><td style="color:grey;">Examination Type: <b>CBT</b></td></tr>
<tr style="font-size:13px;"><td width="160px"><b>COURSE</b></td><td colspan="2"><?php echo $course; ?></td></tr>


<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold; "><td colspan="2">Post UTME Examination Information</td></tr>
<tr style="font-size:13px;"><td width="160px"><b>UTME REGISTRATION NUMBER</b></td><td><?php echo $jamb; ?></td></tr>
<tr style="font-size:13px;"><td width="160px"><b>COURSE APPLIED:</b></td><td><?php echo $course; ?></td></tr>


<table border="0px" width="100%"><br  /><br />
<tr style="font-size:14px; font-weight:bold"><td><hr /></td></tr>

<table border="0px" width="100%">
<tr style="font-size:14px; font-weight:bold"><td width="170px">POST UTME SCREENING SCORE:</td><td style="font-size:30px"><?php echo $score1.'%'; ?></td></tr>

<table border="0px" width="100%">
<tr style="font-size:14px; font-weight:bold"><td><hr /></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:11px;" align="right"><td width="480px"><b><a href="javascript:window.print()">Print Result</b></td><td><b><a style="color:red" href="logout.php">Close Window</a></b></td></tr>


</table>
<!--
 <a href="javascript:window.print()">Print Slip</a>
 --->
<br /><br />
<br /><br />
<br /><br />
<br /><br />






</body>
</html>
