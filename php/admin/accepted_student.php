<?php

session_start();
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s'); 

// Check if the admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // If the session variable is not set or is false, redirect to the login page
    header('Location: admin-login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
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
    main{
        position: absolute;
        left: 300px; /* adjust to match the offcanvas width */
        width: calc(100% - 300px); /* adjust to match the offcanvas width */
    }
    /* body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    } */
    ::backdrop {
        backdrop-filter: blur(3px);
    }
    table th, table td{
       text-align: center;
    }
    #total-register, #accepted-student, #books, #total-stocks, #total-borrowed-books, #total-available{
        border-radius: 10px;
        border: 1px solid lightgray;
        background-color: #ffffff;
    }
    .offcanvas {
        width: 300px !important;
        background-color: #dd2222;
    }
    .content {
        display: none; /* Hide all content sections by default */
    }

    .content.active {
        display: block; /* Show the active content section */
    }
    .main-content {
        display: flex;
        /* flex-direction: column; */
        align-items: center;
        justify-content: center;
        min-height: 100vh; /* Full height of the viewport */
    }
    .dashboard-item {
        padding: 10px;
    }
    .dashboard-link {
        display: block;
        padding: 10px;
        background-color: #dd2222;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .dashboard-link:hover, .dashboard-link:focus, #active {
        background-color: #ca1d1d;
    }

    .dashboard-link i {
        margin-right: 10px;
        font-size: 18px;
    }
    .dashboard-link span {
        font-size: 15px;
    }
    
    .offcanvas-body::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    .offcanvas-body::-webkit-scrollbar-thumb {
        background-color: #ca1d1d;
        border-radius: 5px;
        box-shadow: none;
        cursor: pointer;
    }
    .offcanvas-body::-webkit-scrollbar-track {
        background-color: transparent;
        border-radius: 0;
        box-shadow: none;
    }
    .main-content {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .welcome {
        align-self: flex-start;
        width: 100%;
        padding: 3rem;
        background-color: #f8f9fa;
    }
    #total-accepted {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .navbar-brand {
        position: absolute;
        top: 10px;
        left: 10px;
    }
    .button{
        border-radius: 30px !important;
    }
    form select, form select option{
        cursor: pointer;
    }
    #email-error, #contact_num-error{
        color: red;
        /* font-size: 0.90rem; */
    }
    .profile-pic {
        background-color: white;
        height: 180px;
        width: 180px;
        border-radius: 50%;
        object-fit: contain;
        object-position: center;
        cursor: pointer;
    } 
    .bi-pencil-square{cursor: pointer;}
</style>

