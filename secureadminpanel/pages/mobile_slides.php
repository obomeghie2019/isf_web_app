<?php
ob_start(); // buffer all output — ensures header() redirects always work even after header.php output
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Safe session start — won't conflict if auth.php already started it
if (session_status() === PHP_SESSION_NONE) session_start();
$msg   = "";
$error = "";

$isf_root   = dirname(dirname(__DIR__));
$upload_dir = $isf_root . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
$base_url   = 'https://360globalnetwork.com.ng/isf2025/img/';

// ── Add New Slide ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slide'])) {

    // DEBUG: uncomment next line if upload still fails to see exact error
    // var_dump($_FILES, $_POST, $upload_dir, is_writable($upload_dir)); exit;

    $title      = trim($_POST['title'] ?? '');
    $link_url   = trim($_POST['link_url'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 1);
    $is_active  = isset($_POST['is_active']) ? 1 : 0;

    if (empty($_FILES['image']['name'])) {
        $error = "Please select an image.";
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $codes = [1=>'File too large (php.ini)',2=>'File too large (form)',3=>'Partial upload',4=>'No file',6=>'No tmp folder',7=>'Cannot write',8=>'Extension stopped'];
        $error = "Upload error: " . ($codes[$_FILES['image']['error']] ?? 'Code '.$_FILES['image']['error']);
    } else {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Only JPG, PNG or WEBP allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $error = "Image must be under 2MB.";
        } else {
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $filename  = 'slide_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest      = $upload_dir . $filename;
            $image_url = $base_url . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $stmt = $conn->prepare(
                    "INSERT INTO isf_carousel_slides (image_url,title,link_url,sort_order,is_active)
                     VALUES (?,?,?,?,?)"
                );
                $stmt->execute([$image_url, $title ?: null, $link_url ?: null, $sort_order, $is_active]);
                $_SESSION['flash_msg'] = "Slide added successfully!";
                header("Location: mobile_slides.php");
                exit;
            } else {
                $error = "move_uploaded_file() failed.<br>"
                       . "Destination: <code>".htmlspecialchars($dest)."</code><br>"
                       . "Dir exists: ".(is_dir($upload_dir)?'Yes':'No')."<br>"
                       . "Dir writable: ".(is_writable($upload_dir)?'Yes':'No');
            }
        }
    }
}

// ── Sort Order ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    foreach ($_POST['order'] as $id => $ord) {
        $conn->exec("UPDATE isf_carousel_slides SET sort_order=".(int)$ord." WHERE id=".(int)$id);
    }
    $_SESSION['flash_msg'] = "Order updated!";
    header("Location: mobile_slides.php");
    exit;
}

// ── Delete ──
if (isset($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $row = $conn->query("SELECT image_url FROM isf_carousel_slides WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $fp = $upload_dir . basename($row['image_url']);
        if (file_exists($fp)) unlink($fp);
        $conn->exec("DELETE FROM isf_carousel_slides WHERE id=$id");
    }
    $_SESSION['flash_msg'] = "Slide deleted.";
    header("Location: mobile_slides.php");
    exit;
}

// ── Toggle ──
if (isset($_GET['toggle'])) {
    $conn->exec("UPDATE isf_carousel_slides SET is_active = NOT is_active WHERE id=".(int)$_GET['toggle']);
    header("Location: mobile_slides.php");
    exit;
}

if (!empty($_SESSION['flash_msg'])) { $msg = $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); }

