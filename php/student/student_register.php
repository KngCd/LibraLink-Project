<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    section form input{
        border-radius: 5px;
    }
    form{
        padding: 35px 40px;
        border-radius: 56px;
        width: 400px;
    }
    section{
        padding: 90px 40px;
    }
    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
    }
    .navbar-brand{
        font-family: 'Times New Roman', Times, serif;
        font-size: 30px;
        color: black;
    }
    .navbar-brand:hover{
        color: black;
        text-decoration: underline;
    }
    .logo {
        height: 70px;
        margin: 0 10px;
        position: relative;
        top: 30px;
        transform: translateY(-50%);
    }
    .bg {
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
</style>

<body>

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="student-login.php" class="navbar-brand">
                <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
            </a>
        </div>
    </nav>
    
    <main class="bg">
        <section class="d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                    <h1 style="font-size: 4rem;">Elevate your Library Experience</h1>
                </div>

                <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                <?php
                    // Include the database configuration file
                    require_once '../db_config.php';

                    // Check if the form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Get the username and password from the form
                        $fullname = mysqli_real_escape_string($conn, $_POST['fullName']);
                        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
                        $email = mysqli_real_escape_string($conn, $_POST['email']);

                        $password = $_POST['password'];
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        
                        $program = mysqli_real_escape_string($conn, $_POST['program']);
                        $department = mysqli_real_escape_string($conn, $_POST['department']);

                        // Get file info
                        $fileCOR = basename($_FILES["cor"]["name"]);
                        $fileID = basename($_FILES["id"]["name"]);
                        
                        // Allow certain file formats
                        $allowTypes = array('pdf');

                        // Check if both files are PDF
                        $fileTypeCOR = strtolower(pathinfo($fileCOR, PATHINFO_EXTENSION));
                        $fileTypeID = strtolower(pathinfo($fileID, PATHINFO_EXTENSION));

                        if (in_array($fileTypeCOR, $allowTypes) && in_array($fileTypeID, $allowTypes) &&
                            $_FILES['cor']['error'] === UPLOAD_ERR_OK && $_FILES['id']['error'] === UPLOAD_ERR_OK) {

                            // Get the file contents
                            $corContent = file_get_contents($_FILES['cor']['tmp_name']);
                            $idContent = file_get_contents($_FILES['id']['tmp_name']);

                            // Prepare the SQL statement
                            $stmt = $conn->prepare("INSERT INTO verification_table (full_name, contact_num, email, password, program, 
                            department, cor, cor_filetype, id_file, id_filetype) VALUES (?,?,?,?,?,?,?,?,?,?)");
                            $stmt->bind_param("ssssssssss", $fullname, $contact, $email, $hash, $program, $department, $corContent, 
                            $fileTypeCOR, $idContent, $fileTypeID);
                            
                            // Execute the statement and check for success
                            if ($stmt->execute()) {
                                echo "<script>alert('Register Successful!'); window.location.href='student-login.php';</script>";
                            } else {
                                echo "<script>alert('Uploading Failed!'); window.location.href='student_register.php';</script>";
                            }
                            
                            // Close the statement
                            $stmt->close();
                        } else {
                            echo "<script>alert('Sorry, only PDF files are allowed to upload!'); window.location.href='student_register.php';</script>";
                        }
                    }
                ?>
                <form action="student_register.php" method="post" enctype="multipart/form-data" style="background: rgba(97, 97, 97, 0.2); backdrop-filter: blur(5px);">
                    <h2>Create Your Account</h2><br>
                    <div class="content">
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Full Name" name="fullName" id="fullName" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Program" name="program" id="program" autocomplete="off" required style="border-radius: 0.375rem; width: 40%;">
                                <input type="text" class="form-control ms-1" placeholder="Department" name="department" id="department" autocomplete="off" required style="border-radius: 0.375rem; width: 40%;">
                            </div>
                        </div>
                        <!-- <div class="mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Department" name="department" id="department" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div> -->
                        <div class="mb-2">
                            <div class="input-group">
                                <label for="cor">Upload your COR</label>
                                <input type="file" class="form-control" name="cor" id="cor" accept=".pdf" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <label for="id">Upload your ID</label>
                                <input type="file" class="form-control" name="id" id="id" accept=".pdf" required style="border-radius: 0.375rem; width: auto;">
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <button type="submit" class="btn btn-primary w-40">Register</button>
                        </div>
                    </div>
                </form>

                </div>

            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>