<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        #preview {
            width: 300px;
            height: 300px;
            margin: 20px auto;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>QR Code Tracking</h2>

    <video id="preview"></video>

    <form id="attendanceForm" action="process_logs.php" method="POST">
        <input type="hidden" name="student_id" id="student_id">
    </form>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            document.getElementById('student_id').value = content;  // Assuming the QR contains the student_id
            document.getElementById('attendanceForm').submit();
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>
</body>
</html>