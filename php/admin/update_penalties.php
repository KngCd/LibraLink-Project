<?php
require_once '../db_config.php'; // Include the database connection file

// Get today's date
$today = date("Y-m-d");

// Prepare a statement to fetch borrow records
$stmt = $conn->prepare("SELECT borrow_id, due_date, penalty FROM borrow_table WHERE status = 'Active'");
$stmt->execute();
$result = $stmt->get_result();

// Prepare a statement for updating penalties
$update_stmt = $conn->prepare("UPDATE borrow_table SET penalty = ? WHERE borrow_id = ?");

$penalty_per_day = 10.00; // Set penalty per overdue day

while ($row = $result->fetch_assoc()) {
    $borrow_id = $row['borrow_id'];
    $due_date = $row['due_date'];
    $current_penalty = (float)$row['penalty']; // Cast penalty to float, default is 0.00

    // Check if the book is overdue
    if ($today > $due_date) {
        $date1 = new DateTime($due_date);
        $date2 = new DateTime($today);
        $interval = $date1->diff($date2);

        $days_overdue = $interval->days; // Get the number of overdue days
        $total_penalty = $current_penalty + ($days_overdue * $penalty_per_day); // Update the total penalty

        // Update the penalty in the borrow_table
        $update_stmt->bind_param("di", $total_penalty, $borrow_id);
        $update_stmt->execute();
    }
}

// Close the statements
$update_stmt->close();
$stmt->close();

// Close the database connection
mysqli_close($conn);
?>