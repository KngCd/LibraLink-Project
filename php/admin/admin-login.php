<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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
    section form input{
        border-radius: 5px;
    }
    form{
        padding: 45px 40px;
        border-radius: 56px;
        width: 400px;
    }
    section{
        padding: 124px 40px;
    }
    ::placeholder {
        color: black !important; 
    }
</style>

<body>
    
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="../admin-student.php" class="navbar-brand">
                <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
            </a>
        </div>
    </nav>

    <main class="bg">
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <h1 style="font-size: 4rem;">LibraLink</h1>
                        <h4>Integrated System for Student Logging,<br>
                        Borrowing, and Inventory Management</h4>
                    </div>

                    <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                        <?php
                             // Include the database configuration file
                             require_once '../db_config.php';

                             // Configuration
                             $admin_username = 'admin'; // default admin username
                             $admin_password = 'password'; // default admin password

                             // Start the session
                             session_start();

                             // Check if error message is set in session
                             if (isset($_SESSION['error'])) {
                                 echo '<script>alert("'.$_SESSION['error'].'");</script>';
                                 unset($_SESSION['error']); // remove error message from session
                             }

                             // Check if the form has been submitted
                             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                 // Get the username and password from the form
                                 $username = mysqli_real_escape_string($conn, $_POST['username']);
                                 $password = mysqli_real_escape_string($conn, $_POST['password']);

                                 // Check if the username and password match the admin credentials
                                 if ($username == $admin_username && $password == $admin_password) {
                                     // Login successful, redirect to admin dashboard
                                     header('Location: admin_dashboard.php');
                                     exit;
                                 } else {
                                     // Login failed, store error message in session
                                     $_SESSION['error'] = 'Invalid username or password';
                                     header('Location: admin-login.php');
                                     exit;
                                 }
                             }
                        ?>

                        <!-- Display the login form -->
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            <h2 class="text-center">Welcome, Admin!</h2><br>
                            <div class="content">
                                
                                <div class="input-group mb-4">
                                    <input type="text" class="form-control" id="username" name="username" autocomplete="off" required placeholder="Username" style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>
                                
                                <div class="input-group mb-4">
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" required placeholder="Password" style="border-radius: 16px; border: solid, 1px, black; width: auto;">
                                </div>

                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" class="btn btn-danger w-100">Login</button>
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