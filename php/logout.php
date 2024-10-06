<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Send headers to prevent caching of the previous page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page
header("Location: ../html/NU_LoginPage.php");
exit();
?>
