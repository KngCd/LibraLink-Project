<?php
// Include the PHP QR Code library
require_once 'phpqrcode/qrlib.php'; // Update this path as necessary
require_once '../fpdf_lib/fpdf.php';
require_once '../db_config.php'; // Include your database configuration

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: student-login.php"); // Redirect to login if not logged in
    exit;
}

// Get user information from the session
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
$contact_num = $_SESSION['contact_num'];
$program = $_SESSION['program'];
$department = $_SESSION['department'];

if (isset($_POST['download_pdf']) && isset($_POST['qr_code_path'])) {
    $qrCodeFilePath = $_POST['qr_code_path'];

    // Create a new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set the logo path (adjust the path as necessary)
    $logoPath = '../../img/libra2.png'; // Change this to the correct path

    // Get page width for centering
    $pageWidth = $pdf->GetPageWidth();
    $logoWidth = 60; // Adjust this for the logo width (in mm)
    
    // Add logo and center it
    $pdf->Image($logoPath, ($pageWidth - $logoWidth) / 2, 10, $logoWidth); // Centered at top

    // Add the QR code image
    $pdf->Image($qrCodeFilePath, ($pageWidth - 100) / 2, 40, 100); // Centered below the logo

    // Set font for the name
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Ln(130); // Move down to position the name below the QR code
    $pdf->Cell(0, 10, $first_name . ' ' . $last_name, 0, 1, 'C'); // Centered

    // Output the PDF to the browser for download
    $pdf->Output('D', 'QRCode_' . $first_name . '_' . $last_name . '.pdf'); // 'D' for download
    exit; // Make sure to exit after outputting the PDF
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraLink - Student Profile</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

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
    body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    #firstName-error, #lastName-error, #email-error, #password-error, #confirmPassword-error, #oldPassword-error,
    #contact_num-error, #program-error, #department-error, #cor-error, #id-error, #pic-error, #newPassword-error{
        color: red;
        /* font-size: 0.90rem; */
        /* display: block; */
    }
    .input-group label{
        display: block;
        width: 100%;
    }
    .menu {
        font-size: 30px;
        cursor: pointer;
        background-color: #dd2222;
        color: white;
        padding: 10px 15px;
        border: none;
    }
    .offcanvas {
        width: 300px !important;
        background-color: #dd2222;
    }
    .content {
        display: none; /* Hide all content sections by default */
    }

    .content.active {
        display: block; /* Show the active content section */
    }
    /* .main-content {
        display: flex;
        /* flex-direction: column; */
        /* align-items: center;
        justify-content: center;
        min-height: 100vh; /* Full height of the viewport */
    /* } */
    .sidebar-item {
        padding: 10px;
    }
    .sidebar-link {
        display: block;
        padding: 10px;
        background-color: #dd2222;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .sidebar-link:hover, .dashboard-link:focus, #active {
        background-color: #ca1d1d;
    }

    .sidebar-link i {
        margin-right: 10px;
        font-size: 18px;
    }
    .sidebar-link span {
        font-size: 20px;
    }
    
    .offcanvas-body::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    .offcanvas-body::-webkit-scrollbar-thumb {
        background-color: #ca1d1d;
        border-radius: 5px;
        box-shadow: none;
        cursor: pointer;
    }
    .offcanvas-body::-webkit-scrollbar-track {
        background-color: transparent;
        border-radius: 0;
        box-shadow: none;
    }
    .button{
        border-radius: 30px !important;
    }
</style>

</head>

<body>

