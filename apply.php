<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>

<?php
// ── FIX 1: Only start session if one is not already active ──
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db_params.php';

// ── Paystack secret key — loaded securely from .env file ──
$seckey  = $_ENV['PAYSTACK_SECRET_KEY']  ?? '';
$pubkey  = $_ENV['PAYSTACK_PUBLIC_KEY']  ?? '';

if (empty($seckey)) {
    die('<b>ISF Config Error:</b> PAYSTACK_SECRET_KEY is not set in your .env file.');
}


$stmt = $conn->prepare("SELECT registration_status FROM system_settings LIMIT 1");
$stmt->execute();
$setting = $stmt->fetch(PDO::FETCH_ASSOC);
$status  = $setting['registration_status'] ?? 'closed';

if ($status !== 'open') {
    // Show a friendly closed page instead of a plain die()
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Registration Closed — ISF 2026</title>
        <style>
            body {
                margin: 0;
                font-family: sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .box {
                background: #fff;
                border-radius: 20px;
                padding: 60px 50px;
                text-align: center;
                max-width: 480px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            }
            .icon { font-size: 64px; margin-bottom: 20px; }
            h2   { color: #2c3e50; font-size: 28px; margin-bottom: 12px; }
            p    { color: #5a6c7d; font-size: 16px; line-height: 1.7; }
            a    {
                display: inline-block;
                margin-top: 28px;
                padding: 14px 40px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: #fff;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                font-size: 15px;
            }
            a:hover { opacity: 0.9; }
        </style>
    </head>
    <body>
        <div class="box">
            <div class="icon">🚫</div>
            <h2>Registration is Currently Closed</h2>
            <p>Registration for ISF Marathon 2026 is not open at the moment.<br>
               Please check back later or follow our social media for updates.</p>
            <a href="index.php">← Back to Home</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}


// ════════════════════════════════════════════════
//  PAYSTACK — Initialize Payment
// ════════════════════════════════════════════════
function makePaymentByPaystack($email, $amount, $payid, $payer, $phone, $seckey) {
    $apiurl   = 'https://api.paystack.co/transaction/initialize';
    $callback = 'https://360globalnetwork.com.ng/isf2024/apply.php';

    $actualamt = str_replace([' ', ','], '', $amount);
    $amount    = (float)$amount * 100;

    $fields = [
        'email'        => $email,
        'amount'       => $amount,
        'phone'        => $phone,
        'callback_url' => $callback,
        'metadata'     => [
            'payer'     => $payer,
            'phone'     => $phone,
            'email'     => $email,
            'regno'     => $payid,
            'amt'       => $amount,
            'actualamt' => $actualamt,
            'callback_url' => $callback,
            'custom_fields' => [
                'phone'        => $phone,
                'email'        => $email,
                'regno'        => $payid,
                'payer'        => $payer,
                'callback_url' => $callback,
                'paymenttype'  => 'ISF Marathon 2026 Payment'
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            $apiurl);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query($fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER,     [
        "Authorization: Bearer " . $seckey,
        "Cache-Control: no-cache",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


// ════════════════════════════════════════════════
//  PAYSTACK — Verify Payment
// ════════════════════════════════════════════════
function verifyPaymentByPaystack($trans, $refno, $seckey) {
    $verifyurl = 'https://api.paystack.co/transaction/verify/';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT,      $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_URL,            $verifyurl . $refno);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $seckey
    ]);
    curl_setopt($ch, CURLOPT_HEADER,         false);
    $result = curl_exec($ch);
    $err    = curl_error($ch);
    curl_close($ch);

    return $err ? false : $result;
}


// ════════════════════════════════════════════════
//  Handle POST — User submitted the form
// ════════════════════════════════════════════════
if (isset($_POST['btnPay'])) {
    $email   = trim(strtolower($_POST['uemail']));
    $phone   = trim($_POST['uphone'] ?? '');
    $amt     = str_replace([' ', ','], '', $_POST['amount']);
    $payerid = trim($_POST['uregno']);
    $payer   = trim(strtoupper($_POST['payer1'] ?? $email));

    $response = makePaymentByPaystack($email, $amt, $payerid, $payer, $phone, $seckey);
    $result   = json_decode($response, true);

    if (!empty($result['status']) && ($result['status'] == 'true' || $result['status'] === true)) {
        $url = $result['data']['authorization_url'];
        echo '<script>window.location.href="' . $url . '";</script>';
        exit();
    } else {
        $errMsg = $result['message'] ?? 'Payment initialization failed. Please try again.';
        echo '<script>alert("' . addslashes($errMsg) . '");</script>';
    }
}


// ════════════════════════════════════════════════
//  Handle GET — Paystack callback after payment
// ════════════════════════════════════════════════
if (isset($_GET['trxref']) && isset($_GET['reference'])) {
    $trx   = $_GET['trxref'];
    $refno = $_GET['reference'];

    $response = verifyPaymentByPaystack($trx, $refno, $seckey);
    $result   = json_decode($response, true);

    if (!$response || empty($result)) {
        die('Error: Unable to parse Paystack response.');
    }

    if (!empty($result['status']) && $result['status'] == true) {
        if ($result['data']['status'] == 'success') {

            $refid        = $result['data']['id'];
            $gateway_resp = $result['data']['gateway_response'];
            $datepaid     = $result['data']['paid_at'];
            $pchannel     = $result['data']['channel'];
            $ip           = $result['data']['ip_address'];
            $payer        = $result['data']['metadata']['payer'];
            $phone        = $result['data']['metadata']['phone'];
            $email        = $result['data']['metadata']['email'];
            $amt          = $result['data']['metadata']['actualamt'];
            $payerid2     = $result['data']['metadata']['regno'];
            $cadtype      = $result['data']['authorization']['card_type'];
            $bnk          = $result['data']['authorization']['bank'];
            $lastfour     = $result['data']['authorization']['last4'];
            $c_expmonth   = $result['data']['authorization']['exp_month'];
            $c_expyear    = $result['data']['authorization']['exp_year'];

            // Use PDO ($conn) — safer, no SQL injection
            $sql = "INSERT INTO paymenthistory
                        (payer_names, regno, email, phone, amount_paid,
                         payment_ref, trax_id, payment_status, gatewayRes,
                         payment_date, channel, ip_address, card_type,
                         bank, card_lastfour, card_exp_month, card_exp_year)
                    VALUES
                        (:payer, :regno, :email, :phone, :amt,
                         :trx, :refid, '1', :gwresp,
                         :datepaid, :channel, :ip, :cadtype,
                         :bnk, :lastfour, :expmonth, :expyear)";

            $st = $conn->prepare($sql);
            $ok = $st->execute([
                ':payer'    => $payer,
                ':regno'    => $payerid2,
                ':email'    => $email,
                ':phone'    => $phone,
                ':amt'      => $amt,
                ':trx'      => $trx,
                ':refid'    => $refid,
                ':gwresp'   => $gateway_resp,
                ':datepaid' => $datepaid,
                ':channel'  => $pchannel,
                ':ip'       => $ip,
                ':cadtype'  => $cadtype,
                ':bnk'      => $bnk,
                ':lastfour' => $lastfour,
                ':expmonth' => $c_expmonth,
                ':expyear'  => $c_expyear,
            ]);

            if ($ok) {
                $_SESSION['alert'] = 'success';
                echo "<script>window.location='register.php?tid=" . md5($trx) . "';</script>";
                exit();
            } else {
                echo 'Payment recorded but redirect failed. Please contact support.';
            }

        } else {
            echo 'Payment was not successful. Please try again.';
        }
    } else {
        echo 'Unverified Payment! Please contact support with your reference: ' . htmlspecialchars($refno);
    }

    exit();
}
?>


<style>
.hero-section  { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); padding: 80px 0; color: #fff; }
.hero-title    { font-size: 36px; font-weight: 700; }
.hero-subtitle { font-size: 18px; opacity: 0.9; }

.form-card {
    width: 100%;
    max-width: 800px;
    padding: 50px 60px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.12);
    transition: all 0.4s ease;
}
.form-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.form-label   { font-weight: 600; margin-bottom: 6px; display: block; }
.form-control { height: 56px; font-size: 16px; border-radius: 12px; }

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

.center-page {
    min-height: 100vh;
    display: grid;
    place-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #00c853, #2196f3);
}

.btn-spinner {
    display: none;
    width: 20px; height: 20px;
    border: 3px solid rgba(255,255,255,0.4);
    border-top: 3px solid #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-left: 10px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.btn-loading .btn-text    { opacity: 0.6; }
.btn-loading .btn-spinner { display: inline-block; }
</style>


<!-- Hero -->
<section class="site-section hero-section">
    <div class="container text-center">
        <h1 class="hero-title">
            <i class="fa fa-running"></i> Marathon Registration
        </h1>
        <p class="hero-subtitle">Register below to secure your spot</p>
    </div>
</section>

<!-- Form -->
<section class="site-content site-section center-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">

                <div class="form-card">
                    
                    <form method="post" id="regForm">

                        <input type="hidden" name="uregno"
                               value="<?php echo 'ISF2026-' . mt_rand(100000, 999999); ?>" />

                        <label class="form-label">Valid Email Address</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="uemail"
                                       class="form-control input-lg"
                                       placeholder="yourname@gmail.com" required>
                            </div>
                        </div>

                        <label class="form-label">Registration Fee</label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <input type="text" name="amount"
                                       value="3000"
                                       class="form-control input-lg" readonly>
                            </div>
                        </div>

                        <button type="submit" name="btnPay"
                                class="btn btn-gradient btn-block" id="payBtn">
                            <span class="btn-text">MAKE PAYMENT</span>
                            <span class="btn-spinner"></span>
                        </button>

                        <div class="text-center mt-3">
                            <img src="img/paystack.png"
                                 class="img-responsive center-block"
                                 style="max-width:220px" alt="Paystack Logo">
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


<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<script src="js/pages/login.js"></script>
<script>$(function(){ Login.init(); });</script>
<script>
document.getElementById("regForm").addEventListener("submit", function() {
    document.getElementById("payBtn").classList.add("btn-loading");
});
</script>
<?php include 'inc/template_end.php'; ?>
