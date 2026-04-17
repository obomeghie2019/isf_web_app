<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
if (!isLoggedIn()) { header("Location: index.php"); exit; }

if (session_status() === PHP_SESSION_NONE) session_start();

$versionFile = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'app_version.php';
$msg   = '';
$error = '';

// ── Read current values from app_version.php ──
$latest  = '1.0.0';
$minimum = '1.0.0';
$force   = false;
$message = '';

if (file_exists($versionFile)) {
    $content = file_get_contents($versionFile);
    preg_match("/'latest_version'\s*=>\s*'([^']+)'/",  $content, $m1);
    preg_match("/'min_version'\s*=>\s*'([^']+)'/",      $content, $m2);
    preg_match("/'force_update'\s*=>\s*(true|false)/",  $content, $m3);
    preg_match("/'update_message'\s*=>\s*'([^']+)'/",  $content, $m4);
    $latest  = $m1[1] ?? '1.0.0';
    $minimum = $m2[1] ?? '1.0.0';
    $force   = ($m3[1] ?? 'false') === 'true';
    $message = $m4[1] ?? '';
}

// ── Save updated values ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_version'])) {
    $newLatest  = trim($_POST['latest_version']);
    $newMin     = trim($_POST['min_version']);
    $newForce   = isset($_POST['force_update']) ? 'true' : 'false';
    $newMessage = addslashes(trim($_POST['update_message']));

    // Validate semver format x.x.x
    $semver = '/^\d+\.\d+\.\d+$/';
    if (!preg_match($semver, $newLatest) || !preg_match($semver, $newMin)) {
        $error = 'Invalid version format. Use x.x.x (e.g. 1.2.0)';
    } else {
        $php = "<?php\nheader('Content-Type: application/json');\nheader('Access-Control-Allow-Origin: *');\n\necho json_encode([\n    'latest_version'  => '$newLatest',\n    'min_version'     => '$newMin',\n    'force_update'    => $newForce,\n    'update_message'  => '$newMessage',\n]);\n";

        if (file_put_contents($versionFile, $php) !== false) {
            $latest  = $newLatest;
            $minimum = $newMin;
            $force   = $newForce === 'true';
            $message = stripslashes($newMessage);
            $_SESSION['flash_msg'] = 'App version updated successfully!';
            header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
            exit;
        } else {
            $error = 'Could not write to app_version.php — check file permissions.';
        }
    }
}

if (!empty($_SESSION['flash_msg'])) {
    $msg = $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}

