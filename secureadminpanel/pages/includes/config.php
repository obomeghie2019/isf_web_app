<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'globaln2_glix');
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Application Configuration
define('APP_NAME', 'ISF Admin Panel');
define('APP_VERSION', '3.0.7');
define('APP_ROOT', dirname(__DIR__));
define('APP_URL', 'http://localhost/isf2025/securedadminpanel/pages'); // Change to your domain
define('APP_DEBUG', true); // Set to false in production

// File Upload Configuration
define('UPLOAD_DIR', APP_ROOT . '/uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Session Configuration
define('SESSION_NAME', 'STORE_DESIGNER_SESS');
define('SESSION_LIFETIME', 86400); // 24 hours in seconds
define('SESSION_PATH', '/');
define('SESSION_SECURE', false); // Set to true if using HTTPS
define('SESSION_HTTPONLY', true);

// Security Configuration
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_EXPIRE', 3600); // 1 hour in seconds
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_OPTIONS', ['cost' => 12]);

// Email Configuration (for password reset, notifications)
define('MAIL_FROM', 'noreply@example.com');
define('MAIL_FROM_NAME', APP_NAME);
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_USERNAME', 'user@example.com');
define('MAIL_PASSWORD', 'email_password');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');

// Design Configuration
define('DEFAULT_CANVAS_WIDTH', 1000);
define('DEFAULT_CANVAS_HEIGHT', 600);
define('MAX_CANVAS_WIDTH', 3000);
define('MAX_CANVAS_HEIGHT', 2000);
define('GRID_SIZE', 20); // Pixels between grid lines

// Error Reporting (should be disabled in production)
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone Configuration
date_default_timezone_set('UTC');

// Create required directories if they don't exist
$required_dirs = [
    UPLOAD_DIR,
    UPLOAD_DIR . '/designs',
    UPLOAD_DIR . '/thumbnails',
    UPLOAD_DIR . '/templates'
];

foreach ($required_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Autoload helper functions
require_once __DIR__ . '/functions.php';

// Start session with configured settings
session_name(SESSION_NAME);
session_set_cookie_params(
    SESSION_LIFETIME,
    SESSION_PATH,
    $_SERVER['HTTP_HOST'] ?? '',
    SESSION_SECURE,
    SESSION_HTTPONLY
);

session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    $_SESSION[CSRF_TOKEN_NAME . '_expire'] = time() + CSRF_EXPIRE;
}
?>