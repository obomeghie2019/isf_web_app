<?php
session_start();
error_reporting(0);
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

}
//if(!isset($_SESSION[sid]) || !isset($_SESSION[tid]))
//{
	//header("location: index.php");
//}
?>
	<?php



	
			$sqladmin = mysql_query ("UPDATE studentreg SET status = 'done' WHERE id = '$loginid'");
	
		
			
				mysql_query("insert into mst_result(login,test_id,test_date,score) values('$loginid',$tid,'$tdate',$_SESSION[trueans])") or die(mysql_error());
			
?>
					
				<script type="text/javascript">
				
					window.location='result.php';
				</script>
				