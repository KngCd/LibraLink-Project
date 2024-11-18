<?php
// update_status.php
session_start();
require_once '../db_config.php';

date_default_timezone_set('Asia/Manila');

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
        // Count the number of active borrows for this book
        $stmt = $conn->prepare("SELECT COUNT(*) as active_borrow_count FROM borrow_table WHERE book_id = ? AND status = 'Active'");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count_row = $result->fetch_assoc();
        $active_borrow_count = $count_row['active_borrow_count'];

        // Get the current available stock for this book
        $stmt2 = $conn->prepare("SELECT stocks FROM inventory_table WHERE book_id = ?");
        $stmt2->bind_param("i", $book_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stock_row = $result2->fetch_assoc();

        if (!$stock_row) {
            echo "ERROR: Book stock not found.";
            exit();
        }

        $total_stock = $stock_row['stocks'];
        $available_stock = $total_stock - $active_borrow_count;

        // Check if the available stock is less than or equal to the active borrow count
        if ($available_stock <= 0) {
            $_SESSION['alert2'] = "ERROR: No available stock for this book. Cannot mark it as 'Active'.";
            header("Location: borrowed.php");
            exit();
        }

        // Count the number of active books for this student
        $stmt3 = $conn->prepare("SELECT COUNT(*) as active_count FROM borrow_table WHERE student_id = ? AND book_id = ? AND status = 'Active'");
        $stmt3->bind_param("ii", $student_id, $book_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $count_row3 = $result3->fetch_assoc();
        $active_count = $count_row3['active_count'];

        // If the student already has the book marked as active, prevent the status change
        if ($active_count > 0) {
            $_SESSION['alert2'] = "ERROR: You already have this book marked as 'Active'. Cannot mark it as 'Active' again.";
            header("Location: borrowed.php");
            exit();
        }

        // Check if the student already has 5 active books in total
        $stmt4 = $conn->prepare("SELECT COUNT(*) as active_book_count FROM borrow_table WHERE student_id = ? AND status = 'Active'");
        $stmt4->bind_param("i", $student_id);
        $stmt4->execute();
        $result4 = $stmt4->get_result();
        $count_row4 = $result4->fetch_assoc();
        $active_book_count = $count_row4['active_book_count'];

        // If the student already has 5 active books, prevent the status change
        if ($active_book_count >= 5) {
            $_SESSION['alert2'] = "ERROR: The student cannot have more than 5 active books.";
            header("Location: borrowed.php");
            exit();
        }

        // Prevent changing status from Returned to Active if the limit is reached
        if ($current_status == 'Returned' && $active_book_count >= 5) {
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

            // Get the current timestamp for returned date
            $returned_date = date('Y-m-d H:i:s');

            // Update the borrow_table with the returned date
            $update_date_stmt = $conn->prepare("UPDATE borrow_table SET returned_date = ? WHERE borrow_id = ?");
            $update_date_stmt->bind_param("si", $returned_date, $borrow_id);
            $update_date_stmt->execute();
            $update_date_stmt->close();

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