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
        $query5 = mysqli_query($conn, "SELECT *FROM borrow_table");
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
        <section class="container-fluid content active mt-3" id="home">
            <div class="container">
                <div class="card-container"> <!-- Add this container to allow horizontal scrolling -->
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Student Register</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result ?></b></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Student Accepted</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result2 ?></b></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Books Available</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result3 ?></b></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Stocks of Book</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks ?></b></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Borrowed Book</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $total_borrow ?></b></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Available Stocks</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks - $total_borrow ?></b></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <?php
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

                // NEWWWWWWW

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

                // NEWWWWWWW

                // Query to get the count of how many times each book is borrowed
                $book_query = "SELECT b.title, COUNT(br.book_id) AS borrow_count
                    FROM borrow_table br
                    JOIN book_table b ON br.book_id = b.book_id
                    GROUP BY b.title
                    ORDER BY borrow_count DESC;";

                $book_result = $conn->query($book_query);

                // Create an array to hold the data for the chart
                $bookChartData = [];
                $total_borrowed_books = 0;  // Initialize a variable to keep track of total borrowed books
                while ($row = $book_result->fetch_assoc()) {
                    $book_title = $row['title'];  // The book title
                    $borrow_count = $row['borrow_count'];  // The number of times the book was borrowed
                    
                    // Add the data for the chart (book title and borrow count)
                    $bookChartData[] = "['$book_title', $borrow_count]";
                    
                    // Add to the total borrowed count
                    $total_borrowed_books += $borrow_count;
                }

                // Convert the chart data into a format that JavaScript can use
                $bookChartDataString = implode(", ", $bookChartData);

                // NEWWWWWWW

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

                // NEWWWWWWW

                // SQL query to get the total number of books per category
                $cat_sql = "SELECT category, COUNT(*) AS total_books
                            FROM book_table
                            GROUP BY category
                            ORDER BY total_books DESC";
                $cat_result = $conn->query($cat_sql);

                $categoryData = [];
                while ($row = $cat_result->fetch_assoc()) {
                    $category = $row['category'];
                    $total_books = $row['total_books'];
                    $categoryData[] = "['$category', $total_books]"; // Format data for Google Charts
                }

                // Combine the data into a single string for use in the JavaScript
                $catChartDataString = implode(', ', $categoryData);

                // NEWWWWWWW

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

                // NEWWWWWWW

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

            <div class="container-fluid mt-2">
                <h4 class="text-muted">Students</h4>
                <!-- Row containing first three charts -->
                 <div class="stats">
                    <div class="row" class="d-flex justify-content-between h-100" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                        <!-- First Chart: Registered vs Accepted Students -->
                        <div id="myChart" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>

                        <script>
                            // Google Charts setup
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                // Create a DataTable and populate it with data from PHP
                                const data = google.visualization.arrayToDataTable([
                                    ['Category', 'Count'],  // Column headers
                                    ['Registered Students', <?php echo $total_registered; ?>],  // Dynamic PHP data
                                    ['Accepted Students', <?php echo $total_accepted; ?>]  // Dynamic PHP data
                                ]);

                                const options = {
                                    title: 'Student Registration and Acceptance Status',
                                    pieSliceText: 'percentage', // Show percentages on the pie chart
                                    is3D: true, // Optional: adds a 3D effect to the pie chart
                                };

                                // Create and draw the chart
                                const chart = new google.visualization.PieChart(document.getElementById('myChart'));
                                chart.draw(data, options);
                            }
                        </script>

                        <!-- Second Chart: Department and Program Distribution -->
                        <div id="progDept" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>

                        <script>
                            // Google Charts setup
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart2);

                            function drawChart2() {
                                // Create a DataTable and populate it with data from PHP
                                const data2 = google.visualization.arrayToDataTable([
                                    ['Department and Program', 'Student Count'],  // Column headers
                                    <?php echo $chartDataString; ?>  // Dynamic PHP data (department and program counts)
                                ]);

                                const options2 = {
                                    title: 'Student Distribution by Department and Program',
                                    is3D: true, // Optional: adds a 3D effect to the pie chart
                                    slices: {
                                        0: { offset: 0.1 }, // Optional: adds some space to the first slice
                                        1: { offset: 0.1 },
                                        // Customize slices as needed
                                    }
                                };

                                // Create and draw the second chart
                                const chart2 = new google.visualization.PieChart(document.getElementById('progDept'));
                                chart2.draw(data2, options2);
                            }
                        </script>

                        <!-- Third Chart: Attendance Log -->
                        <div id="attendanceChart" class="col-12 col-md-6" style="height: 470px; flex-shrink: 0; width: 50%;"></div>

                        <script>
                            google.charts.load('current', {'packages':['corechart', 'bar']});
                            google.charts.setOnLoadCallback(drawAttendanceChart);

                            function drawAttendanceChart() {
                                // Create a DataTable and populate it with data from PHP
                                const data = google.visualization.arrayToDataTable([
                                    ['Date', 'Attendance Count'],  // Column headers
                                    <?php echo $attendanceChartDataString; ?>  // Dynamic PHP data (attendance count per date)
                                ]);

                                const options = {
                                    title: 'Attendance Log',
                                    curveType: 'function',  // Makes the line smooth
                                    legend: { position: 'bottom' },
                                    hAxis: {
                                        title: 'Date',
                                        format: 'yyyy-MM-dd',  // Format for date axis
                                        gridlines: { count: 10 },
                                    },
                                    vAxis: {
                                        title: 'Number of Attendees',
                                        minValue: 0
                                    }
                                };

                                // Create and draw the line chart
                                const chart = new google.visualization.LineChart(document.getElementById('attendanceChart'));
                                chart.draw(data, options);
                            }
                        </script>
                    </div>
                    <hr>
                 </div>
            </div>

            <div class="container-fluid">
                <h4 class="text-muted">Book Category and Stocks</h4>
                <!-- Row containing third two charts -->
                <div class="row" class="d-flex flex-wrap justify-content-between h-100 m-0 p-0" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                    <!-- First Chart: Book Category -->
                    <div id="categoryChart" class="col-12 col-lg-6 col-md-6" style="height: 470px;"></div>

                    <script>
                        google.charts.load('current', {'packages':['corechart', 'bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            // Create a DataTable and populate it with data from PHP
                            const data = google.visualization.arrayToDataTable([
                                ['Category', 'Total Books '],  // Column headers
                                <?php echo $catChartDataString; ?>  // Dynamic PHP data (book counts per category)
                            ]);

                            const options = {
                                title: 'Total Books by Category',
                                chartArea: { width: '50%' },
                                hAxis: {
                                    title: 'Number of Books',
                                    minValue: 0
                                },
                                vAxis: {
                                    title: 'Book Category'
                                },
                                bars: 'horizontal', // Optional: Makes it a horizontal bar chart
                                is3D: true,  // Optional: Adds a 3D effect to the bar chart
                            };

                            // Create and draw the bar chart
                            const chart = new google.visualization.ColumnChart(document.getElementById('categoryChart'));
                            chart.draw(data, options);
                        }
                    </script>

                    <!-- Second Chart: Book Stock Chart -->
                    <div id="bookStockChart" class="col-12 col-lg-6 col-md-6" style="height: 470px;"></div>

                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            // Create a DataTable and populate it with the data from PHP
                            const data = google.visualization.arrayToDataTable([
                                ['Book Title', 'Total Stock'],  // Column headers
                                <?php echo $stockChartDataString; ?>  // Dynamic PHP data (book titles and stock counts)
                            ]);

                            const options = {
                                title: 'Total Stock per Book',
                                is3D: true,  // Optional: Adds a 3D effect
                                slices: {
                                    0: { offset: 0.1, color: '#28a745' },
                                    1: { offset: 0.1, color: '#dc3545' },
                                    2: { offset: 0.1, color: '#007bff' }
                                    // Add more slices for more books
                                },
                                pieSliceText: 'percentage',  // Show percentage on slices
                                legend: { position: 'top' }  // Show legend at the top
                            };

                            // Create and draw the pie chart
                            const chart = new google.visualization.PieChart(document.getElementById('bookStockChart'));
                            chart.draw(data, options);
                        }
                    </script>
                </div>
                    <hr>
            </div>
            
            <div class="container-fluid">
                <h4 class="text-muted">Books Borrowed</h4>
                <!-- Row containing second two charts -->
                <div class="row" class="d-flex flex-wrap justify-content-between h-100 m-0 p-0" style="max-width: 100%; overflow-x: auto; overflow-y: hidden;">
                    <!-- First Chart: Total Borrowed Books -->
                    <div id="bookChart" class="col-12 col-lg-6 col-md-6" style="height: 470px;"></div>

                    <script>
                        google.charts.load('current', {'packages':['corechart', 'bar']});
                        google.charts.setOnLoadCallback(drawBookChart);

                        function drawBookChart() {
                            // Create a DataTable and populate it with data from PHP
                            const data = google.visualization.arrayToDataTable([
                                ['Book Title', 'Borrow Count '],  // Column headers
                                <?php echo $bookChartDataString; ?>,  // Dynamic PHP data (book borrow counts)
                                ['Total Borrowed Books', <?php echo $total_borrowed_books; ?>]  // Add total borrow count as a new row
                            ]);

                            const options = {
                                title: 'Books Borrowed',
                                chartArea: { width: '50%' },
                                hAxis: {
                                    title: 'Number of Borrows',
                                    minValue: 0
                                },
                                vAxis: {
                                    title: 'Book Title'
                                },
                                bars: 'horizontal', // Makes it a horizontal bar chart
                                is3D: true,  // Adds 3D effect to the bar chart
                                colors: ['#4CAF50', '#FF9800'],  // Optional: Customize colors (one for individual books, one for the total)
                            };

                            // Create and draw the bar chart
                            const chart = new google.visualization.BarChart(document.getElementById('bookChart'));
                            chart.draw(data, options);
                        }
                    </script>

                    <!-- Second Chart: Active and Returned Books -->
                    <div id="activeReturnedChart" class="col-12 col-lg-6 col-md-6" style="height: 470px;"></div>

                    <script>
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawActiveReturnedChart);

                        function drawActiveReturnedChart() {
                            // Create a DataTable with active and returned books data
                            const data = google.visualization.arrayToDataTable([
                                ['Category', 'Book Count'],
                                ['Active Books', <?php echo $active_books; ?>],  // Active books count
                                ['Returned Books', <?php echo $returned_books; ?>]  // Returned books count
                            ]);

                            const options = {
                                title: 'Active vs Returned Books',
                                pieSliceText: 'percentage',  // Show percentages on the pie chart
                                is3D: true,  // Adds 3D effect to the pie chart
                                slices: {
                                    0: { offset: 0.1 },  // Optional: Add a little space between slices
                                    1: { offset: 0.1 }
                                },
                                colors: ['#FF5722', '#4CAF50']  // Optional: Customize colors (red for active, green for returned)
                            };

                            // Create and draw the pie chart
                            const chart = new google.visualization.PieChart(document.getElementById('activeReturnedChart'));
                            chart.draw(data, options);
                        }
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