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
    /* .main-content {
        display: flex; */
        /* flex-direction: column; */
        /* align-items: center;
        justify-content: center;
        min-height: 100vh; /* Full height of the viewport */
/*  } */
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
    #total-registered {
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
                <a href="#" class="dashboard-link" id="active">
                    <i class="bi bi-people-fill"></i>
                    <span>Registered</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="accepted_student.php" class="dashboard-link">
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
                    <a href=""> <h1 class="navbar-brand fs-1">Registered Students</h1></a>
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

        <!-- REGISTERED STUDENT -->
        <section class="container-fluid content active" id="total-registered">
            <div class="container p-3" id="total-register">
                 <div class="container">
                    <?php
                        require_once '../db_config.php';

                        // Capture search and filter inputs
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $selected_program = isset($_GET['program']) ? $_GET['program'] : '';
                        $selected_department = isset($_GET['department']) ? $_GET['department'] : '';

                        // Fetch distinct programs
                        $programs_query = mysqli_query($conn, "SELECT DISTINCT program FROM verification_table");
                        $programs = [];
                        while ($row = mysqli_fetch_assoc($programs_query)) {
                            $programs[] = $row['program'];
                        }

                        // Fetch distinct departments
                        $departments_query = mysqli_query($conn, "SELECT DISTINCT department FROM verification_table");
                        $departments = [];
                        while ($row = mysqli_fetch_assoc($departments_query)) {
                            $departments[] = $row['department'];
                        }

                        // Build the SQL query based on search, program, and department
                        $where_clauses = [];

                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where_clauses[] = "(first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%')";
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
                        <input class="form-control me-2 w-50 search" type="search" name="search" placeholder="Search for Name or Email" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="program" class="form-select w-25 me-3">
                            <option value="">All Programs</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= htmlspecialchars($program) ?>" <?= $selected_program === $program ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($program) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="department" class="form-select w-25 me-3">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= htmlspecialchars($department) ?>" <?= $selected_department === $department ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department) ?>
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
                        if (!isset($_SESSION['current_page'])) {
                            $_SESSION['current_page'] = 1;
                        }

                        // Handle next and previous button clicks via GET parameters
                        if (isset($_GET['page'])) {
                            $_SESSION['current_page'] = (int)$_GET['page'];
                        }

                        // Fetch the total number of registered students based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_table $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_students = $total_row['total'];
                        $total_pages = ceil($total_students / $records_per_page);

                        // Fetch the students for the current page based on the filter
                        $start_from = ($_SESSION['current_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT * FROM verification_table $where_query LIMIT $start_from, $records_per_page");

                        // Debugging: Output the SQL query and total students
                        // echo "SQL Query: SELECT * FROM verification_table $where_query LIMIT $start_from, $records_per_page";
                        // echo "Total: $total_students";

                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover caption-top'>";
                            echo "<caption>Total: $total_students</caption>";
                            echo "<tr>";
                            echo "<th scope='col'>ID</th>";
                            echo "<th scope='col'>Name</th>";
                            echo "<th scope='col'>Contact Number</th>";
                            echo "<th scope='col'>Email</th>";
                            echo "<th scope='col'>Program</th>";
                            echo "<th scope='col'>Department</th>";
                            echo "<th scope='col'>COR</th>";
                            echo "<th scope='col'>ID</th>";
                            echo "<th scope='col'>Approve</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";

                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td scope='row'>" . $row['student_id'] . "</td>";
                                echo "<td scope='row'>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>";
                                echo "<td scope='row'>" . $row['contact_num'] . "</td>";
                                echo "<td scope='row'>" . $row['email'] . "</td>";
                                echo "<td scope='row'>" . $row['program'] . "</td>";
                                echo "<td scope='row'>" . $row['department'] . "</td>";

                                // COR and ID links
                                echo '<td>' . ($row['cor_filetype'] === 'pdf' ? '<a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['cor']). '\', \''. htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES) .'\')">View COR</a>' : '') . '</td>';
                                echo '<td>' . ($row['id_filetype'] === 'pdf' ? '<a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['id_file']). '\', \''. htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES) .'\')">View ID</a>' : '') . '</td>';

                                echo "<td>
                                    <form action='' method='post' onsubmit='confirmDelete(event);'>
                                        <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                        <input type='hidden' name='firstName' value='" . $row['first_name'] . "'>
                                        <input type='hidden' name='lastName' value='" . $row['last_name'] . "'>
                                        <input type='hidden' name='contact' value='" . $row['contact_num'] . "'>
                                        <input type='hidden' name='email' value='" . $row['email'] . "'>
                                        <input type='hidden' name='password' value='" . $row['password'] . "'>
                                        <input type='hidden' name='program' value='" . $row['program'] . "'>
                                        <input type='hidden' name='department' value='" . $row['department'] . "'>
                                        <input type='hidden' name='profile_pic' value='" . (isset($row['profile_pic']) ? base64_encode($row['profile_pic']) : '') . "'>
                                        <input type='hidden' name='pic_filetype' value='" . (isset($row['pic_filetype']) ? $row['pic_filetype'] : '') . "'>
                                        
                                        <!-- Hidden input to store the approve value (yes or no) -->
                                        <input type='hidden' name='approve' value=''>
                                        
                                        <button type='submit' value='yes' class='btn btn-success'>Yes</button>
                                        <button type='submit' value='no' class='btn btn-danger mt-sm-1'>No</button>
                                    </form>
                                </td>";

                                echo "</tr>";

                                // Process form submission
                                if (isset($_POST['approve']) && $_POST['student_id'] == $row['student_id']) {
                                    $student_id = $_POST['student_id'];
                                    $firstName = $_POST['firstName'];
                                    $lastName = $_POST['lastName'];
                                    $contact = $_POST['contact'];
                                    $email = $_POST['email'];
                                    $password = $_POST['password'];
                                    $program = $_POST['program'];
                                    $department = $_POST['department'];
                                    $profile_pic = $_POST['profile_pic']; // Base64 encoded
                                    $pic_filetype = $_POST['pic_filetype'];

                                    if ($_POST['approve'] == 'yes') {
                                        // Check if the student_id already exists in the student_table
                                        $check_query = "SELECT * FROM student_table WHERE student_id = '$student_id'";
                                        $check_result = mysqli_query($conn, $check_query);
                                        if (mysqli_num_rows($check_result) == 0) {
                                            // Insert the details into student_table
                                            $query2 = "INSERT INTO student_table (student_id, first_name, last_name, contact_num, email, password, program, department, profile_pic, pic_filetype, status)
                                                    VALUES ('$student_id', '$firstName', '$lastName', '$contact', '$email', '$password', '$program', '$department', '" . mysqli_real_escape_string($conn, base64_decode($profile_pic)) . "', '" . mysqli_real_escape_string($conn, $pic_filetype) . "', 'Enabled')";

                                            if (mysqli_query($conn, $query2)) {
                                                // Delete from verification_table
                                                $delete_query = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                                mysqli_query($conn, $delete_query);
                                                // echo "<script>alert(Student approved!'); window.location.href = 'total_register.php';</script>";
                                                // header("Location: total_register.php");
                                                // exit;
                                                // $_SESSION['alert'] = ['message' => 'Student approved!', 'type' => 'success'];
                                                echo "<script>window.location.href = 'total_register.php?alert=success';</script>";
                                            } else {
                                                //  $_SESSION['alert'] = ['message' => 'ERROR: Student not approved!', 'type' => 'danger'];
                                                // echo "<script>alert('Error: Student not approved!'); window.location.href = 'total_register.php';</script>";
                                                echo "<script>window.location.href = 'total_register.php?alert=danger';</script>";
                                            }
                                            exit;
                                        }
                                    } elseif ($_POST['approve'] == 'no') {
                                        // Delete from verification_table
                                        $delete_query2 = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                        if(mysqli_query($conn, $delete_query2)){
                                            //  $_SESSION['alert'] = ['message' => 'Student not approved!', 'type' => 'danger'];
                                            // echo "<script>alert('Student not approved!'); window.location.href = 'total_register.php';</script>";
                                            // header("Location: total_register.php");
                                            // exit;
                                            echo "<script>window.location.href = 'total_register.php?alert=danger';</script>";
                                        }  else {
                                            //  $_SESSION['alert'] = ['message' => 'ERROR: Student not approved!', 'type' => 'danger'];
                                            // echo "<script>alert('Error: Student not approved!'); window.location.href = 'total_register.php';</script>";
                                            echo "<script>window.location.href = 'total_register.php?alert=danger';</script>";
                                        }
                                        exit;
                                    }
                                }
                            }

                            if (mysqli_num_rows($query) === 0) {
                                echo "<tr><td colspan='10'>No records found</td></tr>";
                            }

                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";

                            // Display pagination buttons
                            echo "<div class='pagination-buttons'>";
                            echo "<form action='' method='post'>";
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

                            if ($_SESSION['current_page'] > 1) {
                                echo "<a href='?page=" . ($_SESSION['current_page'] - 1) . "$filter_params' class='btn btn-danger' style='width: 50px;'>&lt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                            }
                            echo "<span> Page " . $_SESSION['current_page'] . " of $total_pages </span>";
                            if ($total_students > $records_per_page && $_SESSION['current_page'] < $total_pages) {
                                echo "<a href='?page=" . ($_SESSION['current_page'] + 1) . "$filter_params' class='btn btn-danger' style='width: 50px;'>&gt;</a>";
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
                        function confirmDelete(event) {
                            event.preventDefault();  // Prevent the form from submitting immediately

                            // Get the value of the button that was clicked (yes or no)
                            const approveValue = event.target.querySelector('button[type="submit"]:focus').value;

                            // Show SweetAlert confirmation dialog
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Do you really want to continue?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes!',
                                confirmButtonColor: '#198754',
                                cancelButtonText: 'No',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // If confirmed, set the hidden 'approve' input value to the button's value
                                    event.target.querySelector('input[name="approve"]').value = approveValue;
                                    event.target.submit();  // Submit the form after confirmation
                                }
                            });
                        }
                    </script>

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
                                const message = alertType === 'success' ? 'Student approved!' : 'Student not approved!';
                                appendAlert(message, alertType);

                                // Clear the alert parameter from the URL
                                urlParams.delete('alert');
                                window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
                            }
                    </script>

            </div>
            <!-- </div> -->
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>

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