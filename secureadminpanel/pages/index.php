
<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}


$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        if (login($email, $password)) {
            $success = true;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISF Admin - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php if ($success): ?>
        <meta http-equiv="refresh" content="5;URL=dashboard.php">
    <?php endif; ?>
    <style>
        /* Modern CSS with Purple/Blue Gradient */
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --success: #2ecc71;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --light: #f8f9fa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
        }
        
        .login-header {
            background: var(--dark);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }
        
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            border: 20px solid transparent;
            border-top-color: var(--dark);
        }
        
        .login-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .brand-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .brand-icon i {
            font-size: 40px;
            color: var(--primary);
        }
        
        .brand-title {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 10px;
        }
        
        .brand-subtitle {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        .login-body {
            padding: 40px 30px 30px;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            border-left: 4px solid var(--danger);
        }
        
        .alert-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.2);
            outline: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--dark);
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        }
        
        .btn-login:hover {
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            box-shadow: 0 6px 20px rgba(106, 17, 203, 0.4);
            transform: translateY(-2px);
        }
        
        .auth-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 15px;
        }
        
        .auth-links a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .auth-links a:hover {
            color: var(--dark);
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                border-radius: 10px;
            }
            
            .login-header {
                padding: 25px 15px;
            }
            
            .login-body {
                padding: 30px 20px 25px;
            }
        }

        .loading-message {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: var(--success);
        }

        .spinner {
            margin: 20px auto;
            border: 6px solid #f3f3f3;
            border-top: 6px solid var(--primary);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-brand">
                <div class="brand-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="brand-title">ISF Administrator Login</h1>
                <div class="brand-subtitle">...passion for sporting</div>
            </div>
        </div>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'password_changed'): ?>
    <div class="loading-message" >
        Password changed successfully. Please login again.
    </div>
<?php endif; ?>
        <div class="login-body">
            <?php if ($success): ?>
                <div class="loading-message">
                    <p>Login successful! Redirecting to your dashboard in <strong>5 seconds</strong>...</p>
                    <div class="spinner"></div>
                </div>
            <?php else: ?>
                <form method="POST" class="login-form">
                    <?php if ($error): ?>
                        <div class="alert-danger">
                            <i class="fas fa-exclamation-circle alert-icon"></i>
                            <span><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" 
                               class="form-input" placeholder="Email address" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-input" 
                               placeholder="Password" required>
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    
                    <div class="form-footer">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                    
                    <div class="auth-links">
                        Don't have an account? <a href="register.php">Register here</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        document.querySelector('.login-form')?.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert-danger';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span>Please fill in all fields</span>
                `;
                document.querySelector('.login-form').prepend(alertDiv);
            }
        });
    </script>
</body>
</html>
