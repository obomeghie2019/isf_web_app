<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$msg = "";

/* Fetch current user securely */
$stmtUser = $conn->prepare("SELECT id, email, password, name FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$user) {if (isset($_POST['submit'])) {

    $opassword = $_POST['opassword'] ?? '';
    $npassword = $_POST['password'] ?? '';
    $cpassword = $_POST['cpassword'] ?? '';

    if (empty($opassword) || empty($npassword) || empty($cpassword)) {
        $msg = "Please fill in all fields!";
    } 
    elseif (!password_verify($opassword, $user['password'])) {
        $msg = "Old password is incorrect!";
    } 
    elseif ($npassword !== $cpassword) {
        $msg = "New passwords do not match!";
    } 
    else {

        // Hash new password
        $newHash = password_hash($npassword, PASSWORD_DEFAULT);

        // Update password
        $stmtUpdate = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmtUpdate->execute([$newHash, $user['id']]);

        // ✅ Destroy session
        session_unset();
        session_destroy();

        // ✅ Redirect to login page with success message
        header("Location: index.php?msg=password_changed");
        exit;
    }
}

    $msg = "User not found!";
}

if (isset($_POST['submit'])) {

    $opassword = $_POST['opassword'] ?? '';
    $npassword = $_POST['password'] ?? '';
    $cpassword = $_POST['cpassword'] ?? '';

    if (empty($opassword) || empty($npassword) || empty($cpassword)) {
        $msg = "Please fill in all fields!";
    } 
    elseif (!password_verify($opassword, $user['password'])) {
        $msg = "Old password is incorrect!";
    } 
    elseif ($npassword !== $cpassword) {
        $msg = "New passwords do not match!";
    } 
    else {

        // Hash new password
        $newHash = password_hash($npassword, PASSWORD_DEFAULT);

        // Update password
        $stmtUpdate = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmtUpdate->execute([$newHash, $user['id']]);

        // ✅ Destroy session
        session_unset();
        session_destroy();

        // ✅ Redirect to login page with success message
        header("Location: index.php?msg=password_changed");
        exit;
    }
}


include 'header.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <p></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <?php if ($msg != ""): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($msg) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-center bg-gradient-primary text-white rounded-top-4">
                        <h4 class="mb-0"><i class="fas fa-user-lock me-2"></i>Update Password</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" class="needs-validation" novalidate>
                            <!-- Old Password -->
                            <div class="mb-3 position-relative">
                                <label for="opassword" class="form-label">Old Password</label>
                                <input type="password" class="form-control form-control-lg" id="opassword" name="opassword" placeholder="Enter old password" required>
                                <i class="far fa-eye toggle-password" data-target="opassword"></i>
                                <div class="invalid-feedback">Old password is required.</div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter new password" required>
                                <i class="far fa-eye toggle-password" data-target="password"></i>
                                <div class="invalid-feedback">New password is required.</div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-4 position-relative">
                                <label for="cpassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control form-control-lg" id="cpassword" name="cpassword" placeholder="Confirm new password" required>
                                <i class="far fa-eye toggle-password" data-target="cpassword"></i>
                                <div class="invalid-feedback" id="matchError">Passwords must match.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="submit" class="btn btn-lg btn-gradient-success">
                                    <i class="fas fa-save me-2"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- Bootstrap 5 form validation + password match check -->
<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;
            if (password !== cpassword) {
                event.preventDefault();
                event.stopPropagation();
                document.getElementById('matchError').style.display = 'block';
            } else {
                document.getElementById('matchError').style.display = 'none';
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Toggle show/hide password
    const toggleIcons = document.querySelectorAll('.toggle-password');
    toggleIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const target = document.getElementById(icon.dataset.target);
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
})();
</script>

<style>
/* Gradient button */
.btn-gradient-success {
    background: linear-gradient(90deg, #28a745, #20c997);
    color: #fff;
    border: none;
    transition: 0.3s;
    font-weight: 500;
}

.btn-gradient-success:hover {
    background: linear-gradient(90deg, #20c997, #28a745);
    color: #fff;
}

/* Card hover effect */
.bg-gradient-primary {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
}

.card {
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

/* Password toggle icon */
.position-relative .toggle-password {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
}
</style>
