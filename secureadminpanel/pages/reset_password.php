<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($token) {
        $error = 'Invalid reset token';
    } elseif (empty($password) {
        $error = 'Please enter a new password';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Verify token
        $conn = db_connect();
        $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $user = User::findByEmail($row['email']);
            
            if ($user) {
                $user->setPassword($password);
                if ($user->save()) {
                    // Delete used token
                    $conn->query("DELETE FROM password_resets WHERE token = '$token'");
                    $success = 'Password updated successfully. You can now <a href="login.php">login</a> with your new password.';
                } else {
                    $error = 'Failed to update password';
                }
            } else {
                $error = 'User not found';
            }
        } else {
            $error = 'Invalid or expired reset token';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="auth-container">
    <h1>Reset Password</h1>
    
    <?php if ($error): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-message"><?= $success ?></div>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Reset Password</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>