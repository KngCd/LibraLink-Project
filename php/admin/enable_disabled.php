<?php
session_start();
require_once '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];  // Get student ID from the form
    $status = $_POST['status'];  // Get the new status ('Enable' or 'Disabled')

    // Get the student ID and current status from the student_table
    $stmt = $conn->prepare("SELECT student_id, status FROM student_table WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "Error: Student ID not found.";
        exit();
    }

    $current_status = $row['status'];

    // Check if the status is already the same, and if so, do not update
    if ($current_status === $status) {
        $_SESSION['alert3'] = "The status is already set to '$status'. No changes were made.";
        $stmt->close();
        $conn->close();
        header("Location: accepted_student.php");
        exit();
    }

    // Update the status in the student_table
    $stmt2 = $conn->prepare("UPDATE student_table SET status = ? WHERE student_id = ?");
    $stmt2->bind_param("si", $status, $student_id);
    
    if ($stmt2->execute()) {
        $_SESSION['alert3'] = "Student status updated to '$status' successfully!";
    } else {
        $_SESSION['alert3'] = "Error updating status: " . $stmt2->error;
    }

    $stmt2->close();
    $stmt->close();
    $conn->close();

    // Redirect back to the students list page
    header("Location: accepted_student.php");
    exit();
}
?>