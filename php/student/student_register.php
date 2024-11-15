<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    *{
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
    }
    section form input{
        border-radius: 5px;
    }
    form{
        padding: 35px 40px;
        border-radius: 56px;
        width: 400px;
    }
    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
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
    body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    ::placeholder {
        color: black !important; 
    }
    #firstName-error, #lastName-error, #email-error, #password-error, #confirmPassword-error, 
    #contact-error, #program-error, #department-error, #cor-error, #id-error, #pic-error, #terms-error {
        color: red;
        font-size: 0.90rem;
        /* display: block; */
    }
    .input-group label{
        display: block;
        width: 100%;
    }
    .input-group input, .input-group select{
        border-radius: 16px; 
        border: solid, 1px, black; 
        width: auto; 
        display: block;
        border-top-right-radius: 16px; 
        border-bottom-right-radius: 16px;
    }
    /* .input-group span{
        border-radius: 16px; 
        border: solid, 1px, black; 
        width: auto; 
        border-top-right-radius: 0; 
        border-bottom-right-radius: 0;
    } */
    form select, form select option{
        cursor: pointer;
    }
    .button{
        border-radius: 30px !important;
        max-width: 100px;
    }
    .input-group span{
        border-radius: 16px; 
        border: solid, 1px, black; 
        width: auto; 
        border-top-right-radius: 0; 
        border-bottom-right-radius: 0;
    }
