<?php include 'inc/config.php'; ?>
<?php
session_start();
$sur = $_SESSION['SUR_NAME'];
if (!$sur){
header("location:index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <?php echo $template['title'] ?> Online Screening Examination</title>
<link rel="stylesheet" href="styselect.css" />
<style type="text/css">
table a{font-family:tahoma; font-size:20px; text-decoration:none; color:white;}
table a:hover{text-decoration:underline; color:#999;}
#aa a{text-decoration:none;}
#aa a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div id="header">
<br /><br /><br />
<div class="hd" style="color:black; font-size:50px; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif; font-weight:bold; line-height:40px; margin-top:-53px;" ><?php echo $template['title'] ?> <BR /><span style="font-family:tahoma; font-size:30px; color: #333333">Online Screening Examination</span><hr /></div>



<fieldset style="border:1px solid #999999; width:50%; margin:auto; font-size:21px;"><legend><b>Candidate's Information</b></legend>
             
<span style="font-family:tahoma; text-transform:uppercase; font-size:18px; color:grey">Welcome...You are:  <b style="color:black"><?php echo $_SESSION['SUR_NAME'].' '.$_SESSION['OTHER_NAME'];?></span><br />
</b>
<span style="font-family:tahoma; text-transform:uppercase; font-size:18px; color:grey">Secondary School Level:  <b style="color:black"><?php echo $_SESSION['LEVEL'];?></span><br />

</b>
<span style="font-family:tahoma; text-transform:uppercase; font-size:18px; color:grey">Exam / Question ID:  <b style="color:black"><?php echo $_SESSION['STUDENT_ID'];?></span><br />
</b>
</fieldset>
<span id="aa"><a style="font-size:20px;"href="logout.php"><span style="color:red">Logout</span></span></a>
					
                    
				
				<br /><br />
                
					<nav id="nav" >
						<div  style="background-color:black; width:900px; margin:auto; height:auto; color:white; border-radius:10px 10px 0px 0px; font-size:20px">
                   <br>
                   
                     <?php
require 'db_params.php';
$idd = 	$_SESSION['STUDENT_ID'];
$result3 = mysql_query ("SELECT * FROM studentreg WHERE id=$idd");
while ($row3 = mysql_fetch_array($result3))
{

 
			
$picture = $row3['photo'];
 $pic = "<img border=\"0\" src=\"".$row3['photo']. "\" width=\"180px\" height=\"150px\" alt=\"Your Name\" height\"20px\">";
 
 echo '<table width="53%" cellpadding="5px"  border="0" style="border-color:white" align="center">
<tr><td rowspan="2">'.$pic.'</td><td width="7%" height="65" valign="bottom"><img src="glx/HLPBUTT2.JPG" width="50" height="50" align="middle"></td>
<td width="93%" valign="bottom" bordercolor="#0000FF"> <a href="sublist.php" class="style4">Proceed to Examination </a></td> </tr>
<tr><td height="58" valign="bottom"><img src="glx/DEGREE.JPG" width="43" height="43" align="absmiddle"></td>    <td valign="bottom"> <a href="result.php" class="style4">Result </a></td>
  </tr>
</table>';

}
?>
	
<br />

                        </div>
					</nav>

			
</div>


<br /><br />
		<br /><br />
</body>
</html>