<?php
// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'libralink2';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

        // If the penalty is not set or if it needs to be updated (i.e., overdue days have increased)
        if ($current_penalty == 0.00 || $current_penalty / $penalty_per_day < $days_overdue) {
            // Calculate the new penalty based on the overdue days
            $new_penalty = $days_overdue * $penalty_per_day;

            // Update the penalty in the borrow_table
            $update_stmt->bind_param("di", $new_penalty, $borrow_id);
            $update_stmt->execute();
        }
    }
}

// Close the statements
$update_stmt->close();
$stmt->close();

// Close the database connection
$conn->close();
?>
    