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
    #stocks {
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
            <h4 class="text-center mt-2 mb-0">Carlo M. Pastrana</h4>
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
                <a href="program_dept.php" class="dashboard-link">
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
                <a href="#" class="dashboard-link" id="active">
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
                    <a href=""><h1 class="navbar-brand fs-1">Book Stocks</h1></a>
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
        
        <!-- BOOK STOCKS -->
        <section class="container-fluid content active" id="stocks">
            <div id="total-stocks" class="container p-3">
                <div class="container">
                    <?php
                        require_once '../db_config.php';

                        // Capture search and filter inputs
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $selected_status = isset($_GET['status']) ? $_GET['status'] : '';

                        // Build the base SQL query with LEFT JOIN
                        $status_query = "SELECT b.book_id, b.title, 
                                                COALESCE(i.status, 'Not Available') AS status, 
                                                COALESCE(i.stocks, 0) AS stocks
                                        FROM book_table AS b
                                        LEFT JOIN inventory_table AS i ON b.book_id = i.book_id";

                        // Build the WHERE clauses
                        $where_clauses = [];

                        // Search filter
                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $where_clauses[] = "(b.title LIKE '%$search%' OR b.author LIKE '%$search%')";
                        }

                        // Status filter
                        if ($selected_status === 'available') {
                            $where_clauses[] = "i.book_id IS NOT NULL"; // Books that exist in inventory
                        } elseif ($selected_status === 'not_available') {
                            $where_clauses[] = "i.book_id IS NULL"; // Books that do not exist in inventory
                        }

                        // Construct the WHERE query
                        $where_query = '';
                        if (count($where_clauses) > 0) {
                            $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                        }

                        // Final query
                        // $final_query = $status_query . ' ' . $where_query;

                        // Execute the query
                        // $result = mysqli_query($conn, $final_query);

                        // Check for errors
                        // if (!$result) {
                        //     die('Query Error: ' . mysqli_error($conn));
                        // }

                        // Fetch results and display them
                        // while ($row = mysqli_fetch_assoc($result)) {
                        //     echo "Book ID: " . $row['book_id'] . " - Title: " . $row['title'] . " - Status: " . $row['status'] . " - Stocks: " . $row['stocks'] . "<br>";
                        // }
                    ?>

                    <form class="d-flex" method="GET">
                        <input class="form-control me-2 w-50 me-5" type="search" name="search" placeholder="Search" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="status" class="form-select w-25 ms-5 me-3">
                            <option value="">All Status</option>
                            <option value="available" <?php echo ($selected_status === 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="not_available" <?php echo ($selected_status === 'not_available') ? 'selected' : ''; ?>>Not Available</option>
                        </select>

                        <!-- <select name="department" class="form-control w-25 me-3">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= htmlspecialchars($department) ?>" <?= $selected_department === $department ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department) ?>
                                </option>
                            <?php endforeach; ?>
                        </select> -->

                        <button class="btn btn-outline-danger" type="submit">Search</button>
                    </form>

                </div>

                <div class="container p-3">
                    <?php
                        $records_per_page = 3;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['scurrent_page'])) {
                            $_SESSION['scurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks
                        if (isset($_POST['snext'])) {
                            $_SESSION['scurrent_page']++;
                        } elseif (isset($_POST['sprevious'])) {
                            if ($_SESSION['scurrent_page'] > 1) {
                                $_SESSION['scurrent_page']--;
                            }
                        }

                        // Fetch the total number of borrowed books based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total 
                                                    FROM book_table AS b
                                                    LEFT JOIN inventory_table AS i ON b.book_id = i.book_id $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_bstocks = $total_row['total'];
                        $total_pages = ceil($total_bstocks / $records_per_page);

                        // Fetch the books with their current stocks
                        $start_from = ($_SESSION['scurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks
                                FROM book_table AS b
                                LEFT JOIN inventory_table AS i ON b.book_id = i.book_id
                                $where_query
                                LIMIT $start_from, $records_per_page");
                        echo "Total: $total_bstocks";
                        
                        // Check if the query was successful
                        if ($query) {
                            // Initialize variables to keep track of totals
                            $total_stocks = 0;
                            $total_borrowed = 0;
                            $total_available_stocks = 0;

                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover'>";
                            echo "<tr>";
                            echo "<th>Book ID</th>";
                            echo "<th>Title</th>";
                            echo "<th>Status</th>";
                            echo "<th>Stocks</th>";
                            echo "<th>Borrowed</th>";
                            echo "<th>Available Stocks</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                // Get the number of borrowed copies
                                $borrowed_query = "SELECT COUNT(*) as borrowed FROM borrow_table WHERE book_id = '" . $row['book_id'] . "'";
                                $borrowed_result = mysqli_query($conn, $borrowed_query);
                                $borrowed_row = mysqli_fetch_assoc($borrowed_result);
                                $borrowed = $borrowed_row['borrowed'];

                                $available_stocks = $row['stocks'] - $borrowed;

                                // Add values to totals
                                $total_stocks += $row['stocks'];
                                $total_borrowed += $borrowed;
                                $total_available_stocks += $available_stocks;

                                echo "<tr>";
                                echo "<td>" . $row['book_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                                echo "<td>" . $row['stocks'] . "</td>";
                                echo "<td>" . $borrowed . "</td>";
                                echo "<td>" . $available_stocks . "</td>";
                                }
                                // Display totals
                                echo "<tr>";
                                echo "<td colspan='3'><b>Total</b></td>";
                                echo "<td><b>" . $total_stocks . "</b></td>";
                                echo "<td><b>" . $total_borrowed . "</b></td>";
                                echo "<td><b>" . $total_available_stocks . "</b></td>";
                                echo "</tr>";

                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='6'>No records found</td>";
                            }
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                    ?>
                </div>
                
                <button class="btn btn-success mb-3" type="button" data-bs-toggle="modal" data-bs-target="#addStocks">Add Stocks</button>

                    <?php
                        // Display pagination buttons
                        echo "<div class='pagination-buttons'>";
                        echo "<form action='' method='post'>";
                        if ($_SESSION['scurrent_page'] > 1) {
                            echo "<button type='submit' name='sprevious' class='btn btn-danger' style='width: 50px;'>&lt;</button>";
                        } else {
                            echo "<button type='submit' name='sprevious' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                        }
                        echo "<span> Page " . $_SESSION['scurrent_page'] . " " . "</span>";
                        if ($total_bstocks > $records_per_page && $_SESSION['scurrent_page'] < $total_pages) {
                            echo "<button type='submit' name='snext' class='btn btn-danger' style='width: 50px;'>&gt;</button>";
                        } else {
                            echo "<button type='submit' name='snext' class='btn btn-danger' style='width: 50px;' disabled>&gt;</button>";
                        }
                        echo "</form>";
                        echo "</div>";
                    } else {
                        // echo "Error fetching data: " . mysqli_error($conn);
                        $_SESSION['alert'] = ['message' => 'Error fetching data: ' . mysqli_error($conn), 'type' => 'danger'];
                    }
                    ?>
                <!-- <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button> -->

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
                            const message = alertType === 'success' ? 'Book Stocks added!' : 'ERROR: Adding Book Stocks!';
                            appendAlert(message, alertType);
                            
                            // Clear the alert parameter from the URL
                            urlParams.delete('alert');
                            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
                        }
                </script>

             </div>

             <!-- Modal for adding Book Stocks -->
             <div class="modal fade" id="addStocks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <?php
                                require_once '../db_config.php';

                                // Check if the form has been submitted
                                if (isset($_POST['add'])) {
                                    $book_id = $_POST['book_id'];
                                    $title = $_POST['title'];
                                    $status = $_POST['status'];
                                    $new_stocks = $_POST['stocks'];

                                    if ($_POST['add'] == 'add') {
                                        // Check if the book_id already exists in the inventory_table
                                        $check_query = "SELECT * FROM inventory_table WHERE book_id = '$book_id'";
                                        $check_result = mysqli_query($conn, $check_query);
                                        
                                        if (mysqli_num_rows($check_result) == 0) {
                                            // Insert the details into the inventory_table
                                            $query = "INSERT INTO inventory_table (book_id, stocks, status) VALUES ('$book_id', '$new_stocks', '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "')";
                                            $result = mysqli_query($conn, $query);

                                            if($result){                                          
                                                // Display an alert message
                                                // echo "<script>alert('Stocks added SUCCESSFULLY!'); window.location.href = 'book_stock.php';</script>";
                                                // exit;
                                                echo "<script>window.location.href = 'book_stock.php?alert=success';</script>";
                                            }
                                            else{    
                                                // Display an alert message
                                                // echo "<script>alert('Error adding stocks: " . mysqli_error($conn) . "');  window.location.href = 'book_stock.php';</script>";
                                                // exit;
                                                echo "<script>window.location.href = 'book_stock.php?alert=danger';</script>";
                                            }
                                            exit;
                                        } else {
                                            // Update the existing record in the inventory_table
                                            $query = "UPDATE inventory_table SET stocks = stocks + '$new_stocks', status = '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "' WHERE book_id = '$book_id'";
                                            $update = mysqli_query($conn, $query);

                                            if($update){                                          
                                                // Display an alert message
                                                // echo "<script>alert('Stocks updated SUCCESSFULLY!'); window.location.href = 'book_stock.php';</script>";
                                                // exit;
                                                echo "<script>window.location.href = 'book_stock.php?alert=success';</script>";
                                            }
                                            else{    
                                                // Display an alert message
                                                // echo "<script>alert('Error updating stocks: " . mysqli_error($conn) . "'); window.location.href = 'book_stock.php';</script>";
                                                // exit;
                                                echo "<script>window.location.href = 'book_stock.php?alert=danger';</script>";
                                            }
                                            exit;
                                        }
                                    }
                                }

                                // Fetch the books with their current stocks
                                $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks 
                                                        FROM book_table AS b
                                                        LEFT JOIN inventory_table AS i ON b.book_id = i.book_id"); // we're using the COALESCE function to replace NULL values in the stocks column with 0

                                // Check if the query was successful
                                if ($query) {
                                    // Display the rows
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-hover'>";
                                    echo "<tr>";
                                    echo "<th scope='col'>Book ID</th>";
                                    echo "<th scope='col'>Title</th>";
                                    echo "<th scope='col'>Status</th>";
                                    echo "<th scope='col'>Stocks</th>";
                                    echo "<th scope='col'>Add Stocks</th>";
                                    echo "</tr>";
                                    echo "<tbody class='table-group-divider'>";
                                    $num_rows = mysqli_num_rows($query);
                                    if ($num_rows === 0) {
                                        echo "<td colspan='5'>No records found</td>";
                                    } else {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>";
                                            echo "<td scope='row'>" . $row['book_id'] . "</td>";
                                            echo "<td scope='row'>" . $row['title'] . "</td>";
                                            echo "<td scope='row'>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                                            echo "<td scope='row'>" . $row['stocks'] . "</td>";
                                            
                                            echo "<td scope='row'>
                                                <form action='' method='post'>
                                                    <input type='hidden' name='book_id' value='" . $row['book_id'] . "'>
                                                    <input type='hidden' name='title' value='" . $row['title'] . "'>
                                                    <input type='hidden' name='status' value='" . $row['status'] . "'>
                                                    <div class='input-group'>
                                                        <input class='form-control me-1' type='number' name='stocks' value='0' required style='border-radius: 0.375rem;'>
                                                        <span><button type='submit' name='add' value='add' class='btn btn-success'>Add</button></span>
                                                    </div>
                                                </form>
                                            </td>";
                                        }
                                    }
                                    echo "</tr>";
                                    echo "</tbody>";
                                    echo "</table>";
                                    echo "</div>";
                                } else {
                                    echo "Error fetching data: " . mysqli_error($conn);
                                }

                                // Close the connection
                                mysqli_close($conn);
                            ?>
                        </div>
                    </div>
                </div>
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