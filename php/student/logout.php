<?php
// logout.php
session_start();

require_once '../db_config.php';

// Prepare variables for logging out
$studentId = $_SESSION['user_id'];
$action = 'Logout';
$details = 'You logged out';

// Insert log activity directly with NOW() for the timestamp
$stmt = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $studentId, $action, $details); // Use variables here
$stmt->execute();
header('Location: student-login.php'); // Redirect to the login page
exit();
?>