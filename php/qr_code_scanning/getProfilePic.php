<?php
require_once '../db_config.php';

header('Content-Type: application/json');

if (isset($_GET['student_id'])) {
    $studentId = $conn->real_escape_string($_GET['student_id']);
    $sql = "SELECT profile_pic FROM student_table WHERE student_id = '$studentId'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(['error' => 'Database query error.']);
        exit;
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Convert the BLOB to a base64 string
        $base64 = base64_encode($row['profile_pic']);
        // Set the correct MIME type based on your image format
        $mimeType = 'image/jpeg'; // Change if necessary (e.g., image/png)
        echo json_encode(['profile_pic' => 'data:' . $mimeType . ';base64,' . $base64]);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
} else {
    echo json_encode(['error' => 'No student ID provided']);
}

$conn->close();
?>