</style>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #dd2222;">
        <div class="container-fluid">
            <a href="student-login.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/librawhite.png" alt="Logo" style="height: 40px; width: auto;">
            </a>
            <a href="student-login.php" class="btn btn-light button fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 fs-6">← Back</a>
        </div>
    </nav>
    
    <main>
        <section class="d-flex align-items-center justify-content-center">
            <div class="container mt-3 mb-3">
                <div class="row">
                    <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <img class="img-fluid d-xxl-none d-xl-none d-lg-none d-md-none d-sm-block d-block mt-3" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 150px; width: auto;">

                        <h1 class="text-break fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-none" style="font-weight: 650; font-size: 3.5rem;">
                            Join the
                            <img class="img-fluid" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 65px; width: auto;">
                        </h1>
                        <h1 class="text-break fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-none" style="font-weight: 650; font-size: 3.5rem;">
                            Family! Your journey to success <br>starts here!
                        </h1>
                    </div>

                    <div class="form-container d-flex justify-content-center col-lg-6 col-md-6 col-sm-12 col-12 
                    mt-lg-5 mt-md-5 mt-sm-0 mt-0 mb-5">
                        <?php
                            // Include the database configuration file
                            require_once '../db_config.php';

                            // Fetch departments
                            $departments = $conn->query("SELECT * FROM department_table");

                            // Fetch programs (assuming there's a department_id in the programs table)
                            $programs = $conn->query("SELECT p.id, p.name, p.department_id,  d.name AS department_name FROM program_table p JOIN department_table d ON p.department_id = d.id");

                            // Store programs by department
                            $programs_by_department = [];
                            while ($row = $programs->fetch_assoc()) {
                                $programs_by_department[$row['department_id']][] = $row;
                            }

                            // Check if the form has been submitted
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                // Get the username and password from the form
                                $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
                                $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
                                $contact = mysqli_real_escape_string($conn, $_POST['contact']);
                                $email = mysqli_real_escape_string($conn, $_POST['email']);

                                $password = $_POST['password'];
                                $hash = password_hash($password, PASSWORD_DEFAULT);
                                
                                // Assuming $department and $program are the IDs from the form submission
                                $department_id = mysqli_real_escape_string($conn, $_POST['department']);
                                $program_id = mysqli_real_escape_string($conn, $_POST['program']);
                                
                                // Fetch department name
                                $department_result = $conn->query("SELECT name FROM department_table WHERE id = '$department_id'");
                                $department_row = $department_result->fetch_assoc();
                                $department_name = $department_row['name'] ?? null; // Use null coalescing to avoid undefined index

                                // Fetch program name
                                $program_result = $conn->query("SELECT name FROM program_table WHERE id = '$program_id'");
                                $program_row = $program_result->fetch_assoc();
                                $program_name = $program_row['name'] ?? null; // Use null coalescing to avoid undefined index

                                // Get file info
                                $fileCOR = basename($_FILES["cor"]["name"]);
                                $fileID = basename($_FILES["id"]["name"]);
                                $filePic = basename($_FILES["pic"]["name"]);
                                
                                // Allow certain file formats
                                $allowTypesPDF = array('pdf');
                                $allowTypesImage = array('jpg', 'jpeg', 'png');

                                // Check file types
                                $fileTypeCOR = strtolower(pathinfo($fileCOR, PATHINFO_EXTENSION));
                                $fileTypeID = strtolower(pathinfo($fileID, PATHINFO_EXTENSION));
                                $fileTypePic = strtolower(pathinfo($filePic, PATHINFO_EXTENSION));

                                // Get the maximum file upload size from the server's php.ini settings
                                $maxUploadSize = ini_get('upload_max_filesize');
                                $maxPostSize = ini_get('post_max_size');

                                // Convert the sizes to bytes
                                function convertToBytes($sizeStr) {
                                    $unit = substr($sizeStr, -1);
                                    $size = (int)$sizeStr;

                                    switch (strtoupper($unit)) {
                                        case 'G':
                                            return $size * 1024 * 1024 * 1024;
                                        case 'M':
                                            return $size * 1024 * 1024;
                                        case 'K':
                                            return $size * 1024;
                                        default:
                                            return $size; // Assume bytes
                                    }
                                }

                                $maxUploadSizeBytes = convertToBytes($maxUploadSize);
                                $maxPostSizeBytes = convertToBytes($maxPostSize);

                                // Check if COR and ID are PDFs and Profile Picture
                                if (in_array($fileTypeCOR, $allowTypesPDF) && in_array($fileTypeID, $allowTypesPDF) &&
                                    in_array($fileTypePic, $allowTypesImage) &&
                                    $_FILES['cor']['error'] === UPLOAD_ERR_OK && 
                                    $_FILES['id']['error'] === UPLOAD_ERR_OK && 
                                    $_FILES['pic']['error'] === UPLOAD_ERR_OK) {

                                    // Check file sizes against the maximum upload size
                                    if ($_FILES['cor']['size'] > $maxUploadSizeBytes) {
                                        // echo "<script>alert('The uploaded COR file exceeds the maximum allowed size of $maxUploadSize.'); window.location.href='student_register.php';</script>";
                                        echo "<script>
                                            Swal.fire({
                                                title: 'File Size Error',
                                                text: 'The uploaded COR file exceeds the maximum allowed size of $maxUploadSize.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                                customClass: {
                                                    confirmButton: 'no-border'
                                                },
                                            }).then(function() {
                                                window.location.href = 'student_register.php';
                                            });    
                                            // Remove the border
                                            document.querySelector('.swal2-confirm').style.border = 'none';
                                        </script>";
                                        exit;
                                    }

                                    if ($_FILES['id']['size'] > $maxUploadSizeBytes) {
                                        // echo "<script>alert('The uploaded ID file exceeds the maximum allowed size of $maxUploadSize.'); window.location.href='student_register.php';</script>";
                                        echo "<script>
                                            Swal.fire({
                                                title: 'File Size Error',
                                                text: 'The uploaded ID file exceeds the maximum allowed size of $maxUploadSize.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                                customClass: {
                                                    confirmButton: 'no-border'
                                                },
                                            }).then(function() {
                                                window.location.href = 'student_register.php';
                                            });    
                                            // Remove the border
                                            document.querySelector('.swal2-confirm').style.border = 'none';
                                        </script>";
                                        exit;
                                    }

                                    if ($_FILES['pic']['size'] > $maxUploadSizeBytes) {
                                        echo "<script>
                                            Swal.fire({
                                                title: 'File Size Error',
                                                text: 'The uploaded profile picture exceeds the maximum allowed size of $maxUploadSize.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                                customClass: {
                                                    confirmButton: 'no-border'
                                                },
                                            }).then(function() {
                                                window.location.href = 'student_register.php';
                                            });    
                                            // Remove the border
                                            document.querySelector('.swal2-confirm').style.border = 'none';
                                        </script>";
                                        exit;
                                    }
                                    
                                    // Check if the email is unique
                                    $emailCheckStmt = $conn->prepare("SELECT email FROM verification_table WHERE email = ?");
                                    $emailCheckStmt->bind_param("s", $email);
                                    $emailCheckStmt->execute();
                                    $emailCheckStmt->store_result();

                                    $emailCheckStmt2 = $conn->prepare("SELECT email FROM student_table WHERE email = ?");
                                    $emailCheckStmt2->bind_param("s", $email);
                                    $emailCheckStmt2->execute();
                                    $emailCheckStmt2->store_result();

                                    // Check if the contact number is unique
                                    $contactCheckStmt = $conn->prepare("SELECT contact_num FROM verification_table WHERE contact_num = ?");
                                    $contactCheckStmt->bind_param("s", $contact); // Assuming $contact_number is the variable storing the contact number
                                    $contactCheckStmt->execute();
                                    $contactCheckStmt->store_result();

                                    $contactCheckStmt2 = $conn->prepare("SELECT contact_num FROM student_table WHERE contact_num = ?");
                                    $contactCheckStmt2->bind_param("s", $contact);
                                    $contactCheckStmt2->execute();
                                    $contactCheckStmt2->store_result();

                                    if ($emailCheckStmt->num_rows > 0 || $emailCheckStmt2->num_rows > 0) {
                                        // Email already exists
                                        // echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='student_register.php';</script>";
                                        echo "<script>
                                            Swal.fire({
                                                title: 'Email Error',
                                                text: 'Email already exists! Please use a different email.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                                customClass: {
                                                    confirmButton: 'no-border'
                                                },
                                            }).then(function() {
                                                window.location.href = 'student_register.php';
                                            });    
                                            // Remove the border
                                            document.querySelector('.swal2-confirm').style.border = 'none';
                                        </script>";
                                        exit;
                                    } elseif ($contactCheckStmt->num_rows > 0 || $contactCheckStmt2->num_rows > 0) {
                                        // Contact number already exists
                                        echo "<script>
                                            Swal.fire({
                                                title: 'Contact Number Error',
                                                text: 'Contact number already exists! Please use a different contact number.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                                customClass: {
                                                    confirmButton: 'no-border'
                                                },
                                            }).then(function() {
                                                window.location.href = 'student_register.php';
                                            });    
                                            // Remove the border
                                            document.querySelector('.swal2-confirm').style.border = 'none';
                                        </script>";
                                        exit;
                                    } else {
                                        // Get the file contents
                                        $corContent = file_get_contents($_FILES['cor']['tmp_name']);
                                        $idContent = file_get_contents($_FILES['id']['tmp_name']);
                                        $picContent = file_get_contents($_FILES['pic']['tmp_name']);

                                        // Prepare the SQL statement
                                        $stmt = $conn->prepare("INSERT INTO verification_table (first_name, last_name, contact_num, email, password, program, 
                                        department, cor, cor_filetype, id_file, id_filetype, profile_pic, pic_filetype) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                        $stmt->bind_param("sssssssssssss", $firstName, $lastName, $contact, $email, $hash, $program_name, $department_name, 
                                        $corContent, $fileTypeCOR, $idContent, $fileTypeID, $picContent, $fileTypePic);
                                        
                                        // Execute the statement and check for success
                                        if ($stmt->execute()) {
                                            // echo "<script>alert('Register Successful!'); window.location.href='student-login.php';</script>";
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'Registration Successful!',
                                                    icon: 'success',
                                                    confirmButtonText: 'Okay',
                                                    confirmButtonColor: '#198754',
                                                    customClass: {
                                                        confirmButton: 'no-border'
                                                    },
                                                }).then(function() {
                                                    window.location.href = 'student-login.php';
                                                });
                                                // Remove the border
                                                document.querySelector('.swal2-confirm').style.border = 'none';
                                            </script>";
                                            exit;
                                        } else {
                                            // echo "<script>alert('Uploading Failed!'); window.location.href='student_register.php';</script>";
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Oops!',
                                                    text: 'Uploading Failed!',
                                                    icon: 'error',
                                                    confirmButtonText: 'Try Again',
                                                    confirmButtonColor: '#dc3545',
                                                    customClass: {
                                                        confirmButton: 'no-border'
                                                    },
                                                }).then(function() {
                                                    window.location.href = 'student_register.php';
                                                });
                                                // Remove the border
                                                document.querySelector('.swal2-confirm').style.border = 'none';
                                            </script>";
                                            exit;
                                        }
                                        
                                        // Close the statement
                                        $stmt->close();
                                    }

                                    // Close the email check statement
                                    $emailCheckStmt->close();
                                    $emailCheckStmt2->close();
                                } else {
                                    // echo "<script>alert('Sorry, only PDF files for COR and ID, and image files for Profile Picture are allowed to upload!'); window.location.href='student_register.php';</script>";
                                    echo "<script>
                                        Swal.fire({
                                            title: 'File Type Error',
                                            text: 'Sorry, only PDF files for COR and ID, and image files for Profile Picture are allowed to upload!',
                                            icon: 'error',
                                            confirmButtonText: 'Okay',
                                            confirmButtonColor: '#dc3545',
                                            customClass: {
                                                confirmButton: 'no-border'
                                            },
                                        }).then(function() {
                                            window.location.href = 'student_register.php';
                                        });
                                        // Remove the border
                                        document.querySelector('.swal2-confirm').style.border = 'none';
                                    </script>";
                                    exit;
                                }
                            }
                        ?>

                    <!-- Register Form -->
                        <form id="registerForm" class="mt-3 mb-3" action="student_register.php" method="post" enctype="multipart/form-data" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            <h3 class="text-center">Create Your Account</h3><br>
                            <div class="content">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="First Name" name="firstName" id="firstName" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                    <input type="text" class="form-control ms-1" placeholder="Last Name" name="lastName" id="lastName" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-top-left-radius: 16px; border-bottom-left-radius: 16px; ">
                                </div>

                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>

                                <div class="input-group mb-2">
                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>

                                <div class="input-group mb-2">
                                    <span class="input-group-text" onclick="togglePassword()"><i class="bi bi-eye-slash-fill" id="password-icon" style="cursor: pointer;"></i></span>
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" onclick="toggle()"><i class="bi bi-eye-slash-fill" id="password-icon2" style="cursor: pointer;"></i></span>
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>
                                <div class="input-group mb-2">
                                    <select name="department" id="department" class="form-select" required style="border-radius: 16px;">
                                        <option value="" disabled selected>Select Department</option>
                                        <?php
                                        // Fetch departments
                                        $result = $conn->query("SELECT * FROM department_table");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <select name="program" id="program" class="form-select ms-1" required style="border-radius: 16px;">
                                        <option value="" disabled selected>Select Program</option>
                                        <!-- Programs will be populated with JavaScript -->
                                    </select>
                                </div>
                                <div class="input-group mb-2">
                                    <label for="cor">Upload your COR</label>
                                    <input type="file" class="form-control" name="cor" id="cor" accept=".pdf">
                                </div>

                                <div class="input-group mb-2">
                                    <label for="id">Upload your ID</label>
                                    <input type="file" class="form-control" name="id" id="id" accept=".pdf">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="id">Upload your Profile Picture</label>
                                    <input type="file" class="form-control" name="pic" id="pic" accept="image/*">
                                </div>

                                    <!-- Terms and Conditions Checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required style="border: 1px solid black;">
                                    <label class="form-check" for="terms">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditions">Terms and Conditions</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#policy">Privacy Policy</a>.
                                    </label>
                                </div>
                                    
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" class="btn btn-danger w-100">Register</button>
                                </div>
                            </div>
                        </form>
                        <!-- Terms and Conditions Modal -->
                        <div class="modal fade" id="termsConditions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: #dd2222; font-weight: bold;">Terms And Conditions</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ol class="text-left fs-4">
                                            <li>
                                                <h4 style="font-weight: bold;">Introduction</h4>
                                                <p>Welcome to
                                                        <img class="img-fluid" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 30px; width: auto;">
                                                        By using our website and services, you agree to the following Terms and Conditions. Please read them carefully before using our website or registering an account. If you do not agree to these terms, do not use the site.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Acceptance of Terms</h4>
                                                <p>By accessing or using our services, you accept and agree to be bound by these Terms and Conditions and our Privacy Policy. If you do not agree with any part of these terms, you must not use the site.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">User Accounts</h4>
                                                <p>To access certain features of our website, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
                                                <ul>
                                                    <li>You must be a <strong>BSU-Lipa Campus</li>strong> student to register on this website.</li>
                                                    <li>You agree to provide accurate and complete information when registering and to update your information if it changes.</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">User Responsibilities</h4>
                                                <p>You agree not to use our services for any unlawful or prohibited activities, including but not limited to:</p>
                                                <ul>
                                                    <li>Spamming or phishing.</li>
                                                    <li>Uploading harmful content such as viruses or malware.</li>
                                                    <li>Engaging in harassment or illegal activities.</li>
                                                </ul>
                                                <p>You must comply with all applicable laws while using our services.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Privacy and Data Collection</h4>
                                                <p>Your privacy is important to us. By using our website, you agree to the collection and use of personal information in accordance with our <a href="privacy-policy.html">Privacy Policy</a>.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Intellectual Property</h4>
                                                <p>All content on this website, including text, images, logos, and trademarks, are the property of LibraLink and are protected by copyright laws. You may not copy, modify, or distribute any of the website content without prior written permission.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Disclaimers</h4>
                                                <p>The website and its services are provided "as is" without any warranties of any kind, either express or implied.</p>
                                                <p>We do not guarantee that our website will be available at all times or free from errors, bugs, or viruses.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Limitation of Liability</h4>
                                                <p>LibraLink will not be held liable for any damages or losses arising from your use of the website or services. This includes any direct, indirect, incidental, or consequential damages.</p>
                                            </li>
                                            <li>
                                                <h style="font-weight: bold;"4>Termination</h4>
                                                <p>We reserve the right to suspend or terminate your account if you violate these Terms and Conditions. You may also terminate your account by contacting us through the provided support channels.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Changes to the Terms</h4>
                                                <p>We reserve the right to update or change these Terms and Conditions at any time. We will notify you of any material changes, and the updated terms will be posted on this page.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Governing Law</h4>
                                                <p>These Terms and Conditions are governed by and construed in accordance with the laws of the Philppines. Any disputes will be resolved in the competent courts of the country.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Contact Us</h4>
                                                <p>If you have any questions regarding these Terms and Conditions, please contact us at:</p>
                                                <ul>
                                                    <li>Email: <a href="mailto:library.lipa@g.batstate-u.edu.ph">library.lipa@g.batstate-u.edu.ph</a></li>
                                                    <li>Phone: (+63 43) 980-0385 loc. 3110</li>
                                                    <li>Address:  A. Tanco Drive, Marawoy, Lipa, Batangas</li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Policy Modal -->
                        <div class="modal fade" id="policy" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel" style="color: #dd2222; font-weight: bold;">Privacy Policy</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ol class="fs-4">
                                            <li>
                                                <h4 style="font-weight: bold;">Introduction</h4>
                                                <p>Welcome to
                                                    <img class="img-fluid" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 30px; width: auto;">
                                                    This Privacy Policy explains how we collect, use, and protect your personal information when you use our website and services. By using our website, you agree to the collection and use of information in accordance with this policy. If you do not agree with this policy, please do not use the site.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Information We Collect</h4>
                                                <p>We collect various types of information to provide and improve our services, including:</p>
                                                <ul>
                                                    <li><strong>Personal Information:</strong> This includes data such as your name, email address, phone number, and any other information you provide when registering or using our services.</li>
                                                    <li><strong>Usage Data:</strong> We may collect information on how the website is accessed and used, including your IP address, browser type, and device information.</li>
                                                    <li><strong>Cookies:</strong> We use cookies and similar tracking technologies to monitor activity on our site and improve user experience.</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">How We Use Your Information</h4>
                                                <p>We use the information we collect for the following purposes:</p>
                                                <ul>
                                                    <li>To provide and maintain our services.</li>
                                                    <li>To notify you about changes to our services or any issues related to your account.</li>
                                                    <li>To improve and customize the user experience on our website.</li>
                                                    <li>To send marketing communications, if you have opted in to receive them.</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Data Retention</h4>
                                                <p>We will retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy. If you wish to delete your data or stop using our services, you can contact us directly.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Data Security</h4>
                                                <p>We take reasonable precautions to protect your personal information from unauthorized access, use, or disclosure. However, no method of electronic transmission or storage is 100% secure, and we cannot guarantee the absolute security of your data.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Sharing Your Information</h4>
                                                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information with third-party service providers who help us operate our website or provide our services, but only in accordance with this Privacy Policy. These third parties are obligated to keep your information confidential and secure.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Your Rights</h4>
                                                <p>You have the right to:</p>
                                                <ul>
                                                    <li>Access the personal data we hold about you.</li>
                                                    <li>Request corrections or updates to your information.</li>
                                                    <li>Request the deletion of your personal data (subject to legal and contractual limitations).</li>
                                                    <li>Opt-out of receiving marketing communications at any time.</li>
                                                </ul>
                                                <p>If you wish to exercise these rights, please contact us at the provided contact details below.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Cookies and Tracking Technologies</h4>
                                                <p>We use cookies to track activity on our website and improve the user experience. You can choose to accept or decline cookies by adjusting your browser settings. However, disabling cookies may affect your ability to use some features of our website.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Third-Party Links</h4>
                                                <p>Our website may contain links to other websites that are not operated by us. We are not responsible for the privacy practices or content of those external sites. We encourage you to review the privacy policies of any third-party sites you visit.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Changes to This Privacy Policy</h4>
                                                <p>We reserve the right to update or modify this Privacy Policy at any time. Any changes will be posted on this page, and the updated policy will take effect as soon as it is published. We encourage you to review this policy periodically for any updates.</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Governing Law</h4>
                                                <p>This Privacy Policy is governed by the laws of the Philippines. Any disputes related to privacy or data protection will be resolved in the competent courts of [Location].</p>
                                            </li>
                                            <li>
                                                <h4 style="font-weight: bold;">Contact Us</h4>
                                                <p>If you have any questions or concerns about this Privacy Policy or how we handle your personal data, please contact us:</p>
                                                <ul>
                                                    <li>Email: <a href="mailto:library.lipa@g.batstate-u.edu.ph">library.lipa@g.batstate-u.edu.ph</a></li>
                                                    <li>Phone: (+63 43) 980-0385 loc. 3110</li>
                                                    <li>Address:  A. Tanco Drive, Marawoy, Lipa, Batangas</li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Populate programs based on department selection
                            document.getElementById('department').addEventListener('change', function() {
                                const departmentId = this.value;
                                const programSelect = document.getElementById('program');

                                // Clear previous options
                                programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

                                // Get programs related to the selected department
                                const programs = <?= json_encode($programs_by_department) ?>;
                                if (programs[departmentId]) {
                                    if (programs[departmentId].length === 0) {
                                        const option = document.createElement('option');
                                        option.value = "";
                                        option.textContent = "No programs yet";
                                        option.disabled = true; // Disable the option
                                        programSelect.appendChild(option);
                                    } else {
                                        programs[departmentId].forEach(program => {
                                            const option = document.createElement('option');
                                            option.value = program.id;
                                            option.textContent = program.name;
                                            programSelect.appendChild(option);
                                        });
                                    }
                                } else {
                                    // In case there's no data for the selected department
                                    const option = document.createElement('option');
                                    option.value = "";
                                    option.textContent = "No programs yet";
                                    option.disabled = true; // Disable the option
                                    programSelect.appendChild(option);
                                }
                            });
                        </script>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/loginValidate.js"></script>
</body>
</html>