<body>

    <!-- SIDEBAR -->
    <div class="offcanvas offcanvas-start text-light" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" data-bs-backdrop="static" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header d-flex justify-content-center align-items-center flex-column" style="margin-bottom: -20px;">
            <img id="adminPicture" src="../../img/formal-pic-von.jpg" alt="Admin Picture" class="profile-pic" onclick="document.getElementById('fileInput').click();">        
            <input type="file" id="fileInput" style="display: none;" onchange="uploadImage()"> <!-- Hidden file input -->
            <div class="d-flex justify-content-center align-items-center mt-2 mb-0">
                <h4 class="mb-0" id="adminName">Carlo M. Pastrana</h4>
                <span class="ms-2" onclick="editName()">
                    <i class="bi bi-pencil-square"></i>
                </span>
            </div>
            <h6 class="text-center">Admin</h6>
        </div>

        <script>
            // Check local storage for the saved image path
            const savedImagePath = localStorage.getItem('adminImagePath');
            if (savedImagePath) {
                document.getElementById('adminPicture').src = savedImagePath; // Set the image src from local storage
            }

            // Check local storage for the saved admin name
            const savedAdminName = localStorage.getItem('adminName');
            if (savedAdminName) {
                document.getElementById('adminName').textContent = savedAdminName; // Set the name from local storage
            }

            function editName() {
                const nameElement = document.getElementById('adminName');
                const currentName = nameElement.textContent;
                const newName = prompt("Edit name:", currentName);
                if (newName) {
                    nameElement.textContent = newName;
                    localStorage.setItem('adminName', newName); // Save the new name to local storage
                }
            }

            function updateAdminPicture(newImagePath) {
                document.getElementById('adminPicture').src = newImagePath;
                localStorage.setItem('adminImagePath', newImagePath); // Save the new image path to local storage
            }

            function uploadImage() {
                const fileInput = document.getElementById('fileInput');
                const file = fileInput.files[0];
                
                if (file) {
                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('upload.php', { // Send to the separate upload file
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateAdminPicture(data.imagePath); // Update the image source with the new path
                        } else {
                            alert('Upload failed: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error uploading image: ' + error);
                    });
                } else {
                    alert("Please select a file to upload.");
                }
            }
        </script>
        
        <div class="offcanvas-body" id="sidebar">
            <div class="dashboard-item">
                <a href="admin_dashboard.php" class="dashboard-link">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Home</span>
                </a>
            </div>
            <hr>
            <h5>Manage</h5>
            <div class="dashboard-item">
                <a href="total_register.php" class="dashboard-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Registered</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" id="active">
                    <i class="bi bi-person-fill-check"></i>
                    <span>Accepted</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="program_dept.php" class="dashboard-link">
                    <i class="bi bi-buildings"></i>
                    <span>Programs and Departments</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="attendance_logs.php" class="dashboard-link">
                    <i class="bi bi-clock"></i>
                    <span>Attendance Logs</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="books.php" class="dashboard-link">
                    <i class="bi bi-book-fill"></i>
                    <span>Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="borrowed.php" class="dashboard-link">
                    <i class="bi bi-bookmark-check-fill"></i>
                    <span>Borrowed Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="book_stock.php" class="dashboard-link">
                    <i class="bi bi-inboxes-fill"></i>
                    <span>Book Stocks</span>
                </a>
            </div>
        </div>
            <hr>
        <div class="offcanvas-footer text-center mb-2">
            <div class="dashboard-item">
                <h6 id="currentTime"><?php echo $currentTime; ?></h6>
            </div>
        </div>

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

            // Scroll to active link when the offcanvas is shown
            document.getElementById('offcanvasWithBothOptions').addEventListener('shown.bs.offcanvas', function () {
                const activeLink = document.getElementById('active');
                if (activeLink) {
                    activeLink.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        </script>
    </div>
    <!-- END SIDEBAR -->    

    <script>
        $(document).ready(function() {
            $('#offcanvasWithBothOptions').offcanvas('show');
        });
    </script>

    <main class="main-content">
        <section class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="height: 80px;">
                <div class="container">
                    <!-- <img class="img-fluid logo text-center" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;"> -->
                    <a href=""> <h1 class="navbar-brand fs-1">Accepted Students</h1></a>
                    <!-- <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navmenu">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mx-2">
                                <a href="admin_logout.php" class="btn btn-danger text-light button">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <hr style="margin-bottom: 0; margin-top: 0;">
        </section>

        <!-- ACCEPTED STUDENT -->
        <section class="container-fluid content active" id="total-accepted">
            <div id="accepted-student" class="container p-3">
                <div class="container">
                    <?php
                        require_once '../db_config.php';

                        // Capture search and filter inputs
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $selected_program = isset($_GET['program']) ? $_GET['program'] : '';
                        $selected_department = isset($_GET['department']) ? $_GET['department'] : '';

                        // Fetch distinct programs
                        $programs_query = mysqli_query($conn, "SELECT DISTINCT program FROM student_table");
                        $programs = [];
                        while ($row = mysqli_fetch_assoc($programs_query)) {
                            $programs[] = $row['program'];
                        }

                        // Fetch distinct departments
                        $departments_query = mysqli_query($conn, "SELECT DISTINCT department FROM student_table");
                        $departments = [];
                        while ($row = mysqli_fetch_assoc($departments_query)) {
                            $departments[] = $row['department'];
                        }

                        // Build the SQL query based on search, program, and department
                        $where_clauses = [];

                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where_clauses[] = "(first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR contact_num LIKE '%$search%')";
                        }

                        if (!empty($selected_program)) {
                            $where_clauses[] = "program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                        }

                        if (!empty($selected_department)) {
                            $where_clauses[] = "department = '" . mysqli_real_escape_string($conn, $selected_department) . "'";
                        }

                        $where_query = '';
                        if (count($where_clauses) > 0) {
                            $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                        }
                    ?>

                    <form class="d-flex" method="GET">
                        <input class="form-control me-2 w-50" type="search" name="search" placeholder="Search for Name or Email" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="department" class="form-select w-25 me-3">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= htmlspecialchars($department) ?>" <?= $selected_department === $department ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="program" class="form-select w-25 me-3">
                            <option value="">All Programs</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= htmlspecialchars($program) ?>" <?= $selected_program === $program ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($program) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button class="btn btn-outline-danger" type="submit">Search</button>
                    </form>
                </div>

                <div class="container p-3">
                    <?php
                        // Set the number of records per page
                        $records_per_page = 3;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['acurrent_page'])) {
                            $_SESSION['acurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks
                        // if (isset($_POST['anext'])) {
                        //     $_SESSION['acurrent_page']++;
                        // } elseif (isset($_POST['aprevious'])) {
                        //     if ($_SESSION['acurrent_page'] > 1) {
                        //         $_SESSION['acurrent_page']--;
                        //     }
                        // }

                        // Handle next and previous button clicks via GET parameters
                        if (isset($_GET['page'])) {
                            $_SESSION['acurrent_page'] = (int)$_GET['page'];
                        }


                        // Fetch the total number of accepted students based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM student_table $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_students = $total_row['total'];
                        $total_pages = ceil($total_students / $records_per_page);

                        // Fetch the students for the current page based on the filter
                        $start_from = ($_SESSION['acurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT * FROM student_table $where_query LIMIT $start_from, $records_per_page");

                        // echo "Total: $total_students";

                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover caption-top'>";
                            echo "<caption>Total: $total_students</caption>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Approved</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";

                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>
                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#view-profile-{$row['student_id']}'>View Profile</button>
                                    <div class='modal fade' id='view-profile-{$row['student_id']}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>

                                                <div class='modal-header'>
                                                    <h1 class='modal-title fs-5'>Personal Information</h1>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                
                                                <div class='modal-body'>
                                                    <div style='display: flex;'>
                                                        <div style='width: 150px; margin-right: 20px;'>
                                                            <img src='data:image/jpeg;base64," . base64_encode($row['profile_pic']) . "' alt='Profile Picture' style='width: 100%; height: 150px; border-radius: 10px; object-fit: cover;'>
                                                            <p style='text-align: center;'><b>" . $row['first_name'] . ' ' . $row['last_name'] . "</b></p>
                                                            <p style='text-align: left;'>Department: <b>" . $row['department']. "</b></p>
                                                            <p style='text-align: left;'>Program: <b>" . $row['program'] . "</b></p>
                                                        </div>
                                                        <div style='flex: 1; text-align: left;'>
                                                            <form id='updateForm' action='' method='post'>
                                                                <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                                                <div class='mb-3'>
                                                                    <label for='contact_num' class='form-label'>Contact Number</label>
                                                                    <input type='text' class='form-control' name='contact_num' value='" . htmlspecialchars($row['contact_num']) . "' required>
                                                                </div>
                                                                <div class='mb-3'>
                                                                    <label for='email' class='form-label'>Email</label>
                                                                    <input type='email' class='form-control' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                                                                </div>
                                                                <button type='submit' class='btn btn-success'>Update</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </td>";

                                echo "</tr>";
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        $student_id = $_POST['student_id'];
                                        $contact_num = $_POST['contact_num'];
                                        $email = $_POST['email'];
                                        // $program = $_POST['program'];
                                        // $department = $_POST['department'];

                                        // Retrieve the current values from the database
                                        $currentValuesStmt = $conn->prepare("SELECT contact_num, email FROM student_table WHERE student_id = ?");
                                        $currentValuesStmt->bind_param("i", $student_id);
                                        $currentValuesStmt->execute();
                                        $currentValuesStmt->bind_result($current_contact_num, $current_email);
                                        $currentValuesStmt->fetch();
                                        $currentValuesStmt->close();

                                        // Check if any of the submitted values are different from the current values
                                        $isChanged = false;

                                        if ($contact_num !== $current_contact_num) {
                                            $isChanged = true;
                                        }
                                        if ($email !== $current_email) {
                                            $isChanged = true;
                                        }
                                        // if ($program !== $current_program) {
                                        //     $isChanged = true;
                                        // }
                                        // if ($department !== $current_department) {
                                        //     $isChanged = true;
                                        // }

                                        if ($isChanged) {
                                            // Only check for email uniqueness if the email has changed
                                            if ($email !== $current_email) {
                                                // Check if the email is unique in both tables
                                                $emailCheckStmt = $conn->prepare("SELECT email FROM verification_table WHERE email = ?");
                                                $emailCheckStmt->bind_param("s", $email);
                                                $emailCheckStmt->execute();
                                                $emailCheckStmt->store_result();

                                                $emailCheckStmt2 = $conn->prepare("SELECT email FROM student_table WHERE email = ?");
                                                $emailCheckStmt2->bind_param("s", $email);
                                                $emailCheckStmt2->execute();
                                                $emailCheckStmt2->store_result();

                                                // If the email exists in either table, show an alert
                                                if ($emailCheckStmt->num_rows > 0 || $emailCheckStmt2->num_rows > 0) {
                                                    // echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='accepted_student.php';</script>";
                                                    echo "<script>window.location.href='accepted_student.php?alert=danger&message=" . urlencode('ERROR: Email already exist!') . "';</script>";
                                                    exit;
                                                }
                                            }

                                            // Prepare and execute the update query
                                            $updateStmt = $conn->prepare("UPDATE student_table SET contact_num = ?, email = ?, program = ?, department = ? WHERE student_id = ?");
                                            $updateStmt->bind_param("ssssi", $contact_num, $email, $program, $department, $student_id);

                                            if ($updateStmt->execute()) {
                                                echo "<script>window.location.href = 'accepted_student.php?alert=success';</script>";
                                            } else {
                                                echo "<script>window.location.href = 'accepted_student.php?alert=danger&message=" . urlencode('ERROR: Student not updated!') . "';</script>";
                                            }
                                            
                                            $updateStmt->close();
                                        } else {
                                            // If no changes, redirect without alert
                                            echo "<script>window.location.href = 'accepted_student.php';</script>";
                                        }
                                    }

                            }

                            if (mysqli_num_rows($query) === 0) {
                                echo "<tr><td colspan='4'>No records found</td></tr>";
                            }

                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";

                            // Display pagination buttons
                            echo "<div class='pagination-buttons'>";
                            echo "<form action='' method='get'>";
                            // Build the filter parameters for pagination links
                            $filter_params = '';

                            // Add search parameter
                            if (!empty($search)) {
                                $filter_params .= '&search=' . urlencode($search);
                            }
                            // Add filter parameter
                            if (!empty($selected_program)) {
                                $filter_params .= '&program=' . urlencode($selected_program);
                            }
                            // Add filter parameter
                            if (!empty($selected_department)) {
                                $filter_params .= '&department=' . urlencode($selected_department);
                            }

                            if ($_SESSION['acurrent_page'] > 1) {
                                echo "<a href='?page=" . ($_SESSION['acurrent_page'] - 1) . "$filter_params' class='btn btn-danger' style='width: 50px;'>&lt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                            }
                            echo "<span> Page " . $_SESSION['acurrent_page'] . " of $total_pages </span>";
                            if ($total_students > $records_per_page && $_SESSION['acurrent_page'] < $total_pages) {
                                echo "<a href='?page=" . ($_SESSION['acurrent_page'] + 1) . "$filter_params' class='btn btn-danger' style='width: 50px;'>&gt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&gt;</button>";
                            }
                            echo "</form>";
                            echo "</div>";


                        } else {
                            // echo "Error fetching data: " . mysqli_error($conn);
                            $_SESSION['alert'] = ['message' => 'Error fetching data: ' . mysqli_error($conn), 'type' => 'danger'];
                        }
                    ?>
                    <script>
                        function confirmDelete() {
                            return confirm("Are you sure you want to delete?");
                        }
                    </script>
                </div>
                
                <div id="liveAlertPlaceholder"></div>

                <script>
                    const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

                    const appendAlert = (message, type) => {
                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = [
                            `<div class="alert alert-${type} alert-dismissible mt-4" role="alert">`,
                            `   <div>${message}</div>`,
                            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                            '</div>'
                        ].join('');

                        alertPlaceholder.append(wrapper);
                    }

                        // Check for alert in query parameters
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('alert')) {
                            const alertType = urlParams.get('alert');
                            const message = alertType === 'success' ? 'Student updated!' : decodeURIComponent(urlParams.get('message'));
                            appendAlert(message, alertType);
                            
                            // Clear the alert parameter from the URL
                            urlParams.delete('alert');
                            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
                        }
                </script>

            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/loginValidate.js"></script>
    
    <script>
        function viewPDF(data, filename) {
            const byteCharacters = atob(data);
            const byteNumbers = new Uint8Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const blob = new Blob([byteNumbers], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            
            const newWindow = window.open(url, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,status=yes');
            if (newWindow) {
                newWindow.document.title = 'COR_' + filename + '.pdf';
            } else {
                alert('Please allow popups for this website');
            }
        }
    </script>
    
    <!-- <script>
        const links = document.querySelectorAll('#sidebar a');
        const contents = document.querySelectorAll('.content');

        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.target.getAttribute('data-target');

                // Hide all content sections
                contents.forEach(content => {
                    content.classList.remove('active');
                });

                // Show the target content section
                document.getElementById(target).classList.add('active');
            });
        });
    </script> -->
</body>
</html>