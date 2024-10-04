<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        /* backdrop-filter: blur(5px); */
    }
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
    .bg {
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
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

                // You can also retrieve more user data from the database if needed
                $stmt = $conn->prepare("SELECT * FROM student_table WHERE student_id = ?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                // // Display additional user data
                // echo "<br>Program: " . $row['program'];
                // echo "<br>Department: " . $row['department'];
                
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

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="admin-login.php" class="navbar-brand">
                <img class="logo" src="../../img/bsulogo.png" alt="Logo">
                <?php echo 'Welcome, ' . $full_name . '!' ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-2">
                        <form action="student_home.php" method="post">
                                <button type="submit" class="btn btn-primary" name="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

        <main class="bg">
        <section class="text-dark p-5 d-flex align-items-center justify-content-center vh-100" style="position: relative;">
            <div class="container">
                <div class="row">
                    <div class="d-flex align-items-center justify-content-center col-lg-4 col-md-6 col-sm-12 w-100">
                        <div class="card me-4">
                            <div class="card-body">
                                <h5 class="card-title">Student Info</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><?php
                                                        echo "Welcome, <b>$full_name!</b>";
                                                        echo "<br>Student ID: $user_id";
                                                        echo "<br>Email: $email";?></h6>
                                <!-- <button class="btn btn-primary" popovertarget="total-register">View</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>