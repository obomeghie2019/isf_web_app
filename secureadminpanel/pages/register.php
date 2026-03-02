<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);

                $success = 'Registration successful! Please login.';
                // Clear form
                $name = $email = '';
            }
        } catch (PDOException $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Layout Designer - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Consistent Purple/Blue Gradient Theme */
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
        
        .register-container {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        .register-container:hover {
            transform: translateY(-5px);
        }
        
        .register-header {
            background: var(--dark);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }
        
        .register-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            border: 20px solid transparent;
            border-top-color: var(--dark);
        }
        
        .register-brand {
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
        
        .register-body {
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
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            border-left: 4px solid var(--success);
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
        
        .password-strength {
            height: 5px;
            margin-top: 8px;
            border-radius: 3px;
            background: #f0f0f0;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s;
        }
        
        .password-rules {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            padding-left: 5px;
        }
        
        .password-rules ul {
            padding-left: 20px;
            margin-top: 5px;
        }
        
        .password-rules li {
            margin-bottom: 3px;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        
        .btn-register {
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
        
        .btn-register:hover {
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
            .register-container {
                border-radius: 10px;
            }
            
            .register-header {
                padding: 25px 15px;
            }
            
            .register-body {
                padding: 30px 20px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-brand">
                <div class="brand-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="brand-title">Create Account</h1>
                <div class="brand-subtitle">Design your perfect store layout</div>
            </div>
        </div>
        
        <div class="register-body">
            <?php if ($error): ?>
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="register-form">
                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" 
                           class="form-input" placeholder="Full name" required autofocus>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" 
                           class="form-input" placeholder="Email address" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Password" required>
                    <span class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                    <div class="password-rules">
                        Password must contain:
                        <ul>
                            <li>At least 8 characters</li>
                            <li>One uppercase letter</li>
                            <li>One lowercase letter</li>
                            <li>One number</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                           placeholder="Confirm password" required>
                    <span class="password-toggle" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
                
                <div class="auth-links">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Password visibility toggle
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        
        document.getElementById('toggleConfirmPassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('confirm_password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        // Password strength meter
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            let strength = 0;
            
            // Check password criteria
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            // Update meter
            strengthMeter.style.width = strength + '%';
            
            // Update color
            if (strength < 50) {
                strengthMeter.style.background = '#e74c3c';
            } else if (strength < 75) {
                strengthMeter.style.background = '#f39c12';
            } else {
                strengthMeter.style.background = '#2ecc71';
            }
        });

        // Form validation
        document.querySelector('.register-form')?.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            
            let errors = [];
            
            if (!name) errors.push('Name is required');
            if (!email) errors.push('Email is required');
            if (!password) errors.push('Password is required');
            if (password !== confirmPassword) errors.push('Passwords do not match');
            if (password.length < 8) errors.push('Password must be at least 8 characters');
            
            if (errors.length > 0) {
                e.preventDefault();
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert-danger';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span>${errors.join('<br>')}</span>
                `;
                document.querySelector('.register-form').prepend(alertDiv);
                
                // Scroll to error
                alertDiv.scrollIntoView({behavior: 'smooth'});
            }
        });
    </script>
</body>
</html>