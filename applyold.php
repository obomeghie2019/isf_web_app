<?php
// ─────────────────────────────────────────────────────────────────
//  apply.php  –  ISF Marathon 2026 Registration & Payment
// ─────────────────────────────────────────────────────────────────

include 'inc/config.php';        // handles session_start() internally
include 'inc/template_start.php';

// ── DB connection ─────────────────────────────────────────────────
$cn = new mysqli("localhost", "root", "", "globaln2_glix");
if ($cn->connect_error) {
    die("Database connection failed: " . $cn->connect_error);
}

// ── Fetch registration status from system_settings ────────────────
$statusResult = mysqli_query($cn, "SELECT registration_status FROM system_settings LIMIT 1");
$statusRow    = mysqli_fetch_assoc($statusResult);
$status       = strtolower(trim($statusRow['registration_status'] ?? 'closed'));


// ═════════════════════════════════════════════════════════════════
//  HELPER: Initiate Paystack transaction
// ═════════════════════════════════════════════════════════════════
function makePaymentByPaystack($email, $amount, $payid, $payer, $phone) {

    $seckey   = $_ENV['STRIPE_SECRET_KEY'];
    $apiurl   = 'https://api.paystack.co/transaction/initialize';
    $callback = 'https://360globalnetwork.com.ng/isf2024/apply.php';

    $actualamt  = str_replace([' ', ','], '', $amount);
    $amountKobo = (float)$actualamt * 100;

    $fields = [
        'email'        => $email,
        'amount'       => $amountKobo,
        'phone'        => $phone,
        'callback_url' => $callback,
        'metadata'     => [
            'payer'         => $payer,
            'phone'         => $phone,
            'email'         => $email,
            'regno'         => $payid,
            'amt'           => $amountKobo,
            'actualamt'     => $actualamt,
            'callback_url'  => $callback,
            'custom_fields' => [
                'phone'       => $phone,
                'email'       => $email,
                'regno'       => $payid,
                'payer'       => $payer,
                'paymenttype' => 'ISF Marathon 2026 Payment',
            ],
        ],
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            $apiurl);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query($fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER,     [
        'Authorization: Bearer ' . $seckey,
        'Cache-Control: no-cache',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


// ═════════════════════════════════════════════════════════════════
//  HELPER: Verify Paystack transaction
// ═════════════════════════════════════════════════════════════════
function verifyPaymentByPaystack($refno) {

    $seckey    = $_ENV['STRIPE_SECRET_KEY'];
    $verifyurl = 'https://api.paystack.co/transaction/verify/';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT,      $_SERVER['HTTP_USER_AGENT'] ?? 'ISF-App/1.0');
    curl_setopt($ch, CURLOPT_URL,            $verifyurl . rawurlencode($refno));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $seckey,
    ]);

    $result = curl_exec($ch);
    $err    = curl_error($ch);
    curl_close($ch);

    return $err ? false : $result;
}


