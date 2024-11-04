<?php
session_start();
require_once '../db_config.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Prepare variables for logging out
    $studentId = $_SESSION['user_id'];
    $action = 'Logout';
    $details = 'You logged out';

    // Insert log activity directly with NOW() for the timestamp
    $stmt = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $studentId, $action, $details);
    $stmt->execute();

    // Destroy the session and clear all session variables
    session_unset(); // Clear session variables
    session_destroy(); // Destroy the session
}

// Redirect to the login page
header('Location: student-login.php');
exit();
?>