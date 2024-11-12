<?php
session_start();
?>

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
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.js"></script>
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
    #otp-error{
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
            <a href="student-login.php" class="navbar-brand">
                <img class="img-fluid logo" src="../../img/librawhite.png" alt="Logo" style="height: 40px; width: auto;">
            </a>
            <a href="student-login.php" class="btn btn-light button fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 fs-6">← Back</a>
        </div>
    </nav>

    <main class="bg">
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="text-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex flex-column justify-content-center
                    mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                        <img class="img-fluid" src="../../img/libra2-cropped.png" alt="Logo" style="max-height: 150px; width: auto;">
                        <p class="text-center text-break fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 fs-3 d-xxl-block d-xl-block d-lg-block d-md-none d-sm-none d-none" style="font-weight: 650;">
                            Integrated System for Student Logging, <br>Borrowing, and Inventory Management
                        </p>
                    </div>

                    <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                            <?php
                                // Include Composer autoloader
                                use Infobip\Configuration;
                                use Infobip\Api\SmsApi;
                                use Infobip\Model\SmsDestination;
                                use Infobip\Model\SmsTextualMessage;
                                use Infobip\Model\SmsAdvancedTextualRequest;

                                require __DIR__ . '../../../vendor/autoload.php';
                                require_once '../db_config.php';

                                if (isset($_GET['otp_sent']) && $_GET['otp_sent'] == 'true') {
                                    // Show OTP form
                                    $student_id = $_GET['student_id']; // Get student ID from query
                                }

                                if (isset($_POST['otp']) && isset($_GET['student_id'])) {
                                    $enteredOtp = mysqli_real_escape_string($conn, $_POST['otp']);
                                    $student_id = $_GET['student_id'];  // Get student_id from query string

                                    $sql = "SELECT otp, expiry_time FROM otp_requests WHERE student_id = '$student_id' ORDER BY created_at DESC LIMIT 1";
                                    $result = mysqli_query($conn, $sql);
                                    $row = mysqli_fetch_assoc($result);
                                
                                    if ($row) {
                                        $current_time = time();
                                        
                                        if ($current_time > $row['expiry_time']) {
                                            // OTP expired, delete from DB
                                            $delete_sql = "DELETE FROM otp_requests WHERE student_id = '$student_id' AND otp = '{$row['otp']}'";
                                            mysqli_query($conn, $delete_sql);
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'OTP has expired. Please request a new one.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay',
                                                    confirmButtonColor: '#dc3545',
                                                }).then(function() {
                                                    window.location.href = 'forgot_password_reset.php';
                                                });
                                            </script>";
                                        } else {
                                            if ($enteredOtp == $row['otp']) {
                                                // OTP is valid, show password reset form
                                                echo "<script>window.location.href = 'reset_pass.php?otp_verified=true&student_id=$student_id';</script>";
                                                exit();
                                            } else {
                                                echo "<script>
                                                    Swal.fire({
                                                        title: 'Error!',
                                                        text: 'Please enter the correct OTP.',
                                                        icon: 'error',
                                                        confirmButtonText: 'Okay',
                                                        confirmButtonColor: '#dc3545',
                                                    }).then(function() {
                                                        window.location.href = 'verify_otp.php?student_id=$student_id';
                                                    });
                                                </script>";
                                            }
                                        }
                                    } else {
                                        echo "<script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'No OTP found. Please request a new one.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                            }).then(function() {
                                                window.location.href = 'forgot_password_reset.php';
                                            });
                                        </script>";
                                    }
                                }
                                ?>

                                <!-- HTML Form for OTP Verification -->
                                <form id="otpSection" action="verify_otp.php?student_id=<?php echo $_GET['student_id']; ?>" method="post" style="border-radius: 16px; background: #efefef; border-style: solid; border-color: black;">
                                    <h3>Enter OTP</h3><br>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="text" class="form-control" id="otp" name="otp" autocomplete="off" placeholder="Enter OTP">
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="submit" class="btn btn-danger w-100">Verify OTP</button>
                                    </div>
                                </form>


                            <!-- Display Error and Success Messages -->
                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<script>alert("' . $_SESSION['error'] . '");</script>';
                                unset($_SESSION['error']);
                            }
                            if (isset($_SESSION['success'])) {
                                echo '<script>alert("' . $_SESSION['success'] . '");</script>';
                                unset($_SESSION['success']);
                            }
                            ?>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/loginValidate.js"></script>
    <script>
        $(document).ready(function(){

            $("#otpSection").validate({
                rules:{
                    otp:{
                        required: true,
                    }
                },
                messages:{
                    otp:{
                        required: "Please enter your OTP"
                    }
                },
                highlight: function(element) { // this is when the form rules is not met
                    $(element).addClass('is-invalid'); // it will add this built-in class for invalid class in bootstrap
                },
                unhighlight: function(element) { 
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

        });
    </script>
</body>
</html>