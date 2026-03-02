<?php
session_start();
 error_reporting(1);

require ('db_params.php');

//require('mysql.php');

$regNumber = trim($_POST['reg']);
$sql=$cn->query("SELECT * FROM regcode WHERE code='$regNumber' LIMIT 1") or die($cn->error);
$queryFetch = $sql->fetch_array(); 

if($sql->num_rows > 0){
         //echo "Pin exist..";
        if ($queryFetch['status'] == "0"){
            $_SESSION['USR'] = $regNumber;
            echo "<script> window.location='register.php';</script>";
        }else{
            echo "<script> alert('The PIN You Entered Has Already Been Used');
            window.location='apply.php';
            </script>";
        }
}else{
    echo "<script>
        alert('The e-PIN You Entered is Invalid or Does Not Exist. Kindly Re-check Your e-PIN and Try Again');
        window.location='apply.php';
    </script>";
}
?>