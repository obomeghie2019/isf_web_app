<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once "PHPMailer/PHPMailer.php";
require_once 'PHPMailer/Exception.php';


//PHPMailer Object
$mail = new PHPMailer;

    
//From email address and name
        
$mail->setFrom($emaila);
   
$mail->FromName = $name;
              
 

$mail->addAddress("$email"); 
//Recipient name is optional

//Address to which recipient will reply


//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Registration Completed";
$mail->Body = '
              
               
<div style="background: #f5f7f8;width: 100%;height: 100%; font-family: sans-serif; font-weight: 100;" class="be_container"> 


<div style="background:#fff;max-width: 600px;margin: 0px auto;padding: 30px;"class="be_inner_containr"> <div class="be_header">



<div class="be_logo" style="float: left;"> 
<img src="https://<?php echo $bankurl;?>/admin/c2wad/logo/<?php echo $logo;?>" alt="navbar brand" 
                    
style="height:40px;width:100px; margin-top:15px;"> 
</div>

<div class="be_user" style="float: right"> <p>Dear: '.$username.'</p> </div> 

<div style="clear: both;">

</div> 

<div class="be_bluebar" style="background: #1976d2; padding: 20px; color: #fff;margin-top: 10px;">

<h1>Thank you for Registering on  '.$name.'</h1>

</div> </div> 

<div class="be_body" style="padding: 20px;"> <p style="line-height: 25px;"> 
You will receive a notification once your account has been activated!</p>
</br>

        </p>

<div class="be_footer">
<div style="border-bottom: 1px solid #ccc;"></div>


<div class="be_bluebar" style="background: #1976d2; padding: 20px; color: #fff;margin-top: 10px;">

<p> Please do not reply to this email. Emails sent to this address will not be answered. 
Copyright ©2021 '.$name.'. </p> <div class="be_logo" style=" width:60px;height:40px;float: right;"> </div> </div> </div> </div></div>      
              


';

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 