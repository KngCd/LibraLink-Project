<?php
// Database connection
// $conn = new mysqli('localhost', 'root', '', 'libralink3');
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $inputData = trim(file_get_contents("php://input"));

    // Parse the URL-encoded string to retrieve JSON data
    parse_str($inputData, $output);
    $student_info_json = $output['student_id'] ?? '';

    // Decode the JSON data
    $student_info = json_decode($student_info_json, true);

    // Check if the JSON is valid
    if (json_last_error() === JSON_ERROR_NONE && isset($student_info['student_id'])) {
        $student_id = $student_info['student_id'];

        // Insert log entry with the current timestamp and retrieve it immediately
        $stmt = $conn->prepare("INSERT INTO log_table (student_id, log_datetime) VALUES (?, NOW())");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        
        // Retrieve the exact timestamp from the inserted log entry
        $log_id = $conn->insert_id; // Get the ID of the newly inserted log
        $log_stmt = $conn->prepare("SELECT log_datetime FROM log_table WHERE log_id = ?");
        $log_stmt->bind_param("i", $log_id);
        $log_stmt->execute();
        $log_result = $log_stmt->get_result();
        $log_row = $log_result->fetch_assoc();
        $log_datetime = $log_row['log_datetime'];

        // Format the timestamp into separate date and time
        $scanDate = date("Y-m-d", strtotime($log_datetime));
        $scanTime = date("H:i:s", strtotime($log_datetime));

        // Fetch the student's profile picture from the database
        $profileStmt = $conn->prepare("SELECT profile_pic FROM student_table WHERE student_id = ?");
        $profileStmt->bind_param("i", $student_id);
        $profileStmt->execute();
        $profileResult = $profileStmt->get_result();
        $profileRow = $profileResult->fetch_assoc();
        $profilePic = !empty($profileRow['profile_pic']) ? base64_encode($profileRow['profile_pic']) : null;

        // Display student details with Bootstrap styling
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Student Details</title>
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body style="background-color: #e3e3e3;">
            <div class="container mt-5">
                <div class="card p-4">
                    <h4 class="text-center mb-4">Student Details</h4>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="border p-3" style="height: 200px; width: 200px; display: flex; justify-content: center; align-items: center; overflow: hidden; margin: 0 auto;">
                                ' . ($profilePic ? '<img src="data:image/jpeg;base64,' . $profilePic . '" alt="Profile Picture" style="height: 100%; width: 100%; object-fit: cover;">' : '<p>Student Photo</p>') . '
                            </div>
                            <h5 class="mt-3">' . htmlspecialchars($student_info['name']) . '</h5>
                        </div>
                        <div class="col-md-8">
                            <h5>Personal Information</h5>
                            <p><strong>Full Name:</strong> ' . htmlspecialchars($student_info['name']) . '</p>
                            <p><strong>Email:</strong> ' . htmlspecialchars($student_info['email']) . '</p>
                            <p><strong>Contact:</strong> ' . htmlspecialchars($student_info['contact']) . '</p>
                            <p><strong>Program:</strong> ' . htmlspecialchars($student_info['program']) . '</p>
                            <p><strong>Department:</strong> ' . htmlspecialchars($student_info['department']) . '</p>
                            <hr>
                            <h5>Access Information</h5>
                            <p><strong>Date:</strong> ' . $scanDate . '</p>
                            <p><strong>Time:</strong> ' . $scanTime . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';
    } else {
        // Debugging output to see what data was received
        echo "Invalid JSON data after decoding: " . htmlspecialchars($student_info_json);
    }
}

$conn->close();
?>
