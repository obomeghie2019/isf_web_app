

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();




function makePaymentByPaystack($email, $amount, $payid, $payer, $phone){
        $seckey = 'sk_test_ce0f2947cda13ba173f254a4531e48f155bbbc74'; 
        $pubkey='pk_test_9585a5cc8e4bca4a48dda848946ddd3d303094ab';
        $apiurl='https://api.paystack.co/transaction/initialize';
        $verifyurl='https://api.paystack.co/transaction/verify/';
        $callback='http://localhost/isf2024/apply.php'; // create new page success.php

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
                      'paymenttype' => 'ISF Marathon Payment'
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
        $seckey = 'sk_test_ce0f2947cda13ba173f254a4531e48f155bbbc74'; 
        $pubkey='pk_test_9585a5cc8e4bca4a48dda848946ddd3d303094ab';
        $apiurl='https://api.paystack.co/transaction/initialize';
        $verifyurl='https://api.paystack.co/transaction/verify/';
        $callback='http://localhost/isf2024/register.php';

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



if(isset($_POST['btnPay'])){
    $email=trim(strtolower($_POST['uemail']));
    $phone=trim($_POST['uphone']);
    $amt=str_replace(array(' ', ','), '', $_POST['amount']);
    $payerid=trim($_POST['uregno']);
    $payer=trim(strtoupper(strtolower($_POST['payer1'])));

    $response = makePaymentByPaystack($email, $amt, $payerid, $payer, $phone);
    $result=json_decode($response, true);

    //var_dump($response);// to see JSON data

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
    var_dump($response);// to see JSON data
    $result=json_decode($response, true);
exit();
    if($result['status'] == true){
        if($result['data']['status']=='success'){
            $amt=$result['data']['metadata']['actualamt'];
            $payerid=$result['data']['metadata']['regno'];

$sql1 = "INSERT INTO paymenthistory (payer_names, regno, email, phone,  amount_paid, payment_ref, trax_id, payment_status)

        VALUES ('$payer','$payerid', '$email', '$phone', '$amt', '$refno','$trx','1')";

         if(mysqli_query($link, $sql1)){

                $_SESSION['alert']="success";
                    /*echo 'Deposit successful!';*/ //Remember to redirect to new page after payment else continous deposit
echo "<script> alert('Payment Successful Proceed To Complete Registration.');
           window.location='index.php';
            </script>";
               }
            }
        }
        else{
            echo 'Unverified Payment!';
        }
    }


?>
<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>Marathon Registration</strong></h1>
        <h2 class="h3 text-center animation-slideUp">Registration requires a payment of NGN 500</h2>
    </div>
</section>
<!-- END Intro -->

<!-- Log In -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <!-- Log In Form -->

                <form method="post"  class="form-horizontal" >


                    <b>ISF Reg. Number:</b><input type="text" name="uregno" value="<?php echo "ISF2024-". mt_rand(100000,999999);?>" class="form-control input-lg" readonly />
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-pen"></i></span>
                                <input type="text" name="payer1" class="form-control input-lg" placeholder="Enter Names Here" required />    
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-pen"></i></span>
                                <input type="text" name="uemail" class="form-control input-lg" placeholder="Enter active email e.g yourname@gmail.com" required />    
                            </div>
                        </div>
                    </div>
                            <div class="form-group">
                             <div class="col-xs-12">
                            <div class="input-group">
                             <span class="input-group-addon"><i class="gi gi-pen"></i></span>
                                <input type="text" name="uphone" maxlength="11" class="form-control input-lg" placeholder="Enter Phone No e.g 08033334444" required />
                            </div>

                        </div>
                    </div>

                     <div class="form-group">
                             <div class="col-xs-12">
                            <div class="input-group">
                             <span class="input-group-addon"><i class="33gi gi-pen"></i></span>
                                <input type="text" name="amount" value="500" class="form-control input-lg"  readonly />
                            </div>

                        </div>
                    </div>

<div align="center" class="mt-3">
                    <div class="form-group form-actions">
                        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-sm btn-primary" name="btnPay"> CONTINUE TO MAKE PAYMENT >></button>
                        </div>
                    </div>
</div>
   
                </form>
                <div class="text-center">
                    <small>Already Registered? -</small> <a href="re-print.php"> Print ISF Marathon 2024 Slip!</a>
                </div>
                <!-- END Log In Form -->
            </div>
        </div>
        <hr>
    </div>
</section>
<!-- END Log In -->


<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/login.js"></script>
<script>$(function(){ Login.init(); });</script>

<?php include 'inc/template_end.php'; ?>