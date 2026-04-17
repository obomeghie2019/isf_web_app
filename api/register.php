

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();
require 'db_params.php';



function makePaymentByPaystack($email, $amount, $payid, $payer, $phone){
       $seckey = 'SK_SECKEY';
        $pubkey='PK_PUBKEY';
        $apiurl='https://api.paystack.co/transaction/initialize';
        $verifyurl='https://api.paystack.co/transaction/verify/';
        $callback='https://360globalnetwork.com.ng/isf2024/apply.php'; // create new page success.php

        $actualamt=str_replace(array(' ',','), '', $amount);
        $amount = (float)$amount * 100;


        $fields = [
            'email' => $email,
            'amount' => $amount,
            'phone' => $phone,
            'callback_url' => $callback,
              'metadata' =>  array(
                  'payer' =>$payer,
                  'phone' =>  $phone,
                  'email' =>  $email,
                  'regno' => $payid,
                  'amt' => $amount,
                  'actualamt' => $actualamt,
                  'callback_url' => $callback,
                    'custom_fields' => array(
                      'phone' =>  $phone,
                      'email' =>  $email,
                      'regno' => $payid,
                      'payer' =>$payer,
                      'callback_url' => $callback,
                      'paymenttype' => 'ISF Marathon 2025 Payment'
                    )
                )
        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $apiurl);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".$seckey,
            "Cache-Control: no-cache",
        ));

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;   

}

function verifyPaymentByPaystack($trans, $refno){
      $seckey = $_ENV['STRIPE_SECRET_KEY'];
        $pubkey=$_ENV['STRIPE_SECRET_KEY'];
        $apiurl='https://api.paystack.co/transaction/initialize';
        $verifyurl='https://api.paystack.co/transaction/verify/';
        $callback='https://360globalnetwork.com.ng/isf2024/register.php';

        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$seckey
        );

        // Set the url
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_URL, $verifyurl.$refno);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // Set the header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HEADER, false);


        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

  

        if ($err) {
            return false;
        } else {
            return $result;
        }

}


/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo 'Form submitted via POST!';
    if (isset($_POST['btnPay'])) {
        echo 'Button pressed and form submitted!';
        // Proceed with the rest of the logic...
    } else {
        echo 'btnPay not detected in POST data.';
    }
} else {
    echo 'No POST request received.';*/

if(isset($_POST['btnPay'])){
    $email=trim(strtolower($_POST['uemail']));
    $phone=trim($_POST['uphone']);
    $amt=str_replace(array(' ', ','), '', $_POST['amount']);
    $payerid=trim($_POST['uregno']);
    $payer=trim(strtoupper(strtolower($_POST['payer1'])));

    $response = makePaymentByPaystack($email, $amt, $payerid, $payer, $phone);
    $result=json_decode($response, true);

   // var_dump($response);// to see JSON data

    if($result['status'] == 'true' || $result['status'] === true){
        $url=$result['data']['authorization_url'];
        $accesscode=$result['data']['access_code'];
        $refno=$result['data']['reference'];

        die('<script type="text/javascript">window.location.href="'.$url.'";</script>');
        exit();
    }
}


if(isset($_GET['trxref']) && isset($_GET['reference'])){
   $trx=$_GET['trxref'];
  
    $refno=$_GET['reference'];
    $response=verifyPaymentByPaystack($trx, $refno);
   // var_dump($response);// to see JSON data returned from paystack
    $result=json_decode($response, true);
    die('Error: Unable to parse Paystack response. Response: ' . $response);
//exit();
    if($result['status'] == true){
        if($result['data']['status']=='success'){
            
            $refid=$result['data']['id'];
            $gateway_resp=$result['data']['gateway_response'];
            $datepaid=$result['data']['paid_at'];
            $pchannel=$result['data']['channel'];
            $ip=$result['data']['ip_address'];
            $payer=$result['data']['metadata']['payer'];
            $phone=$result['data']['metadata']['phone'];
            $email=$result['data']['metadata']['email'];
            $amt=$result['data']['metadata']['actualamt'];
            $payerid2=$result['data']['metadata']['regno'];
            $cadtype=$result['data']['authorization']['card_type'];
            $bnk=$result['data']['authorization']['bank'];
            $lastfour=$result['data']['authorization']['last4'];
            $c_expmonth=$result['data']['authorization']['exp_month'];
            $c_expyear=$result['data']['authorization']['exp_year'];
            $trx2=$result['data']['authorization']['id'];

    $sql1 = "INSERT INTO paymenthistory (payer_names, regno, email, phone,  amount_paid, payment_ref, trax_id, payment_status, gatewayRes, payment_date, channel, ip_address, card_type, bank, card_lastfour, card_exp_month, card_exp_year) VALUES ('$payer','$payerid2', '$email', '$phone', '$amt', '$trx','$refid','1','$gateway_resp','$datepaid','$pchannel','$ip','$cadtype','$bnk','$lastfour','$c_expmonth','$c_expyear')";

         $sender=mysqli_query($cn, $sql1);
if($sender){
                $_SESSION['alert']="success";
                    /*'Payment Successful!';*/ //Remember to redirect to new page after payment
echo "<script> window.location='register.php?tid=".md5($trx)."'; </script>";
exit();
               }
            }
        }
        else{
            echo 'Unverified Payment!';
        }
    }


?>

