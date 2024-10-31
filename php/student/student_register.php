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
    #contact-error, #program-error, #department-error, #cor-error, #id-error, #pic-error{
        color: red;
        font-size: 0.90rem;
        /* display: block; */
    }
    .input-group label{
        display: block;
        width: 100%;
    }
    .input-group input{
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
</style>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="student-login.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;">
            </a>
        </div>
    </nav>
    
    <main>
        <section class="d-flex align-items-center justify-content-center">
            <div class="container mt-3 mb-3">
                <div class="row">
                    <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <h1 style="font-size: 3.5rem;">Elevate your Library Experience</h1>
                    </div>

                    <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
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

                                // Check if COR and ID are PDFs and Profile Picture is an image
                                if (in_array($fileTypeCOR, $allowTypesPDF) && in_array($fileTypeID, $allowTypesPDF) &&
                                    in_array($fileTypePic, $allowTypesImage) &&
                                    $_FILES['cor']['error'] === UPLOAD_ERR_OK && 
                                    $_FILES['id']['error'] === UPLOAD_ERR_OK && 
                                    $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
                                    
                                    // Check if the email is unique
                                    $emailCheckStmt = $conn->prepare("SELECT email FROM verification_table WHERE email = ?");
                                    $emailCheckStmt->bind_param("s", $email);
                                    $emailCheckStmt->execute();
                                    $emailCheckStmt->store_result();

                                    if ($emailCheckStmt->num_rows > 0) {
                                        // Email already exists
                                        echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='student_register.php';</script>";
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
                                            echo "<script>alert('Register Successful!'); window.location.href='student-login.php';</script>";
                                        } else {
                                            echo "<script>alert('Uploading Failed!'); window.location.href='student_register.php';</script>";
                                        }
                                        
                                        // Close the statement
                                        $stmt->close();
                                    }

                                    // Close the email check statement
                                    $emailCheckStmt->close();
                                } else {
                                    echo "<script>alert('Sorry, only PDF files for COR and ID, and image files for Profile Picture are allowed to upload!'); window.location.href='student_register.php';</script>";
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
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>
                                <div class="input-group mb-2">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" autocomplete="off" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
                                </div>
                                <div class="input-group mb-2">
                                    <select name="department" id="department" class="form-select" required>
                                        <option value="" disabled selected>Select Department</option>
                                        <?php
                                        // Fetch departments
                                        $result = $conn->query("SELECT * FROM department_table");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <select name="program" id="program" class="form-select ms-1" required>
                                        <option value="" disabled selected>Select Program</option>
                                        <!-- Programs will be populated with JavaScript -->
                                    </select>
                                </div>
                                <div class="input-group mb-2">
                                    <label for="cor">Upload your COR</label>
                                    <input type="file" class="form-control" name="cor" id="cor" accept=".pdf">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="id">Upload your ID</label>
                                    <input type="file" class="form-control" name="id" id="id" accept=".pdf">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="id">Upload your Profile Picture</label>
                                    <input type="file" class="form-control" name="pic" id="pic" accept="image/*">
                                </div>
                                    
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" class="btn btn-danger w-100">Register</button>
                                </div>
                            </div>
                        </form>
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