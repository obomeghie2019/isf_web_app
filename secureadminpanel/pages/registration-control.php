<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}
/* Fetch current registration status */
$stmt = $conn->prepare("SELECT registration_status FROM system_settings LIMIT 1");
$stmt->execute();
$setting = $stmt->fetch(PDO::FETCH_ASSOC);
$status = $setting['registration_status'] ?? 'closed';
/* Toggle Registration */
if (isset($_POST['registration_toggle'])) {
    $newStatus = ($_POST['registration_toggle'] === 'on') ? 'open' : 'closed';
    $update = $conn->prepare("UPDATE system_settings SET registration_status = ?");
    $update->execute([$newStatus]);
    header("Location: registration-control.php");
    exit;
}
include 'header.php';
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap');
    
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --dark-bg: #1a1d29;
        --card-bg: #ffffff;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 20px rgba(0,0,0,0.12);
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        font-family: 'Urbanist', sans-serif;
        min-height: 100vh;
    }
    
    .admin-panel-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2.5rem 1rem;
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .page-header {
        margin-bottom: 3rem;
        text-align: center;
    }
    
    .page-header h1 {
        font-size: 2.75rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }
    
    .page-header p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .management-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 992px) {
        .management-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title::before {
        content: '';
        width: 4px;
        height: 28px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }
    
    .admin-card {
        background: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color);
        position: relative;
    }
    
    .admin-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .admin-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }
    
    .admin-card:hover::before {
        transform: scaleX(1);
    }
    
    .card-header-custom {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .card-header-custom::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s;
    }
    
    .admin-card:hover .card-header-custom::after {
        opacity: 1;
    }
    
    .card-header-custom h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .card-header-custom .icon {
        font-size: 1.5rem;
    }
    
    .card-body-custom {
        padding: 2rem;
    }
    
    .card-body-custom p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    /* Toggle Switch Enhanced */
    .switch {
        position: relative;
        display: inline-block;
        width: 80px;
        height: 40px;
    }
    
    .switch input {
        display: none;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 40px;
        box-shadow: 0 2px 8px rgba(239, 83, 80, 0.3);
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 32px;
        width: 32px;
        left: 4px;
        bottom: 4px;
        background: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    input:checked + .slider {
        background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
        box-shadow: 0 2px 8px rgba(102, 187, 106, 0.3);
    }
    
    input:checked + .slider:before {
        transform: translateX(40px);
    }
    
    .slider:active:before {
        width: 36px;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }
    
    .status-badge.open {
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        color: #1b5e20;
        box-shadow: 0 4px 15px rgba(150, 230, 161, 0.4);
    }
    
    .status-badge.closed {
        background: linear-gradient(135deg, #fbc2eb 0%, #fa709a 100%);
        color: #b71c1c;
        box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 8px currentColor;
    }
    
    .btn-custom {
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .btn-custom::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-custom:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-custom span {
        position: relative;
        z-index: 1;
    }
    
    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    .btn-gradient-secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
    }
    
    .btn-gradient-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(240, 147, 251, 0.5);
        color: white;
    }
    
    .btn-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
    }
    
    .btn-gradient-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.5);
        color: white;
    }
    
    .btn-gradient-warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
    }
    
    .btn-gradient-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(250, 112, 154, 0.5);
        color: white;
    }
    
    .registration-control-card {
        grid-column: 1 / -1;
    }
    
    .registration-control-content {
        text-align: center;
        padding: 1rem 0;
    }
    
    .toggle-label {
        display: block;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
</style>

<div class="admin-panel-container">
    <div class="page-header">
        <h1>ISF Management Panel</h1>
        <p>Control and customize ISF website settings</p>
    </div>
    
    <!-- SYSTEM CONTROLS SECTION -->
    <div class="section-title">System Controls</div>
    <div class="management-grid">
        <!-- REGISTRATION CONTROL (Full Width) -->
        <div class="admin-card registration-control-card">
            <div class="card-header-custom" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><span class="icon">🎯</span> Web Marathon Registration Control</h3>
            </div>
            <div class="card-body-custom">
                <div class="registration-control-content">
                    <div class="status-badge <?= ($status === 'open') ? 'open' : 'closed' ?>">
                        <span class="status-indicator"></span>
                        <span>Status: <?= strtoupper($status) ?></span>
                    </div>
                    
                    <form method="POST" style="display: inline-block;" id="toggleForm">
                        <input type="hidden" name="registration_toggle" id="toggleValue" value="<?= ($status === 'open') ? 'off' : 'on' ?>">
                        <label class="switch">
                            <input type="checkbox"
                                   id="toggleCheckbox"
                                   onchange="toggleRegistration()"
                                   <?= ($status === 'open') ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-label">Toggle to <?= ($status === 'open') ? 'close' : 'open' ?> registration</span>
                    </form>
                    
                    <script>
                    function toggleRegistration() {
                        const checkbox = document.getElementById('toggleCheckbox');
                        const hiddenInput = document.getElementById('toggleValue');
                        const form = document.getElementById('toggleForm');
                        
                        // Set the value based on checkbox state
                        hiddenInput.value = checkbox.checked ? 'on' : 'off';
                        
                        // Submit the form
                        form.submit();
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CONTENT MANAGEMENT SECTION -->
    <div class="section-title">Content Management</div>
    <div class="management-grid">
        <!-- SLIDE MANAGEMENT -->
        <div class="admin-card">
            <div class="card-header-custom" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><span class="icon">🖼️</span> Slide Management</h3>
            </div>
            <div class="card-body-custom">
                <p>Manage homepage slider images and customize the visual presentation of your website's main carousel.</p>
                <a href="manage-slides.php" class="btn-custom btn-gradient-primary">
                    <span>Manage Slides</span>
                    <span>→</span>
                </a>
            </div>
        </div>
        
        <!-- BODY BANNER MANAGEMENT -->
        <div class="admin-card">
            <div class="card-header-custom" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3><span class="icon">🎨</span> Body Banner Management</h3>
            </div>
            <div class="card-body-custom">
                <p>Update homepage banner text, images, and promotional content to keep your site fresh and engaging.</p>
                <a href="manage-banner.php" class="btn-custom btn-gradient-secondary">
                    <span>Manage Banner</span>
                    <span>→</span>
                </a>
            </div>
        </div>
        
        <!-- ABOUT US MANAGEMENT -->
        <div class="admin-card">
            <div class="card-header-custom" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3><span class="icon">📝</span> About ISF</h3>
            </div>
            <div class="card-body-custom">
                <p>Edit About Us content, company information, mission statements, and team details to reflect your brand.</p>
                <a href="manage-about.php" class="btn-custom btn-gradient-success">
                    <span>Manage About Us</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </div>
</div>