include 'header.php';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap');
*,*::before,*::after{box-sizing:border-box;}
body{background:linear-gradient(135deg,#f0f4ff,#e8ecf8);font-family:'Urbanist',sans-serif;}
.wrap{max-width:800px;margin:2rem auto;padding:0 1rem;}
.ph{text-align:center;margin-bottom:2rem;}
.ph h1{font-size:2rem;font-weight:800;background:linear-gradient(135deg,#1A1AFF,#00CFFF);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.card{background:#fff;border-radius:18px;padding:2rem;box-shadow:0 4px 24px rgba(26,26,255,.1);border:1px solid #e0e7ff;margin-bottom:1.5rem;}
.card h3{font-size:1.1rem;font-weight:700;color:#1e293b;margin:0 0 1.5rem;padding-bottom:.75rem;border-bottom:2px solid #e0e7ff;display:flex;align-items:center;gap:.5rem;}
.alert{border-radius:12px;padding:1rem 1.4rem;font-weight:500;margin-bottom:1.5rem;}
.ok{background:linear-gradient(135deg,#d4fc79,#96e6a1);color:#1b5e20;}
.err{background:linear-gradient(135deg,#fbc2eb,#fa709a);color:#b71c1c;}
.fl{font-weight:600;color:#374151;display:block;margin-bottom:.4rem;font-size:.9rem;}
.fc{border-radius:8px;border:2px solid #e0e7ff;padding:.65rem 1rem;font-size:.93rem;font-family:'Urbanist',sans-serif;width:100%;transition:border-color .25s;}
.fc:focus{border-color:#3B3BDF;outline:none;box-shadow:0 0 0 3px rgba(59,59,223,.1);}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
@media(max-width:580px){.frow{grid-template-columns:1fr;}}
.fg{margin-bottom:1rem;}
textarea.fc{resize:vertical;min-height:90px;}
/* Toggle */
.tw{display:flex;align-items:center;gap:.75rem;margin-top:.4rem;}
.tw input[type=checkbox]{display:none;}
.tl{width:52px;height:28px;background:#cbd5e1;border-radius:14px;cursor:pointer;position:relative;transition:background .3s;}
.tl::after{content:'';position:absolute;width:22px;height:22px;background:#fff;border-radius:50%;top:3px;left:3px;transition:left .3s;box-shadow:0 2px 4px rgba(0,0,0,.2);}
input[type=checkbox]:checked+.tl{background:linear-gradient(135deg,#f5576c,#f093fb);}
input[type=checkbox]:checked+.tl::after{left:27px;}
.tw-label{font-size:.9rem;font-weight:600;}
.force-on{color:#c62828;}
.force-off{color:#718096;}
/* Save button */
.bsave{background:linear-gradient(135deg,#1A1AFF,#00CFFF);color:#fff;padding:.9rem 2.5rem;border:none;border-radius:12px;font-weight:800;font-size:1rem;cursor:pointer;width:100%;box-shadow:0 4px 18px rgba(26,26,255,.35);font-family:'Urbanist',sans-serif;transition:transform .2s;}
.bsave:hover{transform:translateY(-2px);}
/* Status cards */
.statuses{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;}
@media(max-width:580px){.statuses{grid-template-columns:1fr;}}
.stat{background:#fff;border-radius:14px;padding:1.2rem 1.4rem;border:1px solid #e0e7ff;text-align:center;box-shadow:0 2px 10px rgba(26,26,255,.07);}
.stat .label{font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:.4rem;}
.stat .value{font-size:1.5rem;font-weight:800;color:#1e293b;}
.stat.danger .value{color:#ef4444;}
.stat.warn .value{color:#f59e0b;}
.stat.ok2 .value{color:#22c55e;}
.badge{display:inline-block;padding:.25rem .75rem;border-radius:20px;font-size:.78rem;font-weight:700;}
.badge-force{background:#fee2e2;color:#b91c1c;}
.badge-ok{background:#dcfce7;color:#15803d;}
.hint{font-size:.8rem;color:#94a3b8;margin-top:.35rem;}
</style>

<div class="wrap">
  <div class="ph">
    <h1>📱 App Version Control</h1>
    <p style="color:#64748b;margin:0;font-size:.93rem;">Control what version ISF App users must run</p>
  </div>

  <?php if($msg): ?><div class="alert ok">✅ <?=htmlspecialchars($msg)?></div><?php endif; ?>
  <?php if($error): ?><div class="alert err">❌ <?=$error?></div><?php endif; ?>

  <!-- Live status cards -->
  <div class="statuses">
    <div class="stat ok2">
      <div class="label">Latest Version</div>
      <div class="value"><?=htmlspecialchars($latest)?></div>
    </div>
    <div class="stat warn">
      <div class="label">Minimum Version</div>
      <div class="value"><?=htmlspecialchars($minimum)?></div>
    </div>
    <div class="stat <?=$force?'danger':'ok2'?>">
      <div class="label">Force Update</div>
      <div class="value">
        <span class="badge <?=$force?'badge-force':'badge-ok'?>">
          <?=$force?'ACTIVE':'OFF'?>
        </span>
      </div>
    </div>
  </div>

  <!-- Update form -->
  <div class="card">
    <h3>⚙️ Update Settings</h3>
    <form method="POST">

      <div class="frow">
        <div class="fg">
          <label class="fl">Latest Version
            <span style="color:#94a3b8;font-weight:400"> (on Play Store)</span>
          </label>
          <input type="text" name="latest_version" class="fc"
                 value="<?=htmlspecialchars($latest)?>"
                 placeholder="e.g. 1.2.0" required>
          <div class="hint">Bump this every time you publish a new APK</div>
        </div>
        <div class="fg">
          <label class="fl">Minimum Version
            <span style="color:#94a3b8;font-weight:400"> (force update below this)</span>
          </label>
          <input type="text" name="min_version" class="fc"
                 value="<?=htmlspecialchars($minimum)?>"
                 placeholder="e.g. 1.0.0" required>
          <div class="hint">Users below this version are force-updated</div>
        </div>
      </div>

      <div class="fg">
        <label class="fl">Update Message</label>
        <textarea name="update_message" class="fc"
                  placeholder="Describe what's new..."><?=htmlspecialchars($message)?></textarea>
      </div>

      <div class="fg">
        <label class="fl">Force All Users to Update</label>
        <div class="tw">
          <input type="checkbox" id="forceToggle" name="force_update"
                 <?=$force?'checked':''?>>
          <label class="tl" for="forceToggle"></label>
          <span class="tw-label <?=$force?'force-on':'force-off'?>" id="forceLabel">
            <?=$force?'⚠️ All users MUST update — they cannot use the app':'Optional (only users below min version are forced)'?>
          </span>
        </div>
      </div>

      <button type="submit" name="save_version" class="bsave">
        💾 Save & Push to App
      </button>
    </form>
  </div>

  <!-- How it works -->
  <div class="card">
    <h3>📖 How It Works</h3>
    <div style="font-size:.88rem;color:#475569;line-height:1.8;">
      <p style="margin:0 0 .5rem"><strong>1. Optional update</strong> — Set <em>Latest Version</em> higher than what's installed. Users see a dismissible dialog offering to update.</p>
      <p style="margin:0 0 .5rem"><strong>2. Force update</strong> — Set <em>Minimum Version</em> higher than what's installed. Users cannot dismiss the dialog and cannot use the app until they update.</p>
      <p style="margin:0"><strong>3. Emergency force</strong> — Turn on <em>Force All Users</em> toggle. Every user gets a force update dialog regardless of version number.</p>
    </div>
  </div>
</div>

<script>
document.getElementById('forceToggle').addEventListener('change', function() {
  const label = document.getElementById('forceLabel');
  if (this.checked) {
    label.textContent = '⚠️ All users MUST update — they cannot use the app';
    label.className = 'tw-label force-on';
  } else {
    label.textContent = 'Optional (only users below min version are forced)';
    label.className = 'tw-label force-off';
  }
});
</script>