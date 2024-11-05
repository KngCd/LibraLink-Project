<?php
session_start();
require_once '../db_config.php';
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Logs</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    #total-register, #accepted-student, #books, #total-stocks, #total-borrowed-books, #total-available, #attendance-student{
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
    #attendance-logs {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: center;
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

</head>
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
                <a href="#" class="dashboard-link">
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
                <a href="attendance_logs.php" class="dashboard-link" id="active">
                    <i class="bi bi-clock-history"></i>
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

    <main class="main-content">
        <section class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="height: 80px;">
                <div class="container">
                    <!-- <img class="img-fluid logo text-center" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;"> -->
                    <a href=""> <h1 class="navbar-brand fs-1">Log Records</h1></a>
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
        
        <!--Log Records -->
        <section class="container-fluid content active" id="attendance-logs">
            <div id="attendance-student" class="container p-3">
                <div class="container">
                    <?php
                    require_once '../db_config.php';

                    // Capture search and filter inputs
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $selected_date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
                    $date_sort = isset($_GET['date_sort']) ? $_GET['date_sort'] : '';
                    $time_sort = isset($_GET['time_sort']) ? $_GET['time_sort'] : '';

                    // Fetch distinct date filter options (Optional, if you need to fetch distinct dates for any reason)
                    $date_filters = [
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'this_week' => 'This Week',
                        'this_month' => 'This Month',
                        'next_month' => 'Next Month',
                        'previous_week' => 'Previous Week',
                        'previous_month' => 'Previous Month',
                    ];

                    // Build the SQL query based on search, program, and department
                    $where_clauses = [];

                    if (!empty($search)) {
                        $search = mysqli_real_escape_string($conn, $search);
                        $where_clauses[] = "(s.first_name LIKE '%$search%' OR s.last_name LIKE '%$search%')";
                    }

                    // Date filter handling
                    $date_filter_clause = '';
                    if (!empty($selected_date_filter)) {
                        switch ($selected_date_filter) {
                            case 'today':
                                $date_filter_clause = "DATE(date) = CURDATE()";
                                break;
                            case 'yesterday':
                                $date_filter_clause = "DATE(date) = CURDATE() - INTERVAL 1 DAY";
                                break;
                            case 'this_week':
                                $date_filter_clause = "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
                                break;
                            case 'this_month':
                                $date_filter_clause = "MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
                                break;
                            case 'next_month':
                                $date_filter_clause = "MONTH(date) = MONTH(CURDATE()) + 1 AND YEAR(date) = YEAR(CURDATE())";
                                break;
                            case 'previous_week':
                                $date_filter_clause = "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1) - 1";
                                break;
                            case 'previous_month':
                                $date_filter_clause = "MONTH(date) = MONTH(CURDATE()) - 1 AND YEAR(date) = YEAR(CURDATE())";
                                break;
                            default:
                                $date_filter_clause = ''; // No date filter
                                break;
                        }
                        
                        if ($date_filter_clause) {
                            $where_clauses[] = $date_filter_clause;
                        }
                    }

                    $where_query = '';
                    if (count($where_clauses) > 0) {
                        $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                    }
                    // // Build the ORDER BY clause dynamically based on the sort options
                    // $order_clauses = [];

                    // // Handle sorting options for date and time_in
                    // if ($date_sort) {
                    //     $order_clauses[] = "l.date " . strtoupper($date_sort);  // ASC or DESC
                    // }

                    // if ($time_sort) {
                    //     $order_clauses[] = "l.time_in " . strtoupper($time_sort);  // ASC or DESC
                    // }

                    // // If no sorting options are set, default to ORDER BY l.date DESC, l.time_in
                    // if (count($order_clauses) === 0) {
                    //     $order_clauses[] = "l.date DESC, l.time_in";  // Default sorting
                    // }

                    // // Final ORDER BY query
                    // $order_query = 'ORDER BY ' . implode(', ', $order_clauses);
                    ?>

                    <!-- Search and Filter Form -->
                    <form class="d-flex" method="GET">
                        <input class="form-control me-2 w-50 me-5" type="search" name="search" placeholder="Search for Name" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="date_filter" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">All Dates</option>
                            <option value="today" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="yesterday" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                            <option value="this_week" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'this_week' ? 'selected' : '' ?>>This Week</option>
                            <option value="this_month" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'this_month' ? 'selected' : '' ?>>This Month</option>
                            <option value="next_month" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'next_month' ? 'selected' : '' ?>>Next Month</option>
                            <option value="previous_week" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'previous_week' ? 'selected' : '' ?>>Previous Week</option>
                            <option value="previous_month" <?= isset($_GET['date_filter']) && $_GET['date_filter'] === 'previous_month' ? 'selected' : '' ?>>Previous Month</option>
                        </select>

                        <!-- OPTIONAL -->
                        <!-- <select name="date_sort" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">Sort Date</option>
                            <option value="asc" <?= isset($_GET['date_sort']) && $_GET['date_sort'] === 'asc' ? 'selected' : '' ?>>Ascending</option>
                            <option value="desc" <?= isset($_GET['date_sort']) && $_GET['date_sort'] === 'desc' ? 'selected' : '' ?>>Descending</option>
                        </select>

                        <select name="time_sort" class="form-select w-25 me-2" style="width: fit-content;">
                            <option value="">Sort Time</option>
                            <option value="asc" <?= isset($_GET['time_sort']) && $_GET['time_sort'] === 'asc' ? 'selected' : '' ?>>Ascending</option>
                            <option value="desc" <?= isset($_GET['time_sort']) && $_GET['time_sort'] === 'desc' ? 'selected' : '' ?>>Descending</option>
                        </select> -->

                        <button class="btn btn-outline-danger" type="submit">Search</button>
                    </form>
                </div>
                <div class="container">
                    <?php
                        // Set the number of records per page
                        $records_per_page = 3;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['logcurrent_page'])) {
                            $_SESSION['logcurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks via GET parameters
                        if (isset($_GET['page'])) {
                            $_SESSION['logcurrent_page'] = (int)$_GET['page'];
                        }

                        // Fetch the total number of logs with the search and filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total 
                                        FROM log_table l 
                                        LEFT JOIN student_table s ON l.student_id = s.student_id 
                                        $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_logs = $total_row['total'];
                        $total_pages = ceil($total_logs / $records_per_page);

                        // Fetch the logs for the current page
                        $start_from = ($_SESSION['logcurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT l.*, s.first_name, s.last_name, s.email, s.contact_num 
                                                    FROM log_table l 
                                                    LEFT JOIN student_table s ON l.student_id = s.student_id
                                                    $where_query 
                                                    ORDER BY l.date DESC, l.time_in
                                                    LIMIT $start_from, $records_per_page");

                        if ($query) {
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover caption-top'>";
                            echo "<caption>Total: $total_logs</caption>";
                            echo "<thead><tr>";
                            echo "<th>Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Date</th>";
                            echo "<th>Time-In</th>";
                            echo "<th>Time-Out</th>";
                            echo "</tr></thead>";
                            echo "<tbody class='table-group-divider'>";

                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row['date'])) . "</td>";
                                echo "<td>" . date('h:i:s A', strtotime($row['time_in'])) . "</td>";
                                echo "<td>" . ($row['time_out'] ? date('h:i:s A', strtotime($row['time_out'])) : 'Not yet logged out') . "</td>";
                                echo "</tr>";
                            }

                            if (mysqli_num_rows($query) === 0) {
                                echo "<tr><td colspan='5'>No logs found</td></tr>";
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
                            if (!empty($date_filter)) {
                                $filter_params .= '&date_filter=' . urlencode($date_filter);
                            }

                            if ($_SESSION['logcurrent_page'] > 1) {
                                echo "<a href='?page=" . ($_SESSION['logcurrent_page'] - 1) . "$filter_params' class='btn btn-danger'>&lt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' disabled>&lt;</button>";
                            }

                            echo "<span> Page " . $_SESSION['logcurrent_page'] . " of $total_pages </span>";

                            if ($total_logs > $records_per_page && $_SESSION['logcurrent_page'] < $total_pages) {
                                echo "<a href='?page=" . ($_SESSION['logcurrent_page'] + 1) . "$filter_params' class='btn btn-danger'>&gt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' disabled>&gt;</button>";
                            }

                            echo "</form>";
                            echo "</div>";

                            echo "<hr>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>

                </div>
            </div>
        </section>

    </main>

    <script>
        $(document).ready(function() {
            $('#offcanvasWithBothOptions').offcanvas('show');
        });

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
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>