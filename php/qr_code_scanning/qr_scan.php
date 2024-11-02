<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            align-items: flex-start; /* Aligns items to the top */
        }

        #preview {
            width: 50%; /* Adjust as needed */
        }

        #result {
            margin-left: 20px; /* Spacing between video and data */
        }

    </style>
</head>
<body>
    <h2>QR Code Tracking</h2>

    <div class="container d-flex">
        <video id="preview" class="w-50" autoplay></video>

        <div id="result" class="ml-3" style="display: none;">
            <div class="d-flex flex-column align-items-start">
                <img id="profile_pic" src="" alt="Profile Picture" class="img-fluid mb-3" style="max-width: 350px; height: auto;">
                <div>
                    <h3>Scanned Data:</h3>
                    <p><strong>Student ID:</strong> <span id="student_id_display"></span></p>
                    <p><strong>Name:</strong> <span id="student_name_display"></span></p>
                    <p><strong>Email:</strong> <span id="student_email_display"></span></p>
                    <p><strong>Contact:</strong> <span id="student_contact_display"></span></p>
                    <p><strong>Program:</strong> <span id="student_program_display"></span></p>
                    <p><strong>Department:</strong> <span id="student_department_display"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        scanner.addListener('scan', function (content) {
            let data;
            try {
                data = JSON.parse(content); // Assuming QR contains JSON
            } catch (e) {
                console.error('Invalid QR code content:', content);
                return;
            }

            // Display the scanned data
            document.getElementById('student_id_display').textContent = data.student_id || 'N/A';
            document.getElementById('student_name_display').textContent = data.name || 'N/A';
            document.getElementById('student_email_display').textContent = data.email || 'N/A';
            document.getElementById('student_contact_display').textContent = data.contact || 'N/A';
            document.getElementById('student_program_display').textContent = data.program || 'N/A';
            document.getElementById('student_department_display').textContent = data.department || 'N/A';

            // Fetch the profile picture using student ID
            fetch(`getProfilePic.php?student_id=${data.student_id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(profileData => {
                    if (profileData.error) {
                        console.error(profileData.error);
                        document.getElementById('profile_pic').src = ''; // Clear if there's an error
                    } else {
                        // Set the src to the base64 encoded image
                        document.getElementById('profile_pic').src = profileData.profile_pic; // Directly set the base64 data
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('profile_pic').src = ''; // Clear the image on error
                });

            // Show the result section
            document.getElementById('result').style.display = 'flex';
        });

        // Start the scanner
        Instascan.Camera.getCameras()
            .then(cameras => {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            })
            .catch(e => console.error('Camera error:', e));
    </script>

</body>
</html>
