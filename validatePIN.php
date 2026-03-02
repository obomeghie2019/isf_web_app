<?php
error_reporting(0);
session_start();
    
    //Connect to mysql server
    require "db_params.php";
    
    //Function to sanitize values received from the form. Prevents SQL injection
    if (isset($_POST['regsubmit'])){
        $reg = mysql_real_escape_string(trim($_POST['reg']));
    
        
    //Create query
    $qry="SELECT * FROM atheletes WHERE pin='$reg' AND status='UNUSED'";
    $result=mysql_query($qry);
    
    //Check whether the query was successful or not
    if($result) {
        if(mysql_num_rows($result) > 0) {
            //Login Successful

            //if ($level="admin"){
            header("location: register.php");
        }else {
            //Login failed
            
            
                echo '<script type="text/javascript">
alert("The PIN you entered is wrong or does not exist. Kindly re-check your Card and try again later");
</script>';

        
        }

    }

}
   
?>

<?php
include 'takelog.php';
?>