<!-- Moved the php script inside the HTML for the SWAL -->
<?php
    // Fetch profile picture from the database
    $stmt = $conn->prepare("SELECT profile_pic FROM student_table WHERE student_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $profile_pic = $row['profile_pic'];

    // Initialize QR code path
    $qrCodeFilePath = 'qrcodes/' . $user_id . '-' . $first_name . '.png'; // Ensure the qrcodes directory exists and is writable

    // Check if the button is clicked to generate QR code
    $qrCodeGenerated = false; // Flag to check if QR code was generated
    $pdfready = false;

    if (isset($_POST['generate_qr'])) {
        // Data to be encoded in the QR code in JSON format
        $qrData = json_encode([
            'student_id' => $user_id,
            'name' => $first_name . ' ' . $last_name,
            'email' => $email,
            'contact' => $contact_num,
            'program' => $program,
            'department' => $department
        ]);

        // Generate QR code
        QRcode::png($qrData, $qrCodeFilePath, QR_ECLEVEL_L, 4);
        $qrCodeGenerated = true; // Set flag to true after generation
        $pdfready = true; // Set flag to true
    }

    // Check if form is submitted to update profile
    if (isset($_POST['update_profile'])) {
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $email = $_POST['email'];
        $contact_num = $_POST['contact_num'];
        $program = $_POST['program'];
        $department = $_POST['department'];

        // Check if email already exists in student_table or verification_table
        $emailCheckStmt = $conn->prepare("SELECT COUNT(*) FROM student_table WHERE email = ? AND student_id != ? UNION ALL SELECT COUNT(*) FROM verification_table WHERE email = ?");
        $emailCheckStmt->bind_param("sis", $email, $user_id, $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->bind_result($emailCount);
        $emailCheckStmt->fetch();
        $emailCheckStmt->close();

        // Check if contact number exists
        $contactCheckStmt = $conn->prepare("SELECT COUNT(*) 
                        FROM student_table WHERE contact_num = ? AND student_id != ? 
                        UNION ALL 
                        SELECT COUNT(*) 
                        FROM verification_table WHERE contact_num = ?");
        $contactCheckStmt->bind_param("sis", $contact_num, $user_id, $contact_num);
        $contactCheckStmt->execute();
        $contactCheckStmt->bind_result($contactCount);
        $contactCheckStmt->fetch();

        if ($emailCount > 0) {
            // echo "<script>alert('Email is already in use. Please use a different email.'); window.location.href = 'student_profile.php';</script>";
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email is already in use. Please use a different email.',
                    icon: 'error',
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#dc3545',
                }).then(function() {
                    window.location.href = 'student_profile.php';
                });
            </script>";
        } elseif ($contactCount > 0) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Contact number is already in use. Please use a different contact number.',
                    icon: 'error',
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#dc3545',
                }).then(function() {
                    window.location.href = 'student_profile.php';
                });
            </script>";
        } else {
            // Update user details in the database
            $stmt = $conn->prepare("UPDATE student_table SET first_name = ?, last_name = ?, email = ?, contact_num = ?, program = ?, department = ? WHERE student_id = ?");
            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $contact_num, $program, $department, $user_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    // Update session data
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['email'] = $email;
                    $_SESSION['contact_num'] = $contact_num;
                    $_SESSION['program'] = $program;
                    $_SESSION['department'] = $department;

                    // Prepare variables for logging the activity
                    $studentId = $_SESSION['user_id'];
                    $action = 'Update';
                    $details = 'You updated your profile details';

                    // Insert log activity directly with NOW() for the timestamp
                    $stmt2 = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
                    $stmt2->bind_param("iss", $studentId, $action, $details); // Use variables here
                    $stmt2->execute();

                    echo "<script>window.location.href = 'student_profile.php?alert=success';</script>";
                } else {
                    // No changes in the profile
                    echo "<script>window.location.href = 'student_profile.php';</script>";
                }
                exit;
            } else {
                echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
            }
        }
    }

    // Check if a new profile picture is uploaded
    if (isset($_FILES['profile_pic'])) {
        // Get the file info
        $filePic = basename($_FILES["profile_pic"]["name"]);

        // Allow certain file formats
        $allowTypesImage = array('jpg', 'jpeg', 'png');

        // Check file type
        $fileTypePic = strtolower(pathinfo($filePic, PATHINFO_EXTENSION));

        // Check if the file was uploaded without errors
        if ($_FILES['profile_pic']['error'] === UPLOAD_ERR_OK && in_array($fileTypePic, $allowTypesImage)) {
            
            // Get the file content
            $picContent = file_get_contents($_FILES['profile_pic']['tmp_name']);

            // Prepare the statement to update the profile picture
            $stmt = $conn->prepare("UPDATE student_table SET profile_pic = ?, pic_filetype = ? WHERE student_id = ?");
            $stmt->bind_param("ssi", $picContent, $fileTypePic, $user_id);

            if ($stmt->execute()) {
                // Successfully updated the profile picture
                // Prepare variables for logging activity
                $studentId = $_SESSION['user_id'];
                $action = 'Update';
                $details = 'You updated your profile picture';

                // Insert log activity directly with NOW() for the timestamp
                $stmt2 = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
                $stmt2->bind_param("iss", $studentId, $action, $details); // Use variables here
                $stmt2->execute();
                echo "<script>window.location.href = 'student_profile.php?alert=success';</script>";
            } else {
                // No changes in the profile
                echo "<script>window.location.href = 'student_profile.php';</script>";
            } 
            exit;
        } else {
            echo "<script>window.location.href = 'student_profile.php?error=" . $_FILES['profile_pic']['error'] . "';</script>";
            exit;
        }
    }
