<?php include 'inc/config.php'; ?>

<?php
session_start();
require 'db_params.php';
$code_id = $_SESSION['SESS_CODE_NAME'];
$sco = mysql_query ("SELECT * FROM regcode WHERE code = '$code_id'");
$row = mysql_fetch_assoc($sco);
if ($row['status'] == "used"){
header("location:index.php");
}
?>
<?php
if (isset($_POST['submit'])){
$surname = $_POST['surname'];
$othernames = $_POST['othernames'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$religion = $_POST['religion'];
$state = $_POST['state'];
$local = $_POST['local'];
$nationality = $_POST['nationality'];
$date = $_POST['day'].' '.$_POST['month'].' '.$_POST['year'];

$school = $_POST['school'];
$faculty = $_POST['faculty'];
$course = $_POST['course'];
$level = $_POST['level'];
$jamb = $_POST['jamb'];

$sub1 = $_POST['sub1'];
$grade1 = $_POST['grade1'];
$sub2 = $_POST['sub2'];
$grade2 = $_POST['grade2'];
$sub3 = $_POST['sub3'];
$grade3 = $_POST['grade3'];
$sub4 = $_POST['sub4'];
$grade4 = $_POST['grade4'];

}

?>
<?php
include 'register.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $template['title'] ?> Registration </title>
<meta charset="utf-8">
<link rel="stylesheet" href="css1/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css1/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css1/style.css" type="text/css" media="all">
<style type="text/css">
table tr td{border-top:1px dashed #999999; border-bottom:1px dashed #999999; padding:10px; color:black; font-size:18px;}
input{width:360px; border:1px solid #CCCCCC; padding:4px; height:20px;}
input:hover{width:360px; border:1px solid #0066FF; padding:4px; height:20px;}
select{width:130px; border:1px solid #CCCCCC; padding:4px; height:35px;}
.loc select{width:170px; border:1px solid #CCCCCC; padding:4px; height:35px;}
.loc1 select{width:120px;}
.loc2 select{width:73px;}
.loca select{width:250px;}
.loca1 select{width:80px;}
.subm input:hover{ box-shadow:none;}
.reco select{width:330px;}

</style>

<!--[if lt IE 9]>
<script type="text/javascript" src="js/ie6_script_other.js"></script>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
</head>
<body id="page1">
<!-- START PAGE SOURCE -->
<div class="body3"></div>
<div class="body1">
  <div class="main">
    <header>
      <div id="logo_box">
        <h1><a href="#" id="logo"><?php echo $template['title'] ?> <span>Screening Examination Portal</span></a></h1>
      </div>
      <nav>
        <ul id="menu">
          <li id="menu_active"><a href="../logout.php">Home</a></li>
        </ul>
      </nav>
      <div class="wrapper">
        <div class="text1"><?php echo $template['title'] ?> Examination</div>
        <div class="text2"><?php echo date('Y');?> Admission <?php echo $code_id; ?></div>
       
      </div>
    </header>
  </div>
</div>
<div class="body2">
  <div class="main">
    <section id="content">
      <div class="marg_top wrapper">
       
       
      </div>
      <div class="wrapper marg_top2">
 
        <article class="col2 pad_left1">
          <div class="pad">
            <h2>Student Registration</h2>
           <div style="font-size:20px;">
		   <form action="regconfirm.php" method="post" enctype="multipart/form-data">
		   <table border="1px" width="100%">
		   <tr><td colspan="2" style="background-color:#E8E8E8"><b>Student Bio Data</b></td></tr>
		   <tr><td width="150px">Surname</td><td><input type="text" name="surname" placeholder="Your Surname" value="<?php echo $surname; ?>" required><input type="hidden" name="appno" value="<?php echo $code_id;?>"></td></tr>
		   <tr><td>Other Names</td><td><input type="text" name="othernames" placeholder="Your Othernames" value="<?php echo $othernames; ?>" required></td></tr>
		   <tr><td width="150px">Address</td><td><input type="text" name="address" placeholder="Your Address" value="<?php echo $address; ?>" required></td></tr>
		   <tr><td>Phone</td><td><input type="text" name="phone" placeholder="Your Phone Number" value="<?php echo $phone; ?>" required></td></tr>
		   <tr><td>E-mail</td><td><input type="email" name="email" placeholder="Your E-mail" value="<?php echo $email; ?>" required></td></tr>
		   
		    <table border="1px" width="100%">
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="10px">
	<select name="gender" required><option selected="selected" ><?php echo $gender; ?></option>
           <option  value="Male">Male</option>
           <option value="Female">Female</option></select></td><td>
		   
		   <select name="religion" required><option selected="selected"required><?php echo $religion; ?></option>
           <option  value="Christian">Christian</option>
           <option value="Muslim">Muslim</option></select> </td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
	
	
	
	<select name="state" required><option selected="selected"required><?php echo $state; ?></option>
           <option  value="A State">A State</option>
           <option value="B State">B State</option>
           <option value="C State">C State</option></select></td><td>
		<div class="loc">   
		   <select name="local" required><option selected="selected"required><?php echo $local; ?></option>
           <option  value="A LGA">A Local Government</option>
           <option value="B LGA">B Local Government</option>
           <option value="C LGA">C Local Government</option></select> </td></tr>
	</div>
	
	
	</td></tr>
	
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
	
	<div class="loc1">
		   <select name="nationality" required><option selected="selected"required><?php echo $nationality; ?></option>
           <option  value="Nigeria">United State of America</option>
           <option value="Canada">Canada</option></select>
	</div>
	
		   </td><td>
		   
	<div class="bday"><input style="width:150px" type="text" name="bday" value="<?php echo $date ?>" required></div>	   
		   
	   </td></tr>
	
	
	 <table border="1px" width="100%">
		   <tr><td colspan="2" style="background-color:#E8E8E8"><b>Course of Choice</b></td></tr>
		   <tr><td width="150px">School</td><td>
		   <div class="reco">
		   <select name="school" required><option selected="selected"><?php echo $school ?></option>
           <option  value="ABC University">ABC University </option>
           <option  value="ABC Polytechnic">ABC Polytechnic </option>
           <option  value="ABC College">ABC College </option>
           </select>
           </div>
		   </td></tr>
		   <tr><td>Faculty</td><td>
		   <div class="reco">
		   <select name="faculty" required><option selected="selected"required><?php echo $faculty ?></option>
           <option  value="Faculty of Applied Science">Faculty of Applied Science</option>
		   <option  value="Faculty of Applied Science">Faculty of Communication and General Studies</option>
		   <option  value="Faculty of Applied Science">Faculty of Engineering Technology</option>
		   		   </select>
				   </div>
		   </td></tr>
		   <tr><td width="150px">Course</td><td>
		   <div class="reco">
		   <select name="course" required><option selected="selected"required><?php echo $course ?></option>
           <option  value="Computer Engineering">Computer Engineering</option>
		   <option  value="Computer Science">Computer Science</option>
		   <option  value="Mass Coomunication">Mass Coomunication</option>
		   		   </select>
				   </div>
		  </td></tr>
		   
	
	<table border="1px" width="100%">
		   <tr><td colspan="3" style="background-color:#E8E8E8"><b>Jamb (UTM) Details</b></td></tr>
		   <tr><td width="150px">Secondary School Class</td><td colspan="2">
		   <select name="level" required><option selected="selected"required><?php echo $level ?></option>
           <option  value="Science Class">Science Class</option>
		    <option  value="Commercial Class">Commercial Class</option>
			 <option  value="Art Class">Art Class</option>
		   </select>
           </td></tr>
		   <tr><td>Jamb Number</td><td colspan="2"><input type="text" name="jamb" value="<?php echo $jamb;?>" required></td></tr>
		   <tr><td rowspan="4"><br><br><br><br>Jamb Subjects</td><td>
		   <div class="loca">
		   <select name="sub1" required><option selected="selected"required><?php echo $sub1 ?></option>
           <option  value="English Language">English Language</option>
		   <option  value="Mathematics">Mathematics</option>
		   <option  value="Economics">Economics</option>
		    <option  value="Biology">Biology</option>
		   <option  value="Civic Education">Civic Education</option>
		   <option  value="Agricultural Science">Agricultural Science</option>
		    <option  value="Computer Studies">Computer Studies</option>
		   <option  value="Geography">Geography</option>
		   <option  value="Lit-in-English">Lit-in-English</option>
		    <option  value="Government">Government</option>
		   <option  value="Christian Religion Studies">Christian Religion Studies</option>
		   <option  value="Islamic Religion Studies">Islamic Religion Studies</option>
		    <option  value="Commerce">Commerce</option>
		   <option  value="Financial Accounting">Financial Accounting</option>
		   <option  value="Physics">Physics</option>
		   <option  value="Chemistry">Chemistry</option>
		    <option  value="Further Mathematics">Further Mathematics</option>
		   <option  value="Yoruba">Yoruba</option>
		  
		   
		   		   </select></div>
		   </td><td> 
		   <input style="width:70px" type="text" name="grade1" value="<?php echo $grade1 ?>" required>
		   </td></tr>
		   
		   <tr><td>
		   <div class="loca">
		    <select name="sub2" required><option selected="selected"required><?php echo $sub2 ?></option>
           <option  value="English Language">English Language</option>
		   <option  value="Mathematics">Mathematics</option>
		   <option  value="Economics">Economics</option>
		    <option  value="Biology">Biology</option>
		   <option  value="Civic Education">Civic Education</option>
		   <option  value="Agricultural Science">Agricultural Science</option>
		    <option  value="Computer Studies">Computer Studies</option>
		   <option  value="Geography">Geography</option>
		   <option  value="Lit-in-English">Lit-in-English</option>
		    <option  value="Government">Government</option>
		   <option  value="Christian Religion Studies">Christian Religion Studies</option>
		   <option  value="Islamic Religion Studies">Islamic Religion Studies</option>
		    <option  value="Commerce">Commerce</option>
		   <option  value="Financial Accounting">Financial Accounting</option>
		   <option  value="Physics">Physics</option>
		   <option  value="Chemistry">Chemistry</option>
		    <option  value="Further Mathematics">Further Mathematics</option>
		   <option  value="Yoruba">Yoruba</option>
		  
		   
		   		   </select></div>
		  </td><td>  <input style="width:70px" type="text" name="grade2" value="<?php echo $grade2 ?>" required>
		   </td></tr>
		   
		   <tr><td>
		   <div class="loca">
		    <select name="sub3" required><option selected="selected"required><?php echo $sub3 ?></option>
           <option  value="English Language">English Language</option>
		   <option  value="Mathematics">Mathematics</option>
		   <option  value="Economics">Economics</option>
		    <option  value="Biology">Biology</option>
		   <option  value="Civic Education">Civic Education</option>
		   <option  value="Agricultural Science">Agricultural Science</option>
		    <option  value="Computer Studies">Computer Studies</option>
		   <option  value="Geography">Geography</option>
		   <option  value="Lit-in-English">Lit-in-English</option>
		    <option  value="Government">Government</option>
		   <option  value="Christian Religion Studies">Christian Religion Studies</option>
		   <option  value="Islamic Religion Studies">Islamic Religion Studies</option>
		    <option  value="Commerce">Commerce</option>
		   <option  value="Financial Accounting">Financial Accounting</option>
		   <option  value="Physics">Physics</option>
		   <option  value="Chemistry">Chemistry</option>
		    <option  value="Further Mathematics">Further Mathematics</option>
		   <option  value="Yoruba">Yoruba</option>
		  
		   
		   		   </select></div>
		  </td><td><input style="width:70px" type="text" name="grade3" value="<?php echo $grade3 ?>" required>
		   </td></tr>
		   <tr><td>
		   <div class="loca">
		    <select name="sub4" required><option selected="selected"required><?php echo $sub4 ?></option>
           <option  value="English Language">English Language</option>
		   <option  value="Mathematics">Mathematics</option>
		   <option  value="Economics">Economics</option>
		    <option  value="Biology">Biology</option>
		   <option  value="Civic Education">Civic Education</option>
		   <option  value="Agricultural Science">Agricultural Science</option>
		    <option  value="Computer Studies">Computer Studies</option>
		   <option  value="Geography">Geography</option>
		   <option  value="Lit-in-English">Lit-in-English</option>
		    <option  value="Government">Government</option>
		   <option  value="Christian Religion Studies">Christian Religion Studies</option>
		   <option  value="Islamic Religion Studies">Islamic Religion Studies</option>
		    <option  value="Commerce">Commerce</option>
		   <option  value="Financial Accounting">Financial Accounting</option>
		   <option  value="Physics">Physics</option>
		   <option  value="Chemistry">Chemistry</option>
		    <option  value="Further Mathematics">Further Mathematics</option>
		   <option  value="Yoruba">Yoruba</option>
		  
		   
		   		   </select></div>
		  </td><td><input style="width:70px" type="text" name="grade4" value="<?php echo $grade4 ?>" required>
		   </td></tr>
	<table border="1px" width="100%">
		   <tr><td colspan="3" style="background-color:#E8E8E8"><b>Upload Photo Passport</b></td></tr>
	   
		    <tr><td>&nbsp;</td><td colspan="3"><input style="height:35px;" type="file" name="photo" required ></td></tr>
		   
		   
		  <tr><td>&nbsp;</td><td colspan="3"><div class="subm" ><input style="font-size:18px; height:45px; font-weight:bold; color:#0033FF" type="submit" style="height:50px" name="submit1" value="Save / Submit Form" ></div></td></tr>
		   </table>
		   </form>
			
       
	   </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</div>
<div class="main">
  <footer>
    <div class="wrapper">
      
      <article class="col2 pad_left1">
        <div class="pad">
          <div class="wrapper">
            
            
          </div>
        </div>
      </article>
    </div>
    <div class="under2"></div>
    <div class="footerlink">
      <p class="lf" style="font-size:15px;">Copyright &copy; <?php echo date('Y');?> <a href="#"><?php echo $template['title'] ?></a> - All Rights Reserved</p>
 
    </div>
  </footer>
</div>
</body>
</html>