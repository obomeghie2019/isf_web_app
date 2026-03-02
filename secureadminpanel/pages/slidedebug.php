<?php
// ── TEMPORARY DEBUG PAGE ── Upload this, submit the form once, read the output, then delete it.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/auth.php';

$isf_root   = dirname(dirname(__DIR__));
$upload_dir = $isf_root . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;

echo "<pre style='font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px;'>";
echo "=== DIRECTORY ===\n";
echo "__FILE__       : " . __FILE__ . "\n";
echo "__DIR__        : " . __DIR__ . "\n";
echo "dirname x1     : " . dirname(__DIR__) . "\n";
echo "dirname x2     : " . dirname(dirname(__DIR__)) . "\n";
echo "upload_dir     : " . $upload_dir . "\n";
echo "dir exists     : " . (is_dir($upload_dir) ? 'YES' : 'NO') . "\n";
echo "dir writable   : " . (is_writable($upload_dir) ? 'YES' : 'NO - THIS IS THE PROBLEM') . "\n";

echo "\n=== PHP UPLOAD LIMITS ===\n";
echo "upload_max_filesize : " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size       : " . ini_get('post_max_size') . "\n";
echo "file_uploads        : " . ini_get('file_uploads') . "\n";
echo "session.save_path   : " . ini_get('session.save_path') . "\n";

echo "\n=== SESSION TEST ===\n";
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION['test'] = 'hello';
echo "Session write : " . ($_SESSION['test'] === 'hello' ? 'OK' : 'FAILED') . "\n";
echo "Session ID    : " . session_id() . "\n";

echo "\n=== POST DATA (if form submitted) ===\n";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST keys : " . implode(', ', array_keys($_POST)) . "\n";
    echo "FILES     : ";
    if (!empty($_FILES['image'])) {
        echo "name=" . $_FILES['image']['name']
           . " size=" . $_FILES['image']['size']
           . " error=" . $_FILES['image']['error']
           . " tmp=" . $_FILES['image']['tmp_name'] . "\n";
        $errors = [0=>'OK',1=>'INI size',2=>'Form size',3=>'Partial',4=>'No file',6=>'No tmp',7=>'No write',8=>'Extension'];
        echo "error meaning: " . ($errors[$_FILES['image']['error']] ?? 'unknown') . "\n";
    } else {
        echo "NO FILE RECEIVED\n";
    }

    // Try actual upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $dest = $upload_dir . 'debug_test_' . time() . '.jpg';
        $result = move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        echo "move_uploaded_file: " . ($result ? 'SUCCESS — file saved to '.$dest : 'FAILED') . "\n";
        if ($result) unlink($dest); // clean up test file
    }
} else {
    echo "No POST yet — submit the form below\n";
}
echo "</pre>";
?>

<form method="POST" enctype="multipart/form-data" style="margin:20px;font-family:sans-serif;">
    <p><strong>Debug upload test:</strong></p>
    <input type="file" name="image" accept="image/*"><br><br>
    <button type="submit" style="padding:10px 20px;background:#667eea;color:white;border:none;border-radius:8px;cursor:pointer;font-size:16px;">
        Test Upload
    </button>
</form>