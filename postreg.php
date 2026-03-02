<?php
session_start();
require 'db_params.php';
$regNumber = $_SESSION['USR'];

if(isset($_POST['regsubmit'])){
    $athelete = trim(strtoupper($_POST['atname']));
    $gender =trim($_POST['gender']);
    $state = ($_POST['state']);
    $localg = trim($_POST['lga']);
    $email = trim($_POST['email']);
    $phoneno = trim($_POST['phoneno']);
    $regcode = "ISF2024-". mt_rand(1000,9999);
    $postregcode = trim($_POST['$regcode']);
    $date = date('DdMY').' '.date('h:i:sA');
    $fitt = trim($_POST['fit']);
    $run = trim($_POST['run-purpose']);

   
    $query2 = "INSERT INTO atheletes (names, gender, state, LGA, email, phone, date, Med_Fit, appno, purpose) VALUES ('$athelete', '$gender', '$state', '$localg', '$email', '$phoneno', '$date', '$fitt','$regcode','$run')";
        
$postsubmit=mysqli_query($cn, $query2);

 echo "<script> alert('Congratulation! Yous Registration Was Successful. Kindly Wait for an E-Mail or SMS Message From ISF for Update');
</script>";

            ?>
    <script type="text/javascript">
                
                    window.location='index.php';
                </script>
                <?php
       }else{
           //echo mysqli_error();
           echo "<script> alert('An error occured, please try again. or contact the administrator.');
           window.location='index.php';
            </script>";
       }
       
  


?>