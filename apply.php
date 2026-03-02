<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>


<?php 
 session_start();
require 'db_params.php';



if ($status !== 'open') {
    die("Registration is currently closed.");
}



function makePaymentByPaystack($email, $amount, $payid, $payer, $phone){
        $_ENV['STRIPE_SECRET_KEY'];
        $pubkey=$_ENV['STRIPE_PUBLIC_KEY'];
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
        $pubkey=$_ENV['STRIPE_PUBLIC_KEY'];
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
<style>
    /* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    padding: 80px 0;
    color: #fff;
}

.hero-title {
    font-size: 36px;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 18px;
    opacity: 0.9;
}

/* Form Card */
.form-card {
    width: 100%;
    max-width: 800px;      /* BIG width */
    padding: 50px 60px;    /* more padding */
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.12);
}

.form-card .form-control,
.form-card .btn-gradient {
    width: 100% !important;  /* override any Bootstrap limits */
    font-size: 18px;
    padding: 16px;
    border-radius: 12px;
    box-sizing: border-box;
}

.site-section.center-page .container,
.site-section.center-page .row,
.site-section.center-page .col-12 {
    max-width: none;
    padding: 0;
    margin: 0;
}


/* Labels */
.form-label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

/* Inputs */
.form-control {
    height: 56px;
    font-size: 16px;
    border-radius: 12px;
}


/* Gradient Button */
.btn-gradient {
    background: linear-gradient(135deg, #ff512f, #f09819);
    color: #fff;
    border: none;
    padding: 14px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 30px;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(240,152,25,0.4);
    color: #fff;
}

/* Links */
.form-card a {
    color: #ff512f;
    font-weight: 600;
}

.form-card a:hover {
    text-decoration: underline;
}
/* Green → Blue Gradient Button */
.btn-gradient {
    background: linear-gradient(135deg, #00c853, #2196f3);
    color: #fff;
    border: none;
    padding: 14px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 30px;
    width: 100%;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(33,150,243,0.4);
    color: #fff;
}

/* Center page content */
.center-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 15px;
}


/* Button spinner */
.btn-spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,0.4);
    border-top: 3px solid #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-left: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.btn-loading .btn-text {
    opacity: 0.6;
}

.btn-loading .btn-spinner {
    display: inline-block;
}


.form-card {
    transition: all 0.4s ease;
}

.form-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.site-section.center-page {
    min-height: 100vh;
    display: grid;
    place-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #00c853, #2196f3);
}




/* Allow the centered form to grow */
.site-section.center-page .container {
    max-width: none;      /* 👈 remove Bootstrap cap */
    width: 100%;
}

.site-section.center-page .row {
    margin: 0;            /* 👈 kill negative margins */
}

.site-section.center-page .col-12 {
    padding: 0;
}



</style>
<!-- Intro Section -->
<section class="site-section hero-section">
    <div class="container text-center">
        <h1 class="hero-title">
            <i class="fa fa-running"></i> Marathon Registration
        </h1>
        <p class="hero-subtitle">Register below to secure your spot</p>
    </div>
</section>

<!-- Registration Form -->
<section class="site-content site-section center-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                
                <div class="form-card">
                    <form method="post" id="regForm">

                        <input type="hidden" name="uregno"
                               value="<?php echo 'ISF2026-' . mt_rand(100000,999999); ?>" />

                        <label class="form-label">Valid Email Address</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" name="uemail"
                                       class="form-control input-lg"
                                       placeholder="yourname@gmail.com" required>
                            </div>
                        </div>

                        <label class="form-label">Registration Fee</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-money"></i>
                                </span>
                                <input type="text" name="amount"
                                       value="3000"
                                       class="form-control input-lg" readonly>
                            </div>
                        </div>

             <button type="submit" name="btnPay" class="btn btn-gradient btn-block" id="payBtn">
    <span class="btn-text">MAKE PAYMENT</span>
    <span class="btn-spinner"></span>
</button>



                        <div class="text-center mt-3">
                            <img src="img/paystack.png"
                                 class="img-responsive center-block"
                                 style="max-width:220px"
                                 alt="Paystack Logo">
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small>
                            Already Registered?
                            <a href="re-print.php">Print ISF Marathon 2026 Slip</a>
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- END Log In -->


<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/login.js"></script>
<script>$(function(){ Login.init(); });</script>
<script>
document.getElementById("regForm").addEventListener("submit", function(e) {
    var btn = document.getElementById("payBtn");
    btn.classList.add("btn-loading");
});
</script>



<?php include 'inc/template_end.php'; ?>

