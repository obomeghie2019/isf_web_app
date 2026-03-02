<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg = "";
$error = "";

// Fetch current banner
$stmt = $conn->query("SELECT * FROM banners LIMIT 1");
$banner = $stmt->fetch(PDO::FETCH_ASSOC);

// Create default banner if none exists
if (!$banner) {
    $conn->exec("INSERT INTO banners (image) VALUES ('')");
    $banner = $conn->query("SELECT * FROM banners LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

// Update banner
if (isset($_POST['update_banner'])) {
    $imageName = $banner['image']; // Keep existing image by default
    
    // Handle image upload
    if (!empty($_FILES['banner_image']['name'])) {
        $targetDir = "../../uploads/banners/";
        
        // Create directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Delete old image if exists
        if (!empty($banner['image']) && file_exists($targetDir . $banner['image'])) {
            unlink($targetDir . $banner['image']);
        }
        
        // Upload new image
        $fileName = time() . "_" . basename($_FILES["banner_image"]["name"]);
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $targetFile)) {
            $imageName = $fileName;
            $msg = "Banner image updated successfully!";
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please select an image to upload.";
    }
    
    // Update database
    if ($imageName) {
        $stmt = $conn->prepare("UPDATE banners SET image=? WHERE id=?");
        $stmt->execute([$imageName, $banner['id']]);
        
        // Refresh banner data
        $banner = $conn->query("SELECT * FROM banners LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }
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
    
    .banner-container {
        max-width: 900px;
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
    
    .banner-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section h3 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .current-image-section {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 2px dashed var(--border-color);
        text-align: center;
    }
    
    .current-image-section h4 {
        color: #667eea;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .banner-preview {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: block;
        margin: 0 auto;
    }
    
    .no-image-placeholder {
        text-align: center;
        padding: 4rem 2rem;
        background: #e9ecef;
        border-radius: 12px;
        color: var(--text-secondary);
    }
    
    .no-image-placeholder svg {
        width: 100px;
        height: 100px;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    
    .file-upload-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }
    
    .file-upload-label {
        display: block;
        padding: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .file-upload-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .file-name-display {
        margin-top: 0.75rem;
        color: var(--text-secondary);
        font-size: 0.95rem;
        text-align: center;
    }
    
    .btn-update {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        width: 100%;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.5);
        color: white;
    }
    
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
    
    .info-box {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border-left: 4px solid #00acc1;
    }
    
    .info-box h4 {
        color: #006064;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .info-box p {
        margin: 0;
        color: #00838f;
        line-height: 1.6;
    }
</style>

<div class="banner-container">
    <div class="page-header">
        <h1>🎨 Body Banner Management</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Upload a vertical banner image for your homepage</p>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="info-box">
        <h4>📋 Banner Guidelines</h4>
        <p>Upload a vertical banner image. Recommended dimensions: 400px width x 600px height (or any vertical orientation). Max file size: 5MB. Formats: JPG, PNG, WEBP</p>
    </div>

    <div class="banner-card">
        <form method="POST" enctype="multipart/form-data">
            
            <!-- Current Banner Preview -->
            <div class="form-section">
                <h3>🖼️ Current Banner</h3>
                <div class="current-image-section">
                    <?php if (!empty($banner['image']) && file_exists("../../uploads/banners/" . $banner['image'])): ?>
                        <img src="../../uploads/banners/<?= htmlspecialchars($banner['image']) ?>" 
                             class="banner-preview" 
                             alt="Current Banner">
                    <?php else: ?>
                        <div class="no-image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <p style="font-size: 1.1rem; margin: 0; font-weight: 600;">No banner image uploaded yet</p>
                            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Upload your first banner below</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="form-section">
                <h3>📤 Upload New Banner Image</h3>
                <div class="file-upload-wrapper">
                    <input type="file" 
                           name="banner_image" 
                           id="banner_image" 
                           accept="image/*"
                           onchange="displayFileName()"
                           required>
                    <label for="banner_image" class="file-upload-label">
                        📁 Choose Banner Image
                    </label>
                    <div class="file-name-display" id="file-name-display">
                        No file chosen
                    </div>
                </div>
                <small style="color: var(--text-secondary); display: block; margin-top: 1rem; text-align: center;">
                    💡 Recommended: 400x600px (vertical) | Max: 5MB | JPG, PNG, WEBP
                </small>
            </div>

            <!-- Submit Button -->
            <div class="form-section">
                <button type="submit" name="update_banner" class="btn btn-update">
                    Upload Banner 🚀
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function displayFileName() {
    const input = document.getElementById('banner_image');
    const display = document.getElementById('file-name-display');
    
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        display.textContent = `✅ Selected: ${fileName} (${fileSize} MB)`;
        display.style.color = '#667eea';
        display.style.fontWeight = '600';
    } else {
        display.textContent = 'No file chosen';
        display.style.color = '#718096';
        display.style.fontWeight = '400';
    }
}
</script>