// ═════════════════════════════════════════════════════════════════
//  STEP 1 – Form submitted: initiate payment
// ═════════════════════════════════════════════════════════════════
if (isset($_POST['btnPay'])) {

    $email   = trim(strtolower($_POST['uemail']  ?? ''));
    $phone   = trim($_POST['uphone']             ?? '');
    $amt     = str_replace([' ', ','], '', $_POST['amount'] ?? '3000');
    $payerid = trim($_POST['uregno']             ?? '');
    $payer   = trim(strtoupper($_POST['payer1']  ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Please enter a valid email address.';
    } else {
        $response = makePaymentByPaystack($email, $amt, $payerid, $payer, $phone);
        $result   = json_decode($response, true);

        if (!empty($result['status']) && $result['status'] === true) {
            header('Location: ' . $result['data']['authorization_url']);
            exit();
        } else {
            $formError = 'Payment initialisation failed: '
                       . ($result['message'] ?? 'Unknown error. Please try again.');
        }
    }
}


// ═════════════════════════════════════════════════════════════════
//  STEP 2 – Paystack callback: verify & save payment
// ═════════════════════════════════════════════════════════════════
if (isset($_GET['trxref'], $_GET['reference'])) {

    $trx   = $_GET['trxref'];
    $refno = $_GET['reference'];

    $response = verifyPaymentByPaystack($refno);

    if ($response === false) {
        $verifyError = 'Could not reach Paystack to verify your payment. '
                     . 'Please contact support with reference: ' . htmlspecialchars($refno);
    } else {
        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $verifyError = 'Invalid response from Paystack. '
                         . 'Please contact support with reference: ' . htmlspecialchars($refno);

        } elseif (!empty($result['status']) && $result['status'] === true) {

            if ($result['data']['status'] === 'success') {

                $refid        = $result['data']['id'];
                $gateway_resp = $result['data']['gateway_response'];
                $datepaid     = $result['data']['paid_at'];
                $pchannel     = $result['data']['channel'];
                $ip           = $result['data']['ip_address'];
                $payer        = $result['data']['metadata']['payer']     ?? '';
                $phone        = $result['data']['metadata']['phone']     ?? '';
                $email        = $result['data']['metadata']['email']     ?? '';
                $amt          = $result['data']['metadata']['actualamt'] ?? '';
                $payerid2     = $result['data']['metadata']['regno']     ?? '';
                $cadtype      = $result['data']['authorization']['card_type']  ?? '';
                $bnk          = $result['data']['authorization']['bank']       ?? '';
                $lastfour     = $result['data']['authorization']['last4']      ?? '';
                $c_expmonth   = $result['data']['authorization']['exp_month']  ?? '';
                $c_expyear    = $result['data']['authorization']['exp_year']   ?? '';

                $esc = fn($v) => mysqli_real_escape_string($cn, $v);

                $sql = "INSERT INTO paymenthistory
                            (payer_names, regno, email, phone, amount_paid,
                             payment_ref, trax_id, payment_status, gatewayRes,
                             payment_date, channel, ip_address, card_type,
                             bank, card_lastfour, card_exp_month, card_exp_year)
                        VALUES
                            ('{$esc($payer)}','{$esc($payerid2)}','{$esc($email)}',
                             '{$esc($phone)}','{$esc($amt)}','{$esc($trx)}',
                             '{$esc($refid)}','1','{$esc($gateway_resp)}',
                             '{$esc($datepaid)}','{$esc($pchannel)}','{$esc($ip)}',
                             '{$esc($cadtype)}','{$esc($bnk)}','{$esc($lastfour)}',
                             '{$esc($c_expmonth)}','{$esc($c_expyear)}')";

                if (mysqli_query($cn, $sql)) {
                    $_SESSION['alert'] = 'success';
                    header('Location: register.php?tid=' . md5($trx));
                    exit();
                } else {
                    $verifyError = 'Payment verified but record could not be saved. '
                                 . 'Please contact support with reference: ' . htmlspecialchars($refno);
                }

            } else {
                $verifyError = 'Payment was not completed. Status: '
                             . htmlspecialchars($result['data']['status'] ?? 'unknown');
            }

        } else {
            $verifyError = 'Paystack could not verify this transaction. '
                         . ($result['message'] ?? 'Please try again or contact support.');
        }
    }
}
?>

<!-- ══════════════════════════════════════════════════════
     PAGE STYLES
════════════════════════════════════════════════════════ -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

:root {
    --green:    #00c853;
    --blue:     #1565c0;
    --dark:     #0a1628;
    --mid:      #132237;
    --light:    #f0f4ff;
    --card-bg:  #ffffff;
    --radius:   20px;
    --shadow:   0 24px 56px rgba(0,0,0,.15);
}

/* ── Hero ───────────────────────────────────────────────── */
.isf-hero {
    background: linear-gradient(140deg, var(--dark) 0%, var(--mid) 60%, #0d3b6e 100%);
    padding: 72px 0 64px;
    position: relative;
    overflow: hidden;
}
.isf-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 70% 50%,
                rgba(0,200,83,.13), transparent 70%);
    pointer-events: none;
}
.isf-hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(40px, 6vw, 64px);
    letter-spacing: 3px;
    color: #fff;
    line-height: 1;
    margin-bottom: 12px;
}
.isf-hero-title span { color: var(--green); }
.isf-hero-sub {
    font-family: 'DM Sans', sans-serif;
    font-size: 17px;
    color: rgba(255,255,255,.72);
    letter-spacing: .4px;
}

