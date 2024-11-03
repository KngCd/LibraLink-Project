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

</style>
<body>
    <?php
       require_once '../db_config.php';
        session_start();
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
                
                // Check if the logout button was clicked
                if (isset($_POST['submit'])) {
                    // Destroy the session
                    session_destroy();
                    // Redirect to the login page
                    header('Location: student-login.php');
                    exit;
                }
            }
    ?>

    <header class="sticky-top z-1" style="backdrop-filter: blur(5px);">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container">
                <a href="" class="navbar-brand">
                    <img class="img-fluid logo" src="../../img/cropped-libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    <?php echo 'Welcome, ' . $firstName . '!' ?>
                </a>
                <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navmenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cart">View Cart</button>
                            <a href="student_profile.php" class="btn btn-primary text-light">Profile</a>
                            <a href="logout.php" class="btn btn-danger text-light">LOGOUT</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
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
            $stmt = $conn->prepare("SELECT title, author, book_id FROM book_table WHERE book_id IN ($ids_placeholder)");
            $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
            $stmt->execute();
            $result = $stmt->get_result();

            // Display the books
            while ($book = $result->fetch_assoc()) {
                echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                echo '<span>' . htmlspecialchars($book['title']) . ' by ' . htmlspecialchars($book['author']) . '</span>';
                echo '<a href="remove_from_cart.php?book_id=' . $book['book_id'] . '" class="btn btn-danger btn-sm">Remove</a>';
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
        <button type="submit" name="submit" class="btn btn-primary">Confirm Borrowing</button>
    </form>
</div>


            </div>
        </div>
    </div>

    <main>
        <section class="text-dark m-5 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row gx-5 gy-5">
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

                        // Query all the books available
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, b.author, b.description, b.book_cover, i.stocks, i.status 
                                                    FROM book_table b 
                                                    LEFT JOIN inventory_table i ON b.book_id = i.book_id");

                        if ($query) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                // Get the number of borrowed copies
                                $borrowed_query = "SELECT COUNT(*) as borrowed FROM borrow_table WHERE book_id = '" . $row['book_id'] . "'";
                                $borrowed_result = mysqli_query($conn, $borrowed_query);
                                $borrowed_row = mysqli_fetch_assoc($borrowed_result);
                                $borrowed = $borrowed_row['borrowed'];

                                // Calculate the number of available copies
                                $available = $row['stocks'] - $borrowed;

                                // Check if the user has already borrowed this book
                                $user_id = $_SESSION['user_id']; // assuming you have a session variable for the user ID
                                $already_borrowed_query = "SELECT * FROM borrow_table WHERE book_id = '" . $row['book_id'] . "' AND student_id = '$user_id'";
                                $already_borrowed_result = mysqli_query($conn, $already_borrowed_query);
                                $already_borrowed = mysqli_num_rows($already_borrowed_result) > 0;

                                echo '<div class="col-md-4 col-lg-3 col-sm-6">
                                    <div class="book-wrapper text-center"> <!-- Add a wrapper element -->
                                        <a href="#" class="book-link" data-bs-toggle="modal" data-bs-target="#bookModal' . $row['book_id'] . '">
                                           <img src="data:image/jpeg;base64,' . base64_encode($row['book_cover']) . '" alt="Book Cover" title="' . htmlspecialchars($row['title']) . '" width="200" height="300">
                                        </a>
                                        <div class="book-label-container"> <!-- Add a container for the book label -->
                                            ';
                                            if ($available > 0 && !$already_borrowed) {
                                                echo '<div class="book-label">Available</div>';
                                            } else {
                                                if ($already_borrowed) {
                                                    echo '<div class="book-label not-available">You Already Borrowed it</div>';
                                                } else {
                                                    echo '<div class="book-label not-available">Not Available</div>';
                                                }
                                            }
                                            echo '
                                        </div>
                                    </div>
                                </div>';

                                // Book Modal
                                echo '
                                <div class="modal fade" id="bookModal' . $row['book_id'] . '" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bookModalLabel">' . $row['title'] . '</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span><h1 class="card-title">' . $row['title'] . '</h1> <h6>' . $available . ' of ' . $row['stocks'] . ' available</h6></span>
                                                        <h3 class="card-text mb-4 text-center">' . $row['author'] . '</h3>
                                                        <h5 class="card-text mb-4 text-center">' . $row['description'] . '</h5>';

                                                        if ($available > 0 && !$already_borrowed) {
                                                            // Check if the book is already in the cart
                                                            if (in_array($row['book_id'], $cart_books)) {
                                                                // Book is already in the cart
                                                                echo '<button class="btn btn-danger disabled">Already Added to Cart</button>';
                                                            } else {
                                                                // Book is available and not already in the cart
                                                                echo '<a href="add_to_cart.php?book_id=' . $row['book_id'] . '" onclick="alert(\'Book has been added to your cart!\')"><button class="btn btn-danger">Add to Cart</button></a>';
                                                            }
                                                        } else {
                                                            if ($already_borrowed) {
                                                                echo '<button class="btn btn-danger disabled">You have already borrowed it</button>';
                                                            } else {
                                                                echo '<button class="btn btn-danger disabled">' . ($available == 0 ? 'Out of stock' : $row['status']) . '</button>';
                                                            }
                                                        }

                                            echo '</div>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>';
                                                    
                                }
                        }
                        $stmt2->close();
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>