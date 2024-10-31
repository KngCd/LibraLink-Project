<?php

session_start();
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s');

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
    #program-dept {
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
    .vertical-center {
        vertical-align: middle;
    }
    form select, form select option{
        cursor: pointer;
    }
</style>

<body>

    <!-- SIDEBAR -->
    <div class="offcanvas offcanvas-start text-light" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" data-bs-backdrop="static" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header d-flex justify-content-center align-items-center flex-column" style="margin-bottom: -20px;">
            <!-- <a href="admin-login.php" class="navbar-brand px-3">
                <img class="img-fluid logo text-center" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;">
            </a> -->
            <!-- <button type="button" class="btn-close" aria-label="Close"></button> -->
            <img src="../../img/formal-pic-von.jpg" alt="Admin Picture" style="background-color: white; height: 180px; width: 180px; border-radius: 50%; object-fit: contain; object-position: contain;">
            <h4 class="text-center mt-2 mb-0">Von Cedric R. Latag</h4>
            <h6 class="text-center">Admin</h6>
        </div>

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
                <a href="#" class="dashboard-link" id="active">
                    <i class="bi bi-buildings"></i>
                    <span>Programs and Departments</span>
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
                    <a href=""> <h1 class="navbar-brand fs-1">Programs and Departments</h1></a>
                    <!-- <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navmenu">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mx-2">
                                <a href="admin-login.php" class="btn btn-danger text-light button">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <hr style="margin-bottom: 0; margin-top: 0;">
        </section>

        <!-- REGISTERED STUDENT -->
        <section class="container-fluid content active" id="program-dept">
            <div class="container p-3" id="program_dept">
                 <div class="container">
                    <?php
                        require_once '../db_config.php';

                        // Capture search input
                        $search = isset($_GET['search']) ? $_GET['search'] : '';

                        // Capture filter input
                        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

                        // Fetch the total number of programs with optional search filter
                        $where_clauses = [];
                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where_clauses[] = "(p.name LIKE '%$search%' OR d.name LIKE '%$search%')";
                        }

                        // Apply the filter based on the selected option
                        if ($filter === 'no_programs') {
                            $where_clauses[] = "d.id NOT IN (SELECT DISTINCT department_id FROM program_table)";
                        } elseif ($filter === 'with_programs') {
                            $where_clauses[] = "d.id IN (SELECT DISTINCT department_id FROM program_table)";
                        }

                        // Build the WHERE query
                        $where_query = '';
                        if (count($where_clauses) > 0) {
                            $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                        }
                    ?>

                <form class="d-flex" method="GET">
                    <input class="form-control me-2 w-50" type="search" name="search" placeholder="Search" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                    <select class="form-select me-2" style="width: 350px;" name="filter" aria-label="Filter">
                        <option value="" <?= empty($_GET['filter']) ? 'selected' : '' ?>>Show all departments and programs</option>
                        <option value="with_programs" <?= isset($_GET['filter']) && $_GET['filter'] === 'with_programs' ? 'selected' : '' ?>>Show departments with programs</option>
                        <option value="no_programs" <?= isset($_GET['filter']) && $_GET['filter'] === 'no_programs' ? 'selected' : '' ?>>Show departments without programs</option>
                    </select>

                    <button class="btn btn-outline-danger" type="submit">Search</button>
                </form>

                </div>

                <div class="container p-3">
                    <?php
                        // Set the number of records per page
                        $records_per_page = 3;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['pcurrent_page'])) {
                            $_SESSION['pcurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks
                        if (isset($_POST['pnext'])) {
                            $_SESSION['pcurrent_page']++;
                        } elseif (isset($_POST['pprevious'])) {
                            if ($_SESSION['pcurrent_page'] > 1) {
                                $_SESSION['pcurrent_page']--;
                            }
                        }

                        // Handle program deletion
                        if (isset($_POST['delete_program'])) {
                            $program_id = (int)$_POST['program_id'];
                            $delete_query = "DELETE FROM program_table WHERE id = ?";

                            if ($stmt = mysqli_prepare($conn, $delete_query)) {
                                mysqli_stmt_bind_param($stmt, 'i', $program_id);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                $_SESSION['message'] = "Program deleted successfully.";
                            } else {
                                $_SESSION['message'] = "Error deleting program: " . mysqli_error($conn);
                            }
                        }

                        // Fetch the total number of programs with the search filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM department_table d LEFT JOIN program_table p ON d.id = p.department_id $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_programs = $total_row['total'];
                        $total_pages = ceil($total_programs / $records_per_page);

                        // Fetch the programs and departments for the current page with the search filter
                        $start_from = ($_SESSION['pcurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT d.name AS department_name, p.name AS program_name, p.id AS program_id 
                                                        FROM department_table d 
                                                        LEFT JOIN program_table p ON d.id = p.department_id 
                                                        $where_query
                                                        ORDER BY d.name
                                                        LIMIT $start_from, $records_per_page");

                        echo "Total: $total_programs";

                        if ($query) {
                            $programs_by_department = [];
                            
                            // Group programs by department
                            while ($row = mysqli_fetch_assoc($query)) {
                                $dept_name = $row['department_name'];
                                if (!isset($programs_by_department[$dept_name])) {
                                    $programs_by_department[$dept_name] = [];
                                }
                                $programs_by_department[$dept_name][] = $row;
                            }

                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table'>";
                            echo "<tr>";
                            echo "<th scope='col'>Department</th>";
                            echo "<th scope='col'>Program</th>";
                            echo "<th scope='col'>Actions</th>"; // Add Actions column
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";

                            foreach ($programs_by_department as $department => $programs) {
                                $rowspan = count($programs);
                                echo "<tr class='department-row vertical-center'>";
                                echo "<td rowspan='$rowspan'>" . htmlspecialchars($department) . "</td>";

                                // Check if there are programs
                                if (isset($programs[0]['program_name'])) {
                                    echo "<td>" . htmlspecialchars($programs[0]['program_name']) . "</td>"; // Display the first program
                                } else {
                                    echo "<td>No Program</td>"; // If no program, show "No Program"
                                }
                                
                                // Disable delete button if no program
                                $delete_disabled = empty($programs[0]['program_name']) ? 'disabled' : '';
                                echo "<td><form method='post' onsubmit='return confirmDelete();'>
                                        <input type='hidden' name='program_id' value='" . htmlspecialchars($programs[0]['program_id'] ?? '') . "'>
                                        <button type='submit' name='delete_program' class='btn btn-danger btn-sm' $delete_disabled>Delete</button>
                                        </form></td>";
                                echo "</tr>";

                                // Display the remaining programs for this department
                                for ($i = 1; $i < $rowspan; $i++) {
                                    echo "<tr class='program-row'>";
                                    echo "<td>" . htmlspecialchars($programs[$i]['program_name']) . "</td>";
                                    // Disable delete button if no program
                                    $delete_disabled = empty($programs[$i]['program_name']) ? 'disabled' : '';
                                    echo "<td><form method='post' onsubmit='return confirmDelete();'>
                                            <input type='hidden' name='program_id' value='" . htmlspecialchars($programs[$i]['program_id']) . "'>
                                            <button type='submit' name='delete_program' class='btn btn-danger btn-sm' $delete_disabled>Delete</button>
                                        </form></td>";
                                    echo "</tr>";
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
                            echo "<form action='' method='post'>";
                            if ($_SESSION['pcurrent_page'] > 1) {
                                echo "<button type='submit' name='pprevious' class='btn btn-danger' style='width: 50px;'>&lt;</button>";
                            } else {
                                echo "<button type='submit' name='pprevious' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                            }
                            echo "<span> Page " . $_SESSION['pcurrent_page'] . " </span>";
                            if ($total_programs > $records_per_page && $_SESSION['pcurrent_page'] < $total_pages) {
                                echo "<button type='submit' name='pnext' class='btn btn-danger' style='width: 50px;'>&gt;</button>";
                            } else {
                                echo "<button type='submit' name='pnext' class='btn btn-danger' style='width: 50px;' disabled>&gt;</button>";
                            }
                            echo "</form>";
                            echo "</div>";
                            echo "<hr>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>

                    <script>
                        function confirmDelete() {
                            return confirm("Are you sure you want to delete this program?");
                        }
                    </script>

                    <div class="row">
                        <div class="container col-6">
                            <!-- Add Departments -->
                            <form action="" method="POST">
                                <input type="text" class="form-control mt-3 mb-3" name="department_name" placeholder="Department Name" required>
                                <button type="submit" class="btn btn-danger" name="dept">Add Department</button>
                            </form>

                            <?php
                                if (isset($_POST['dept'])) {
                                    $department_name = $_POST['department_name'];

                                    $stmt = $conn->prepare("INSERT INTO department_table (name) VALUES (?)");
                                    $stmt->bind_param("s", $department_name);
                                    
                                    if ($stmt->execute()) {
                                        // echo "<script> alert('Department added succesfully'); window.location.href = 'admin_dashboard.php';</script>";
                                        echo "<script>window.location.href = 'program_dept.php?alert=success';</script>";
                                    } else {
                                        echo "Error: " . $stmt->error;
                                    }
                                    exit;
                                    $stmt->close();
                                }
                            ?>
                        </div>
                        <div class="container col-6">
                            <!-- Add Programs -->
                            <form action="" method="POST">
                                <select class="form-control mt-3 mb-2" name="department_id" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <?php
                                    // Fetch departments
                                    $result = $conn->query("SELECT * FROM department_table");

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <input type="text" class="form-control mb-3" name="program_name" placeholder="Program Name" required>
                                <button type="submit" class="btn btn-danger" name="program">Add Program</button>
                            </form>

                            <?php
                                if (isset($_POST['program'])) {
                                    $program_name = $_POST['program_name'];
                                    $department_id = $_POST['department_id'];

                                    $stmt2 = $conn->prepare("INSERT INTO program_table (name, department_id) VALUES (?, ?)");
                                    $stmt2->bind_param("si", $program_name, $department_id);
                                    
                                    if ($stmt2->execute()) {
                                        // echo "<script> alert('Program added succesfully'); window.location.href = 'admin_dashboard.php';</script>";
                                        echo "<script>window.location.href = 'program_dept.php?alert=success';</script>";
                                    } else {
                                        echo "Error: " . $stmt2->error;
                                    }
                                    exit;

                                    $stmt2->close();
                                    $conn->close();
                                }
                            ?>
                        </div>
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
                                const message = alertType === 'success' ? 'Succesfully added!' : 'ERROR: Adding failed!';
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