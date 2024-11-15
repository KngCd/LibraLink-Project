<?php
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>QR Code Scanner</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    *{
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
    }
    .container {
        display: flex;
        align-items: flex-start; /* Aligns items to the top */
    }

    #preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        max-width: 100%; /* Prevents exceeding the screen width */
        max-height: 100%; /* Prevents exceeding the screen height */
    }

    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        /* backdrop-filter: blur(5px); */
    }
    .navbar-brand{
        /* font-family: 'Times New Roman', Times, serif; */
        font-size: 30px;
        color: black;
    }
    .navbar-brand:hover{
        color: black;
        text-decoration: underline;
    }
    .logo {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .logo:hover {
        transform: scale(1.1);
    }
    .bg {
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    .button{
        border-radius: 30px !important;
    }
</style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #dd2222;">
        <div class="container-fluid">
            <a href="../admin-student.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/librawhite.png" alt="Logo" style="height: 40px; width: auto;">
            </a>
            <!-- <a href="../admin-student.php" class="btn btn-light button" style="width: 150px;">← Back</a> -->
            <h2 class="text-light" id="currentTime"><?php echo $currentTime; ?></h2>
            <script>
                function updateClock() {
                    
                    const now = new Date();
                    
                    const options = {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Manila'
                    };
                    
                    const formatter = new Intl.DateTimeFormat('en-US', options);
                    const timeString = formatter.format(now);
                    
                    document.getElementById('currentTime').textContent = timeString;
                }

                updateClock();
                setInterval(updateClock, 1000);
            </script>
        </div>
    </nav>

    <main class="bg">
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <!-- Subtract 56px (navbar height) from the viewport height -->
            <div class="container-fluid p-x-2 mt-5">
                <div class="row" style="height: 100%;">
                    <!-- Video Card -->
                    <div class="col-12 col-md-6">
                        <div class="card shadow-lg h-100">
                            <div class="card-body p-0">
                                <video id="preview" autoplay style="width: 100%; height: 100%; object-fit: cover;"></video>
                            </div>
                        </div>
                    </div>

                    <!-- Scanned Data Card -->
                    <div class="col-12 col-md-6">
                        <div class="card" style="background: none; border: none;">
                            <div class="card-body" style="padding: 20px; height: 450px; overflow: hidden;">
                                <div id="result" style="display: none;">
                                    <div class="d-flex flex-column align-items-start">
                                        <img id="profile_pic" src="" alt="Profile Picture" class="img-fluid mb-3" style="max-width: 350px; height: auto;">
                                        <div>
                                            <!-- <h3>Scanned Data:</h3> -->
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
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>



    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        scanner.addListener('scan', function (content) {
            let data;
            try {
                data = JSON.parse(content); // Assuming QR contains JSON
            } catch (e) {
                console.error('Invalid QR code content:', content);
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid QR code format. Please scan a valid QR code.',
                    icon: 'error',
                    confirmButtonText: 'Okay',
                    confirmButtonColor: "#dc3545",
                    timer: 1500,
                });
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

            // Send student ID to process_logs.php to log time_in or time_out
            fetch('process_logs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ student_id: data.student_id })
            })
            .then(response => response.json())
            .then(logResult => {
                // Check if it's time-in or time-out based on the message
                if (logResult.status === 'success') {
                    if (logResult.message.includes("Time-in")) {
                        // Time-in Success Alert
                        Swal.fire({
                            title: 'Time-In Logged!',
                            text: 'You have successfully logged in!',
                            icon: 'success',
                            confirmButtonText: 'Okay',
                            confirmButtonColor: "#198754",
                            timer: 1500,
                        });
                    } else if (logResult.message.includes("Time-out")) {
                        // Time-out Success Alert
                        Swal.fire({
                            title: 'Time-Out Logged!',
                            text: 'You have successfully logged out!',
                            icon: 'success',
                            confirmButtonText: 'Okay',
                            confirmButtonColor: "#198754",
                            timer: 1500,
                        });
                    }
                } else {
                    // Error alert
                    Swal.fire({
                        title: 'Error!',
                        text: logResult.message,
                        icon: 'error',
                        confirmButtonText: 'Okay',
                        confirmButtonColor: "#dc3545",
                        timer: 1500,
                    });
                }
            })
            .catch(error => {
                console.error('Request failed:', error);
                // Show a generic error message if the fetch request fails
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing the log.',
                    icon: 'error',
                    confirmButtonText: 'Okay',
                    confirmButtonColor: "#dc3545",
                    timer: 1500,
                });
            });

            setTimeout(function () {
                document.getElementById('result').style.display = 'none';
            }, 2500); // Adjust the time as needed
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