<?php
session_start();
require_once '../db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $current_due_date = $_POST['current_due_date'];

    function calculateNewDueDate($currentDueDate, $daysToAdd) {
        $newDueDate = $currentDueDate;
        $dayCount = 0;

        while ($dayCount < $daysToAdd) {
            $newDueDate = date("Y-m-d", strtotime("+1 day", strtotime($newDueDate)));
            if (date("w", strtotime($newDueDate)) != 0) {
                $dayCount++;
            }
        }

        return $newDueDate;
    }

    $new_due_date = calculateNewDueDate($current_due_date, 7);

    // Update the due date and set is_renewed to true
    $stmt = $conn->prepare("UPDATE borrow_table SET due_date = ?, is_renewed = 1, penalty = 0.00 WHERE borrow_id = ?");
    $stmt->bind_param("si", $new_due_date, $borrow_id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = ['message' => 'Due date successfully renewed!', 'type' => 'success'];
    } else {
        $_SESSION['alert'] = ['message' => 'Error renewing due date: ' . $stmt->error, 'type' => 'danger'];
    }

    $stmt->close();
    mysqli_close($conn);
    header("Location: borrowed.php");
    exit();
}
?>
