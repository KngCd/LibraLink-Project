<?php
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    // Redirect to the admin dashboard if already logged in
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
        padding: 124px 40px;
    }
    ::placeholder {
        color: black !important; 
    }
    #username-error, #password-error{
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
    .button{
        border-radius: 30px !important;
    }
</style>

<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #dd2222;">
        <div class="container-fluid">
            <a href="../admin-student.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/librawhite.png" alt="Logo" style="height: 40px; width: auto;">
            </a>
            <a href="../admin-student.php" class="btn btn-light button" style="width: 150px;">← Back</a>
        </div>
    </nav>

    <main class="bg">
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <img class="img-fluid" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 150px; width: auto;">
                        <p class="text-center text-break fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 fs-3 d-xxl-block d-xl-block d-lg-block d-md-none d-sm-none d-none" style="font-weight: 650;">
                            Integrated System for Student Logging, <br>Borrowing, and Inventory Management
                        </p>
                    </div>

                    <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                        <?php
                            // Include the database configuration file
                            require_once '../db_config.php';

                            // Configuration
                            $admin_username = 'admin'; // default admin username
                            $admin_password = 'password'; // default admin password

                            // session_start(); // Start the session at the beginning of the script

                            // Initialize attempts if it doesn't exist
                            if (!isset($_SESSION['attempts'])) {
                                $_SESSION['attempts'] = 0;
                            }

                            // Initialize the timestamp for lockout if it doesn't exist
                            if (!isset($_SESSION['lockout_time'])) {
                                $_SESSION['lockout_time'] = null;
                            }

                            // Check if the user is locked out
                            if ($_SESSION['lockout_time'] !== null) {
                                $time_since_lockout = time() - $_SESSION['lockout_time'];

                                // If 3 minutes have passed, reset attempts and lockout time
                                if ($time_since_lockout >= 180) {
                                    $_SESSION['attempts'] = 0; // Reset attempts
                                    $_SESSION['lockout_time'] = null; // Reset lockout time
                                    // $_SESSION['is_admin'] = true;
                                }
                                // } else {
                                //     // User is still locked out
                                //     echo "<p>You are locked out. Please try again in " . (180 - $time_since_lockout) . " seconds.</p>";
                                //     exit; // Stop further processing
                                // }
                            }

                            // Check if the form has been submitted
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                // Get the username and password from the form
                                $username = mysqli_real_escape_string($conn, $_POST['username']);
                                $password = mysqli_real_escape_string($conn, $_POST['password']);

                                // Check if the username and password match the admin credentials
                                if ($username == $admin_username && $password == $admin_password) {
                                    // Login successful, reset attempts and lockout time
                                    $_SESSION['attempts'] = 0; // Reset attempts on successful login
                                    $_SESSION['lockout_time'] = null; // Reset lockout time
                                    $_SESSION['is_admin'] = true; // Set this session variable to indicate admin is logged in

                                    // header('Location: admin_dashboard.php');
                                    echo "<script>window.location.href = 'admin_dashboard.php';</script>";
                                    exit;
                                } else {
                                    // Login failed
                                    $_SESSION['attempts']++; // Increment attempt counter on invalid login

                                    // Check if maximum attempts reached
                                    if ($_SESSION['attempts'] >= 5) {
                                        $_SESSION['lockout_time'] = time(); // Set lockout time to current time
                                    }

                                    // Redirect to the login page with an error message
                                    // header('Location: admin-login.php');
                                    echo "<script>window.location.href = 'admin-login.php';</script>";
                                    exit;
                                }
                            }

                            // // Check if the user is locked out
                            // if ($_SESSION['lockout_time'] !== null) {
                            //     // Calculate the time since lockout
                            //     $time_since_lockout = time() - $_SESSION['lockout_time'];

                            //     // If 3 minutes have passed, reset attempts and lockout time
                            //     if ($time_since_lockout >= 180) {
                            //         $_SESSION['attempts'] = 0; // Reset attempts
                            //         $_SESSION['lockout_time'] = null; // Reset lockout time
                            //     }
                            // }
                        ?>

                        <!-- Display the login form -->
                        <form id="adminForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                            <h2 class="text-center">Welcome, Admin!</h2><br>
                            <div class="content">
                                
                                <div class="input-group mb-4">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Username">
                                </div>
                                
                                <div class="input-group mb-4">
                                    <span class="input-group-text" onclick="togglePassword()"><i class="bi bi-eye-slash-fill" id="password-icon" style="cursor: pointer;"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password">
                                </div>

                                <div class="mb-3">
                                    <!-- <?php if(isset($error)) { ?>
                                        <div id="invalid" style="display: block; color: red;"><?php echo $error; ?></div>
                                    <?php } ?> -->
                                    <div id="attempt" style="display: none;"></div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" id="login-button" class="btn btn-danger w-100">Login</button>
                                </div>

                            </div>
                        </form>

                        <script>
                            window.onload = function() {
                                const failedAttempt = document.getElementById('attempt');
                                const attempts = <?php echo isset($_SESSION['attempts']) ? $_SESSION['attempts'] : 0; ?>;
                                const lockoutTime = <?php echo isset($_SESSION['lockout_time']) ? $_SESSION['lockout_time'] : 'null'; ?>;
                                const maxAttempts = 5; // Maximum allowed attempts

                                // Check if the user is locked out
                                if (lockoutTime !== null) {
                                    const lockoutDuration = 180; // 180 seconds lockout duration

                                    // Function to update the countdown
                                    function updateCountdown() {
                                        const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
                                        const remainingTime = lockoutDuration - (currentTime - lockoutTime); // Calculate remaining time

                                        if (remainingTime > 0) {
                                            failedAttempt.style.display = 'block';
                                            failedAttempt.style.color = 'red';

                                            const minutes = Math.floor(remainingTime / 60);
                                            const seconds = remainingTime % 60;
                                            failedAttempt.textContent = 'Maximum attempts reached. Please try again in ' + 
                                                minutes + ':' + (seconds < 10 ? '0' + seconds : seconds);
                                            
                                            // Schedule the next update
                                            setTimeout(updateCountdown, 1000); // Update every second
                                        } else {
                                            failedAttempt.textContent = ''; // Clear the message after the timer ends
                                            document.getElementById('login-button').disabled = false; // Enable the button again
                                        }
                                    }

                                    // Start the countdown
                                    updateCountdown();
                                    document.getElementById('login-button').disabled = true; // Disable login button
                                    return; // Exit early if locked out
                                }

                                // Display attempts remaining
                                if (attempts > 0) {
                                    failedAttempt.style.display = 'block';
                                    failedAttempt.style.color = 'red';
                                    failedAttempt.textContent = 'Invalid credentials. Attempts remaining: ' + (maxAttempts - attempts);

                                    // Disable login button if maximum attempts reached
                                    if (attempts >= maxAttempts) {
                                        document.getElementById('login-button').disabled = true;
                                        failedAttempt.textContent = 'Maximum attempts reached. Please try again in 3 minutes';
                                    }
                                }
                            };
                        </script>
                        
                    </div>
                </div>
            </div>
        </section>
    </main>

    

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/loginValidate.js"></script>
</body>
</html>