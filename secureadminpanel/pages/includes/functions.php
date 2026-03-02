<?php
/**
 * Core functions for Store Layout Designer application
 */

/**
 * Database connection helper
 */
function db_connect() {
    static $conn;
    
    if (!isset($conn)) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Secure redirect helper
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit;
    }
    echo '<script>window.location.href="' . $url . '";</script>';
    exit;
}

/**
 * CSRF token generation and validation
 */
function generate_csrf_token() {
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || time() > $_SESSION[CSRF_TOKEN_NAME . '_expire']) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_expire'] = time() + CSRF_EXPIRE;
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validate_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && 
           hash_equals($_SESSION[CSRF_TOKEN_NAME], $token) &&
           time() < $_SESSION[CSRF_TOKEN_NAME . '_expire'];
}

/**
 * Authentication helpers
 */
function is_authenticated() {
    return isset($_SESSION['user_id']);
}

function require_auth() {
    if (!is_authenticated()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
}

function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Input sanitization
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * File upload handling
 */
function handle_file_upload($file, $directory, $allowed_types = ALLOWED_FILE_TYPES) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error: " . $file['error']);
    }
    
    // Validate file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    
    if (!in_array($mime, $allowed_types)) {
        throw new Exception("Invalid file type. Allowed types: " . implode(', ', $allowed_types));
    }
    
    // Validate file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new Exception("File too large. Maximum size: " . (MAX_UPLOAD_SIZE / 1024 / 1024) . "MB");
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = rtrim($directory, '/') . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Failed to move uploaded file");
    }
    
    return $filename;
}

/**
 * Design-specific helpers
 */
function create_design_thumbnail($design_id, $width = 300, $height = 200) {
    // This would be implemented using your canvas rendering logic
    // For now returns a placeholder path
    return 'assets/images/design-thumbnail-placeholder.png';
}

function validate_design_data($data) {
    if (!isset($data['width'], $data['height'], $data['elements'])) {
        return false;
    }
    
    $data['width'] = (int)$data['width'];
    $data['height'] = (int)$data['height'];
    
    if ($data['width'] <= 0 || $data['width'] > MAX_CANVAS_WIDTH ||
        $data['height'] <= 0 || $data['height'] > MAX_CANVAS_HEIGHT) {
        return false;
    }
    
    if (!is_array($data['elements'])) {
        return false;
    }
    
    // Basic element validation
    $valid_types = ['wall', 'shelf', 'rack', 'counter', 'display', 'door', 'window'];
    foreach ($data['elements'] as $element) {
        if (!isset($element['type'], $element['x'], $element['y'], $element['width'], $element['height']) ||
            !in_array($element['type'], $valid_types)) {
            return false;
        }
    }
    
    return true;
}

/**
 * Template rendering function
 */
function render_template($template, $data = []) {
    extract($data);
    ob_start();
    include APP_ROOT . "/templates/$template.php";
    return ob_get_clean();
}

/**
 * Error handling
 */
function handle_error($message, $code = 500) {
    http_response_code($code);
    
    if (APP_DEBUG) {
        echo "<h1>Error $code</h1>";
        echo "<p>$message</p>";
        debug_print_backtrace();
    } else {
        error_log("Application Error [$code]: $message");
        echo "<h1>An error occurred</h1>";
        echo "<p>Please try again later.</p>";
    }
    
    exit;
}

/**
 * JSON response helper
 */
function json_response($data, $status_code = 200) {
    header('Content-Type: application/json');
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

/**
 * Password hashing wrapper
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_ALGO, PASSWORD_OPTIONS);
}

/**
 * URL generation
 */
function url($path = '') {
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Asset path helper
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Debugging helper
 */
function dd($data) {
    if (APP_DEBUG) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}