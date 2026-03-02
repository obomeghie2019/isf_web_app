<?php
require 'db_params.php';

    if(isset($_POST['phonesubmit'])){ //check if browser has posted any data to be collected
$email=$_POST['email'];

    $query= "SELECT * FROM studentreg where email='$email'"; 
    $result= $link->query($query);

if (!$result->num_rows ==1){

     echo "<script>alert('Wrong Phone Number Entered')</script>";
}
else{
    
    $_SESSION['email']= $email;
    header("location:ppprint.php");


}   
}
function get_post($link, $var)
{
return $link->real_escape_string($_POST[$var]);
}
?>