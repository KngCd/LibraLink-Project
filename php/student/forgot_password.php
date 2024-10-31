<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        /* backdrop-filter: blur(5px); */
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
    ::placeholder{
        color: black !important;
    }
    #email-error, #password-error, #confirmPassword-error{
        color: red;
        font-size: 0.90rem;
        /* display: block; */
    }
    .input-group label{
        display: block;
        width: 100%;
    }
    .input-group input{
        border-radius: 16px; 
        border: solid, 1px, black; 
        width: auto; 
        display: block;
        border-top-left-radius: 0; 
        border-bottom-left-radius: 0;
    }
    .input-group span{
        border-radius: 16px; 
        border: solid, 1px, black; 
        width: auto; 
        border-top-right-radius: 0; 
        border-bottom-right-radius: 0;
    }
</style>

<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="student-login.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;">
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
                                unset($_SESSION['success']); // remove success message from session
                            }

                            // Check if the form has been submitted
                            if (isset($_POST['submit'])) {
                                $email = mysqli_real_escape_string($conn, $_POST['email']); // get the details from the form
                                $password = mysqli_real_escape_string($conn, $_POST['password']);
                                $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

                                if ($password !== $confirmPassword) {
                                    // $_SESSION['error'] = "Passwords do not match!";
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

                        <!-- Forgot Password Form -->
                        <form id="forgotForm" action="forgot_password.php" method="post" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            <h3>Reset Your Password</h3><br>
                            <div class="content">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" placeholder="Email">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" onclick="togglePassword()"><i class="bi bi-eye-slash-fill" id="password-icon" style="cursor: pointer;"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <span class="input-group-text" onclick="toggle()"><i class="bi bi-eye-slash-fill" id="password-icon2" style="cursor: pointer;"></i></span>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" autocomplete="off" placeholder="Confirm Password">
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
    <script src="../../js/loginValidate.js"></script>
</body>
</html>