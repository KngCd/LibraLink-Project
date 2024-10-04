<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    header div h4{
    width: 100px;
    height: 100px;
    border-radius: 50%;
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
</style>

<body>
    
    <header class="container-fluid">
        <div class="bg-dark""><h4 class="bg-secondary text-light justify-content-center d-flex align-items-center">Logo</h4></div>
    </header>

    <main>
        <div class="container-fluid">
                <section class="bg-success">
                    <div class="row">
                        <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                        mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                            <a href="../admin-student.php" class="text-dark"><h1 style="font-size: 4rem;">LibraLink</h1></a>
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
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="bg-warning">
                                <h2 class="text-center">Welcome, Admin!</h2><br>
                                <div class="content">
                                    <div class="mb-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Username" style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="submit" class="btn btn-primary w-40">Login</button>
                                    </div>

                                    <?php //if (isset($error)): ?>
                                        <!-- <div class="alert alert-danger"><?//= $error ?></div> -->
                                    <?php //endif; ?>
                                </div>
                            </form>
                        </div>

                    </div>
                </section>
        </div>
    </main>

    

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>