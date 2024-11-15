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
    #total-borrowed {
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
                <a href="total_register.php" class="dashboard-link">
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
                <a href="#" class="dashboard-link" id="active">
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
                    // Get the height of the offcanvas body
                    const offcanvasBody = document.getElementById('sidebar');
                    const offcanvasBodyHeight = offcanvasBody.clientHeight;
                    
                    // Get the position of the active link relative to the top of the offcanvas
                    const activeLinkRect = activeLink.getBoundingClientRect();
                    const activeLinkTop = activeLinkRect.top + window.scrollY;

                    // Calculate the desired scroll position to center the active link
                    const desiredScrollPosition = activeLinkTop - (offcanvasBodyHeight / 2) + (activeLinkRect.height / 2);

                    // Smoothly scroll to the calculated position
                    offcanvasBody.scrollTo({ top: desiredScrollPosition, behavior: 'smooth' });
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
                    <a href="">
                        <h1 class="navbar-brand fs-1">
                            <span style="color: #dd2222; font-weight: bold;">Borrowed</span> Books
                        </h1>
                    </a>
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

        <!-- BORROWED BOOKS -->
        <section class="container-fluid content active" id="total-borrowed">
             <div id="total-borrowed-books" class="container p-3">
                <div class="container">
                    <?php
                        require_once '../db_config.php';

                        // Capture search and filter inputs
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $selected_program = isset($_GET['program']) ? $_GET['program'] : '';
                        $selected_department = isset($_GET['department']) ? $_GET['department'] : '';
                        $borrow_date_filter = isset($_GET['borrow_date_filter']) ? $_GET['borrow_date_filter'] : '';
                        $due_date_filter = isset($_GET['due_date_filter']) ? $_GET['due_date_filter'] : '';
                        $selected_status = isset($_GET['status']) ? $_GET['status'] : '';

                        // Fetch distinct programs
                        $programs_query = mysqli_query($conn, "SELECT DISTINCT s.program
                                        FROM borrow_table AS br
                                        INNER JOIN student_table AS s ON br.student_id = s.student_id
                                        INNER JOIN book_table AS b ON br.book_id = b.book_id");
                        $programs = [];
                        while ($row = mysqli_fetch_assoc($programs_query)) {
                            $programs[] = $row['program'];
                        }

                        // Fetch distinct departments
                        $departments_query = mysqli_query($conn, "SELECT DISTINCT s.department
                                            FROM borrow_table AS br
                                            INNER JOIN student_table AS s ON br.student_id = s.student_id
                                            INNER JOIN book_table AS b ON br.book_id = b.book_id");
                        $departments = [];
                        while ($row = mysqli_fetch_assoc($departments_query)) {
                            $departments[] = $row['department'];
                        }

                        // Build the SQL query based on search, program, and department
                        $where_clauses = [];

                        // Ensure to use the correct table alias for the search fields
                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where_clauses[] = "(s.first_name LIKE '%$search%' OR s.last_name LIKE '%$search%' OR s.email LIKE '%$search%' or b.title LIKE '%$search%')";
                        }

                        if (!empty($selected_program)) {
                            $selected_program = mysqli_real_escape_string($conn, $selected_program);
                            $where_clauses[] = "s.program = '$selected_program'"; // Use alias 's' for student_table
                        }

                        if (!empty($selected_department)) {
                            $selected_department = mysqli_real_escape_string($conn, $selected_department);
                            $where_clauses[] = "s.department = '$selected_department'"; // Use alias 's' for student_table
                        }

                        if (!empty($selected_status)) {
                            $where_clauses[] = "br.status = '" . mysqli_real_escape_string($conn, $selected_status) . "'";
                        }
                        
                        // Borrow date filter
                        // if (!empty($borrow_date_filter)) {
                        //     $today = date('Y-m-d');
                        //     $start_of_week = date('Y-m-d', strtotime('monday this week'));
                        //     $end_of_week = date('Y-m-d', strtotime('sunday this week'));
                        //     $start_of_month = date('Y-m-01');
                        //     $end_of_month = date('Y-m-t');
                        //     $yesterday = date('Y-m-d', strtotime('-1 day'));
                        //     $start_of_past_week = date('Y-m-d', strtotime('last monday'));
                        //     $end_of_past_week = date('Y-m-d', strtotime('last sunday'));
                        //     $start_of_past_month = date('Y-m-d', strtotime('first day of last month'));
                        //     $end_of_past_month = date('Y-m-d', strtotime('last day of last month'));

                        //     switch ($borrow_date_filter) {
                        //         case 'today':
                        //             $where_clauses[] = "br.date_borrowed = '$today'";
                        //             break;
                        //         case 'this_week':
                        //             $where_clauses[] = "br.date_borrowed BETWEEN '$start_of_week' AND '$end_of_week'";
                        //             break;
                        //         case 'this_month':
                        //             $where_clauses[] = "br.date_borrowed BETWEEN '$start_of_month' AND '$end_of_month'";
                        //             break;
                        //         case 'past_day':
                        //             $where_clauses[] = "br.date_borrowed = '$yesterday'";
                        //             break;
                        //         case 'past_week':
                        //             $where_clauses[] = "br.date_borrowed BETWEEN '$start_of_past_week' AND '$end_of_past_week'";
                        //             break;
                        //         case 'past_month':
                        //             $where_clauses[] = "br.date_borrowed BETWEEN '$start_of_past_month' AND '$end_of_past_month'";
                        //             break;
                        //     }
                        // }

                        // Due date filter
                        // if (!empty($due_date_filter)) {
                        //     $today = date('Y-m-d');
                        //     $start_of_week = date('Y-m-d', strtotime('monday this week'));
                        //     $end_of_week = date('Y-m-d', strtotime('sunday this week'));
                        //     $start_of_month = date('Y-m-01');
                        //     $end_of_month = date('Y-m-t');
                        //     $start_of_next_month = date('Y-m-d', strtotime('first day of next month'));
                        //     $end_of_next_month = date('Y-m-d', strtotime('last day of next month'));

                        //     switch ($due_date_filter) {
                        //         case 'today':
                        //             $where_clauses[] = "br.due_date = '$today'";
                        //             break;
                        //         case 'this_week':
                        //             $where_clauses[] = "br.due_date BETWEEN '$start_of_week' AND '$end_of_week'";
                        //             break;
                        //         case 'this_month':
                        //             $where_clauses[] = "br.due_date BETWEEN '$start_of_month' AND '$end_of_month'";
                        //             break;
                        //         case 'next_month':
                        //             $where_clauses[] = "br.due_date BETWEEN '$start_of_next_month' AND '$end_of_next_month'";
                        //             break;
                        //         // New case for Past Due Dates
                        //         case 'past_due':
                        //             $where_clauses[] = "br.due_date < '$today' AND br.status != 'Returned'";
                        //             break;
                        //     }
                        // }
                        if (!empty($due_date_filter)) {
                            $where_clauses[] = "DATE(due_date) = '$due_date_filter'"; // Filter by the selected due date
                        }

                        // Construct the WHERE query
                        $where_query = '';
                        if (count($where_clauses) > 0) {
                            $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                        }
                    ?>

                    <form class="d-flex" method="GET">
                        <input class="form-control me-2 w-75" type="search" name="search" placeholder="Search for Name, Email, or Title" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="program" class="form-select w-25" style="width: fit-content;">
                            <option value="">All Programs</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= htmlspecialchars($program) ?>" <?= $selected_program === $program ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($program) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="department" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= htmlspecialchars($department) ?>" <?= $selected_department === $department ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- <select name="borrow_date_filter" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">All Borrow Dates</option>
                            <option value="today" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="this_week" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'this_week' ? 'selected' : '' ?>>This Week</option>
                            <option value="this_month" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'this_month' ? 'selected' : '' ?>>This Month</option>
                            <option value="past_day" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'past_day' ? 'selected' : '' ?>>Past Day</option>
                            <option value="past_week" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'past_week' ? 'selected' : '' ?>>Past Week</option>
                            <option value="past_month" <?= isset($_GET['borrow_date_filter']) && $_GET['borrow_date_filter'] === 'past_month' ? 'selected' : '' ?>>Past Month</option>
                        </select> -->

                        <select name="status" class="form-select w-25 me-2">
                            <option value="">All Status</option>
                            <option value="active" <?= $selected_status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="returned" <?= $selected_status === 'returned' ? 'selected' : '' ?>>Returned</option>
                        </select>

                        <!-- <select name="due_date_filter" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">All Due Dates</option>
                            <option value="past_due" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'past_due' ? 'selected' : '' ?>>Past Due Dates</option>
                            <option value="today" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="this_week" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'this_week' ? 'selected' : '' ?>>This Week</option>
                            <option value="this_month" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'this_month' ? 'selected' : '' ?>>This Month</option>
                            <option value="next_month" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'next_month' ? 'selected' : '' ?>>Next Month</option>
                        </select> -->

                        <!-- Date Picker for selecting specific due date -->
                        <input type="date" name="due_date_filter" class="form-control w-25 me-2" value="<?= isset($_GET['due_date_filter']) ? htmlspecialchars($_GET['due_date_filter']) : '' ?>" />

                        <button class="btn btn-outline-danger" type="submit">Search</button>
                    </form>

                </div>

                <div class="container p-3">
                    <?php
                        // Set the number of records per page
                        $records_per_page = 5;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['bbcurrent_page'])) {
                            $_SESSION['bbcurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks
                        // if (isset($_POST['bbnext'])) {
                        //     $_SESSION['bbcurrent_page']++;
                        // } elseif (isset($_POST['bbprevious'])) {
                        //     if ($_SESSION['bbcurrent_page'] > 1) {
                        //         $_SESSION['bbcurrent_page']--;
                        //     }
                        // }

                        if (isset($_SESSION['alert2'])) {
                            echo '<script>
                                Swal.fire({
                                    title: "Notification",
                                    text: "' . $_SESSION['alert2'] . '",
                                    icon: "info", // You can change this to success, error, warning, etc.
                                    confirmButtonText: "Okay",
                                    confirmButtonColor: "#198754",
                                });
                            </script>';
                            unset($_SESSION['alert2']); // Clear the alert after displaying
                        }

                        // Handle next and previous button clicks via GET parameters
                        if (isset($_GET['page'])) {
                            $_SESSION['bbcurrent_page'] = (int)$_GET['page'];
                        }

                        // Fetch the total number of borrowed books based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total 
                                                            FROM borrow_table AS br
                                                            INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                            INNER JOIN book_table AS b ON br.book_id = b.book_id
                                                            $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_borrowed = $total_row['total'];
                        $total_pages = ceil($total_borrowed / $records_per_page);

                        // Fetch the borrowed books with their details
                        $start_from = ($_SESSION['bbcurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT s.student_id, s.first_name, s.last_name, s.email, s.contact_num, s.program, s.department, s.profile_pic,
                                                        b.title, br.borrow_id, br.status, br.date_borrowed, br.due_date, br.penalty, br.is_renewed
                                                        FROM borrow_table AS br
                                                        INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                        INNER JOIN book_table AS b ON br.book_id = b.book_id
                                                        $where_query ORDER BY br.penalty DESC
                                                        LIMIT $start_from, $records_per_page");

                        // echo "Total: $total_borrowed";

                        // Check if the query was successful
                        if ($query) {
                            $total_penalty = 0;

                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover caption-top'>";
                            echo "<caption>Total: $total_borrowed</caption>";
                            echo "<tr>";
                            echo "<th>Name</th>";
                            echo "<th>Profile</th>";
                            echo "<th>Book Title</th>";
                            echo "<th>Status</th>";
                            echo "<th>Date Borrowed</th>";
                            echo "<th>Due Date</th>";
                            echo "<th>Penalty</th>";
                            echo "<th>Renewal</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";

                            while ($row = mysqli_fetch_assoc($query)) {
                                $total_penalty += $row['penalty'];

                                echo "<tr>";
                                echo "<td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>";
                                
                                echo "<td>
                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#view-profile-{$row['student_id']}'>View Profile</button>
                                    <div class='modal fade' id='view-profile-{$row['student_id']}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                                <div class='modal-body'>
                                                    <div style='float: left; width: 150px; margin-right: 20px;'>
                                                        <img src='data:image/jpeg;base64," . base64_encode($row['profile_pic']) . "' alt='Profile Picture' style='width: 100%; height: 150px; border-radius: 10px; object-fit: cover;'>
                                                        <p style='text-align: center;'><b>" . $row['first_name'] . ' ' . $row['last_name'] . "</b></p>
                                                    </div>
                                                    <div style='flex: 1; float: left;'>
                                                        <h2>Student Profile</h2>
                                                        <p>Student ID: " . $row['student_id'] . "</p>
                                                        <p>Contact Number: " . $row['contact_num'] . "</p>
                                                        <p>Email: " . $row['email'] . "</p>
                                                        <p>Program: " . $row['program'] . "</p>
                                                        <p>Department: " . $row['department'] . "</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </td>";

                                echo "<td>" . $row['title'] . "</td>";
                                
                                // Dropdown for status
                                echo "<td>
                                    <form action='update_status.php' method='post'>
                                        <input type='hidden' name='borrow_id' value='" . $row['borrow_id'] . "'>
                                        <select class='form-select' name='status' onchange='this.form.submit()'>
                                            <option value='Active'" . ($row['status'] == 'Active' ? ' selected' : '') . ">Active</option>
                                            <option value='Returned'" . ($row['status'] == 'Returned' ? ' selected' : '') . ">Returned</option>
                                        </select>
                                    </form>
                                </td>";

                                echo "<td>" . $row['date_borrowed'] . "</td>";
                                echo "<td>" . $row['due_date'] . "</td>";
                                echo "<td>" . $row['penalty'] . "</td>";
                                echo "<td>";
                                if ($row['status'] == 'Active') {
                                    echo "<form action='renew_due_date.php' method='post' onsubmit='return disableButton(this)'>";
                                    echo "<input type='hidden' name='borrow_id' value='" . $row['borrow_id'] . "'>";
                                    echo "<input type='hidden' name='current_due_date' value='" . $row['due_date'] . "'>";
                                    
                                    // Check if the item has already been renewed
                                    if ($row['is_renewed']) {
                                        echo "<button class='btn btn-success' disabled>Renewed</button>";
                                    } else {
                                        echo "<button type='submit' class='btn btn-success'>Renew</button>";
                                    }
                                    echo "</form>";
                                } else {
                                    echo "<button class='btn btn-success' disabled>Renew</button>";
                                }

                                echo "
                                <script>
                                    function disableButton(form) {
                                        const button = form.querySelector('button');
                                        button.textContent = 'Renewed'; // Change button text
                                        button.disabled = true; // Disable the button
                                        alert('Due date will be renewed!'); // Show alert
                                        return true; // Allow form submission
                                    }
                                </script>";

                                echo "</td>";

                                // echo "</td>";
                                echo "</tr>";
                            }
                                // Display totals
                                echo "<tr>";
                                echo "<td colspan='6' class='text-center'><b>Total</b></td>";
                                echo "<td class='text-danger'><b>" . $total_penalty . "</b></td>";
                                echo "</tr>";

                            if (mysqli_num_rows($query) === 0) {
                                echo "<td colspan='11'>No records found</td>"; 
                            }
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";

                            // Display pagination buttons
                            echo "<div class='pagination-buttons'>";
                            echo "<form action='' method='get'>"; // Change to GET method

                            // Include search and status in the pagination links
                            $filter_params = '';
                            if (!empty($search)) {
                                $filter_params .= '&search=' . urlencode($search);
                            }
                            if (!empty($selected_program)) {
                                $filter_params .= '&program=' . urlencode($selected_program);
                            }
                            if (!empty($selected_department)) {
                                $filter_params .= '&department=' . urlencode($selected_department);
                            }
                            if (!empty($selected_status)) {
                                $filter_params .= '&status=' . urlencode($selected_status);
                            }     
                            if (!empty($borrow_date_filter)) {
                                $filter_params .= '&borrow_date_filter=' . urlencode($borrow_date_filter);
                            }                            
                            if (!empty($due_date_filter)) {
                                $filter_params .= '&due_date_filter=' . urlencode($due_date_filter);
                            }        

                            // Pagination buttons
                            if ($_SESSION['bbcurrent_page'] > 1) {
                                echo "<a href='?page=" . ($_SESSION['bbcurrent_page'] - 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&lt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                            }
                            echo "<span> Page " . $_SESSION['bbcurrent_page'] . " of $total_pages </span>";
                            if ($total_borrowed > $records_per_page && $_SESSION['bbcurrent_page'] < $total_pages) {
                                echo "<a href='?page=" . ($_SESSION['bbcurrent_page'] + 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&gt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&gt;</button>";
                            }
                            echo "</form>";
                            echo "</div>";
                            echo "</form>";
                            echo "</div>";

                        } else {
                            //  echo "Error fetching data: " . mysqli_error($conn);
                            $_SESSION['alert'] = ['message' => 'Error fetching data: ' . mysqli_error($conn), 'type' => 'danger'];
                        }
                    ?>

                <div id="liveAlertPlaceholder"></div>

                <script>
                    const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

                    const appendAlert = (message, type) => {
                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = [
                            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                            `   <div>${message}</div>`,
                            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                            '</div>'
                        ].join('');

                        alertPlaceholder.append(wrapper);
                        
                        // Add an event listener for the close button
                        const closeButton = wrapper.querySelector('.btn-close');
                        // closeButton.addEventListener('click', () => {
                        //     // Optionally set a timeout before reloading
                        //     window.location.reload(); // Reload the page when the alert is closed
                        // });
                    }

                    // Check for session alert
                    <?php if (isset($_SESSION['alert'])): ?>
                        appendAlert('<?php echo addslashes($_SESSION['alert']['message']); ?>', '<?php echo $_SESSION['alert']['type']; ?>');
                        <?php unset($_SESSION['alert']); // Clear the alert after displaying ?>
                    <?php endif; ?>
                </script>
                
                </div>
                 <!-- <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button> -->
             </div>
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