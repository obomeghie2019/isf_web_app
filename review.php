<?php include 'inc/config.php'; ?>
<?php
session_start();
extract($_POST);
extract($_SESSION);
include("db_params.php");
if($submit=='Finish')
{
	mysql_query("delete from mst_useranswer where sess_id='" . session_id() ."'") or die(mysql_error());
	unset($_SESSION[qn]);
	header("Location: select.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="author" content="Ritedev Technologies" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="images/style.css" type="text/css" />
	<title><?php echo $_SESSION['SUR_NAME'].' '.$_SESSION['OTHER_NAME'];?></title>
	<style type="text/css">
	img{border:1px solid #666666; box-shadow:5px 5px 29px grey;}
	table tr td a{color:black; text-decoration:none; line-height:40px;}
	table tr td a:hover{color:black; text-decoration:none; color:#006666}
	.style2{color: #990000; line-height:30px; font-size:17px;}
	.style8{color: #003399; line-height:30px; font-size:17px;}
	.tot{font-weight:bold;}
	.tans{color:green; font-weight:bold;}
	.fans{color:red;}
	.head1{font-size:20px;}
	
	</style>
</head>
<body>
	<div id="page" align="center">
		<div id="content" style="width:800px">
			<div id="logo">
				<div style="margin-top:35px; font-size:30px;" class="whitetitle"><?php echo $template['title'] ?></div>
			</div>
			<div id="topheader">
				<div align="left" class="bodytext">
					<br />
					<strong style="font-size:16px"><?php echo $template['title'] ?></strong><br />
					<span style="font-size:15px">
					<?php echo $template['author'] ?><br />
					</span>
					
				</div>
			</div>
			<div id="menu">
				<div align="right" class="smallwhitetext" style="padding:9px;">
					<span style=" color:black; font-size:18px; text-shadow:2px 2px 3px white;"> Kindly Continue...</span> <a style="font-size:16px">Mr. <?php echo $_SESSION['SUR_NAME'].' '.$_SESSION['OTHER_NAME'];?></a><span style="color:red; font-size:18px; text-shadow:1px 1px 2px white;"> (<?php echo $_SESSION['STUDENT_ID'];?>)</span>
				</div>
			</div>
			
			<div id="contenttext">
				<div style="padding:10px"><br />
					<span class="titletext" style="font-size:19px;">Select your Class Level to Continue...</span><hr />
					</div>
									
					<div class="bodytext" style="padding:19px; font-size:20px; color:black;" align="justify" >
					
					
					
					  
<?php



if(!isset($_SESSION[qn]))
{
		$_SESSION[qn]=0;
}
else if($submit=='Next Question' )
{
	$_SESSION[qn]=$_SESSION[qn]+1;
	
}

$rs=mysql_query("select * from mst_useranswer where sess_id='" . session_id() ."'",$cn) or die(mysql_error());
mysql_data_seek($rs,$_SESSION[qn]);
$row= mysql_fetch_row($rs);
echo "<form name=myfm method=post action=review.php>";
echo "<table width=100%> <tr> <td width=30>&nbsp;<td> <table border=0>";
$n=$_SESSION[qn]+1;
echo "<tR><td><span class=style2><b>Question(s) ".  $n .": </b><br>$row[2]</style>";
echo "<tr><td class=".($row[7]==1?'tans':'style8').">$row[3]";
echo "<tr><td class=".($row[7]==2?'tans':'style8').">$row[4]";
echo "<tr><td class=".($row[7]==3?'tans':'style8').">$row[5]";
echo "<tr><td class=".($row[7]==4?'tans':'style8').">$row[6]";
if($_SESSION[qn]<mysql_num_rows($rs)-1)
echo "<tr><td><input type=submit name=submit value='Next Question'></form>";
else
echo "<tr><td><input type=submit name=submit value='Finish'></form>";

echo "</table></table>";
?>

					
					
					
				</div>
			</div>
			<div id="leftpanel">
				<div align="justify" class="graypanel" style="font-size:20px;">
					<span class="smalltitle" style="font-size:17px">Student Information</span><br />
					 <?php
require 'db_params.php';
$idd = 	$_SESSION['STUDENT_ID'];
$result3 = mysql_query ("SELECT * FROM studentreg WHERE id=$idd");
while ($row3 = mysql_fetch_array($result3))
{

 
			
$picture = $row3['photo'];
 $pic = "<img border=\"0\" src=\"".$row3['photo']. "\" width=\"210px\" height=\"200px\" alt=\"Your Name\" height\"20px\">";
 
 echo '<table width="53%" cellpadding="5px"  border="0" style="border-color:white" align="center">
<tr><td>'.$pic.'</td></tr>
</table>';

?><br />
<span style="font-family:tahoma; text-transform:uppercase; font-size:15px; color:grey">Full Name: <br /> <b style="color:black"><?php echo $_SESSION['SUR_NAME'].' '.$_SESSION['OTHER_NAME'];?></span><br /><br />
</b>
<span style="font-family:tahoma; text-transform:uppercase; font-size:18px; color:grey">Secondary School Level:  <br /><b style="color:black"><?php echo $_SESSION['LEVEL'];?></span><br /><br />

</b>

<?php
}
?>
						</div>
			</div>
			<div id="footer" class="smallgraytext">
				<a style="font-size:17px; font-weight:100;" ><?php echo $template['title'] ?> (c) <?php echo date ('Y');?> <a style="font-size:17px; font-weight:100; color: #99CCFF;">All Right Reserved</a>
				
			</div>
		</div>
	</div>
</body>
</html>