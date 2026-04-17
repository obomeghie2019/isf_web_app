<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg   = "";
$error = "";

// ── Add new highlight ──
if (isset($_POST['add_highlight'])) {
    $title       = trim($_POST['title']       ?? '');
    $url         = trim($_POST['url']         ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($url)) {
        $error = "YouTube URL is required.";
    } elseif (!extractYoutubeId($url)) {
        $error = "Invalid YouTube URL. Please use a valid youtube.com or youtu.be link.";
    } else {
        $videoId      = extractYoutubeId($url);
        $thumbnail    = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        $displayTitle = !empty($title) ? $title : "ISF Highlight";

        $stmt = $conn->prepare("
            INSERT INTO isf_highlights (title, url, thumbnail_url, description, is_active, created_at)
            VALUES (?, ?, ?, ?, 1, NOW())
        ");
        $stmt->execute([$displayTitle, $url, $thumbnail, $description]);
        $msg = "Highlight Added successfully!";
    }
}

// ── Toggle active/inactive ──
if (isset($_GET['toggle'])) {
    $id      = (int) $_GET['toggle'];
    $current = (int) $_GET['status'];
    $newVal  = $current === 1 ? 0 : 1;
    $stmt    = $conn->prepare("UPDATE isf_highlights SET is_active = ? WHERE id = ?");
    $stmt->execute([$newVal, $id]);
    header("Location: football_highlights.php");
    exit;
}

// ── Delete highlight ──
if (isset($_GET['delete'])) {
    $id   = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM isf_highlights WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: football_highlights.php");
    exit;
}

// ── Edit highlight ──
if (isset($_POST['edit_highlight'])) {
    $id          = (int)   $_POST['id'];
    $title       = trim($_POST['title']       ?? '');
    $url         = trim($_POST['url']         ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($url)) {
        $error = "YouTube URL is required.";
    } elseif (!extractYoutubeId($url)) {
        $error = "Invalid YouTube URL.";
    } else {
        $videoId   = extractYoutubeId($url);
        $thumbnail = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";

        $stmt = $conn->prepare("
            UPDATE isf_highlights
            SET title = ?, url = ?, thumbnail_url = ?, description = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $url, $thumbnail, $description, $id]);
        $msg = "Highlight updated successfully!";
    }
}

// ── Fetch all highlights ──
$highlights = $conn->query("
    SELECT * FROM isf_highlights ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ── Fetch single for edit modal ──
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM isf_highlights WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ── Helper ──
function extractYoutubeId(string $url): ?string {
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
    ];
    foreach ($patterns as $p) {
        if (preg_match($p, $url, $m)) return $m[1];
    }
    return null;
}

