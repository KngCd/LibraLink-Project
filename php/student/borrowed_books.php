<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
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
    /* .logo {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .logo:hover {
        transform: scale(1.1);
    } */
    body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    .book-label {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 10px;
        background-color: #34C759;
        color: #fff;
    }

    .book-label-container {
        margin-top: 10px;
    }
    .not-available {
        background-color: #DC4C64;
    }
    /* Style the button that is used to open the sidepanel */
    .menu {
        font-size: 30px;
        cursor: pointer;
        background-color: #dd2222;
        color: white;
        padding: 10px 15px;
        border: none;
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
    .sidebar-item {
        padding: 10px;
    }
    .sidebar-link {
        display: block;
        padding: 10px;
        background-color: #dd2222;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .sidebar-link:hover, .dashboard-link:focus, #active {
        background-color: #ca1d1d;
    }

    .sidebar-link i {
        margin-right: 10px;
        font-size: 18px;
    }
    .sidebar-link span {
        font-size: 20px;
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
    #desc::-webkit-scrollbar {
        display: none;
    }
    #total-borrowed{
        border-radius: 10px;
        border: 1px solid lightgray;
        background-color: #ffffff;
    }
</style>
<body>
    <?php
       require_once '../db_config.php';
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $firstName = $_SESSION['first_name'];
                $lastName = $_SESSION['last_name'];
                $email = $_SESSION['email'];
                $contact_num = $_SESSION['contact_num'];
                $program = $_SESSION['program'];
                $department = $_SESSION['department'];
                $profile = $_SESSION['profile_pic'];

                // You can also retrieve more user data from the database if needed
                $stmt = $conn->prepare("SELECT * FROM student_table WHERE student_id = ?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
            }
    ?>

    <header class="sticky-top z-1" style="background-color: #dd2222;">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container-fluid">
                <a href="" class="navbar-brand text-light fs-xxl-1 fs-xl-1 fs-lg-1 fs-md-2 fs-sm-5 fs-2 d-flex align-items-center">
                    <div class="img d-flex align-items-center">
                        <img class="img-fluid logo d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-none" src="../../img/cropped-libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    </div>
                    <span class="ms-2"><?php echo 'Welcome, ' . $firstName . '!' ?></span>
                </a>

                <div class="d-flex">
                    <button class="btn btn-danger me-2 menu" type="button" data-bs-toggle="modal" data-bs-target="#cart">
                        <i class="bi bi-cart2"></i>
                    </button>
                    <button class="btn btn-danger menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                        &#9776;
                    </button>
                </div>
            </div>
        </nav>

        <div class="offcanvas offcanvas-end text-light" data-bs-backdrop="static" data-bs-scroll= "true" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
            <div class="offcanvas-header">
               <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="sidebar-item d-flex align-items-center justify-content-center">
                    <img class="img-fluid" src="../../img/librawhite.png" alt="Logo" style="height: 30px;">
                </div>
                <div class="sidebar-item">
                    <a href="student_home.php" class="sidebar-link" data-target="home">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="student_profile.php" class="sidebar-link">
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="sidebar-link"  id="active">
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Borrowed Books</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="activity_logs.php" class="sidebar-link">
                        <i class="bi bi-clipboard-data-fill"></i>
                        <span>Activity Logs</span>
                    </a>
                </div>
            </div>
            <hr>
            <div class="offcanvas-footer text-center mb-2">
                <div class="sidebar-item">
                    <a href="logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal -->
    <div class="modal fade" id="cart" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Your Cart</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="list-group">
                <?php 
                    $user_id = $_SESSION['user_id'];

                    // Fetch book IDs for the user from the cart
                    $stmt = $conn->prepare("SELECT book_id FROM cart WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // Create an array to hold book IDs from the cart
                    $book_ids = [];
                    while ($row = $result->fetch_assoc()) {
                        $book_ids[] = $row['book_id'];
                    }

                    if (empty($book_ids)) {
                        echo '<div class="list-group-item">Your cart is empty.</div>';
                    } else {
                        // Prepare a statement to fetch book details based on the retrieved book IDs
                        $ids_placeholder = implode(',', array_fill(0, count($book_ids), '?'));
                        $stmt = $conn->prepare("SELECT title, author, category, book_id, book_cover FROM book_table WHERE book_id IN ($ids_placeholder)");
                        $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display the books
                        // Inside your modal body where you're displaying the books
                        while ($book = $result->fetch_assoc()) {
                            echo '<div class="list-group-item d-flex align-items-start">';
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($book['book_cover']) . '" alt="Book Cover" width="200" height="300" class="me-3">';
                            echo '<div class="d-flex flex-column">';
                            echo '<h4 class="text-muted">' . htmlspecialchars($book['title']) . '</h4>';
                            echo '<h6 class="text-muted">' . htmlspecialchars($book['author']) . '</h6>';
                            echo '<h6 class="text-muted">' . htmlspecialchars($book['category']) . '</h6>';
                            echo '</div>';
                            echo '<button class="btn btn-danger btn-sm ms-auto align-items-end remove-book" data-book-id="' . $book['book_id'] . '">Remove</button>';
                            // echo '<a href="remove_from_cart.php?book_id=' . $book['book_id'] . '" class="btn btn-danger btn-sm ms-auto align-items-end">Remove</a>';
                            echo '</div>';
                        }

                    }
                    $stmt->close();
                ?>
                </div>

                 <!-- Borrowing Policies Section (inserted right here) -->
                <div class="mt-4">
                    <h5><strong>Borrowing Policies</strong></h5>
                    <ul>
                        <li><strong>Maximum number of books:</strong> You can borrow a maximum of 5 books at a time.</li>
                        <li><strong>Penalty:</strong> There is a penalty of <strong>10.00</strong> per book, per overdue day.</li>
                        <li><strong>Book condition:</strong> You must return the books in good condition. If the book is damaged, you must buy a replacement. If the book is an older version, you need to buy a new version.</li>
                        <li><strong>Maximum borrow duration:</strong> Books can be borrowed for a maximum of 7 days.</li>
                        <li><strong>Renewal:</strong> You can renew the borrowed book once only.</li>
                        <li><strong>Early returns:</strong> You can return the books early if you wish.</li>
                    </ul>
                    <p>By confirming borrowing, you agree to the above policies.</p>
                </div>
                
            </div>

            <div class="modal-footer">
                <form action="borrow_form.php" method="post" class="mt-3">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" name="submit" class="btn btn-danger">Confirm Borrowing</button>
                </form>
            </div>
            
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.remove-book').on('click', function() {
                var bookId = $(this).data('book-id');
                var button = $(this); // Store reference to the button for later use

                $.ajax({
                    url: 'remove_from_cart.php?book_id=' + bookId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Remove the book from the modal view
                            button.closest('.list-group-item').remove();

                            // Check if the cart is empty
                            if ($('.list-group-item').length === 0) {
                                $('.list-group').append('<div class="list-group-item">Your cart is empty.</div>');
                            }
                        } else {
                            alert('Could not remove the book. Please try again.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while removing the book.');
                    }
                });
            });
        });
    </script>

    <main class="main-content">
        <section>
            <div class="container mt-3">
                <?php
                    // Capture search and filter inputs
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $selected_status = isset($_GET['status']) ? $_GET['status'] : '';
                    $due_date_filter = isset($_GET['due_date_filter']) ? $_GET['due_date_filter'] : '';

                    // Build the SQL query based on search and category
                    $where_clauses = [];

                    if (!empty($search)) {
                        $search = mysqli_real_escape_string($conn, $search);
                        $where_clauses[] = "(title LIKE '%$search%' OR author LIKE '%$search%')";
                    }

                    if (!empty($selected_status)) {
                        $where_clauses[] = "status = '" . mysqli_real_escape_string($conn, $selected_status) . "'";
                    }

                    // Due date filter
                    if (!empty($due_date_filter)) {
                        $today = date('Y-m-d');
                        $start_of_week = date('Y-m-d', strtotime('monday this week'));
                        $end_of_week = date('Y-m-d', strtotime('sunday this week'));
                        $start_of_month = date('Y-m-01');
                        $end_of_month = date('Y-m-t');
                        $start_of_next_month = date('Y-m-d', strtotime('first day of next month'));
                        $end_of_next_month = date('Y-m-d', strtotime('last day of next month'));

                        switch ($due_date_filter) {
                            case 'today':
                                $where_clauses[] = "br.due_date = '$today'";
                                break;
                            case 'this_week':
                                $where_clauses[] = "br.due_date BETWEEN '$start_of_week' AND '$end_of_week'";
                                break;
                            case 'this_month':
                                $where_clauses[] = "br.due_date BETWEEN '$start_of_month' AND '$end_of_month'";
                                break;
                            case 'next_month':
                                $where_clauses[] = "br.due_date BETWEEN '$start_of_next_month' AND '$end_of_next_month'";
                                break;
                            // New case for Past Due Dates
                            case 'past_due':
                                $where_clauses[] = "br.due_date < '$today' AND br.status != 'Returned'";
                                break;
                        }
                    }

                    $where_query = '';
                    if (count($where_clauses) > 0) {
                        $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                    }
                ?>
                <form class="d-flex align-items-center" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search for Title or Author" aria-label="Search" value="<?= htmlspecialchars($search) ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">

                    <select name="status" class="form-select w-50 me-2" style="border-radius: 16px; border: solid 1px black; width: 100%; display: block; font-size: 16px;">
                        <option value="">All Status</option>
                        <option value="active" <?= $selected_status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="returned" <?= $selected_status === 'returned' ? 'selected' : '' ?>>Returned</option>
                    </select>

                    <select name="due_date_filter" class="form-select w-25 me-2" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                        <option value="">All Due Dates</option>
                        <!-- New Option for Past Due Dates -->
                        <option value="past_due" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'past_due' ? 'selected' : '' ?>>Past Due Dates</option>
                        <option value="today" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'today' ? 'selected' : '' ?>>Today</option>
                        <option value="this_week" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'this_week' ? 'selected' : '' ?>>This Week</option>
                        <option value="this_month" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'this_month' ? 'selected' : '' ?>>This Month</option>
                        <option value="next_month" <?= isset($_GET['due_date_filter']) && $_GET['due_date_filter'] === 'next_month' ? 'selected' : '' ?>>Next Month</option>
                    </select>

                    <button class="btn btn-outline-danger" type="submit">Search</button>
                </form>
            </div>
        </section>

        <section class="container-fluid mt-3">
            <div id="total-borrowed" class="container p-3">
                <div class="container p-3">
                    <?php
                        // Set the number of records per page
                        $records_per_page = 5;

                        // Get the current page from the session or set it to 1
                        if (!isset($_SESSION['bbscurrent_page'])) {
                            $_SESSION['bbscurrent_page'] = 1;
                        }

                        // Handle next and previous button clicks via GET parameters
                        if (isset($_GET['page'])) {
                            $_SESSION['bbscurrent_page'] = (int)$_GET['page'];
                        }

                        // Fetch the total number of borrowed books based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total 
                                                            FROM borrow_table AS br
                                                            INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                            INNER JOIN book_table AS b ON br.book_id = b.book_id
                                                            $where_query AND s.student_id = $user_id");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_borrowed = $total_row['total'];
                        $total_pages = ceil($total_borrowed / $records_per_page);

                        // Fetch the borrowed books with their details
                        $start_from = ($_SESSION['bbscurrent_page'] - 1) * $records_per_page;
                        $query = mysqli_query($conn, "SELECT s.student_id, s.first_name, s.last_name, s.email, s.contact_num, s.program, s.department, s.profile_pic,
                                                        b.title, br.status, br.date_borrowed, br.due_date, br.penalty
                                                        FROM borrow_table AS br
                                                        INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                        INNER JOIN book_table AS b ON br.book_id = b.book_id
                                                        $where_query AND s.student_id = $user_id ORDER BY br.penalty DESC
                                                        LIMIT $start_from, $records_per_page");

                        // echo "Total: $total_borrowed";

                        // Check if the query was successful
                        if ($query) {
                            $total_penalty = 0;

                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover caption-top p-3'>";
                            echo "<caption>Total: $total_borrowed</caption>";
                            echo "<tr>";
                            // echo "<th>Name</th>";
                            //  echo "<th>Email</th>";
                            //  echo "<th>Contact Number</th>";
                            //  echo "<th>Program</th>";
                            //  echo "<th>Department</th>";
                            // echo "<th>Profile</th>";
                            echo "<th>Book Title</th>";
                            echo "<th>Status</th>";
                            echo "<th>Date Borrowed</th>";
                            echo "<th>Due Date</th>";
                            echo "<th>Penalty</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                $total_penalty += $row['penalty'];
                                echo "<tr>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td><button class='btn " . ($row['status'] == 'Returned' ? 'btn-success' : 'btn-danger') . " disabled'>" . $row['status'] . "</button></td>";
                                echo "<td>" . $row['date_borrowed'] . "</td>";
                                echo "<td>" . $row['due_date'] . "</td>";
                                echo "<td>" . $row['penalty'] . "</td>";
                                echo "</tr>";
                            }

                                // Display totals
                                echo "<tr>";
                                echo "<td colspan='4' class='text-center'><b>Total</b></td>";
                                echo "<td class='text-danger'><b>" . $total_penalty . "</b></td>";
                                echo "</tr>";

                            if (mysqli_num_rows($query) === 0) {
                                echo "<td colspan='5'>No records found</td>"; 
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
                            if (!empty($selected_status)) {
                                $filter_params .= '&status=' . urlencode($selected_status);
                            }   
                            if (!empty($due_date_filter)) {
                                $filter_params .= '&due_date_filter=' . urlencode($due_date_filter);
                            }   

                            // Pagination buttons
                            if ($_SESSION['bbscurrent_page'] > 1) {
                                echo "<a href='?page=" . ($_SESSION['bbscurrent_page'] - 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&lt;</a>";
                            } else {
                                echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                            }
                            echo "<span> Page " . $_SESSION['bbscurrent_page'] . " </span>";
                            if ($total_borrowed > $records_per_page && $_SESSION['bbscurrent_page'] < $total_pages) {
                                echo "<a href='?page=" . ($_SESSION['bbscurrent_page'] + 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&gt;</a>";
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
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>