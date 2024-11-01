<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $targetDir = "../../img/admin_pic/"; 
    $targetFile = $targetDir . basename($_FILES['file']['name']);
    
    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        // Return a JSON response with the new image path
        echo json_encode(['success' => true, 'imagePath' => $targetFile]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
