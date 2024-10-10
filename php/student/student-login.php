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
    /* section{
        padding: 90px 40px;
    } */
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
                            if (isset($_SESSION['success'])) {
                                echo '<script>alert("'.$_SESSION['success'].'");</script>';
                                unset($_SESSION['success']); // remove success message from session
                            }

                            // Check if the form has been submitted
                            if (isset($_POST['submit'])) {
                                $email = $_POST['email'];
                                $password = $_POST['password'];

                                // Use prepared statements to retrieve user data
                                $stmt = $conn->prepare("SELECT * FROM student_table WHERE email = ?");
                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();

                                if (is_array($row) && !empty($row)) {
                                    // Verify password
                                    if (password_verify($password, $row['password'])) {
                                        // Login successful, redirect to dashboard
                                        $_SESSION['user_id'] = $row['student_id'];
                                        $_SESSION['full_name'] = $row['full_name'];
                                        $_SESSION['email'] = $row['email'];
                                        $_SESSION['contact_num'] = $row['contact_num'];
                                        $_SESSION['program'] = $row['program'];
                                        $_SESSION['department'] = $row['department'];
                                        header("Location: student_home.php");
                                        exit;
                                    } else {
                                        // Login failed, store error message in session
                                        $_SESSION['error'] = 'Invalid email or password';
                                        header('Location: student-login.php');
                                        exit;
                                    }
                                } else {
                                    $_SESSION['error'] = 'USER NOT FOUND!';
                                    header('Location: student-login.php');
                                    exit;
                                }
                            }

                        ?>

                        <form action="student-login.php" method="post" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            <h2>Welcome, Ka-Spartan</h2><br>
                            <div class="content">
                                <div class="mb-3">
                                    <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" placeholder="Email" style="border-radius: 16px; border: solid, 1px, black; width: auto;" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" style="border-radius: 16px; border: solid, 1px, black; width: auto;" required>
                                    </div>
                                </div>

                                <div class="mb-3" style="font-size:0.90rem;">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                <span><a class="float-end link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="forgot_password.php">Forgot Password?</a></span>
                                </div>

                                <div class="mb-2 d-flex align-items-center justify-content-center">
                                    <button type="submit" name="submit" class="btn btn-danger w-100">Login</button>
                                </div>

                                <div style="font-size: 0.90rem;">
                                    <a class="link-register float-start link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="student_register.php">Don't have an account? <b>Register</b></a>
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