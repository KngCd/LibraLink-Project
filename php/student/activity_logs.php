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
    #act-logs{
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
                <a href="" class="navbar-brand text-light">
                    <img class="img-fluid logo" src="../../img/cropped-libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    <?php echo 'Welcome, ' . $firstName . '!' ?>
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

        <div class="offcanvas offcanvas-end text-light" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
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
                    <a href="borrowed_books.php" class="sidebar-link"  id="active">
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Borrowed Books</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="sidebar-link">
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
        <section class="container-fluid mt-3">
            <div id="act-logs" class="container p-3">
                <div class="container p-3">
                    <?php
                    // Fetch distinct dates for the specific student
                    $actLogs_query = "SELECT DISTINCT DATE(timestamp) AS log_date FROM activity_logs WHERE student_id = '$user_id' ORDER BY log_date DESC";
                    $result_query = $conn->query($actLogs_query);
                    ?>

                    <div class="accordion" id="accordionExample">
                        <?php if ($result_query->num_rows > 0): ?>
                            <?php $index = 0; ?>
                            <?php while ($date = $result_query->fetch_assoc()): ?>
                                <?php
                                // Fetch logs for the current date for the specific student
                                $dateValue = $date['log_date'];
                                $logQuery = "SELECT action, details, timestamp FROM activity_logs WHERE DATE(timestamp) = '$dateValue' AND student_id = '$user_id' ORDER BY timestamp"; // Ensure student_id is filtered here
                                $logResult = $conn->query($logQuery);

                                // Format the date
                                $formattedDate = date('F j, Y', strtotime($dateValue)); // e.g., "November 4, 2024"
                                ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $index ?>">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                            <?= htmlspecialchars($formattedDate) ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php if ($logResult->num_rows > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>Action</th>
                                                                <th>Details</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-group-divider">
                                                            <?php while ($log = $logResult->fetch_assoc()): ?>
                                                                <tr>
                                                                    <td class="text-muted"><?= htmlspecialchars($log['action']) ?></td>
                                                                    <td class="text-muted"><?= htmlspecialchars($log['details']) ?></td>
                                                                    <td class="text-muted"><?= date('g:i A', strtotime($log['timestamp'])) ?></td> <!-- Converted to 12-hour format -->
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted">No logs for this date.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $index++; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No activity logs found.</p>
                        <?php endif; ?>
                    </div>

                    <?php
                    $conn->close(); // Close the connection
                    ?>                  
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>