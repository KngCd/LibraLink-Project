<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        padding: 90px 40px;
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
                    <div class="text-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex flex-column justify-content-center
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <h1 style="font-size: 4rem;">LibraLink</h1>
                        <h4>Integrated System for Student Logging,<br>
                        Borrowing, and Inventory Management</h4>
                    </div>

                    <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                        <?php
                            // Include the database configuration file
                            require_once '../db_config.php';

                            session_start();

                            if(isset($_SESSION['error'])){
                                echo '<script>alert("'.$_SESSION['error'].'");</script>';
                                unset($_SESSION['error']); // remove error message from session
                            }
                            if(isset($_SESSION['success'])){
                                echo '<script>alert("'.$_SESSION['success'].'");</script>';
                                unset($_SESSION['success']); // remove error message from session
                            }

                            // Check if the form has been submitted
                            if (isset($_POST['submit'])) {
                                $email = mysqli_real_escape_string($conn, $_POST['email']);
                                $password = mysqli_real_escape_string($conn, $_POST['password']);
                                $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

                                if ($password !== $confirmPassword) {
                                    $_SESSION['error'] = "Passwords do not match!";
                                    header('Location: forgot_password.php');
                                    exit;
                                } else {
                                    $confirmPassword = $_POST['confirmPassword'];
                                    $hashed_password = password_hash($confirmPassword, PASSWORD_DEFAULT);

                                    // Find the user in the student table
                                    $result = mysqli_query($conn, "SELECT * FROM student_table WHERE email='$email'") or die("Select Error");
                                    $row = mysqli_fetch_assoc($result);

                                    if (!empty($row)) {
                                        // If the user is found, update his/her password
                                        $sql = "UPDATE student_table SET password = '$hashed_password' WHERE email = '$email'";
                                        $result = mysqli_query($conn, $sql);
                                        if ($result) {
                                            $_SESSION['success'] = "Password reset successfully!";
                                            header("Location: student-login.php");
                                            exit;
                                        } else {
                                            $_SESSION['error'] = "Password reset unsuccessfully!";
                                            header('Location: ' . $_SERVER['PHP_SELF']);
                                            exit;
                                        }
                                    }
                                }
                            }

                        ?>
                        <form action="forgot_password.php" method="post" style="background: rgba(97, 97, 97, 0.2); backdrop-filter: blur(5px);">
                            <h2>Reset Your Password</h2><br>
                            <div class="content">
                                <div class="mb-4">
                                    <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" placeholder="Email" style="border-radius: 0.375rem; width: auto;" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" style="border-radius: 0.375rem; width: auto;" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" autocomplete="off" placeholder="Confirm Password" style="border-radius: 0.375rem; width: auto;" required>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" name="submit" class="btn btn-primary w-40">Confirm</button>
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