?>

    <header class="sticky-top z-1" style="background-color: #dd2222;">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container-fluid">
                <a href="" class="navbar-brand text-light fs-xxl-1 fs-xl-1 fs-lg-1 fs-md-2 fs-sm-5 fs-2 d-flex align-items-center">
                    <div class="img d-flex align-items-center">
                        <img class="img-fluid logo d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-none" src="../../img/cropped-libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    </div>
                    <span class="ms-2"><?php echo 'Welcome, ' . $first_name . '!' ?></span>
                </a>

                <div class="d-flex">
                    <button class="btn btn-danger me-2 menu" type="button" data-bs-toggle="modal" data-bs-target="#cart">
                        <i class="bi bi-cart2"></i>
                    </button>
                    <button class="btn btn-danger menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                        &#9776;
                    </button>
                </div>
            </div>
        </nav>

        <div class="offcanvas offcanvas-end text-light" data-bs-backdrop="static" data-bs-scroll= "true" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
            <div class="offcanvas-header">
               <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="sidebar-item d-flex align-items-center justify-content-center">
                    <img class="img-fluid" src="../../img/librawhite.png" alt="Logo" style="height: 30px;">
                </div>
                <div class="sidebar-item">
                    <a href="student_home.php" class="sidebar-link" data-target="home">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="#" class="sidebar-link" id="active">
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="borrowed_books.php" class="sidebar-link">
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Borrowed Books</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a href="activity_logs.php" class="sidebar-link">
                        <i class="bi bi-clipboard-data-fill"></i>
                        <span>Activity Logs</span>
                    </a>
                </div>
            </div>
            <hr>
            <div class="offcanvas-footer text-center mb-2">
                <div class="sidebar-item">
                    <a href="logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal -->
    <div class="modal fade" id="cart" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Your Cart</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="list-group">
                <?php 
                    $user_id = $_SESSION['user_id'];

                    // Fetch book IDs for the user from the cart
                    $stmt = $conn->prepare("SELECT book_id FROM cart WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // Create an array to hold book IDs from the cart
                    $book_ids = [];
                    while ($row = $result->fetch_assoc()) {
                        $book_ids[] = $row['book_id'];
                    }

                    if (empty($book_ids)) {
                        echo '<div class="list-group-item">Your cart is empty.</div>';
                    } else {
                        // Prepare a statement to fetch book details based on the retrieved book IDs
                        $ids_placeholder = implode(',', array_fill(0, count($book_ids), '?'));
                        $stmt = $conn->prepare("SELECT title, author, category, book_id, book_cover FROM book_table WHERE book_id IN ($ids_placeholder)");
                        $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display the books
                        // Inside your modal body where you're displaying the books
                        while ($book = $result->fetch_assoc()) {
                            echo '<div class="list-group-item d-flex align-items-start">';
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($book['book_cover']) . '" alt="Book Cover" width="200" height="300" class="me-3">';
                            echo '<div class="d-flex flex-column">';
                            echo '<h4 class="text-muted">' . htmlspecialchars($book['title']) . '</h4>';
                            echo '<h6 class="text-muted">' . htmlspecialchars($book['author']) . '</h6>';
                            echo '<h6 class="text-muted">' . htmlspecialchars($book['category']) . '</h6>';
                            echo '</div>';
                            echo '<button class="btn btn-danger btn-sm ms-auto align-items-end remove-book" data-book-id="' . $book['book_id'] . '">Remove</button>';
                            // echo '<a href="remove_from_cart.php?book_id=' . $book['book_id'] . '" class="btn btn-danger btn-sm ms-auto align-items-end">Remove</a>';
                            echo '</div>';
                        }

                    }
                    $stmt->close();
                ?>
                </div>

                 <!-- Borrowing Policies Section (inserted right here) -->
                <div class="mt-4">
                    <h5><strong>Borrowing Policies</strong></h5>
                    <ul>
                        <li><strong>Maximum number of books:</strong> You can borrow a maximum of 5 books at a time.</li>
                        <li><strong>Penalty:</strong> There is a penalty of <strong>10.00</strong> per book, per overdue day.</li>
                        <li><strong>Book condition:</strong> You must return the books in good condition. If the book is damaged, you must buy a replacement. If the book is an older version, you need to buy a new version.</li>
                        <li><strong>Maximum borrow duration:</strong> Books can be borrowed for a maximum of 7 days.</li>
                        <li><strong>Renewal:</strong> You can renew the borrowed book once only.</li>
                        <li><strong>Early returns:</strong> You can return the books early if you wish.</li>
                    </ul>
                    <p>By confirming borrowing, you agree to the above policies.</p>
                </div>
                
            </div>

            <div class="modal-footer">
                <form action="borrow_form.php" method="post" class="mt-3">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" name="submit" class="btn btn-danger">Confirm Borrowing</button>
                </form>
            </div>
            
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card p-3">
            <div class="d-flex justify-content-end">
                <!-- <h4>Student Profile</h4> -->
                <a href="student_home.php" class="btn btn-danger button" style="width: 150px;">Back</a>
            </div>
            <div class="card-body">
                <div class="row">     
                    <!-- Image Column -->
                    <div class="col-md-4 text-center">
                    
                        <div class="border p-3" style="height: 200px; width: 200px; display: flex; justify-content: center; align-items: center; overflow: hidden; margin: 0 auto; position: relative;">
                            <form action="student_profile.php" method="POST" enctype="multipart/form-data" style="height: 100%; width: 100%; display: flex; align-items: center; justify-content: center;">
                                <img class="img-fluid" src="data:image/jpeg;base64,<?php echo base64_encode($profile_pic); ?>" alt="Profile Picture" style="height: 100%; width: 100%; object-fit: cover; cursor: pointer;" onclick="document.getElementById('profilePicInput').click();">
                                <input type="file" id="profilePicInput" name="profile_pic" accept="image/*" style="display: none;" onchange="this.form.submit();">
                                
                                <div style="position: absolute; bottom: 10px; right: 10px;">
                                    <i class="bi bi-pencil" style="font-size: 24px; color: white; background: rgba(0, 0, 0, 0.5); border-radius: 50%; padding: 5px; cursor: pointer;" onclick="document.getElementById('profilePicInput').click();"></i>
                                </div>
                            </form>
                        </div>


                        <h5 class="mt-3"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h5>
                        <form method="POST" action="">
                            <button type="submit" name="generate_qr" class="btn btn-danger button mt-2">Generate QR Code</button>
                        </form>

                        <div id="qr-code" class="text-center mt-3">
                            <?php if ($qrCodeGenerated && file_exists($qrCodeFilePath)) : ?>
                                <img src="<?php echo $qrCodeFilePath; ?>" alt="QR Code" />
                                <div class="mt-2">
                                    <form method="POST" action="">
                                        <input type="hidden" name="qr_code_path" value="<?php echo htmlspecialchars($qrCodeFilePath); ?>">
                                        <button type="submit" name="download_pdf" class="btn btn-success">Download PDF</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                    <!-- Information Column -->
                    <div class="col-md-8">
                        <h3 style="color: #dd2222; font-weight: 600;">Personal Information</h3>

                        <a href="" type="button" data-bs-toggle="modal" data-bs-target="#changePass" class="change-pass text-muted text-decoration-none">Change Password<i class="bi bi-shield-lock"></i></a>
                        
                       <?php
                        if (isset($_POST['changePass'])) {

                            // Retrieve the form data
                            $user_id = htmlspecialchars($_SESSION['user_id']);
                            $old_password = htmlspecialchars($_POST['old_password']);
                            $new_password = htmlspecialchars($_POST['new_password']);
                            $confirm_password = htmlspecialchars($_POST['confirm_password']);
                            
                            // Check if all the inputs is not empty
                            if (empty($old_password) && empty($new_password) && empty($confirm_password)) {
                                echo "<script>alert('Please input necessary fields.'); window.location.href = 'student_profile.php';</script>";
                                exit; // Stop further processing if old password is missing
                            }

                            // Check if the old password is empty
                            if (empty($old_password)) {
                                echo "<script>alert('Old password is required.'); window.location.href = 'student_profile.php';</script>";
                                exit; // Stop further processing if old password is missing
                            }

                            // Check if the new password is empty
                            if (empty($new_password)) {
                                echo "<script>alert('New password is required.'); window.location.href = 'student_profile.php';</script>";
                                exit; // Stop further processing if new password is missing
                            }

                            // Check if the confirm password is empty
                            if (empty($confirm_password)) {
                                echo "<script>alert('Please confirm your new password.'); window.location.href = 'student_profile.php';</script>";
                                exit; // Stop further processing if confirm password is missing
                            }

                            // Check if the new password and confirm password match
                            if ($new_password !== $confirm_password) {
                                echo "<script>alert('New password and confirm password do not match.'); window.location.href = 'student_profile.php';</script>";
                                exit; // Stop further processing if passwords don't match
                            }

                            // Fetch the current hashed password from the database
                            $pass_stmt = $conn->prepare("SELECT password FROM student_table WHERE student_id = ?");
                            $pass_stmt->bind_param("s", $user_id);
                            $pass_stmt->execute();
                            $pass_result = $pass_stmt->get_result();

                            // Check if the user exists
                            if ($pass_result->num_rows === 1) {
                                $row = $pass_result->fetch_assoc();
                                $current_hashed_password = $row['password'];

                                // Verify the old password
                                if (password_verify($old_password, $current_hashed_password)) {
                                    // Hash the new password
                                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                                    // Update the password in the database
                                    $update_stmt = $conn->prepare("UPDATE student_table SET password = ? WHERE student_id = ?");
                                    $update_stmt->bind_param("ss", $hashed_new_password, $user_id);
                                    if ($update_stmt->execute()) {
                                        // echo "<script>alert('Password changed successfully!'); window.location.href = 'student_profile.php';</script>";
                                        echo "<script>
                                            Swal.fire({
                                                title: 'Success!',
                                                text: 'Password changed successfully',
                                                icon: 'success',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#198754',
                                            }).then(function() {
                                                window.location.href = 'student_profile.php';
                                            });
                                        </script>";

                                        $studentId = $_SESSION['user_id'];
                                        $action = 'Update Password';
                                        $details = 'You updated your password';

                                        // Insert log activity directly with NOW() for the timestamp
                                        $stmt3 = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
                                        $stmt3->bind_param("iss", $studentId, $action, $details); // Use variables here
                                        $stmt3->execute();
                                    } else {
                                        // echo "<script>alert('Failed to update the password. Please try again.'); window.location.href = 'student_profile.php';</script>";
                                        echo "<script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Failed to update the password. Please try again.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                            }).then(function() {
                                                window.location.href = 'student_profile.php';
                                            });
                                        </script>";
                                    }
                                } else {
                                    // echo "<script>alert('Old password is incorrect.'); window.location.href = 'student_profile.php';</script>";
                                    echo "<script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Old password is incorrect.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay',
                                                confirmButtonColor: '#dc3545',
                                            }).then(function() {
                                                window.location.href = 'student_profile.php';
                                            });
                                        </script>";
                                }
                            } else {
                                // echo "<script>alert('User not found.'); window.location.href = 'student_profile.php';</script>";
                                echo "<script>
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'User not found.',
                                        icon: 'error',
                                        confirmButtonText: 'Okay',
                                        confirmButtonColor: '#dc3545',
                                    }).then(function() {
                                        window.location.href = 'student_profile.php';
                                    });
                                </script>";
                            }
                        }
                        ?>


                        <!-- Modal -->
                        <div class="modal fade" id="changePass" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="sUpdatePass" action="student_profile.php" method="post" class="mt-3">
                                            <div class="mt-0 mb-1">
                                                <label for="oldPassword" class="form-label">Old Password</label>
                                                <input type="password" class="form-control" id="oldPassword" name="old_password" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                            </div>
                                            <!-- Password toggle checkbox -->
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" id="showOldPassword" style="cursor: pointer;">
                                                <label class="form-check-label" for="showPassword" style="font-size: 14px; cursor: pointer;">Show Password</label>
                                            </div>

                                            <div class="mb-1">
                                                <label for="newPassword" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="newPassword" name="new_password" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                            </div>
                                            <!-- Password toggle checkbox -->
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" id="showNewPassword" style="cursor: pointer;">
                                                <label class="form-check-label" for="showPassword" style="font-size: 14px; cursor: pointer;">Show Password</label>
                                            </div>

                                            <div class="mb-1">
                                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                            </div>
                                            <!-- Password toggle checkbox -->
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" id="showConfirmPassword" style="cursor: pointer;">
                                                <label class="form-check-label" for="showPassword" style="font-size: 14px; cursor: pointer;">Show Password</label>
                                            </div>

                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                            <button type="submit" name="changePass" class="btn btn-danger">Change Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="editProfileForm" method="POST" action="">
                            <input type="hidden" name="student_id" value="<?php echo $user_id; ?>">
                            <?php
                                // Fetch departments and programs for dropdowns
                                $departments = [];
                                $programs = [];

                                // Fetch departments
                                $departmentQuery = "SELECT name FROM department_table";
                                $result = $conn->query($departmentQuery);
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        $departments[] = $row['name'];
                                    }
                                }

                                // Fetch programs
                                foreach ($departments as $dept) {
                                    $programQuery = "SELECT p.name 
                                        FROM program_table p 
                                        JOIN department_table d ON p.department_id = d.id 
                                        WHERE d.name = ?";
                                    $stmt = $conn->prepare($programQuery);
                                    $stmt->bind_param("s", $dept);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $programs[$dept] = [];
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            $programs[$dept][] = $row['name'];
                                        }
                                    }
                                }
                            ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="firstName" style="font-weight: bold;">First Name</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($first_name); ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="lastName" style="font-weight: bold;">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($last_name); ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="email" style="font-weight: bold;">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="contact_num" style="font-weight: bold;">Contact Number</label>
                                        <input type="tel" class="form-control" id="contact_num" name="contact_num" value="<?php echo htmlspecialchars($contact_num); ?>" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="department" style="font-weight: bold;">Department</label>
                                        <select class="form-select" id="department" name="department" onchange="updatePrograms(this.value)" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                            <?php foreach ($departments as $dept): ?>
                                                <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo ($dept === $department) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($dept); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="program" style="font-weight: bold;">Program</label>
                                        <select class="form-select" id="program" name="program" style="border-radius: 16px; border: solid, 1px, black; width: 100%; display: block; font-size: 16px;">
                                            <?php if (!empty($programs[$department])): ?>
                                                <?php foreach ($programs[$department] as $prog): ?>
                                                    <option value="<?php echo htmlspecialchars($prog); ?>" <?php echo ($prog === $program) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($prog); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled selected>No programs available yet</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                            </div>
                        </div>
                            <button type="submit" name="update_profile" class="btn btn-success mt-3">Save Changes</button>
                        </form>

                        <script>
                            function updatePrograms(department) {
                                const programs = <?php echo json_encode($programs); ?>;
                                const programSelect = document.getElementById('program');
                                programSelect.innerHTML = ''; // Clear existing options

                                if (programs[department] && programs[department].length > 0) {
                                    programs[department].forEach(prog => {
                                        const option = document.createElement('option');
                                        option.value = prog;
                                        option.textContent = prog;
                                        programSelect.appendChild(option);
                                    });
                                } else {
                                    // If no programs available, add a message
                                    const option = document.createElement('option');
                                    option.value = '';
                                    option.textContent = 'No programs available yet';
                                    option.disabled = true; // Disable the option
                                    programSelect.appendChild(option);
                                }

                                // Optionally set the selected program if it exists
                                const currentProgram = "<?php echo htmlspecialchars($program); ?>";
                                if (programs[department] && programs[department].includes(currentProgram)) {
                                    programSelect.value = currentProgram;
                                } else {
                                    programSelect.value = ''; // Reset if current program is not in the list
                                }
                            }

                        </script>

                    </div>
                </div>
            </div>

            <div id="liveAlertPlaceholder"></div>
            
            <script>
                const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

                const appendAlert = (message, type) => {
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = [
                        `<div class="alert alert-${type} alert-dismissible mt-4" role="alert">`,
                        `   <div>${message}</div>`,
                        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                        '</div>'
                    ].join('');

                    alertPlaceholder.append(wrapper);
                }

                    // Check for alert in query parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('alert')) {
                        const alertType = urlParams.get('alert');
                        const message = alertType === 'success' ? 'Profile updated!' : decodeURIComponent(urlParams.get('message'));
                        appendAlert(message, alertType);
                        
                        // Clear the alert parameter from the URL
                        urlParams.delete('alert');
                        window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
                    }
            </script>
        </div>
    </div>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/loginValidate.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle old password visibility
            $('#showOldPassword').on('change', function() {
                if (this.checked) {
                    $('#oldPassword').attr('type', 'text');  // Show old password
                } else {
                    $('#oldPassword').attr('type', 'password');  // Hide old password
                }
            });

            // Toggle new password visibility
            $('#showNewPassword').on('change', function() {
                if (this.checked) {
                    $('#newPassword').attr('type', 'text');  // Show new password
                } else {
                    $('#newPassword').attr('type', 'password');  // Hide new password
                }
            });

            // Toggle confirm password visibility
            $('#showConfirmPassword').on('change', function() {
                if (this.checked) {
                    $('#confirmPassword').attr('type', 'text');  // Show confirm password
                } else {
                    $('#confirmPassword').attr('type', 'password');  // Hide confirm password
                }
            });
        });

        $(document).ready(function() {
            $("#sUpdatePass").validate({
                rules: {
                    old_password: {
                        required: true // Ensure the old password is required
                    },
                    new_password: {
                        required: true,
                        minlength: 8 // Password must be at least 8 characters long
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#newPassword" // Ensure the confirm password matches the new password
                    }
                },
                messages: {
                    old_password: {
                        required: "Please enter your old password"
                    },
                    new_password: {
                        required: "Please enter a new password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    confirm_password: {
                        required: "Please confirm your new password",
                        equalTo: "Passwords do not match"
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    // jQuery validation passed, now submit the form
                    form.submit(); // This will trigger PHP execution
                }
            });
        });
    </script>

</body>
</html> 