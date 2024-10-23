<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
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
                $full_name = $_SESSION['full_name'];
                $email = $_SESSION['email'];
                $contact_num = $_SESSION['contact_num'];
                $program = $_SESSION['program'];
                $department = $_SESSION['department'];

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
                <a href="student-login.php" class="navbar-brand">
                    <img class="img-fluid logo" src="../../img/cropped-libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    <?php echo 'Welcome, ' . $full_name . '!' ?>
                </a>
                <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navmenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2">
                            <a href="student-login.php" class="btn btn-danger text-light">LOGOUT</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="text-dark m-5 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row gx-5 gy-5">
                    <?php
                        // Query all the books available
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, b.author, b.description, i.stocks, i.status 
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
                                            <img src="../../img/book' . $row['book_id'] . '.jpg" alt="" width="200" height="300">
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
                                    echo '<a href="borrow_form.php?book_id=' . $row['book_id'] . '"><button class="btn btn-success">Borrow</button></a>';
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
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>

<?php 
                                // echo '<div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                                //     <div class="card">
                                //         <div class="card-body">
                                //             <span><h1 class="card-title">' . $row['title'] . '</h1> <h6>' . $available . ' of ' . $row['stocks'] . ' available</h6></span>
                                //             <h3 class="card-text mb-4 text-center">' . $row['author'] . '</h3>
                                //             <h5 class="card-text mb-4 text-center">' . $row['description'] . '</h5>';

                                // if ($available > 0 && !$already_borrowed) {
                                //     echo '<a href="borrow_form.php?book_id=' . $row['book_id'] . '"><button class="btn btn-success">Available</button></a>';
                                // } else {
                                //     if ($already_borrowed) {
                                //         echo '<button class="btn btn-danger disabled">You have already borrowed it</button>';
                                //     } else {
                                //         echo '<button class="btn btn-danger disabled">' . ($available == 0 ? 'Out of stock' : $row['status']) . '</button>';
                                //     }
                                // }
                                // echo '</div>';
                                // echo '</div>';
                                // echo '</div>';
?>