/* ── Registration closed ────────────────────────────────── */
.isf-closed-wrap {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: var(--light);
}
.isf-closed-box {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 52px 44px;
    max-width: 460px;
    text-align: center;
}
.isf-closed-icon {
    font-size: 52px;
    margin-bottom: 16px;
    display: block;
}
.isf-closed-box h2 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px;
    letter-spacing: 2px;
    color: #c62828;
    margin-bottom: 12px;
}
.isf-closed-box p {
    font-family: 'DM Sans', sans-serif;
    color: #555;
    line-height: 1.7;
    margin-bottom: 24px;
}
.isf-closed-box a {
    display: inline-block;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    color: var(--blue);
    text-decoration: none;
    border: 2px solid var(--blue);
    padding: 10px 28px;
    border-radius: 50px;
    transition: background .2s, color .2s;
}
.isf-closed-box a:hover { background: var(--blue); color: #fff; }

/* ── Form section ───────────────────────────────────────── */
.isf-section {
    background: var(--light);
    padding: 60px 20px 80px;
    min-height: 60vh;
}
.isf-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 48px 44px;
    max-width: 560px;
    margin: 0 auto;
    transition: transform .3s ease, box-shadow .3s ease;
}
.isf-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 32px 64px rgba(0,0,0,.18);
}

