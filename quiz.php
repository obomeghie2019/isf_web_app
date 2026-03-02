<?php include 'inc/config.php'; ?>
<?php
session_start();
$loginid = $_SESSION['STUDENT_ID'];
error_reporting(1);
$time = date("h:i:s");
$date = date("l, F j, Y");
$tdate = $time.'  '.$date;
include("db_params.php");
extract($_POST);
extract($_GET);
extract($_SESSION);
/*$rs=mysql_query("select * from mst_question where test_id=$tid",$cn) or die(mysql_error());
if($_SESSION[qn]>mysql_num_rows($rs))
{
unset($_SESSION[qn]);
exit;
}*/
if(isset($subid) && isset($testid))
{
$_SESSION[sid]=$subid;
$_SESSION[tid]=$testid;
header("location:quiz.php");
}
//if(!isset($_SESSION[sid]) || !isset($_SESSION[tid]))
//{
	//header("location: index.php");
//}
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
	.tans{color:green;}
	.fans{color:red;}
	.head1{font-size:20px;}
	.back a{ text-decoration:none; color:red;}
	.back a:hover{ text-decoration:none; color:#FF6A6F}
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
					<?php echo $template['description'] ?><br />
					<?php echo $template['author'] ?><br />
					Lxndrbrain@gmail.com
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
					<span class="titletext" style="font-size:19px;"><b style="font-size:26px">30</b> Questions in All. <span style="color:black; font-weight:bold">Time To Be Used:</span> <span style="font-size:22px; color:#990000">45 Minutes</span></span><hr />
					
					
					<?php 
					//$se = mysql_query ("SELECT * FROM timer WHERE studentid = '$loginid'");
					//$rowse = mysql_fetch_assoc($se);
					
					
					
					include 'timer.php';?>
					</div>
									
					<div class="bodytext" style="padding:19px; font-size:20px; color:black;" align="justify" >
					
					
					   <?php
					   $timeri = date('H:i:s');
$timi = mysql_query ("SELECT * FROM timer WHERE studentid = '$loginid'");
$rowtimer = mysql_fetch_assoc($timi);
if ($rowtimer['studentid'] == $loginid){

}else{	
$timer = mysql_query ("INSERT INTO timer (studentid,timer) VALUES ('$loginid', '$timeri')");

}




$scoli = mysql_query ("SELECT * FROM studentreg WHERE id = '$loginid'");
$rowli = mysql_fetch_assoc($scoli);
if ($rowli['status'] == "done"){
echo '<script type="text/javascript">

alert("You have already taken exam; Don\'t be over-smart!!!");
</script>';
echo '<div class="back">	<span style="font-size:20px; color: #003366">Exam Already taken by <B>YOU</B></span>';	
	?>
	<script type="text/javascript">
				
					window.location='select.php';
				</script>
	
	<?php
			
}else{



$query="select * from question";

$rs=mysql_query("select * from question where test_id=$tid and studentid=$loginid",$cn) or die(mysql_error());
if(!isset($_SESSION[qn]))
{
	$_SESSION[qn]=0;
	mysql_query("delete from mst_useranswer where sess_id='" . session_id() ."'") or die(mysql_error());
	$_SESSION[trueans]=0;
	
}
else
{	
		if($submit=='Next Question' && isset($ans))
		{
				mysql_data_seek($rs,$_SESSION[qn]);
				$row= mysql_fetch_row($rs);	
				mysql_query("insert into mst_useranswer(sess_id, test_id, que_des, ans1,ans2,ans3,ans4,true_ans,your_ans) values ('".session_id()."', $tid,'$row[2]','$row[4]','$row[5]','$row[6]', '$row[7]','$row[8]','$ans')") or die(mysql_error());
				if($ans==$row[8])
				{
							$_SESSION[trueans]=$_SESSION[trueans]+1;
				}
				$_SESSION[qn]=$_SESSION[qn]+1;
		}
		else if($submit=='Submit Exam' && isset($ans))
		{
		
			$sqladmin = mysql_query ("UPDATE studentreg SET status = 'done' WHERE id = '$loginid'");
	
		
				mysql_data_seek($rs,$_SESSION[qn]);
				$row= mysql_fetch_row($rs);	
				mysql_query("insert into mst_useranswer(sess_id, test_id, que_des, ans1,ans2,ans3,ans4,true_ans,your_ans) values ('".session_id()."', $tid,'$row[2]','$row[4]','$row[5]','$row[6]', '$row[7]','$row[8]','$ans')") or die(mysql_error());
				if($ans==$row[8])
				{
							$_SESSION[trueans]=$_SESSION[trueans]+1;
				}
				echo "<h1 class=head1> </h1>";
				$_SESSION[qn]=$_SESSION[qn]+1;
				echo "<Table align=center><tr class=tot><td>";
				echo "<tr class=tans><td>";
				$w=$_SESSION[qn]-$_SESSION[trueans];
				echo "<tr class=fans><td>";
				echo "</table>";
				mysql_query("insert into mst_result(login,test_id,test_date,score) values('$loginid',$tid,'$tdate',$_SESSION[trueans])") or die(mysql_error());
				echo "<h2 align=center><a href=review.php> Review Question</a> </h2>";
				unset($_SESSION[qn]);
				unset($_SESSION[sid]);
				unset($_SESSION[tid]);
				unset($_SESSION[trueans]);
				exit;
		}
}
$rs=mysql_query("select * from question where test_id=$tid and studentid=$loginid",$cn) or die(mysql_error());
if($_SESSION[qn]>mysql_num_rows($rs)-1)
{
unset($_SESSION[qn]);
echo "<h1 class=head1>No Question(s) for this User yet. Kindly check back later</h1>";

?>
<span style="font-size:25px">Please</span> <a style="font-size:25px" href=select.php> Let Out</a>
<?php
exit;
}
mysql_data_seek($rs,$_SESSION[qn]);
$row= mysql_fetch_row($rs);
echo "<form name=myfm method=post action=quiz.php>";
echo "<table width=100%> <tr> <td width=30>&nbsp;<td> <table border=0>";
$n=$_SESSION[qn]+1;
echo "<tR><td><span class=style2><b>Question(s) ".  $n .":</b><br> $row[2]</style>";
echo "<tr><td class=style8><input type=radio name=ans value=1>$row[4]";
echo "<tr><td class=style8> <input type=radio name=ans value=2>$row[5]";
echo "<tr><td class=style8><input type=radio name=ans value=3>$row[6]";
echo "<tr><td class=style8><input type=radio name=ans value=4>$row[7]";

if($_SESSION[qn]<mysql_num_rows($rs)-1)
echo "<tr><td><input type=submit name=submit value='Next Question'></form>";
else
echo "<tr><td><input type=submit name=submit value='Submit Exam'></form>";
echo "</table></table>";

}
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
				<a style="font-size:17px; font-weight:100;" > <?php echo $template['title'] ?> (c) <?php echo date ('Y');?> <a style="font-size:17px; font-weight:100; color: #99CCFF;">All Right Reserved</a>
				
			</div>
		</div>
	</div>
</body>
</html>