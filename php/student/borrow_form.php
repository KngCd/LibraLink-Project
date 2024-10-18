<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar-brand{
        font-family: 'Times New Roman', Times, serif;
        font-size: 30px;
        color: black;
    }
    .navbar-brand:hover{
        color: black;
        text-decoration: underline;
    }
    .logo {
        height: 70px;
        margin: 0 10px;
        position: relative;
        top: 30px;
        transform: translateY(-50%);
    }
    body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
        section form input{
        border-radius: 5px;
    }
    form{
        padding: 45px 40px;
        border-radius: 56px;
        width: 400px;
    }
    ::placeholder{
        color: black !important;
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

                $book_id = $_GET['book_id'];

                // retrieve the book information
                $stmt = $conn->prepare("SELECT * FROM book_table WHERE book_id = ?");
                $stmt->bind_param("s", $book_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
            }
    ?>

    <header class="sticky-top z-1" style="backdrop-filter: blur(5px);">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container">
                <a href="student_home.php" class="navbar-brand">
                    <img class="logo" src="../../img/bsulogo.png" alt="Logo">
                    <?php echo 'Welcome, ' . $full_name . '!' ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
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
                <div class="row">
                    <?php
                        if (isset($_POST['submit'])) {
                            // Get today's date
                            $today = date("Y-m-d");

                            // Calculate due date
                            function calculateDueDate($startDate, $days) {
                                $dueDate = $startDate;
                                $dayCount = 0;

                                while ($dayCount < $days) {
                                    $dueDate = date("Y-m-d", strtotime("+1 day", strtotime($dueDate)));
                                    $dayOfWeek = date("w", strtotime($dueDate));
                                    if ($dayOfWeek != 0) { // 0 represents Sunday
                                        $dayCount++;
                                    }
                                }

                                return $dueDate;
                            }

                            $dueDate = calculateDueDate($today, 7);

                            $book_id = $_POST['book_id'];
                            $user_id = $_POST['user_id'];

                            // Insert into borrow_table
                            $query = "INSERT INTO borrow_table (student_id, book_id, status, penalty, date_borrowed, due_date)
                                    VALUES ('$user_id', '$book_id', 'Active', 'None', '$today', '$dueDate')";

                            // Execute the query and check for errors
                            if (mysqli_query($conn, $query)) {
                                echo "<script>alert('Borrow Successful!'); window.location.href='student_home.php';</script>";
                            } else {
                                echo "Error: " . mysqli_error($conn);
                                echo "<script>alert('Borrow Unsuccessful!');</script>";
                            }
                        }
                            // Close the connection
                            mysqli_close($conn);
                    ?>

                    <!-- Borrow Form -->
                    <div class="form-container col-12 d-flex align-items-center justify-content-center">
                        <form action="borrow_form.php" method="post" enctype="multipart/form-data" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            
                            <h2>Fill-up Form</h2>
                            <h6 class="text-center">Please check your details below</h6>

                            <input type="hidden" class="form-control" value="<?php echo $book_id?>" name="book_id" id="book_id">
                            <input type="hidden" class="form-control" value="<?php echo $user_id?>" name="user_id" id="user_id">

                            <div class="content">
                                
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="<?php echo $full_name?>" name="username" id="username" autocomplete="off" required style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>

                                <div class="input-group mb-2">
                                    <input type="email" class="form-control" value="<?php echo $email?>" name="email" id="email" autocomplete="off" required style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>

                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="<?php echo $contact_num?>" name="contact" id="contact" autocomplete="off" required style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>
                                
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="<?php echo $program?>" name="program" id="program" autocomplete="off" required style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>
                                
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="<?php echo $department?>" name="department" id="department" autocomplete="off" required style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <input class="form-control me-1 w-40" disabled value="<?php echo $row['title']?>" style="border-radius: 16px; border: solid, 1px, black;">
                                    <input class="form-control w-40" disabled value="<?php echo $row['author']?>" style="border-radius: 16px; border: solid, 1px, black;">
                                </div>
                                
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" name="submit" class="btn btn-danger w-100">Confirm</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>