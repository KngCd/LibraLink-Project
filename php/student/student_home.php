<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header('Location: student-login.php');
    exit;
}
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
    <button id="backToTop" class="btn btn-danger" onclick="scrollToTop()" style="border-radius: 30px !important;">↑</button>

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
                <!-- <div data-bs-theme="dark"> -->
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                <!-- </div> -->
            </div>
            <div class="offcanvas-body">
                <div class="sidebar-item d-flex align-items-center justify-content-center">
                    <img class="img-fluid" src="../../img/librawhite.png" alt="Logo" style="height: 30px;">
                </div>
                <div class="sidebar-item">
                    <a href="#" class="sidebar-link" data-target="home" id="active">
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
                    <a href="borrowed_books.php" class="sidebar-link">
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

    <main>
        <section>
            <div class="container mt-3">
                <?php
                    // Capture search and filter inputs
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $selected_category = isset($_GET['category']) ? $_GET['category'] : '';

                    // Fetch distinct programs
                    $category_query = mysqli_query($conn, "SELECT DISTINCT category FROM book_table");
                    $categories = [];
                    while ($row = mysqli_fetch_assoc($category_query)) {
                        $categories[] = $row['category'];
                    }

                    // Build the SQL query based on search and category
                    $where_clauses = [];

                    if (!empty($search)) {
                        $search = mysqli_real_escape_string($conn, $search);
                        $where_clauses[] = "(title LIKE '%$search%' OR author LIKE '%$search%')";
                    }

                    if (!empty($selected_category)) {
                        $where_clauses[] = "category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                    }

                    $where_query = '';
                    if (count($where_clauses) > 0) {
                        $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                    }
                ?>
                <form class="d-flex align-items-center" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search for Title or Author" aria-label="Search" value="<?= htmlspecialchars($search) ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">

                    <select name="category" class="form-select w-50 me-2" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                        <option value="">All Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category === $category ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn btn-outline-danger" type="submit">Search</button>
                </form>
            </div>
        </section>

        <section class="text-dark mt-3 mx-4 d-flex align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="row">
                    <?php
                        // Fetch the user's cart from the database
                        $stmt2 = $conn->prepare("SELECT book_id FROM cart WHERE user_id = ?");
                        $stmt2->bind_param("i", $_SESSION['user_id']);
                        $stmt2->execute();
                        $result = $stmt2->get_result();

                        $cart_books = [];
                        while ($row = $result->fetch_assoc()) {
                            $cart_books[] = $row['book_id'];
                        }

                        // Fetch the total number of books based on the filter
                        $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM book_table $where_query");
                        $total_row = mysqli_fetch_assoc($total_query);
                        $total_books = $total_row['total'];

                        // Query all the books available
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, b.author, b.description, b.book_cover, b.category, i.stocks, i.status 
                                                    FROM book_table b 
                                                    LEFT JOIN inventory_table i ON b.book_id = i.book_id $where_query");

                        echo "<div class='container d-flex align-items-start fs-4'>Total result: $total_books</div>";

                        if ($query) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                // Get the number of borrowed copies
                                $borrowed_query = "SELECT COUNT(*) as borrowed 
                                    FROM borrow_table 
                                    WHERE book_id = '" . $row['book_id'] . "' AND status != 'returned'";
                                $borrowed_result = mysqli_query($conn, $borrowed_query);
                                $borrowed_row = mysqli_fetch_assoc($borrowed_result);
                                $borrowed = $borrowed_row['borrowed'];

                                // Calculate the number of available copies
                                $available = $row['stocks'] - $borrowed;

                                // Check if the user has already borrowed this book (not returned)
                                $user_id = $_SESSION['user_id']; // assuming you have a session variable for the user ID
                                $already_borrowed_query = "SELECT * FROM borrow_table 
                                                        WHERE book_id = '" . $row['book_id'] . "' AND student_id = '$user_id' AND status != 'returned'";
                                $already_borrowed_result = mysqli_query($conn, $already_borrowed_query);
                                $already_borrowed = mysqli_num_rows($already_borrowed_result) > 0;

                                // Displaying the book information as before
                                echo '<div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 mt-2 mt-sm-2">
                                    <div class="book-wrapper text-center"> <!-- Add a wrapper element -->
                                        <a href="#" class="book-link" data-bs-toggle="modal" data-bs-target="#bookModal' . $row['book_id'] . '">
                                            <img src="data:image/jpeg;base64,' . base64_encode($row['book_cover']) . '" alt="Book Cover" title="' . htmlspecialchars($row['title']) . '" width="200" height="300">
                                        </a>
                                        <div class="book-label-container"> <!-- Add a container for the book label -->
                                            <div class="mb-2"><h3 class="mb-0"><b>' . $row['title'] . '</b></h3>' . $row['author'] . '</div>';

                                            // Create a flex container for the label
                                            echo '<div class="d-flex justify-content-center">'; // Flex container
                                                if ($available > 0 && !$already_borrowed) {
                                                    echo '<div class="book-label w-50 text-center">Available</div>';
                                                } else {
                                                    if ($already_borrowed) {
                                                        echo '<div class="book-label not-available w-75 text-center">You Already Borrowed it</div>';
                                                    } else {
                                                        echo '<div class="book-label not-available w-50 text-center">Not Available</div>';
                                                    }
                                                }
                                            echo '</div>'; // End flex container
                                            echo '
                                        </div>
                                    </div>
                                </div>';

                                // Book Modal (remains unchanged)
                                echo '
                                <div class="modal fade" id="bookModal' . $row['book_id'] . '" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="bookModalLabel" style="color: #dd2222; font-weight: 700;">Book Information</h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4 text-center">
                                                                <img src="data:image/jpeg;base64,' . base64_encode($row['book_cover']) . '" alt="Book Cover" width="200" height="300" class="me-3">
                                                                <h6>' . $available . ' of ' . $row['stocks'] . ' available</h6>';
                                                                
                                                                // Buttons based on availability
                                                                if ($available > 0 && !$already_borrowed) {
                                                                    // Check if the book is already in the cart
                                                                    if (in_array($row['book_id'], $cart_books)) {
                                                                        echo '<button class="btn btn-danger disabled">Already Added to Cart</button>';
                                                                    } else {
                                                                        echo '<a href="add_to_cart.php?book_id=' . $row['book_id'] . '" 
                                                                                onclick="event.preventDefault(); addToCart(' . $row['book_id'] . ')">
                                                                                <button class="btn btn-danger">Add to Cart</button>
                                                                            </a>';                                   
                                                                        // echo '<a href="add_to_cart.php?book_id=' . $row['book_id'] . '" onclick="alert(\'Book has been added to your cart!\')"><button class="btn btn-danger">Add to Cart</button></a>';                                 
                                                                    }
                                                                } else {
                                                                    if ($already_borrowed) {
                                                                        echo '<button class="btn btn-danger disabled">You have already borrowed it</button>';
                                                                    } else {
                                                                        echo '<button class="btn btn-danger disabled">' . ($available == 0 ? 'Out of stock' : $row['status']) . '</button>';
                                                                    }
                                                                }
                                                                
                                                            echo '</div>
                                                            <div class="col-8">
                                                                <div class="mb-2">
                                                                    <label for="title"><h4 style="font-weight: bold;">Title</h4></label>
                                                                    <input type="text" name="title" class="form-control px-3 mb-3" value="' . htmlspecialchars($row['title']) . '" readonly style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                                                </div>
                                                                <div class="mt-2">
                                                                    <label for="author"><h4 style="font-weight: bold;">Author</h4></label>
                                                                    <input type="text" name="author" class="form-control px-3 mb-3" value="' . htmlspecialchars($row['author']) . '" readonly style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="category"><h4 style="font-weight: bold;">Category</h4></label>
                                                                    <input type="text" name="category" class="form-control px-3 mb-3" value="' . htmlspecialchars($row['category']) . '" readonly style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="desc"><h4 style="font-weight: bold;">Description</h4></label>
                                                                    <div style="border: solid 1px black; border-radius: 16px; padding: 10px; height: 100px; overflow: hidden; position: relative;">
                                                                        <div class="form-control textdesc" id="desc" readonly style="height: 100%; overflow: auto; resize: none; border: none; padding: 0; width: calc(100% - 20px);">
                                                                            ' . nl2br(htmlspecialchars($row['description'])) . ' 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            }

                        if (mysqli_num_rows($query) === 0) {
                                echo "<div class='container d-flex align-items-center justify-content-center fs-2'>No records found</div>";
                            }
                        }
                        $stmt2->close();
                    ?>
                </div>

                <script>
                    function addToCart(bookId) {
                        // Trigger SweetAlert confirmation
                        Swal.fire({
                            title: 'Success!',
                            text: 'Book has been added to your cart!',
                            icon: 'success',
                            confirmButtonText: 'Okay',
                            confirmButtonColor: '#198754',
                        }).then((result) => {
                            // After clicking "OK", redirect to add_to_cart.php with the book_id
                            if (result.isConfirmed) {
                                window.location.href = 'add_to_cart.php?book_id=' + bookId;
                            }
                        });
                    }
                </script>
            </div>
            <!-- </div> -->
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
</body>
</html>