<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg = "";
$error = "";

/* Upload Multiple Slides */
if (isset($_POST['add_slides'])) {
    $titles = $_POST['title'];
    $subtitles = $_POST['subtitle'];
    $files = $_FILES['images'];
    
    // Go up from secureadminpanel/pages/ to root, then into uploads/slides/
    $targetDir = "../../uploads/slides/";
    
    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $uploadedCount = 0;
    $totalFiles = count($files['name']);
    
    for ($i = 0; $i < $totalFiles; $i++) {
        if (!empty($files['name'][$i])) {
            $fileName = time() . "_" . $i . "_" . basename($files["name"][$i]);
            $targetFile = $targetDir . $fileName;
            
            if (move_uploaded_file($files["tmp_name"][$i], $targetFile)) {
                $title = !empty($titles[$i]) ? $titles[$i] : "Slide " . ($i + 1);
                $subtitle = !empty($subtitles[$i]) ? $subtitles[$i] : "";
                
                $stmt = $conn->prepare("INSERT INTO slides (image, title, subtitle) VALUES (?, ?, ?)");
                $stmt->execute([$fileName, $title, $subtitle]);
                $uploadedCount++;
            }
        }
    }
    
    if ($uploadedCount > 0) {
        $msg = "$uploadedCount slide(s) uploaded successfully to /uploads/slides/";
    } else {
        $error = "No slides were uploaded. Please try again.";
    }
}

/* Delete Slide */
if (isset($_GET['delete'])) {
    // Get the image filename before deleting
    $stmt = $conn->prepare("SELECT image FROM slides WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $slide = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($slide) {
        // Delete the file from server - go up from admin folder to root
        $filePath = "../../uploads/slides/" . $slide['image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM slides WHERE id=?");
        $stmt->execute([$_GET['delete']]);
    }
    
    header("Location: manage-slides.php");
    exit;
}

$slides = $conn->query("SELECT * FROM slides ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

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
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        font-family: 'Urbanist', sans-serif;
    }
    
    .slides-container {
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
    
    .upload-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }
    
    .upload-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .slide-input-group {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 2px dashed var(--border-color);
        transition: all 0.3s ease;
    }
    
    .slide-input-group:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }
    
    .slide-input-group h5 {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-upload {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    .slides-table-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .table {
        margin: 0;
    }
    
    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.05em;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }
    
    .slide-thumbnail {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .slide-thumbnail:hover {
        transform: scale(1.5);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        z-index: 10;
        position: relative;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
    }
    
    .alert {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        border: none;
        font-weight: 500;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        color: #1b5e20;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fbc2eb 0%, #fa709a 100%);
        color: #b71c1c;
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
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    
    .instruction-box ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #00838f;
    }
    
    .instruction-box li {
        margin-bottom: 0.5rem;
    }
</style>

<div class="slides-container">
    <div class="page-header">
        <h1>🖼️ Slide Management</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Upload and manage your homepage carousel slides</p>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Instructions -->
    <div class="instruction-box">
        <h4>📋 How to Upload Multiple Slides</h4>
        <ul>
            <li>You can upload up to 3 slides at once</li>
            <li>Fill in the title and subtitle for each slide</li>
            <li>Select an image for each slide position</li>
            <li>Leave any slide empty if you want to upload fewer than 3</li>
            <li>Click "Upload All Slides" when ready</li>
        </ul>
    </div>

    <!-- Bulk Upload Form -->
    <div class="upload-card">
        <h3>📤 Upload Multiple Slides</h3>
        <form method="POST" enctype="multipart/form-data">
            
            <!-- Slide 1 -->
            <div class="slide-input-group">
                <h5>Slide 1</h5>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <input type="text" name="title[]" class="form-control" placeholder="Slide Title (e.g., 2023 Football Champions)">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" name="subtitle[]" class="form-control" placeholder="Subtitle (e.g., Oge Family)">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="file" name="images[]" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="slide-input-group">
                <h5>Slide 2</h5>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <input type="text" name="title[]" class="form-control" placeholder="Slide Title">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" name="subtitle[]" class="form-control" placeholder="Subtitle">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="file" name="images[]" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="slide-input-group">
                <h5>Slide 3</h5>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <input type="text" name="title[]" class="form-control" placeholder="Slide Title">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" name="subtitle[]" class="form-control" placeholder="Subtitle">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="file" name="images[]" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button type="submit" name="add_slides" class="btn btn-upload">
                    Upload All Slides 🚀
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Slides Table -->
    <div class="slides-table-card">
        <h3 style="margin-bottom: 1.5rem; color: var(--text-primary); font-weight: 700;">Current Slides</h3>
        
        <?php if(count($slides) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($slides as $slide): ?>
                <tr>
                    <td>
                        <img src="../../uploads/slides/<?= $slide['image'] ?>" 
                             class="slide-thumbnail" 
                             width="120" 
                             alt="<?= htmlspecialchars($slide['title']) ?>">
                    </td>
                    <td><strong><?= htmlspecialchars($slide['title']) ?></strong></td>
                    <td><?= htmlspecialchars($slide['subtitle']) ?></td>
                    <td>
                        <a href="?delete=<?= $slide['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this slide?')">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="text-center" style="padding: 3rem; color: var(--text-secondary);">
                <p style="font-size: 1.2rem;">📭 No slides uploaded yet</p>
                <p>Upload your first slide using the form above</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>