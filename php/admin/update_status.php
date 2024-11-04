<?php
// update_status.php
session_start();
require_once '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $status = $_POST['status'];

    // Get the student ID and current status from the borrow ID
    $stmt = $conn->prepare("SELECT student_id, status FROM borrow_table WHERE borrow_id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        echo "Error: Borrow ID not found.";
        exit();
    }

    $student_id = $row['student_id'];
    $current_status = $row['status'];

    // Check if the status is being changed to 'Active'
    if ($status == 'Active') {
        // Count the number of active books for this student
        $stmt = $conn->prepare("SELECT COUNT(*) as active_count FROM borrow_table WHERE student_id = ? AND status = 'Active'");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count_row = $result->fetch_assoc();
        
        $active_count = $count_row['active_count'];

        // Check if the student already has 5 active books
        if ($active_count >= 5) {
            $_SESSION['alert2'] = "Error: The student cannot have more than 5 active books.";
            header("Location: borrowed.php");
            exit();
        }

        // Prevent changing status from Returned to Active if the limit is reached
        if ($current_status == 'Returned' && $active_count >= 5) {
            $_SESSION['alert2'] = "Error: Cannot change status from Returned to Active when the student has 5 active books.";
            header("Location: borrowed.php");
            exit();
        }
    }

    // Update status in the database
    $sql = "UPDATE borrow_table SET status = ? WHERE borrow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $borrow_id);
    
    if ($stmt->execute()) {
        $_SESSION['alert2'] = "Status Updated Successfully!";
    } else {
        $_SESSION['alert2'] = 'Error updating status: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the previous page
    header("Location: borrowed.php");
    exit();
}
?>