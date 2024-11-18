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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Include Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    *{
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
    }
    /* .navbar-brand{
        /* font-family: 'Times New Roman', Times, serif; */
        /* font-size: 30px;
        color: black;
    }
    .navbar-brand:hover{
        color: black;
        text-decoration: underline;
    } */
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
        display: flex;
        /* flex-direction: column; */
        /* align-items: center;
        justify-content: center;
        min-height: 100vh; /* Full height of the viewport */
    /* } */
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
    #home {
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
    .btn{
        border-radius: 30px !important;
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
    .card-container {
        display: flex;       
        flex-wrap: nowrap;        
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 20px;
        padding: 10px;
    }
    .card-container::-webkit-scrollbar {
        height: 8px;
    }
    .card-container::-webkit-scrollbar-thumb {
        background-color: #dd2222; 
        border-radius: 10px;
    }
    .card-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .card {
        flex-shrink: 0;
        margin-right: 20px;
        min-width: 250px;
    }
    /* Loader Styles */
    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensure it appears on top of all content */
    }

    /* Optional: Customize spinner size */
    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.25em;
    }
    /* Back to Top Button */
    #backToTop {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: none; /* Hidden by default */
        border: none;
        background-color: #dd2222;
        color: white;
        border-radius: 50%;
        padding: 15px;
        font-size: 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #backToTop:hover {
        background-color: #ca1d1d;
    }
    #mostBorrowedBooksChart, #progDept {
        height: 470px !important;
        width: 50% !important; /* Adjust width as needed */
    }
    .card-content {
        padding: 20px;
        border-radius: 15px;
        height: 245px;
    }
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .title {
        font-size: 1.2rem;
        margin: 0;
    }
    .body {
        background-color: white; /* Optional: to differentiate background color of the body */
        border-radius: 50px 10px; /* Custom radius for the main body */
        padding: 20px;
        text-align: center;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .count-text {
        margin: 0;
    }
</style>

<body>

    <!-- Loading Animation -->
    <div id="loader" class="loader-container">
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-danger" onclick="scrollToTop()">↑</button>

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
                <a href="#" class="dashboard-link" data-target="home" id="active">
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
        </script>

    </div>
    <!-- END SIDEBAR -->    

    <script>
        $(document).ready(function() {
            $('#offcanvasWithBothOptions').offcanvas('show');
        });
    </script>

    <?php
        require_once '../db_config.php';

        // Fetch the registered students from the database
        $query = mysqli_query($conn, "SELECT *FROM verification_table");
        $result = mysqli_num_rows($query);

        // Fetch the accepted students from the database
        $query2 = mysqli_query($conn, "SELECT *FROM student_table");
        $result2 = mysqli_num_rows($query2);

        // Fetch the total books from the database
        $query3 = mysqli_query($conn, "SELECT *FROM book_table");
        $result3 = mysqli_num_rows($query3);

        // Fetch the total stock of books from the database
        $query4 = mysqli_query($conn, "SELECT SUM(stocks) AS total_stocks FROM inventory_table");
        $result4 = mysqli_fetch_assoc($query4);

        if($result4['total_stocks'] == 0){
            $sum_stocks = 0;
        }
        else{
            $sum_stocks = $result4['total_stocks'];
        }

        // Fetch the students who borrows a book/s from the database
        $query5 = mysqli_query($conn, "SELECT *FROM borrow_table WHERE status = 'Active'");
        $total_borrow = mysqli_num_rows($query5);

        if(isset($_SESSION['message'])) {
            echo "<script>alert('" . $_SESSION['message'] . "')</script>";
            unset($_SESSION['message']);
        }
    ?>

    <main class="main-content">
        <section class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="height: 80px;">
                <div class="container">
                    <a href="">
                        <img class="img-fluid logo text-center" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    </a>
                    <!-- <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navmenu">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mx-2">
                                <a href="admin_logout.php" class="btn btn-danger text-light">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <hr style="margin-bottom: 0; margin-top: 0;">
        </section>

        <!-- HOME -->
        <section class="container-fluid content active mt-3 mb-2" id="home">
            <div class="container">
                <div class="card-container">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card-content" style="background-color: #F7D16A;">
                            <div class="header">
                                <p class="title text-light">Total Student Register</p>
                                <img src="../../img/register.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $result ?></b></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card-content" style="background-color: #F7946A;">
                            <div class="header">
                                <p class="title text-light">Total Student Accepted</p>
                                <img src="../../img/accepted.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $result2 ?></b></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card-content" style="background-color: #F76A6A;">
                            <div class="header">
                                <p class="title text-light">Total Books Available</p>
                                <img src="../../img/total-books.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $result3 ?></b></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card-content" style="background-color: #6ADBF7;">
                            <div class="header">
                                <p class="title text-light">Books Stocks</p>
                                <img src="../../img/stocks.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $sum_stocks ?></b></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card-content" style="background-color: #6AF78D;">
                            <div class="header">
                                <p class="title text-light">Borrowed Books</p>
                                <img src="../../img/borrowed.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $total_borrow ?></b></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card-content" style="background-color: #F76ABF;">
                            <div class="header">
                                <p class="title text-light">Borrowed Books</p>
                                <img src="../../img/total-books.png" alt="Icon" height="30" width="30">
                            </div>
                            <div class="body">
                                <h1 class="count-text"><?php echo $sum_stocks - $total_borrow ?></b></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container-fluid mt-2">
                <?php
                    // FIRST CHART
                    // Query for the total number of registered students
                    $sql_registered = "SELECT COUNT(*) AS total_registered FROM verification_table"; // Adjust the query as needed
                    $result_registered = $conn->query($sql_registered);
                    $row_registered = $result_registered->fetch_assoc();
                    $total_registered = $row_registered['total_registered'];

                    // Query for the total number of accepted students
                    $sql_accepted = "SELECT COUNT(*) AS total_accepted FROM student_table"; // Adjust the query as needed
                    $result_accepted = $conn->query($sql_accepted);
                    $row_accepted = $result_accepted->fetch_assoc();
                    $total_accepted = $row_accepted['total_accepted'];
                    
                    // SECOND CHART
                    // Fetching how many students are there in each department and program
                    $query = "SELECT department, program, COUNT(student_id) AS student_count
                            FROM student_table
                            GROUP BY department, program
                            ORDER BY department, program;";
                    $result = $conn->query($query);

                    // Create an array to hold the data for the chart
                    $chartData = [];
                    while ($row = $result->fetch_assoc()) {
                        $department_name = $row['department'];
                        $program_name = $row['program'];
                        $student_count = $row['student_count'];

                        // Add data to the array in the format required by Google Charts
                        $chartData[] = "['$department_name - $program_name', $student_count]";
                    }

                    // Convert the chart data into a format suitable for JavaScript
                    $chartDataString = implode(", ", $chartData);

                    // THIRD CHART
                    // Get a range of the last 30 days
                    $dateRange = [];
                    for ($i = 0; $i < 30; $i++) {
                        $dateRange[] = date('Y-m-d', strtotime("-$i days"));
                    }

                    // SQL query to get attendance count per day in the last 30 days
                    $sql = "SELECT DATE(date) AS date, COUNT(*) AS attendance_count
                            FROM log_table
                            WHERE date >= NOW() - INTERVAL 30 DAY
                            GROUP BY DATE(date)
                            ORDER BY DATE(date) ASC";
                    $attendanceResult = $conn->query($sql);

                    $attendanceData = [];
                    $attendanceDates = [];

                    // Fetch data
                    while ($row = $attendanceResult->fetch_assoc()) {
                        $attendanceDates[] = $row['date'];
                        $attendanceData[$row['date']] = $row['attendance_count'];
                    }

                    // Format the data for the chart
                    $attendanceDataFormatted = [];
                    foreach ($dateRange as $date) {
                        $attendanceCount = isset($attendanceData[$date]) ? $attendanceData[$date] : 0;
                        $attendanceDataFormatted[] = "['$date', $attendanceCount]";
                    }

                    // Convert to a string for Google Charts
                    $attendanceChartDataString = implode(', ', $attendanceDataFormatted);

                    // FOURTH CHART
                    // SQL query to get the top 10 students with the most borrowed books
                    $most_sql = "SELECT s.first_name, s.last_name, COUNT(*) AS total_borrowed_books
                            FROM borrow_table b
                            JOIN student_table s ON b.student_id = s.student_id
                            WHERE b.status IN ('active', 'returned')
                            GROUP BY b.student_id
                            ORDER BY total_borrowed_books DESC
                            LIMIT 10";

                    $most_result = mysqli_query($conn, $most_sql);

                    $mostBorrowedBooksData = [];
                    $studentNames = [];
                    while ($row = mysqli_fetch_assoc($most_result)) {
                        $studentName = $row['first_name'] . ' ' . $row['last_name'];
                        $borrowCount = (int)$row['total_borrowed_books'];

                        // Prepare data in format suitable for Highcharts
                        $mostBorrowedBooksData[] = $borrowCount;
                        $studentNames[] = $studentName;
                    }

                    // Convert to JavaScript-friendly format
                    $borrowedDataString = implode(", ", $mostBorrowedBooksData);
                    $namesDataString = implode(", ", array_map(function($name) { return "'$name'"; }, $studentNames));
                ?>
                <h4 class="text-muted">Students</h4>
                <!-- Row containing first four charts -->
                 <div class="stats">
                    <div class="row mb-2" class="d-flex justify-content-between h-100" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                        <!-- First Chart: Registered vs Accepted Students -->
                        <div id="myChart" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>

                        <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Highcharts.chart('myChart', {
                                        chart: {
                                            type: 'pie',
                                            options3d: {
                                                enabled: true,
                                                alpha: 45 // Control the 3D rotation angle
                                            },
                                            backgroundColor: '#F2F2F2',
                                            borderRadius: 50,
                                            spacing: [10, 10, 10, 10]
                                        },
                                        title: {
                                            text: 'Student Registration and Acceptance Status'
                                        },
                                        plotOptions: {
                                            pie: {
                                                depth: 45, // 3D effect depth
                                                dataLabels: {
                                                    enabled: true,
                                                    format: '{point.name}: {point.percentage:.1f}%' // Display percentages
                                                }
                                            }
                                        },
                                        series: [{
                                            name: 'Count',
                                            data: [
                                                ['Registered Students', <?php echo $total_registered; ?>],
                                                ['Accepted Students', <?php echo $total_accepted; ?>]
                                            ]
                                        }]
                                    });
                                });
                        </script>

                        <!-- Second Chart: Department and Program Distribution -->
                        <div id="progDept" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>
                        
                        <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Highcharts pie chart for Department and Program distribution
                                    Highcharts.chart('progDept', {
                                        chart: {
                                            type: 'pie',
                                            options3d: {
                                                enabled: true,
                                                alpha: 45 // 3D effect angle
                                            },
                                            backgroundColor: '#F2F2F2',
                                            borderRadius: 50,
                                            spacing: [10, 10, 10, 10]
                                        },
                                        title: {
                                            text: 'Student Distribution by Department and Program'
                                        },
                                        plotOptions: {
                                            pie: {
                                                depth: 45, // 3D depth
                                                dataLabels: {
                                                    enabled: true,
                                                    format: '{point.name}: {point.percentage:.1f}%' // Percentage format
                                                },
                                                slices: {
                                                    // Add some offset to slices if necessary (like in the Google Charts example)
                                                    0: { offset: 0.1 }, 
                                                    1: { offset: 0.1 }
                                                }
                                            }
                                        },
                                        series: [{
                                            name: 'Student Count',
                                            data: [
                                                <?php echo $chartDataString; ?> // PHP dynamic data for department and program distribution
                                            ]
                                        }]
                                    });
                                });
                        </script>
                    </div>

                    <div class="row" class="d-flex justify-content-between h-100" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                        <!-- Third Chart: Attendance Log -->
                        <div id="attendanceChart" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Highcharts.chart('attendanceChart', {
                                    chart: {
                                        type: 'line',
                                        backgroundColor: '#F2F2F2',
                                        borderRadius: 50,
                                        spacing: [10, 10, 10, 10]
                                    },
                                    title: {
                                        text: 'Attendance Log'
                                    },
                                    xAxis: {
                                        categories: [
                                            <?php echo implode(', ', array_map(function($date) { return "'$date'"; }, $dateRange)); ?>
                                        ],
                                        title: {
                                            text: 'Date'
                                        },
                                        labels: {
                                            rotation: -45, // Rotate the date labels to avoid overlap
                                        },            
                                        scrollbar: {
                                            enabled: true // Enable horizontal scrollbar
                                        },
                                        min: 0, // Set the starting position of the x-axis
                                        max: 29 // Default visible range for the last 30 days
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Number of Attendees'
                                        },
                                        min: 0
                                    },
                                    series: [{
                                        name: 'Attendance Count',
                                        data: [
                                            <?php echo $attendanceChartDataString; ?>
                                        ],
                                        marker: {
                                            radius: 4 // Increase size of data points for better visibility
                                        }
                                    }],
                                    plotOptions: {
                                        line: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '{y}' // Show the exact number on top of the points
                                            },
                                            enableMouseTracking: true
                                        }
                                    },
                                    tooltip: {
                                        headerFormat: '<b>{point.x}</b><br>',
                                        pointFormat: 'Attendance Count: {point.y}'
                                    }
                                });
                            });
                        </script>

                        <!-- Fourth Chart: Most Borrowed Books -->
                        <div id="mostBorrowedBooksChart" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Highcharts.chart('mostBorrowedBooksChart', {
                                    chart: {
                                        type: 'bar',
                                        backgroundColor: '#F2F2F2',
                                        borderRadius: 50,
                                        spacing: [10, 10, 10, 10]
                                    },
                                    title: {
                                        text: 'Top 10 Students with the Most Borrowed Books (Active/Returned)'
                                    },
                                    xAxis: {
                                        categories: [<?php echo $namesDataString; ?>],
                                        title: {
                                            text: 'Student Name'
                                        },
                                        labels: {
                                            rotation: -45, // Rotate labels to avoid overlap
                                            style: {
                                                fontSize: '12px'
                                            }
                                        }
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Number of Borrowed Books'
                                        },
                                        min: 0
                                    },
                                    series: [{
                                        name: 'Borrowed Books',
                                        data: [<?php echo $borrowedDataString; ?>],
                                        color: '#007bff'
                                    }],
                                    plotOptions: {
                                        bar: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '{y}'
                                            }
                                        }
                                    },
                                    tooltip: {
                                        headerFormat: '<b>{point.x}</b><br>',
                                        pointFormat: 'Borrowed Books: {point.y}'
                                    }
                                });
                            });
                        </script>
                        
                    </div>
                    <hr>
                 </div>
            </div>

            <div class="container-fluid">
                <?php
                    // FIRST CHART
                    // SQL query to get the total number of books per category
                    $cat_sql = "SELECT category, COUNT(*) AS total_books
                                FROM book_table
                                GROUP BY category
                                ORDER BY total_books DESC";
                    $cat_result = $conn->query($cat_sql);
                        // Prepare arrays to store categories and total book counts
                        $categoryData = [];
                        $categoryNames = [];

                        // Fetch data from the database
                        while ($row = $cat_result->fetch_assoc()) {
                            $category = $row['category'];
                            $total_books = $row['total_books'];

                            // Add category and book count to the arrays
                            $categoryNames[] = $category;  // Store category names
                            $categoryData[] = $total_books;  // Store total books in each category
                        }

                        // Convert arrays into JavaScript-friendly format
                        $catChartDataString = implode(", ", $categoryData);
                        $catChartCategoriesString = "'" . implode("', '", $categoryNames) . "'";

                    // SECOND CHART
                    // Initialize an array to hold the data for each book's stock
                    $bookData = [];

                    // Query to get stock data from the inventory table
                    $stock_sql = "SELECT b.title, SUM(i.stocks) as total_stocks
                                FROM inventory_table i
                                JOIN book_table b ON i.book_id = b.book_id
                                GROUP BY b.title
                                ORDER BY b.title";

                    $stock_result = $conn->query($stock_sql);

                    // Loop through the result set and prepare the data for the pie chart
                    while ($row = $stock_result->fetch_assoc()) {
                        $book_title = addslashes($row['title']); // Escape single quotes in titles
                        $total_stocks = (int)$row['total_stocks'];  // Ensure stock is treated as an integer
                        
                        // Add the book title and stock to the data array
                        $bookData[] = "['$book_title', $total_stocks]";
                    }

                    // Combine the data into a string format for use in JavaScript
                    $stockChartDataString = implode(', ', $bookData);
                ?>
                <h4 class="text-muted">Book Category and Stocks</h4>
                <!-- Row containing third two charts -->
                <div class="row" class="d-flex flex-wrap justify-content-between h-100 m-0 p-0" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                    <!-- First Chart: Book Category -->
                    <div id="categoryChart" class="col-12 col-lg-5 col-md-6" style="height: 470px;"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Highcharts.chart('categoryChart', {
                                chart: {
                                    type: 'column',
                                    backgroundColor: '#F2F2F2',
                                    borderRadius: 50,
                                    spacing: [10, 10, 10, 10] 
                                },
                                title: {
                                    text: 'Total Books by Category'  // Title of the chart
                                },
                                xAxis: {
                                    title: {
                                        text: 'Book Category'  // X-axis title (categories)
                                    },
                                    categories: [<?php echo $catChartCategoriesString; ?>],  // Categories from PHP
                                    labels: {
                                        rotation: -45,  // Rotate labels if they are long
                                        align: 'right'
                                    }
                                },
                                yAxis: {
                                    title: {
                                        text: 'Number of Books'  // Y-axis title (number of books)
                                    },
                                    min: 0  // Start Y-axis from 0
                                },
                                series: [{
                                    name: 'Books',
                                    data: [<?php echo $catChartDataString; ?>],  // Data points from PHP
                                    color: '#007bff',  // Bar color
                                    dataLabels: {
                                        enabled: true,
                                        format: '{y}'  // Display the number of books on top of the bars
                                    }
                                }],
                                tooltip: {
                                    headerFormat: '<b>{point.x}</b><br>',
                                    pointFormat: 'Number of Books: {point.y}'  // Tooltip format
                                }
                            });
                        });
                    </script>

                    <!-- Second Chart: Book Stock Chart -->
                    <div id="bookStockChart" class="col-12 col-lg-7 col-md-6" style="height: 470px;"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Highcharts.chart('bookStockChart', {
                                chart: {
                                    type: 'pie',  // Pie chart type
                                    options3d: {
                                        enabled: true,  // Enable 3D effect
                                        alpha: 45,  // Rotation angle for 3D
                                        beta: 0  // Rotation angle for 3D
                                    },
                                    backgroundColor: '#F2F2F2',
                                    borderRadius: 50,
                                    spacing: [10, 10, 10, 10]
                                },
                                title: {
                                    text: 'Total Stock per Book'  // Title of the chart
                                },
                                tooltip: {
                                    pointFormat: '{point.name}: {point.y}'  // Display the stock count on the slice
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: true,
                                            format: '{point.name}: {point.percentage:.1f}%'  // Display percentage on slices
                                        },
                                        showInLegend: true  // Display legend
                                    }
                                },
                                series: [{
                                    name: 'Stock',
                                    colorByPoint: true,  // Automatically color the slices
                                    data: [<?php echo $stockChartDataString; ?>],  // Data from PHP (book title and stock count)
                                }],
                                legend: {
                                    layout: 'vertical',  // Position legend vertically
                                    align: 'left',  // Align legend to the left
                                    verticalAlign: 'top',  // Position legend at the top
                                    x: 50,  // Adjust position if needed
                                    y: 30  // Adjust position if needed
                                }
                            });
                        });
                    </script>
                    
                </div>
                    <hr>
            </div>
            
            <div class="container-fluid">
                <?php
                    // FIRST CHART
                    // Query to get the count of how many times each book is borrowed
                    $book_query = "SELECT b.title, COUNT(br.book_id) AS borrow_count, br.status
                        FROM borrow_table br
                        JOIN book_table b ON br.book_id = b.book_id
                        WHERE br.status = 'Active'
                        GROUP BY b.title
                        ORDER BY borrow_count DESC;";

                    $book_result = $conn->query($book_query);

                    // Create an array to hold the data for the chart
                    $bookTitles = [];
                    $borrowCounts = [];

                    // Calculate total borrowed books and prepare data for the chart
                    $total_borrowed_books = 0;
                    while ($row = $book_result->fetch_assoc()) {
                        $book_title = $row['title'];  // The book title
                        $borrow_count = $row['borrow_count'];  // The number of times the book was borrowed
                        
                        // Add book titles to the categories array
                        $bookTitles[] = "'$book_title'";
                        
                        // Add borrow counts to the data array
                        $borrowCounts[] = $borrow_count;
                        
                        // Add to the total borrowed count
                        $total_borrowed_books += $borrow_count;
                    }

                    // Convert arrays into a format for JavaScript
                    $bookTitlesString = implode(", ", $bookTitles);
                    $borrowCountsString = implode(", ", $borrowCounts);

                    // SECOND CHART
                    // Query to get the count of active (borrowed) and returned books based on status
                    $active_query = "SELECT
                            COUNT(br.book_id) AS active_books
                            FROM borrow_table br
                            WHERE br.status = 'active'";  // Status is 'active' for currently borrowed books

                    $returned_query = "SELECT
                            COUNT(br.book_id) AS returned_books
                            FROM borrow_table br
                            WHERE br.status = 'returned'";  // Status is 'returned' for books that have been returned

                    $active_result = $conn->query($active_query);
                    $returned_result = $conn->query($returned_query);

                    // Get the active and returned book counts
                    $active_books = 0;
                    $returned_books = 0;

                    if ($active_result->num_rows > 0) {
                        $row = $active_result->fetch_assoc();
                        $active_books = $row['active_books'];  // Number of active books
                    }

                    if ($returned_result->num_rows > 0) {
                        $row = $returned_result->fetch_assoc();
                        $returned_books = $row['returned_books'];  // Number of returned books
                    }
                ?>
                <h4 class="text-muted">Books Borrowed</h4>
                <!-- Row containing second two charts -->
                <div class="row" class="d-flex flex-wrap justify-content-between h-100 m-0 p-0" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                    <!-- First Chart: Total Borrowed Books -->
                    <div id="bookChart" class="col-12 col-lg-5 col-md-6" style="height: 470px;"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Highcharts.chart('bookChart', {
                                chart: {
                                    type: 'column',  // Column chart for vertical bars
                                    options3d: {
                                        enabled: true,  // Enable 3D effect
                                        alpha: 45,      // Angle of 3D effect
                                        depth: 50       // Depth of the 3D bars
                                    },
                                    backgroundColor: '#F2F2F2',
                                    borderRadius: 50,
                                    spacing: [10, 10, 10, 10]
                                },
                                title: {
                                    text: 'Books Borrowed'  // Main chart title
                                },
                                subtitle: {
                                    text: 'Total Borrowed Books: ' + <?php echo $total_borrowed_books; ?>  // Display total borrowed books in the subtitle
                                },
                                xAxis: {
                                    title: {
                                        text: 'Book Title'  // Title for the x-axis
                                    },
                                    categories: [<?php echo $bookTitlesString; ?>],  // Book titles as categories
                                },
                                yAxis: {
                                    title: {
                                        text: 'Number of Borrows'  // Title for the y-axis
                                    },
                                    min: 0,  // Set minimum value for y-axis to 0
                                },
                                tooltip: {
                                    formatter: function () {
                                        return `<b>${this.point.category}</b><br>Borrow Count: ${this.point.y}`;  // Tooltip format
                                    },
                                },
                                series: [{
                                    name: 'Books Borrowed',
                                    data: [<?php echo $borrowCountsString; ?>]  // Borrow counts for each book
                                }]
                            });
                        });
                    </script>

                    <!-- Second Chart: Active and Returned Books -->
                    <div id="activeReturnedChart" class="col-12 col-lg-7 col-md-6" style="height: 470px;"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Highcharts.chart('activeReturnedChart', {
                                chart: {
                                    type: 'pie',  // Pie chart type
                                    options3d: {
                                        enabled: true,  // Enable 3D effect
                                        alpha: 45,      // Angle of the 3D effect
                                        beta: 0         // Rotation of the 3D effect
                                    },
                                    backgroundColor: '#F2F2F2',
                                    borderRadius: 50,
                                    spacing: [10, 10, 10, 10]
                                },
                                title: {
                                    text: 'Active vs Returned Books'  // Title of the chart
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.y}</b>'  // Display the actual count (point.y) in the tooltip
                                },
                                plotOptions: {
                                    pie: {
                                        innerSize: 100,  // Make the chart a donut chart
                                        depth: 45,       // Depth of the pie chart
                                        dataLabels: {
                                            enabled: true,
                                            format: '{point.name}: {point.percentage:.1f}%'  // Display percentage
                                        }
                                    }
                                },
                                series: [{
                                    name: 'Books',
                                    data: [
                                        ['Active Books', <?php echo $active_books; ?>],
                                        ['Returned Books', <?php echo $returned_books; ?>]
                                    ],
                                    colors: ['#FF5722', '#4CAF50']  // Color for active (red) and returned (green) books
                                }]
                            });
                        });
                    </script>

                </div>
                    <hr>
            </div>

        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    
    <script>
        // When the page is fully loaded, hide the loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            loader.style.display = 'none'; // Hide the loader
        });

        // Get the button
        let backToTopBtn = document.getElementById("backToTop");

        // When the user scrolls down 300px from the top, show the button
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTopBtn.style.display = "block"; // Show the button
            } else {
                backToTopBtn.style.display = "none"; // Hide the button
            }
        };

        // Function to scroll to the top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Smooth scrolling
            });
        }
    </script>
    
    <!-- <script>
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
    </script> -->
    
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