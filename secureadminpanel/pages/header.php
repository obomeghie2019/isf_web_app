<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>ISF Admin Panel</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS -->
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
}

/* Sidebar */
#sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #1f2937;
    color: #fff;
    transition: all 0.3s;
    overflow-y: auto;
    z-index: 1000;
    padding-top: 1rem;
}
#sidebar.collapsed {
    width: 70px;
}
#sidebar .sidebar-header {
    padding: 1.2rem 1rem;
    font-size: 1.3rem;
    font-weight: 700;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
#sidebar ul.components {
    padding: 0;
    list-style: none;
}
#sidebar ul li {
    padding: 1rem 1.2rem;
}
#sidebar ul li a {
    display: flex;
    align-items: center;
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}
#sidebar ul li a i {
    margin-right: 1rem;
    min-width: 25px;
    font-size: 1.2rem;
}
#sidebar ul li a:hover, #sidebar ul li a.active {
    background-color: #0d6efd;
    color: #fff;
}

/* Hide text when collapsed */
#sidebar.collapsed ul li a span {
    display: none;
}

/* Sidebar scroll */
#sidebar::-webkit-scrollbar {
    width: 6px;
}
#sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,0.2);
    border-radius: 10px;
}

/* Top Navbar */
#content {
    margin-left: 250px;
    transition: all 0.3s;
}
#content.full-width {
    margin-left: 70px;
}
.navbar-custom {
    background-color: #111827;
    color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.navbar-custom .navbar-brand, 
.navbar-custom .nav-link {
    color: #fff;
}
.navbar-custom .nav-link:hover {
    color: #0d6efd;
}

/* Toggle Button */
#sidebarCollapse {
    background-color: #0d6efd;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    border-radius: 0.4rem;
    padding: 0.45rem 0.6rem;
}
#sidebarCollapse:hover {
    background-color: #0b5ed7;
}

/* Cards */
.card {
    border-radius: 0.75rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.card .card-header {
    font-weight: 600;
    font-size: 1.1rem;
}
.card .card-body {
    font-size: 1.05rem;
}

/* Action Buttons */
.btn-custom {
    font-size: 0.95rem;
    padding: 0.55rem 0.9rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}
.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 992px) {
    #sidebar {
        left: -250px;
    }
    #sidebar.active {
        left: 0;
    }
    #content {
        margin-left: 0;
    }
}
</style>
</head>
<body>

<div id="sidebar">
    <div class="sidebar-header">
        ISF Admin
    </div>
    <ul class="components">
        <li><a href="dashboard.php" class=""><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        
        <li><a href="payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
        <li><a href="registration-control.php"><i class="fas fa-globe"></i> <span>Web App Settings</span></a></li>
          <li><a href="mobile-app-control.php"><i class="fas fa-mobile"></i> <span>Mobile App Settings</span></a></li>
          <li><a href="mobile_slides.php"><i class="fas fa-mobile"></i><span>🖼️ Mobile App Slides</span></a></li>
          <li><a href="football_highlights.php"><i class="fas fa-mobile"></i><span>▶️ISF Highlights</span></a></li>
        <li><a href="passwordChange.php"><i class="fas fa-key"></i> <span>Change Admin Password</span></a></li>
        <li><a href="log-out.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
</div>

<div id="content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom shadow-sm px-3">
        <button type="button" id="sidebarCollapse" class="btn">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand ms-2" href="#">ISF Admin Panel</a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="../assets/img/avatar/avatar-1.png" class="rounded-circle me-2" width="40" height="40"> Hi, Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="passwordChange.php"><i class="fas fa-key me-2"></i> Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="log-out.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    // Toggle sidebar
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('collapsed');
        $('#content').toggleClass('full-width');
        if ($(window).width() < 992) {
            $('#sidebar').toggleClass('active');
        }
    });
});
</script>
