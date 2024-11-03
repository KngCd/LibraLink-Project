<?php
// logout.php
session_start();
unset($_SESSION['user_id']); // Clear only user ID
// Optionally clear other user-specific session variables, but keep the cart
header('Location: student-login.php'); // Redirect to the login page
exit();
?>