/* ── Alerts ─────────────────────────────────────────────── */
.isf-alert {
    border-radius: 12px;
    padding: 14px 18px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14.5px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.isf-alert-error   { background:#ffeaea; color:#c62828; border-left:4px solid #e53935; }
.isf-alert-success { background:#e8f5e9; color:#2e7d32; border-left:4px solid #43a047; }

/* ── Form fields ────────────────────────────────────────── */
.isf-label {
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: 12.5px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #444;
    display: block;
    margin-bottom: 7px;
    margin-top: 22px;
}
.isf-field {
    display: flex;
    align-items: center;
    background: #f7f9ff;
    border: 2px solid #dde3f0;
    border-radius: 12px;
    overflow: hidden;
    transition: border-color .2s;
}
.isf-field:focus-within { border-color: var(--blue); }
.isf-field-icon {
    padding: 0 14px;
    color: #7a8caa;
    font-size: 16px;
    flex-shrink: 0;
}
.isf-field input {
    border: none;
    background: transparent;
    flex: 1;
    height: 52px;
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    color: #1a1a2e;
    outline: none;
    padding-right: 14px;
}
.isf-field input[readonly] { color: #777; cursor: default; }

/* ── Pay button ─────────────────────────────────────────── */
.isf-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    margin-top: 32px;
    padding: 16px;
    border: none;
    border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: 15px;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    cursor: pointer;
    background: linear-gradient(120deg, var(--green), var(--blue));
    color: #fff;
    transition: opacity .25s, transform .25s, box-shadow .25s;
    box-shadow: 0 8px 24px rgba(21,101,192,.35);
}
.isf-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(21,101,192,.45);
}
.isf-btn:disabled { opacity: .7; cursor: not-allowed; }
.isf-spinner {
    width: 18px; height: 18px;
    border: 3px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: isf-spin .75s linear infinite;
    display: none;
}
.isf-btn.loading .isf-spinner { display: block; }
.isf-btn.loading .btn-label   { opacity: .7; }
@keyframes isf-spin { to { transform: rotate(360deg); } }

/* ── Footer extras ──────────────────────────────────────── */
.isf-pay-foot {
    text-align: center;
    margin-top: 28px;
}
.isf-pay-foot img { max-width: 180px; opacity: .8; }
.isf-reprint {
    text-align: center;
    margin-top: 18px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: #666;
}
.isf-reprint a {
    color: var(--blue);
    font-weight: 600;
    text-decoration: none;
}
.isf-reprint a:hover { text-decoration: underline; }

/* ── Verify error ───────────────────────────────────────── */
.isf-verify-wrap {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: var(--light);
}
.isf-verify-box {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 48px 40px;
    max-width: 480px;
    text-align: center;
}
.isf-verify-box h3 {
    font-family: 'DM Sans', sans-serif;
    color: #c62828;
    margin-bottom: 12px;
}
.isf-verify-box p {
    font-family: 'DM Sans', sans-serif;
    color: #555;
    line-height: 1.65;
}
.isf-verify-box a { color: var(--blue); font-weight: 600; }
</style>

<!-- ══ HERO ══════════════════════════════════════════════ -->
<section class="isf-hero">
    <div class="container text-center">
        <h1 class="isf-hero-title">ISF <span>Marathon Registration</span> 2026</h1>
        <p class="isf-hero-sub">Secure your race bib &mdash; pay &amp; register below</p>
    </div>
</section>

<?php if ($status !== 'open'): ?>
<!-- ══ REGISTRATION CLOSED ════════════════════════════════ -->
<div class="isf-closed-wrap">
    <div class="isf-closed-box">
        <span class="isf-closed-icon">🚫</span>
        <h2>Registration Closed</h2>
        <p>ISF Marathon 2026 registration is currently closed.<br>
           Please check back later or contact the organisers.</p>
        <a href="index.php">&larr; Back to Home</a>
    </div>
</div>

<?php elseif (!empty($verifyError)): ?>
<!-- ══ PAYMENT VERIFICATION ERROR ════════════════════════ -->
<div class="isf-verify-wrap">
    <div class="isf-verify-box">
        <h3>&#9888; Payment Verification Issue</h3>
        <p><?= htmlspecialchars($verifyError) ?></p>
        <p style="margin-top:20px">
            <a href="apply.php">&larr; Try again</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="index.php">Home</a>
        </p>
    </div>
</div>

<?php else: ?>
<!-- ══ REGISTRATION FORM ══════════════════════════════════ -->
<section class="isf-section">
    <div class="isf-card">

        <?php if (!empty($formError)): ?>
        <div class="isf-alert isf-alert-error">
            <span>&#10060;</span>
            <span><?= htmlspecialchars($formError) ?></span>
        </div>
        <?php endif; ?>

        <form method="post" id="regForm" novalidate>

            <input type="hidden" name="uregno"
                   value="ISF2026-<?= mt_rand(100000, 999999) ?>">

            <label class="isf-label" for="uemail">Active Email Address</label>
            <div class="isf-field">
                <span class="isf-field-icon"><i class="fa fa-envelope"></i></span>
                <input type="email" id="uemail" name="uemail"
                       placeholder="enter valid here e.g yourname@example.com"
                       value="<?= htmlspecialchars($_POST['uemail'] ?? '') ?>"
                       required>
            </div>

            <label class="isf-label" for="uphone">Phone Number</label>
            <div class="isf-field">
                <span class="isf-field-icon"><i class="fa fa-phone"></i></span>
                <input type="tel" id="uphone" name="uphone"
                       placeholder="08012345678"
                       value="<?= htmlspecialchars($_POST['uphone'] ?? '') ?>"
                       required>
            </div>

            <label class="isf-label" for="payer1">Full Name</label>
            <div class="isf-field">
                <span class="isf-field-icon"><i class="fa fa-user"></i></span>
                <input type="text" id="payer1" name="payer1"
                       placeholder="Your Names Here"
                       value="<?= htmlspecialchars($_POST['payer1'] ?? '') ?>"
                       required>
            </div>

            <label class="isf-label">Registration Fee (&#8358;)</label>
            <div class="isf-field">
                <span class="isf-field-icon"><i class="fa fa-money"></i></span>
                <input type="text" name="amount" value="3000" readonly>
            </div>

            <button type="submit" name="btnPay" id="payBtn" class="isf-btn">
                <span class="btn-label">Proceed to Payment</span>
                <span class="isf-spinner"></span>
            </button>

        </form>

        <div class="isf-pay-foot">
            <img src="img/paystack.png" alt="Secured by Paystack">
        </div>

        <div class="isf-reprint">
            Already registered?
            <a href="re-print.php">Print your ISF Marathon 2026 slip &rarr;</a>
        </div>

    </div>
</section>
<?php endif; ?>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<script>
(function () {
    var form = document.getElementById('regForm');
    var btn  = document.getElementById('payBtn');
    if (!form || !btn) return;

    form.addEventListener('submit', function (e) {
        var email = document.getElementById('uemail').value.trim();
        var phone = document.getElementById('uphone').value.trim();
        var payer = document.getElementById('payer1').value.trim();

        if (!email || !phone || !payer) {
            e.preventDefault();
            alert('Please fill in all fields before proceeding.');
            return;
        }

        btn.classList.add('loading');
        btn.disabled = true;
    });
})();
</script>

<?php include 'inc/template_end.php'; ?>