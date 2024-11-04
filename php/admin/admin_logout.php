<?php
session_start();

// Unset all session variables
$_SESSION = array(); // Clear session array

// Optionally destroy the session
session_destroy();

// Redirect to the login page
header('Location: admin-login.php');
exit;
?>
