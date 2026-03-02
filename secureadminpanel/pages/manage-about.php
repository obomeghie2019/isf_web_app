<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg = "";
$error = "";

// Fetch current about us content
$stmt = $conn->query("SELECT * FROM about_us LIMIT 1");
$aboutUs = $stmt->fetch(PDO::FETCH_ASSOC);

// Create default content if none exists
if (!$aboutUs) {
    $defaultContent = "The Iyekhei Sport Festival (ISF) is an annual sporting event organized under the Auchi Dynamic Youth Association (Zone E).\n\nSince 2018, ISF has promoted unity, youth empowerment, and sportsmanship among Iyekhei sons and daughters, bringing together communities through the power of sports.";
    $conn->exec("INSERT INTO about_us (content) VALUES ('$defaultContent')");
    $aboutUs = $conn->query("SELECT * FROM about_us LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

// Update about us content
if (isset($_POST['update_about'])) {
    $content = $_POST['content'];
    
    if (!empty($content)) {
        $stmt = $conn->prepare("UPDATE about_us SET content=? WHERE id=?");
        $stmt->execute([$content, $aboutUs['id']]);
        
        $msg = "About Us content updated successfully!";
        
        // Refresh data
        $aboutUs = $conn->query("SELECT * FROM about_us LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "Content cannot be empty.";
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
    
    .about-container {
        max-width: 1000px;
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
    
    .about-card {
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
    
    .form-control {
        border-radius: 8px;
        border: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Urbanist', sans-serif;
        line-height: 1.8;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    textarea.form-control {
        min-height: 300px;
        resize: vertical;
    }
    
    .preview-section {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 2px dashed var(--border-color);
    }
    
    .preview-section h4 {
        color: #667eea;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .preview-content {
        color: var(--text-primary);
        line-height: 1.8;
        font-size: 1rem;
        white-space: pre-wrap;
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
    
    .char-counter {
        text-align: right;
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
</style>

<div class="about-container">
    <div class="page-header">
        <h1>📝 About Us Management</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Update your About Iyekhei Sport Festival content</p>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="info-box">
        <h4>💡 Tips for Great Content</h4>
        <p>Write a compelling story about ISF. Include your mission, history, achievements, and what makes your festival special. Use line breaks to separate paragraphs for better readability.</p>
    </div>

    <div class="about-card">
        <form method="POST" onsubmit="return confirm('Are you sure you want to update the About Us content?')">
            
            <!-- Current Content Preview -->
            <div class="form-section">
                <h3>👁️ Current Content Preview</h3>
                <div class="preview-section">
                    <div class="preview-content">
                        <?= !empty($aboutUs['content']) ? nl2br(htmlspecialchars($aboutUs['content'])) : 'No content yet' ?>
                    </div>
                </div>
            </div>

            <!-- Edit Content -->
            <div class="form-section">
                <h3>✏️ Edit About Us Content</h3>
                <textarea name="content" 
                          id="aboutContent"
                          class="form-control" 
                          placeholder="Enter your About Us content here... Use Enter to create new paragraphs."
                          required
                          oninput="updateCharCount()"><?= htmlspecialchars($aboutUs['content']) ?></textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> characters
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-section">
                <button type="submit" name="update_about" class="btn btn-update">
                    Update About Us 🚀
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter
function updateCharCount() {
    const textarea = document.getElementById('aboutContent');
    const charCount = document.getElementById('charCount');
    charCount.textContent = textarea.value.length;
}

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
});
</script>