$slides = $conn->query("SELECT * FROM isf_carousel_slides ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap');
*,*::before,*::after{box-sizing:border-box;}
body{background:linear-gradient(135deg,#f5f7fa,#e9ecef);font-family:'Urbanist',sans-serif;margin:0;}
.wrap{max-width:1100px;margin:2rem auto;padding:0 1rem;}
.ph{text-align:center;margin-bottom:2rem;}
.ph h1{font-size:2.2rem;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.5rem;}
.sbadge{display:inline-flex;align-items:center;background:linear-gradient(135deg,#f093fb,#f5576c);color:#fff;padding:.4rem 1.2rem;border-radius:30px;font-weight:700;font-size:.9rem;margin-bottom:.75rem;box-shadow:0 4px 12px rgba(245,87,108,.35);}
.card{background:#fff;border-radius:16px;padding:2rem;box-shadow:0 4px 20px rgba(0,0,0,.12);border:1px solid #e2e8f0;margin-bottom:2rem;}
.card h3{color:#2d3748;font-weight:700;margin-bottom:1.5rem;font-size:1.2rem;display:flex;align-items:center;gap:.5rem;border-bottom:2px solid #e2e8f0;padding-bottom:.75rem;}
.pbox{background:#f0fdf4;border:1px solid #86efac;border-radius:10px;padding:.85rem 1.2rem;margin-bottom:1.5rem;font-size:.83rem;color:#166534;}
.pbox code{background:#dcfce7;padding:.1rem .4rem;border-radius:4px;word-break:break-all;}
.ibox{background:linear-gradient(135deg,#e0f7fa,#b2ebf2);padding:1.2rem 1.5rem;border-radius:12px;margin-bottom:1.5rem;border-left:4px solid #00acc1;}
.ibox h4{color:#006064;font-weight:700;margin:0 0 .3rem;font-size:1rem;}
.ibox p{margin:0;color:#00838f;font-size:.9rem;}
.alert{border-radius:12px;padding:1rem 1.5rem;border:none;font-weight:500;margin-bottom:1.5rem;font-size:.95rem;}
.ok{background:linear-gradient(135deg,#d4fc79,#96e6a1);color:#1b5e20;}
.err{background:linear-gradient(135deg,#fbc2eb,#fa709a);color:#b71c1c;}

/* --- The simplest possible file input --- */
.file-wrap{margin-bottom:1rem;}
.flbl{font-weight:600;color:#2d3748;display:block;margin-bottom:.5rem;font-size:.93rem;}
/* Style the label as the clickable zone */
.pick-zone{display:block;border:3px dashed #667eea;border-radius:14px;padding:2rem 1rem;text-align:center;background:#f8f7ff;cursor:pointer;transition:all .3s;}
.pick-zone:hover{background:#ede9ff;border-color:#764ba2;}
.pick-zone span{display:block;font-size:2rem;margin-bottom:.4rem;}
.pick-zone strong{display:block;color:#667eea;font-size:1rem;margin-bottom:.25rem;}
.pick-zone small{color:#718096;font-size:.82rem;}
.hint{display:inline-block;background:#fff3cd;color:#856404;border:1px solid #ffc107;border-radius:8px;padding:.3rem .8rem;font-size:.8rem;font-weight:600;margin-top:.6rem;}
/* The actual input is hidden, triggered by label's for= */
#imgFile{display:none;}

/* Preview */
#prevWrap{display:none;margin-top:1rem;border:3px solid #667eea;border-radius:12px;overflow:hidden;}
#prevWrap img{width:100%;max-height:200px;object-fit:cover;display:block;}
#prevWrap .pi{background:#f8f7ff;padding:.5rem 1rem;text-align:center;font-size:.82rem;color:#667eea;font-weight:600;}

.frow{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
@media(max-width:600px){.frow{grid-template-columns:1fr;}}
.fg{margin-bottom:1rem;}
.fl{font-weight:600;color:#2d3748;display:block;margin-bottom:.4rem;font-size:.9rem;}
.fc{border-radius:8px;border:2px solid #e2e8f0;padding:.6rem 1rem;font-size:.92rem;font-family:'Urbanist',sans-serif;width:100%;transition:border-color .3s;}
.fc:focus{border-color:#667eea;outline:none;box-shadow:0 0 0 3px rgba(102,126,234,.15);}
.tw{display:flex;align-items:center;gap:.75rem;margin-top:.5rem;}
.tw input[type=checkbox]{display:none;}
.tl{width:50px;height:26px;background:#ccc;border-radius:13px;cursor:pointer;position:relative;transition:background .3s;}
.tl::after{content:'';position:absolute;width:20px;height:20px;background:#fff;border-radius:50%;top:3px;left:3px;transition:left .3s;}
input[type=checkbox]:checked+.tl{background:linear-gradient(135deg,#667eea,#764ba2);}
input[type=checkbox]:checked+.tl::after{left:27px;}
.bup{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:.85rem 2rem;border:none;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;width:100%;margin-top:.5rem;box-shadow:0 4px 15px rgba(102,126,234,.4);font-family:'Urbanist',sans-serif;transition:all .3s;}
.bup:hover{transform:translateY(-2px);}

.tbl{width:100%;border-collapse:collapse;}
.tbl th{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:.8rem 1rem;text-align:left;font-size:.87rem;font-weight:600;}
.tbl th:first-child{border-radius:10px 0 0 0;}.tbl th:last-child{border-radius:0 10px 0 0;}
.tbl td{padding:.8rem 1rem;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:.87rem;}
.tbl tr:hover td{background:#f8f7ff;}
.thumb{width:120px;height:41px;object-fit:cover;border-radius:6px;border:2px solid #e2e8f0;}
.bon{background:linear-gradient(135deg,#d4fc79,#96e6a1);color:#1b5e20;padding:.25rem .75rem;border-radius:20px;font-size:.77rem;font-weight:700;}
.bof{background:linear-gradient(135deg,#fbc2eb,#fa709a);color:#b71c1c;padding:.25rem .75rem;border-radius:20px;font-size:.77rem;font-weight:700;}
.acts{display:flex;gap:.5rem;}
.bs{padding:.3rem .8rem;border-radius:8px;font-size:.77rem;font-weight:600;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.25rem;transition:all .2s;font-family:'Urbanist',sans-serif;}
.bt{background:#e0f2fe;color:#0277bd;}.bt:hover{background:#0277bd;color:#fff;}
.bd{background:#fce4ec;color:#c62828;}.bd:hover{background:#c62828;color:#fff;}
.oi{width:55px;text-align:center;padding:.3rem;border:2px solid #e2e8f0;border-radius:6px;font-family:'Urbanist',sans-serif;font-weight:600;}
.bord{background:linear-gradient(135deg,#4facfe,#00f2fe);color:#fff;padding:.6rem 1.5rem;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.9rem;margin-top:1rem;font-family:'Urbanist',sans-serif;}
.empty{text-align:center;padding:3rem;color:#718096;}
</style>

<div class="wrap">
    <div class="ph">
        <h1>🖼️ Mobile Carousel Slides</h1>
        <div class="sbadge">📐 750 × 257 px &nbsp;|&nbsp; JPG / PNG / WEBP &nbsp;|&nbsp; Max 2MB</div>
        <p style="color:#718096;font-size:.93rem;margin:0;">Manage banner slides on the ISF mobile app</p>
    </div>

    <?php if($msg): ?><div class="alert ok">✅ <?=htmlspecialchars($msg)?></div><?php endif; ?>
    <?php if($error): ?><div class="alert err">❌ <?=$error?></div><?php endif; ?>

    <div class="ibox">
        <h4>💡 Tips</h4>
        <p>Use <strong>750 × 257 px</strong> images. Refresh after upload will <strong>not</strong> duplicate slides.</p>
    </div>

    <!-- ADD SLIDE -->
    <div class="card">
        <h3>➕ Add New Slide</h3>
        <div class="pbox">
            <strong>📁 Save path:</strong> <code><?=htmlspecialchars($upload_dir)?></code>
            — Writable: <?=(is_writable($upload_dir)||!is_dir($upload_dir))?'✅':'❌ chmod 755 needed'?>
            <br><strong>🌐 URL:</strong> <code><?=htmlspecialchars($base_url)?></code>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_slide" value="1">

            <!-- FILE PICK: label triggers hidden input — zero JS needed to open picker -->
            <div class="file-wrap">
                <label class="flbl">Slide Image <span style="color:#f5576c">*</span></label>
                <label class="pick-zone" for="imgFile" id="pickZone">
                    <span>🖼️</span>
                    <strong id="pickText">Click here to choose image</strong>
                    <small>JPG / PNG / WEBP · Max 2MB</small>
                    <span class="hint">📐 Ideal: 750 × 257 px</span>
                </label>
                <input type="file" id="imgFile" name="image" accept="image/jpeg,image/png,image/webp">
            </div>

            <!-- PREVIEW -->
            <div id="prevWrap">
                <img id="prevImg" src="" alt="preview">
                <div class="pi" id="prevInfo"></div>
            </div>

            <div class="frow" style="margin-top:1.2rem;">
                <div class="fg">
                    <label class="fl">Title <span style="font-weight:400;color:#718096;">(optional)</span></label>
                    <input type="text" name="title" class="fc" placeholder="e.g. ISF 2025 Opening">
                </div>
                <div class="fg">
                    <label class="fl">Link URL <span style="font-weight:400;color:#718096;">(optional)</span></label>
                    <input type="url" name="link_url" class="fc" placeholder="https://...">
                </div>
            </div>
            <div class="frow">
                <div class="fg">
                    <label class="fl">Sort Order</label>
                    <input type="number" name="sort_order" class="fc" value="<?=count($slides)+1?>" min="1">
                </div>
                <div class="fg">
                    <label class="fl">Show on App?</label>
                    <div class="tw">
                        <input type="checkbox" id="ia" name="is_active" checked>
                        <label class="tl" for="ia"></label>
                        <span style="color:#718096;font-size:.88rem;">Active</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="bup">🚀 Upload & Add Slide</button>
        </form>
    </div>

    <!-- MANAGE SLIDES -->
    <div class="card">
        <h3>📋 Slides (<?=count($slides)?>)</h3>
        <?php if(empty($slides)): ?>
            <div class="empty"><div style="font-size:3rem;">🖼️</div><p>No slides yet.</p></div>
        <?php else: ?>
        <form method="POST">
            <div style="overflow-x:auto;">
            <table class="tbl">
                <thead><tr><th>Preview</th><th>Title</th><th>File</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach($slides as $s): ?>
                <tr>
                    <td><img src="<?=htmlspecialchars($s['image_url'])?>" class="thumb"
                             onerror="this.src='https://via.placeholder.com/120x41?text=No+Img'"></td>
                    <td><strong><?=htmlspecialchars($s['title']??'—')?></strong></td>
                    <td><small style="color:#667eea;word-break:break-all;"><?=htmlspecialchars(basename($s['image_url']))?></small></td>
                    <td><?=$s['is_active']?'<span class="bon">● Active</span>':'<span class="bof">● Hidden</span>'?></td>
                    <td><input type="number" name="order[<?=$s['id']?>]" value="<?=(int)$s['sort_order']?>" class="oi" min="1"></td>
                    <td><div class="acts">
                        <a href="?toggle=<?=$s['id']?>" class="bs bt"><?=$s['is_active']?'👁️ Hide':'✅ Show'?></a>
                        <a href="?delete=<?=$s['id']?>" class="bs bd" onclick="return confirm('Delete?')">🗑️ Delete</a>
                    </div></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <button type="submit" name="update_order" class="bord">💾 Save Order</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<script>
// Preview only — no form submit logic at all
document.getElementById('imgFile').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 2097152) {
        alert('Too large. Max 2MB.');
        this.value = '';
        return;
    }

    document.getElementById('pickText').textContent = '✅ ' + file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('prevImg');
        const wrap = document.getElementById('prevWrap');
        img.src = e.target.result;
        wrap.style.display = 'block';
        img.onload = function() {
            document.getElementById('prevInfo').textContent =
                img.naturalWidth + ' × ' + img.naturalHeight + ' px'
                + (img.naturalWidth===750 && img.naturalHeight===257 ? ' ✅ Perfect!' : ' — ideal: 750×257');
        };
    };
    reader.readAsDataURL(file);
});
</script>