include 'header.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-bg: #ffffff;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --shadow-md: 0 4px 20px rgba(0,0,0,0.12);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        font-family: 'Urbanist', sans-serif;
    }

    .highlights-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .card-box {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .card-box h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Urbanist', sans-serif;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .btn-primary-grad {
        background: var(--primary-gradient);
        color: white;
        padding: 0.85rem 2.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        font-family: 'Urbanist', sans-serif;
    }

    .btn-primary-grad:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .instruction-box {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border-left: 4px solid #00acc1;
    }

    .instruction-box h4 {
        color: #006064;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .instruction-box ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #00838f;
    }

    .instruction-box li { margin-bottom: 0.4rem; }

    .alert {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        border: none;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        color: #1b5e20;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fbc2eb 0%, #fa709a 100%);
        color: #b71c1c;
    }

    /* ── Video Cards Grid ── */
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .video-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .video-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.15);
    }

    .video-thumb-wrap {
        position: relative;
        width: 100%;
        height: 170px;
        overflow: hidden;
        background: #111;
    }

    .video-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .video-card:hover .video-thumb-wrap img {
        transform: scale(1.06);
    }

    .play-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 48px;
        height: 48px;
        background: rgba(255, 0, 0, 0.85);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .play-badge svg {
        width: 22px;
        height: 22px;
        fill: white;
        margin-left: 3px;
    }

    .status-pill {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    .status-pill.active   { background: #c6f6d5; color: #22543d; }
    .status-pill.inactive { background: #fed7d7; color: #742a2a; }

    .video-card-body {
        padding: 1rem;
    }

    .video-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.35rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .video-card-desc {
        font-size: 0.82rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .video-card-meta {
        font-size: 0.78rem;
        color: #a0aec0;
        margin-bottom: 1rem;
    }

    .video-card-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-sm-action {
        flex: 1;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        border: none;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
        font-family: 'Urbanist', sans-serif;
    }

    .btn-edit    { background: #ebf8ff; color: #2b6cb0; }
    .btn-edit:hover { background: #bee3f8; }

    .btn-toggle-on  { background: #fefcbf; color: #744210; }
    .btn-toggle-on:hover  { background: #faf089; }

    .btn-toggle-off { background: #c6f6d5; color: #22543d; }
    .btn-toggle-off:hover { background: #9ae6b4; }

    .btn-delete { background: #fff5f5; color: #c53030; }
    .btn-delete:hover { background: #fed7d7; }

    .btn-preview { background: #f0fff4; color: #276749; }
    .btn-preview:hover { background: #c6f6d5; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state p { font-size: 1.1rem; margin-bottom: 0.5rem; }

    /* ── Modal ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 540px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        position: relative;
        margin: 1rem;
    }

    .modal-box h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #f7fafc;
        border: none;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #718096;
        transition: background 0.2s;
    }

    .modal-close:hover { background: #edf2f7; }

    /* Stats bar */
    .stats-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .stat-chip {
        flex: 1;
        min-width: 120px;
        background: #f7fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }

    .stat-chip .stat-num {
        font-size: 2rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-chip .stat-label {
        font-size: 0.82rem;
        color: var(--text-secondary);
        font-weight: 500;
        margin-top: 0.25rem;
    }
</style>

<div class="highlights-container">

    <div class="page-header">
        <h1>🎬 ISF Football Highlights Manager</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">
            Manage YouTube football highlight videos shown in the app
        </p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ── Stats Bar ── -->
    <?php
        $total    = count($highlights);
        $active   = count(array_filter($highlights, fn($h) => $h['is_active'] == 1));
        $inactive = $total - $active;
    ?>
    <div class="stats-bar">
        <div class="stat-chip">
            <div class="stat-num"><?= $total ?></div>
            <div class="stat-label">Total Videos</div>
        </div>
        <div class="stat-chip">
            <div class="stat-num"><?= $active ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-chip">
            <div class="stat-num"><?= $inactive ?></div>
            <div class="stat-label">Hidden</div>
        </div>
    </div>

    <!-- ── Instructions ── -->
    <div class="instruction-box">
        <h4>📋 How to Add Highlights</h4>
        <ul>
            <li>Paste any YouTube URL — standard, short (youtu.be), embed, or Shorts links all work</li>
            <li>Title is optional — defaults to "ISF Highlight" if left blank</li>
            <li>Thumbnails are auto-fetched from YouTube — no upload needed</li>
            <li>Use the toggle button to show/hide a video without deleting it</li>
            <li>Only <strong>Active</strong> videos are shown to app users</li>
        </ul>
    </div>

    <!-- ── Add Form ── -->
    <div class="card-box">
        <h3>➕ Add New Highlight</h3>
        <form method="POST">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label style="font-weight:600; color:var(--text-primary); margin-bottom:.4rem; display:block;">
                        Video Title <span style="color:#a0aec0; font-weight:400;">(optional)</span>
                    </label>
                    <input type="text" name="title" class="form-control"
                           placeholder="e.g. ISF 2025 Grand Final Highlights">
                </div>
                <div class="col-md-5 mb-3">
                    <label style="font-weight:600; color:var(--text-primary); margin-bottom:.4rem; display:block;">
                        YouTube URL <span style="color:#e53e3e;">*</span>
                    </label>
                    <input type="text" name="url" class="form-control" required
                           placeholder="https://youtu.be/xxxx  or  youtube.com/watch?v=xxxx">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" name="add_highlight" class="btn-primary-grad w-100">
                        Add ➕
                    </button>
                </div>
            </div>
            <div class="mb-2">
                <label style="font-weight:600; color:var(--text-primary); margin-bottom:.4rem; display:block;">
                    Description <span style="color:#a0aec0; font-weight:400;">(optional)</span>
                </label>
                <textarea name="description" class="form-control" rows="2"
                          placeholder="Short description about this highlight..."></textarea>
            </div>
        </form>
    </div>

    <!-- ── Video Grid ── -->
    <div class="card-box">
        <h3>🎥 All Highlights (<?= $total ?>)</h3>

        <?php if ($total > 0): ?>
        <div class="video-grid">
            <?php foreach ($highlights as $h):
                $vid = extractYoutubeId($h['url']);
                $thumb = !empty($h['thumbnail_url'])
                    ? $h['thumbnail_url']
                    : ($vid ? "https://img.youtube.com/vi/{$vid}/hqdefault.jpg" : '');
                $isActive = (int) $h['is_active'] === 1;
            ?>
            <div class="video-card">

                <!-- Thumbnail -->
                <div class="video-thumb-wrap">
                    <?php if ($thumb): ?>
                        <img src="<?= htmlspecialchars($thumb) ?>"
                             alt="<?= htmlspecialchars($h['title']) ?>"
                             onerror="this.src='https://via.placeholder.com/300x170/111827/ffffff?text=No+Thumbnail'">
                    <?php else: ?>
                        <div style="width:100%;height:100%;background:#1a202c;display:flex;align-items:center;justify-content:center;">
                            <span style="color:#4a5568;font-size:2rem;">🎬</span>
                        </div>
                    <?php endif; ?>

                    <div class="play-badge">
                        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>

                    <span class="status-pill <?= $isActive ? 'active' : 'inactive' ?>">
                        <?= $isActive ? '● Active' : '● Hidden' ?>
                    </span>
                </div>

                <!-- Body -->
                <div class="video-card-body">
                    <div class="video-card-title" title="<?= htmlspecialchars($h['title']) ?>">
                        <?= htmlspecialchars($h['title']) ?>
                    </div>

                    <?php if (!empty($h['description'])): ?>
                    <div class="video-card-desc">
                        <?= htmlspecialchars($h['description']) ?>
                    </div>
                    <?php endif; ?>

                    <div class="video-card-meta">
                        🕒 <?= date('d M Y, H:i', strtotime($h['created_at'])) ?>
                    </div>

                    <!-- Actions -->
                    <div class="video-card-actions">
                        <!-- Preview -->
                        <?php if ($vid): ?>
                        <a href="https://www.youtube.com/watch?v=<?= $vid ?>"
                           target="_blank" class="btn-sm-action btn-preview">
                            ▶ Preview
                        </a>
                        <?php endif; ?>

                        <!-- Edit -->
                        <a href="?edit=<?= $h['id'] ?>"
                           class="btn-sm-action btn-edit">✏️ Edit</a>

                        <!-- Toggle -->
                        <a href="?toggle=<?= $h['id'] ?>&status=<?= $h['is_active'] ?>"
                           class="btn-sm-action <?= $isActive ? 'btn-toggle-on' : 'btn-toggle-off' ?>"
                           onclick="return confirm('<?= $isActive ? 'Hide this video from the app?' : 'Make this video visible in the app?' ?>')">
                           <?= $isActive ? '🙈 Hide' : '👁 Show' ?>
                        </a>

                        <!-- Delete -->
                        <a href="?delete=<?= $h['id'] ?>"
                           class="btn-sm-action btn-delete"
                           onclick="return confirm('Permanently delete this highlight?')">
                           🗑 Delete
                        </a>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <p>🎬 No highlights added yet</p>
            <p style="font-size:.9rem;">Use the form above to add your first YouTube highlight video</p>
        </div>
        <?php endif; ?>
    </div>

</div>


<!-- ══════════════════════════════════════
     Edit Modal — opens if ?edit=ID in URL
════════════════════════════════════════ -->
<div class="modal-overlay <?= $editItem ? 'show' : '' ?>" id="editModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal()">✕</button>
        <h3>✏️ Edit Highlight</h3>

        <form method="POST">
            <input type="hidden" name="id"
                   value="<?= $editItem ? (int)$editItem['id'] : '' ?>">

            <div class="mb-3">
                <label style="font-weight:600;color:var(--text-primary);margin-bottom:.4rem;display:block;">
                    Title
                </label>
                <input type="text" name="title" class="form-control"
                       value="<?= $editItem ? htmlspecialchars($editItem['title']) : '' ?>"
                       placeholder="Video title">
            </div>

            <div class="mb-3">
                <label style="font-weight:600;color:var(--text-primary);margin-bottom:.4rem;display:block;">
                    YouTube URL <span style="color:#e53e3e;">*</span>
                </label>
                <input type="text" name="url" class="form-control" required
                       value="<?= $editItem ? htmlspecialchars($editItem['url']) : '' ?>"
                       placeholder="https://youtu.be/xxxx">
            </div>

            <div class="mb-4">
                <label style="font-weight:600;color:var(--text-primary);margin-bottom:.4rem;display:block;">
                    Description
                </label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Short description..."><?= $editItem ? htmlspecialchars($editItem['description']) : '' ?></textarea>
            </div>

            <div style="display:flex; gap:1rem;">
                <button type="submit" name="edit_highlight" class="btn-primary-grad" style="flex:1;">
                    Save Changes ✅
                </button>
                <button type="button" onclick="closeModal()"
                        style="flex:1; padding:.85rem; border-radius:10px; border:2px solid var(--border-color);
                               background:#fff; font-weight:600; cursor:pointer; font-family:'Urbanist',sans-serif;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('editModal').classList.remove('show');
    // Remove ?edit= from URL without reload
    const url = new URL(window.location.href);
    url.searchParams.delete('edit');
    window.history.replaceState({}, '', url);
}
// Close modal on overlay click
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

