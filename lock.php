<?php
session_start();  // Destroy all session
session_destroy();
header("Location: index.php");
?>