<?php
require_once 'includes/config.php';

/* Optimized Dashboard Counts */
$totalStmt = $conn->prepare("SELECT COUNT(*) FROM atheletes");
$totalStmt->execute();
$totalu = $totalStmt->fetchColumn();

/* Example: Gender stats (optional performance boost) */
$maleStmt = $conn->prepare("SELECT COUNT(*) FROM atheletes WHERE gender = ?");
$maleStmt->execute(['Male']);
$totalMale = $maleStmt->fetchColumn();

$femaleStmt = $conn->prepare("SELECT COUNT(*) FROM atheletes WHERE gender = ?");
$femaleStmt->execute(['Female']);
$totalFemale = $femaleStmt->fetchColumn();
?>
