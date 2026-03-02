<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg = "";
$error = "";

/* Fetch All Settings */
$stmt = $conn->prepare("SELECT * FROM mobile_settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

/* Fetch About ISF Football */
$aboutFootball = $conn->query("SELECT * FROM about_mobile LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// =============================
// HANDLE ALL POST REQUESTS WITH REDIRECTS
// =============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    $redirectUrl = $_SERVER['PHP_SELF'] . '?';

    // ADD NEW UPDATE
    if ($_POST['action'] === 'isf_update') {
        $title   = isset($_POST['update_title']) ? trim($_POST['update_title']) : '';
        $content = isset($_POST['update_content']) ? trim($_POST['update_content']) : '';

        if ($title !== '' && $content !== '') {
            $stmt = $conn->prepare("INSERT INTO isf_updates (title, content) VALUES (:title, :content)");
            $stmt->execute([
                'title'   => $title,
                'content' => $content
            ]);
            $redirectUrl .= 'msg=update_added';
        } else {
            $redirectUrl .= 'error=empty_fields';
        }
        
        header("Location: " . $redirectUrl);
        exit;
    }

    // DELETE UPDATE
    if ($_POST['action'] === 'delete_update') {
        $deleteId = isset($_POST['delete_id']) ? intval($_POST['delete_id']) : 0;

        if ($deleteId > 0) {
            $stmt = $conn->prepare("DELETE FROM isf_updates WHERE id = :id");
            $stmt->execute(['id' => $deleteId]);
            $redirectUrl .= 'msg=update_deleted';
        } else {
            $redirectUrl .= 'error=invalid_id';
        }
        
        header("Location: " . $redirectUrl);
        exit;
    }

    // MARATHON TOGGLE
    if ($_POST['action'] === 'marathon_toggle') {
        $newStatus = ($_POST['reg_status'] === 'open') ? 'open' : 'closed';
        
        $stmt = $conn->prepare("UPDATE mobile_settings SET marathon_registration=?");
        $success = $stmt->execute([$newStatus]);
        
        if ($success) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?updated=marathon&status=" . $newStatus);
            exit;
        } else {
            $redirectUrl .= 'error=marathon_failed';
            header("Location: " . $redirectUrl);
            exit;
        }
    }

    // LIVESCORE URL
    if ($_POST['action'] === 'livescore_url') {
        $conn->prepare("UPDATE mobile_settings SET livescore_url=?")
             ->execute([trim($_POST['livescore_url'])]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=livescore_updated");
        exit;
    }

    // FIXTURES URL
    if ($_POST['action'] === 'fixtures_url') {
        $conn->prepare("UPDATE mobile_settings SET fixtures_url=?")
             ->execute([trim($_POST['fixtures_url'])]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=fixtures_updated");
        exit;
    }

    // STANDING URL
    if ($_POST['action'] === 'standing_url') {
        $conn->prepare("UPDATE mobile_settings SET standing_url=?")
             ->execute([trim($_POST['standing_url'])]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=standing_updated");
        exit;
    }

    // STATISTICS URL
    if ($_POST['action'] === 'statistics_url') {
        $conn->prepare("UPDATE mobile_settings SET statistics_url=?")
             ->execute([trim($_POST['statistics_url'])]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=statistics_updated");
        exit;
    }

    // ABOUT FOOTBALL
    if ($_POST['action'] === 'about_football') {
        $content = $_POST['about_football_content'];
        if ($aboutFootball && isset($aboutFootball['id'])) {
            $conn->prepare("UPDATE about_mobile SET write_up=? WHERE id=?")
                 ->execute([$content, $aboutFootball['id']]);
        } else {
            $conn->prepare("INSERT INTO about_mobile (write_up) VALUES (?)")
                 ->execute([$content]);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=about_updated");
        exit;
    }
}

// =============================
// FETCH ALL UPDATES
// =============================
$isfUpdates = [];
$result = $conn->query("SELECT * FROM isf_updates ORDER BY id DESC");
if ($result) {
    $isfUpdates = $result->fetchAll(PDO::FETCH_ASSOC);
}

// =============================
// HANDLE MESSAGES FROM URL PARAMETERS
// =============================
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'update_added':
            $msg = "New ISF Update added successfully!";
            break;
        case 'update_deleted':
            $msg = "ISF Update deleted successfully!";
            break;
        case 'livescore_updated':
            $msg = "LiveScore URL updated successfully!";
            break;
        case 'fixtures_updated':
            $msg = "Fixtures URL updated successfully!";
            break;
        case 'standing_updated':
            $msg = "Football Standing URL updated successfully!";
            break;
        case 'statistics_updated':
            $msg = "Football Statistics URL updated successfully!";
            break;
        case 'about_updated':
            $msg = "About ISF Football updated successfully!";
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'empty_fields':
            $error = "Please fill in all fields!";
            break;
        case 'invalid_id':
            $error = "Invalid update ID!";
            break;
        case 'marathon_failed':
            $error = "Failed to update marathon registration status!";
            break;
    }
}

/* Current marathon status */
$marStatus = $settings['marathon_registration'] ?? 'closed';

/* Show success message if redirected after marathon update */
if (isset($_GET['updated']) && $_GET['updated'] === 'marathon') {
    $statusSaved = $_GET['status'] ?? $marStatus;
    $msg = "Marathon registration " . strtoupper($statusSaved) . " successfully!";
}

// Refresh settings after potential updates (though we redirect, this is for initial load)
$stmt = $conn->prepare("SELECT * FROM mobile_settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
$marStatus = $settings['marathon_registration'] ?? 'closed';

include 'header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Your existing CSS remains unchanged */
    :root {
        --card-bg: #ffffff;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --shadow-md: 0 4px 20px rgba(0,0,0,0.10);
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        font-family: 'Urbanist', sans-serif;
    }

    .dash-wrap {
        max-width: 1400px;
        margin: 2.5rem auto;
        padding: 0 1.25rem;
        animation: fadeUp .6s ease-out;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(24px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .dash-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .dash-header h1 {
        font-size: 2.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -.02em;
        margin-bottom: .4rem;
    }

    .dash-header p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .toast-msg {
        background: linear-gradient(135deg, #d4fc79, #96e6a1);
        color: #1b5e20;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 2rem;
        animation: slideDown .4s ease-out;
    }

    @keyframes slideDown {
        from { opacity:0; transform:translateY(-18px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .section-sep {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: .12em;
        margin: 2.5rem 0 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .section-sep::after {
        content:'';
        flex: 1;
        height: 2px;
        background: var(--border-color);
        border-radius: 2px;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.75rem;
    }

    .card-full { grid-column: 1 / -1; }

    @media (max-width: 960px) {
        .cards-grid { grid-template-columns: 1fr; }
        .card-full  { grid-column: auto; }
    }

    .dash-card {
        background: var(--card-bg);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: transform .35s cubic-bezier(.4,0,.2,1),
                    box-shadow .35s cubic-bezier(.4,0,.2,1);
        position: relative;
    }

    .dash-card::before {
        content: '';
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .4s cubic-bezier(.4,0,.2,1);
    }

    .dash-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
    .dash-card:hover::before { transform: scaleX(1); }

    .card-head {
        padding: 1.5rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .card-head::after {
        content:'';
        position: absolute;
        top: -60%; right: -60%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,.15) 0%, transparent 65%);
        opacity: 0;
        transition: opacity .4s;
    }

    .dash-card:hover .card-head::after { opacity: 1; }

    .card-head h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .card-body { padding: 2rem; }

    .card-desc {
        color: var(--text-secondary);
        font-size: .98rem;
        line-height: 1.65;
        margin: 1rem 0 1.5rem;
    }

    /* ── STATUS PILL ── */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.4rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: .95rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 1.25rem;
    }

    .pill-green {
        background: linear-gradient(135deg, #d4fc79, #96e6a1);
        color: #1b5e20;
        box-shadow: 0 4px 14px rgba(150,230,161,.45);
    }

    .pill-red {
        background: linear-gradient(135deg, #fbc2eb, #fa709a);
        color: #7f1d1d;
        box-shadow: 0 4px 14px rgba(250,112,154,.4);
    }

    .pill-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: currentColor;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.6; transform:scale(1.25); }
    }

    /* ── TOGGLE ── */
    .toggle-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: .75rem;
    }

    .toggle-row .label-text {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 76px;
        height: 38px;
        flex-shrink: 0;
    }

    .switch input { display: none; }

    .slider {
        position: absolute;
        inset: 0;
        cursor: pointer;
        background: linear-gradient(135deg, #ef5350, #e53935);
        border-radius: 38px;
        transition: all .4s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 2px 8px rgba(239,83,80,.3);
    }

    .slider::before {
        content: "";
        position: absolute;
        width: 30px; height: 30px;
        left: 4px; bottom: 4px;
        background: #fff;
        border-radius: 50%;
        transition: all .4s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 2px 8px rgba(0,0,0,.2);
    }

    input:checked + .slider {
        background: linear-gradient(135deg, #66bb6a, #43a047);
        box-shadow: 0 2px 8px rgba(102,187,106,.35);
    }

    input:checked + .slider::before { transform: translateX(38px); }
    .slider:active::before { width: 34px; }

    .field-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        font-size: .95rem;
        margin-bottom: .5rem;
    }

    .field-input {
        width: 100%;
        padding: .85rem 1rem;
        border-radius: 10px;
        border: 2px solid var(--border-color);
        font-size: 1rem;
        font-family: 'Urbanist', sans-serif;
        margin-bottom: 1rem;
        transition: border-color .3s, box-shadow .3s;
    }

    .field-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,.12);
    }

    textarea.field-input {
        min-height: 160px;
        resize: vertical;
        line-height: 1.7;
    }

    .btn-save {
        width: 100%;
        padding: .9rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Urbanist', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        transition: transform .3s, box-shadow .3s;
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    .btn-save::before {
        content:'';
        position: absolute;
        top:50%; left:50%;
        width:0; height:0;
        border-radius: 50%;
        background: rgba(255,255,255,.25);
        transform: translate(-50%,-50%);
        transition: width .55s, height .55s;
    }

    .btn-save:hover::before { width:320px; height:320px; }
    .btn-save:hover { transform: translateY(-2px); }
    .btn-save span { position: relative; z-index: 1; }

    .btn-purple { background: linear-gradient(135deg,#667eea,#764ba2); box-shadow: 0 4px 15px rgba(102,126,234,.4); }

    .url-preview {
        font-size: .85rem;
        color: #667eea;
        word-break: break-all;
        margin-bottom: 1rem;
        padding: .6rem 1rem;
        background: #f0f4ff;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .url-preview a { color: #667eea; text-decoration: none; }
    .url-preview a:hover { text-decoration: underline; }
</style>

<div class="dash-wrap">

    <div class="dash-header">
        <h1>📱 ISF App Control Dashboard</h1>
        <p>Manage mobile app settings, links, and content in real-time</p>
    </div>

    <?php if ($msg): ?>
        <div class="toast-msg">✅ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="toast-msg" style="background: linear-gradient(135deg, #fbc2eb, #fa709a); color: #7f1d1d;">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- ══ SECTION 1 – REGISTRATION ══ -->
    <div class="section-sep">🏃 Registration Control</div>
    <div class="cards-grid">

        <div class="dash-card card-full">
            <div class="card-head">
                <h3>🏃 ISF Marathon Registration</h3>
            </div>
            <div class="card-body">

                <!-- Status pill reads directly from $marStatus -->
                <div class="status-pill <?= $marStatus === 'open' ? 'pill-green' : 'pill-red' ?>"
                     id="statusPill">
                    <span class="pill-dot"></span>
                    <span id="pillText">
                        Marathon Registration: <?= strtoupper($marStatus) ?>
                    </span>
                </div>

                <!--
                    KEY FIX:
                    - action  field = "marathon_toggle"    (identifies which form)
                    - reg_status field = "open" OR "closed" (set by JS before submit)
                    - NO checkbox field called "marathon_toggle" — no name collision
                -->
                <form method="POST" id="marathonForm" onsubmit="return prepareSubmit()">
                    <input type="hidden" name="action"     value="marathon_toggle">
                    <input type="hidden" name="reg_status" id="regStatusField" value="">

                    <div class="toggle-row">
                        <label class="switch">
                            <!--
                                Checkbox is UI-only. It has NO name so it
                                never appears in $_POST. JS writes the real
                                value into #regStatusField instead.
                            -->
                            <input type="checkbox"
                                   id="marathonCheck"
                                   <?= $marStatus === 'open' ? 'checked' : '' ?>
                                   onchange="handleToggleAndSubmit(this)">
                            <span class="slider"></span>
                        </label>
                        <span class="label-text" id="marathonLabel">
                            <?= $marStatus === 'open'
                                ? 'Registration is OPEN — toggle to CLOSE'
                                : 'Registration is CLOSED — toggle to OPEN' ?>
                        </span>
                    </div>

                    <p class="card-desc">
                        When <strong>OPEN</strong>, athletes can register for the ISF Marathon
                        through the mobile app. When <strong>CLOSED</strong>, the registration
                        button is hidden.
                    </p>
                </form>

            </div>
        </div>

    </div>

    <!-- ══ SECTION 2 – LIVE LINKS ══ -->
    <div class="section-sep">🔗 Live URL Links</div>
    <div class="cards-grid">

        <!-- LiveScore URL -->
        <div class="dash-card">
            <div class="card-head"><h3>🟢 LiveScore URL</h3></div>
            <div class="card-body">
                <?php if (!empty($settings['livescore_url'])): ?>
                    <div class="url-preview">
                        🔗 <a href="<?= htmlspecialchars($settings['livescore_url']) ?>" target="_blank">
                               <?= htmlspecialchars($settings['livescore_url']) ?></a>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="livescore_url">
                    <label class="field-label">LiveScore Page URL</label>
                    <input type="url" name="livescore_url" class="field-input"
                           placeholder="https://example.com/livescore"
                           value="<?= htmlspecialchars($settings['livescore_url'] ?? '') ?>">
                    <p class="card-desc">URL that opens in the app's LiveScore section.</p>
                    <button type="submit" class="btn-save btn-purple">
                        <span>💾 Save LiveScore URL</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Fixtures URL -->
        <div class="dash-card">
            <div class="card-head"><h3>📅 ISF Fixtures URL</h3></div>
            <div class="card-body">
                <?php if (!empty($settings['fixtures_url'])): ?>
                    <div class="url-preview">
                        🔗 <a href="<?= htmlspecialchars($settings['fixtures_url']) ?>" target="_blank">
                               <?= htmlspecialchars($settings['fixtures_url']) ?></a>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="fixtures_url">
                    <label class="field-label">Fixtures Page URL</label>
                    <input type="url" name="fixtures_url" class="field-input"
                           placeholder="https://example.com/fixtures"
                           value="<?= htmlspecialchars($settings['fixtures_url'] ?? '') ?>">
                    <p class="card-desc">URL that opens the match fixtures screen in the app.</p>
                    <button type="submit" class="btn-save btn-purple">
                        <span>💾 Save Fixtures URL</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Standing URL -->
        <div class="dash-card">
            <div class="card-head"><h3>🏆 Football Standing URL</h3></div>
            <div class="card-body">
                <?php if (!empty($settings['standing_url'])): ?>
                    <div class="url-preview">
                        🔗 <a href="<?= htmlspecialchars($settings['standing_url']) ?>" target="_blank">
                               <?= htmlspecialchars($settings['standing_url']) ?></a>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="standing_url">
                    <label class="field-label">Football Standing URL</label>
                    <input type="url" name="standing_url" class="field-input"
                           placeholder="https://example.com/standing"
                           value="<?= htmlspecialchars($settings['standing_url'] ?? '') ?>">
                    <p class="card-desc">URL for the ISF Football league standings table.</p>
                    <button type="submit" class="btn-save btn-purple">
                        <span>💾 Save Standing URL</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistics URL -->
        <div class="dash-card">
            <div class="card-head"><h3>📊 Football Statistics URL</h3></div>
            <div class="card-body">
                <?php if (!empty($settings['statistics_url'])): ?>
                    <div class="url-preview">
                        🔗 <a href="<?= htmlspecialchars($settings['statistics_url']) ?>" target="_blank">
                               <?= htmlspecialchars($settings['statistics_url']) ?></a>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="statistics_url">
                    <label class="field-label">Football Statistics URL</label>
                    <input type="url" name="statistics_url" class="field-input"
                           placeholder="https://example.com/statistics"
                           value="<?= htmlspecialchars($settings['statistics_url'] ?? '') ?>">
                    <p class="card-desc">URL for player and team statistics screen in the app.</p>
                    <button type="submit" class="btn-save btn-purple">
                        <span>💾 Save Statistics URL</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- ══ SECTION 3 – CONTENT ══ -->
    <div class="section-sep">📝 App Content Management</div>
    <div class="cards-grid">

        <!-- About ISF Football -->
        <div class="dash-card card-full">
            <div class="card-head"><h3>⚽ About ISF Football</h3></div>
            <div class="card-body">
                <?php if ($aboutFootball && !empty($aboutFootball['write_up'])): ?>
                    <div class="url-preview" style="border-color:#2c3e50; background:#f0f4fa;">
                        📖 Currently showing: <em><?= mb_strimwidth(strip_tags($aboutFootball['write_up']), 0, 120, '...') ?></em>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="about_football">
                    <label class="field-label">About ISF Football Content</label>
                    <textarea name="about_football_content" class="field-input"
                              placeholder="Write about ISF Football history, rules, teams..."
                              required><?= htmlspecialchars($aboutFootball['write_up'] ?? '') ?></textarea>
                    <p class="card-desc">This text appears in the About section of the ISF Football screen in the mobile app.</p>
                    <button type="submit" class="btn-save btn-purple">
                        <span>💾 Save About Football</span>
                    </button>
                </form>
            </div>
        </div>

       <!-- ISF Update -->
<div class="dash-card card-full">
    <div class="card-head"><h3>📣 ISF Update / Announcement</h3></div>
    <div class="card-body">
        
        <!-- ADD NEW UPDATE FORM (separate) -->
        <form method="POST">
            <input type="hidden" name="action" value="isf_update">
            <label class="field-label">Update Title</label>
            <input type="text" name="update_title" class="field-input"
                   placeholder="e.g., ISF 2026 Kickoff Date Announced"
                   required>
            <label class="field-label">Update Content</label>
            <textarea name="update_content" class="field-input"
                      placeholder="Write the full update or announcement..."
                      required></textarea>
            <p class="card-desc">This update / announcement is pushed to the mobile app's news or updates screen.</p>
            <button type="submit" class="btn-save btn-purple">
                <span>💾 Post ISF Update</span>
            </button>
        </form>

        <hr style="margin:2rem 0;">

        <h4 style="margin-bottom:1rem;">📋 All Updates</h4>

        <?php if (!empty($isfUpdates)): ?>
            <?php foreach ($isfUpdates as $update): ?>
                <div style="border:1px solid #e2e8f0;
                            padding:1rem;
                            border-radius:10px;
                            margin-bottom:1rem;">

                    <strong><?= htmlspecialchars($update['title']) ?></strong>

                    <p style="margin:.5rem 0;">
                        <?= nl2br(htmlspecialchars($update['content'])) ?>
                    </p>

                    <!-- DELETE FORM (separate, not nested) -->
                    <form method="POST" style="margin-top:.5rem;">
                        <input type="hidden" name="action" value="delete_update">
                        <input type="hidden" name="delete_id" value="<?= $update['id'] ?>">
                        <button type="submit"
                                style="background:#e53935;
                                       color:#fff;
                                       border:none;
                                       padding:.4rem .8rem;
                                       border-radius:6px;
                                       cursor:pointer;">
                            🗑 Delete
                        </button>
                    </form>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#718096;">No updates added yet.</p>
        <?php endif; ?>

    </div>
</div>

<script>
// Initialize the hidden field value on page load based on checkbox state
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('marathonCheck');
    const field = document.getElementById('regStatusField');
    
    // Set initial value based on checkbox state
    field.value = checkbox.checked ? 'open' : 'closed';
});

function handleToggleAndSubmit(checkbox) {
    const field  = document.getElementById('regStatusField');
    const label  = document.getElementById('marathonLabel');
    const pill   = document.getElementById('statusPill');
    const text   = document.getElementById('pillText');
    const form   = document.getElementById('marathonForm');

    // Update UI immediately for instant feedback
    if (checkbox.checked) {
        // User switched ON → will save "open"
        field.value       = 'open';
        label.textContent = 'Registration is OPEN — toggle to CLOSE';
        pill.className    = 'status-pill pill-green';
        text.textContent  = 'Marathon Registration: OPEN';
    } else {
        // User switched OFF → will save "closed"
        field.value       = 'closed';
        label.textContent = 'Registration is CLOSED — toggle to OPEN';
        pill.className    = 'status-pill pill-red';
        text.textContent  = 'Marathon Registration: CLOSED';
    }

    // Auto-submit the form after a tiny delay to ensure UI updates first
    setTimeout(function() {
        form.submit();
    }, 100);
}
</script>