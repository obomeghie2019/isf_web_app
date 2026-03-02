<html><head>
<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>
<script src="js1/application.js" type="text/javascript" charset="utf-8"></script>	
<script src="lib/jquery.js" type="text/javascript"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>

<!--[if lt IE 9]>
<script type="text/javascript" src="js/ie6_script_other.js"></script>
<script type="text/javascript" src="js/html5.js"></script>

<![endif]-->
<style type="text/css">

table td{ font-size:30px; color:red;}
fieldset{border:1px solid red; padding:10px; text-align:justify;}

a{text-decoration:none; color:red;}
</style>
<script type="text/javascript">
jQuery(document).ready(function($) {
$('a[rel*=facebox]').facebox({
loadingImage : 'src/loading.gif',
closeImage   : 'src/closelabel.png'
})
})
</script>
</head><body>
 
<?php 
require "db_params.php";
$get = $_GET['show'];
	$result= mysql_query("select * from studentreg WHERE id=$get")or die(mysql_error());
	echo '<table width="auto" border="0px">';
	
	while($row=mysql_fetch_array($result)){
	$id=$row['id'];
	echo '<span style="color:black; font-size:25px; font-weight:bold; text-transform:uppercase;">'.$row['surname'].' '.$row['othernames'].'</span><hr>
';

echo '<tr><td><img class="img-rounded" src="'. $row['photo'].'" width="400" height="330"></td></tr>';

   echo ' <tr><td><fieldset><legend><b>'.$row['time'].'</b></legend>';
 if ($row['time']=='active'){;
	 echo '<span style="color:blue; font-size:13px;"><br>You have been cleared for your CBT. Kindly proceed with your Online Examination...</span>';
	 }
	 if ($row['time']=='inactive'){;
	 echo '<span style="color:red; font-size:13px;"><br>You have not been cleared. Please wait for the TIME AND DATE specified in your PUTME Print Out...</span>';
	 }
	 if ($row['time']=='re-register'){;
	 echo '<span style="color:red; font-size:13px;"><br>Your registration has seriously encountered error, though you were able to have print-out but you will not be able to partake in the Online Examination because your registration has been nullified and cancelled. Kindly purchase another card and re-register...</span>';
	 }
	 
	 echo '</fieldset';
	 echo '</td></tr>';
	 echo '   <tr> <td style="color:black; font-size:14px;"><b>Screening Examination Status:</b> '.$row['status'].'</td></tr>';

	 
	 
	 } 
	 
	 echo '</table>';
	 
	 ?>
	 
</body>
</html>
