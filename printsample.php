<!--<?php 
 session_start();
?>

<?php

require_once 'db_params.php';

    if(isset($_POST['Login'])){ //check if browser has posted any data to be collected
$email=$_POST['email'];
$phon=$_POST['phone'];

    $query= "SELECT * FROM studentreg where email='$email' AND phone='$phon'"; 
    $result= $link->query($query);

if (!$result->num_rows ==1){

    echo"Login Failed Invalid Credentails";
}
else{
    
    $_SESSION['email']= $email;
    header("location:print-slip.php");


}   
}
function get_post($cn, $var)
{
return $link->real_escape_string($_POST[$var]);
}
?>





-->