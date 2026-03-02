<?php
error_reporting(0);
session_start();
    
    //Connect to mysql server
    require "db_params.php";
    
    //Function to sanitize values received from the form. Prevents SQL injection
    if (isset($_POST['submit'])){
        $trx = mysql_real_escape_string(trim($_POST['trxref']));
    
        
    //Create query
    $qry="SELECT * FROM paymenthistory WHERE payment_status='1' AND trax_id='$trx'";
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
alert("Payment Was Not Fount. Kindly Make Payment and Try Again");
</script>';

        
        }

    }

}
   
?>

