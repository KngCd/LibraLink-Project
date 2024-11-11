<?php
// update_status.php
session_start();
require_once '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $status = $_POST['status'];

    // Get the student ID and current status from the borrow ID
    $stmt = $conn->prepare("SELECT student_id, status, book_id FROM borrow_table WHERE borrow_id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        echo "ERROR: Borrow ID not found.";
        exit();
    }

    $student_id = $row['student_id'];
    $current_status = $row['status'];
    $book_id = $row['book_id'];

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
            $_SESSION['alert2'] = "ERROR: The student cannot have more than 5 active books.";
            header("Location: borrowed.php");
            exit();
        }

        // Prevent changing status from Returned to Active if the limit is reached
        if ($current_status == 'Returned' && $active_count >= 5) {
            $_SESSION['alert2'] = "ERROR: Cannot change status from Returned to Active when the student has 5 active books.";
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
        // If the status is changed to 'Returned', log the action
        if ($status == 'Returned') {
            // Get the book title
            $bookStmt = $conn->prepare("SELECT title FROM book_table WHERE book_id = ?");
            $bookStmt->bind_param("i", $book_id);
            $bookStmt->execute();
            $bookStmt->bind_result($book_title);
            $bookStmt->fetch();
            $bookStmt->close();

            // Log the return action with the book title
            $action = 'Return';
            $details = "You returned $book_title";
            
            $logStmt = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
            $logStmt->bind_param("iss", $student_id, $action, $details);
            $logStmt->execute();
            $logStmt->close();

            // Reset the penalty to 0.00 when the book is returned
            $reset_penalty_stmt = $conn->prepare("UPDATE borrow_table SET penalty = 0.00 WHERE borrow_id = ?");
            $reset_penalty_stmt->bind_param("i", $borrow_id);
            $reset_penalty_stmt->execute();
            $reset_penalty_stmt->close();
        }
    } else {
        $_SESSION['alert2'] = 'ERROR updating status: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the previous page
    header("Location: borrowed.php");
    exit();
}
?>