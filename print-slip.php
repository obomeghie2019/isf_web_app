
<?php
 session_start();
require_once 'db_params.php';

    if(isset($_POST['printslip'])){ //check if browser has posted any data to be collected
$fone=$_POST['phone'];

    $query= "SELECT * FROM atheletes where phone='$fone'"; 
    $result= mysqli_query($cn,$sql);

if (!$result->num_rows ==1){


  echo  'Phone Number Not Fund ISF 2024 Registration Contact The Support Or Try Again!.';
           

}
else{
    
    $_SESSION['phone']= $fone;
    header("location:print-slip.php");

}   
}


?>


 <?php 
  $sql= "SELECT * FROM atheletes WHERE phone = '$fone'";
 $result = mysqli_query($cn,$sql);
              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)){  
                  if(isset($row['phone']) ){
  $appnum=$row['appno'];
  $atnames = $row['names'];                                 
  $statt = $row['state'];
   $sexx = $row['gender'];
   $phon = $row['phone'];
   $lgov = $row['LGA'];
   $run = $row['purpose'];
   $regdate=$row['date'];



                      
                  }else{
                     
                  }
                }
              }
                  
                  ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ISF Marathon 2024 Registration Slip</title>
<style type="text/css">

table tr td a{color:blue; text-decoration:none;}
table tr td a:hover{ text-decoration:underline;}



</style>
</head>

<body style="width:600px; margin:auto; height:auto; font-family:tahoma; font-size:11px">
<br /><br />
<table border="0px" width="100%">
<!-- <td><a style="color:red" href="logout.php"><b>Logout</b></a></td></tr> -->


<table border="0px" width="100%"><br />
<tr><td width="100px" ><img height="80px" width="300px" src="img/logoisf.png" /></td><td valign="top" style="padding:10px; font-size:14px; text-align:center"><b  style="font-size:17px"><?php echo $template['title'] ?></b><br />
<span style=" font-size:15px;">ISF Marathon Registration 2024 </span><BR />
<b style="font-family:'Times New Roman', Times, serif;">Athelete(s) Registration Slip
<span style="font-family:'Times New Roman', Times, serif; font-size:11px; font-style:italic;"></span> <b><?php echo $level; ?></b>

</td><td width="100px"></b></td></tr>

<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>APPLICATION NUMBER:</td></tr>
<tr style="font-size:13px; text-transform:uppercase"><td><?php echo $appnum; ?></td></tr>





<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>ATHELETE NAMES:</td><td>PHONE NO:</td></tr>
<tr style="font-size:13px; text-transform:uppercase"><td><?php echo $atnames; ?></td><td><?php echo $phon; ?></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>SEX:</td><td>STATE:</td></tr>
<tr style="font-size:13px;">
    <td width="270px"><?php echo $sexx; ?></td>
    <td><?php echo $statt; ?></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td>RUN FOR:</td><td>REGISTERED DATE:</td></tr>
<tr style="font-size:13px;">
    <td width="270px"><?php echo $run; ?></td>
    <td><?php echo $regdate; ?></td></tr>

<tr style="font-size:13px;"><td width="270px" style="border-top:2px solid grey;" ><b>ISF (6KM) Marathon Rules:</b></td><td style="border-top:2px solid grey;"><b></b></td></tr>



<table border="0px" width="100%"><br  /><br />
<tr style="font-size:13px; font-weight:bold"><td colspan="2">Below are the marathon rules:</td></tr>
<td colspan="2"> 
    <b>(a)</b>Use of drugs or any intoxicant before or during marathon race is highly <b>PROHIBITED</b>If found guilty of using any drugs you will be disqualified even after completing the race.</td></tr>
<td colspan="2">
    <b>(b)</b>You must stay on the marathon race track throughout the race from <b>START</b> to <b>FINISH</b>, if you leave the race track you will be disqualified.</td></tr>

    <td colspan="2">
        <b>(c)</b>Always make sure you wear your Bib No for easy identification.</td></tr>


<table border="0px" width="100%"><br  /><br />
<tr style="font-size:12px;"><td width="10px" style="padding:10px; border-top:2px solid grey; border-bottom:2px solid grey;"><b></b></td><td style="padding:5px; font-size:11px; border-top:2px solid grey; border-bottom:2px solid grey;">Congratulation for your successful registration.<br><b>We hope to see you at the starting point Madaste Juction Auchi-Aviele Road, After NNPC, Edo State, Nigeria</b></td></tr>


<table border="0px" width="100%"><br  /><br />
<tr style="font-size:11px;" align="right"><td width="480px"><b><a href="javascript:window.print()">Print Slip</b></td><td><b><a style="color:red" href="re-print.php">Close Window</a></b></td></tr>


</table>

<br /><br />
<br /><br />
<br /><br />
<br /><br />
</body>
</html>
