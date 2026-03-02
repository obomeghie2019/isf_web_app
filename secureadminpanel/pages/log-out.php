<?php
require_once 'includes/config.php'; // Needed if session is started here
require_once 'includes/auth.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Call the logout function
logout();

// Redirect to login page
header("Location: index.php");
exit;
