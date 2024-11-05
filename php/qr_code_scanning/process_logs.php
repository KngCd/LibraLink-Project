<?php
// Set timezone to Philippines/Manila
date_default_timezone_set('Asia/Manila');

// Database connection
require_once '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);
    $student_id = $inputData['student_id'] ?? null;

    if ($student_id) {
        // Get the current date and time
        $current_date = date("Y-m-d");
        $current_time = date("H:i:s");

        // Check if there is already a log entry for the student today
        $check_sql = "SELECT * FROM log_table WHERE student_id = ? AND date = ? AND time_out IS NULL";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $student_id, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Entry exists for today without time_out, so update the time_out
            $update_sql = "UPDATE log_table SET time_out = ? WHERE student_id = ? AND date = ? AND time_out IS NULL";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sss", $current_time, $student_id, $current_date);
            if ($update_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Time-out logged successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to log time-out', 'error' => $conn->error]);
            }
            $update_stmt->close();
        } else {
            // No open entry for today, so insert a new row with time_in
            $insert_sql = "INSERT INTO log_table (student_id, date, time_in) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $student_id, $current_date, $current_time);
            if ($insert_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Time-in logged successfully']);
            } else {
                error_log("Insert Error: " . $insert_stmt->error);
                echo json_encode(['status' => 'error', 'message' => 'Failed to log time-in', 'error' => $insert_stmt->error]);
            }
            $insert_stmt->close();
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid student ID in request']);
    }
}

// Close the database connection
$conn->close();
?>  