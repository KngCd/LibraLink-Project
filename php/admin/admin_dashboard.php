<?php

session_start();
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s'); 

// Check if the admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // If the session variable is not set or is false, redirect to the login page
    header('Location: admin-login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <!-- <script src="../../js/bootstrap.bundle.min.js"></script> -->
    <!-- jQuery -->
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
    /* .navbar-brand{
        /* font-family: 'Times New Roman', Times, serif; */
        /* font-size: 30px;
        color: black;
    }
    .navbar-brand:hover{
        color: black;
        text-decoration: underline;
    } */
    .logo {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .logo:hover {
        transform: scale(1.1);
    }
    main{
        position: absolute;
        left: 300px; /* adjust to match the offcanvas width */
        width: calc(100% - 300px); /* adjust to match the offcanvas width */
    }
    /* body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    } */
    ::backdrop {
        backdrop-filter: blur(3px);
    }
    table th, table td{
       text-align: center;
    }
    #total-register, #accepted-student, #books, #total-stocks, #total-borrowed-books, #total-available{
        border-radius: 10px;
        border: 1px solid lightgray;
        background-color: #ffffff;
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
    .dashboard-item {
        padding: 10px;
    }
    .dashboard-link {
        display: block;
        padding: 10px;
        background-color: #dd2222;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .dashboard-link:hover, .dashboard-link:focus, #active {
        background-color: #ca1d1d;
    }

    .dashboard-link i {
        margin-right: 10px;
        font-size: 18px;
    }
    .dashboard-link span {
        font-size: 15px;
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
    .main-content {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .welcome {
        align-self: flex-start;
        width: 100%;
        padding: 3rem;
        background-color: #f8f9fa;
    }
    #home {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .navbar-brand {
        position: absolute;
        top: 10px;
        left: 10px;
    }
    .btn{
        border-radius: 30px !important;
    }
    .profile-pic {
        background-color: white;
        height: 180px;
        width: 180px;
        border-radius: 50%;
        object-fit: contain;
        object-position: center;
        cursor: pointer;
    } 
    .bi-pencil-square{cursor: pointer;}
</style>

<body>

    <!-- SIDEBAR -->
    <div class="offcanvas offcanvas-start text-light" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" data-bs-backdrop="static" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header d-flex justify-content-center align-items-center flex-column" style="margin-bottom: -20px;">
            <img id="adminPicture" src="../../img/formal-pic-von.jpg" alt="Admin Picture" class="profile-pic" onclick="document.getElementById('fileInput').click();">        
            <input type="file" id="fileInput" style="display: none;" onchange="uploadImage()"> <!-- Hidden file input -->
            <div class="d-flex justify-content-center align-items-center mt-2 mb-0">
                <h4 class="mb-0" id="adminName">Carlo M. Pastrana</h4>
                <span class="ms-2" onclick="editName()">
                    <i class="bi bi-pencil-square"></i>
                </span>
            </div>
            <h6 class="text-center">Admin</h6>
        </div>

        <script>
            // Check local storage for the saved image path
            const savedImagePath = localStorage.getItem('adminImagePath');
            if (savedImagePath) {
                document.getElementById('adminPicture').src = savedImagePath; // Set the image src from local storage
            }

            // Check local storage for the saved admin name
            const savedAdminName = localStorage.getItem('adminName');
            if (savedAdminName) {
                document.getElementById('adminName').textContent = savedAdminName; // Set the name from local storage
            }

            function editName() {
                const nameElement = document.getElementById('adminName');
                const currentName = nameElement.textContent;
                const newName = prompt("Edit name:", currentName);
                if (newName) {
                    nameElement.textContent = newName;
                    localStorage.setItem('adminName', newName); // Save the new name to local storage
                }
            }

            function updateAdminPicture(newImagePath) {
                document.getElementById('adminPicture').src = newImagePath;
                localStorage.setItem('adminImagePath', newImagePath); // Save the new image path to local storage
            }

            function uploadImage() {
                const fileInput = document.getElementById('fileInput');
                const file = fileInput.files[0];
                
                if (file) {
                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('upload.php', { // Send to the separate upload file
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateAdminPicture(data.imagePath); // Update the image source with the new path
                        } else {
                            alert('Upload failed: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error uploading image: ' + error);
                    });
                } else {
                    alert("Please select a file to upload.");
                }
            }
        </script>

        <div class="offcanvas-body" id="sidebar">
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="home" id="active">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Home</span>
                </a>
            </div>
            <hr>
            <h5>Manage</h5>
            <div class="dashboard-item">
                <a href="total_register.php" class="dashboard-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Registered</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="accepted_student.php" class="dashboard-link">
                    <i class="bi bi-person-fill-check"></i>
                    <span>Accepted</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="program_dept.php" class="dashboard-link">
                    <i class="bi bi-buildings"></i>
                    <span>Programs and Departments</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="books.php" class="dashboard-link">
                    <i class="bi bi-book-fill"></i>
                    <span>Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="borrowed.php" class="dashboard-link">
                    <i class="bi bi-bookmark-check-fill"></i>
                    <span>Borrowed Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="book_stock.php" class="dashboard-link">
                    <i class="bi bi-inboxes-fill"></i>
                    <span>Book Stocks</span>
                </a>
            </div>
        </div>
            <hr>
        <div class="offcanvas-footer text-center mb-2">
            <div class="dashboard-item">
                <h6 id="currentTime"><?php echo $currentTime; ?></h6>
            </div>
        </div>

        <script>
            function updateClock() {
                
                const now = new Date();
                
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true,
                    timeZone: 'Asia/Manila'
                };
                
                const formatter = new Intl.DateTimeFormat('en-US', options);
                const timeString = formatter.format(now);
                
                document.getElementById('currentTime').textContent = timeString;
            }

            updateClock();
            setInterval(updateClock, 1000);
        </script>

    </div>
    <!-- END SIDEBAR -->    

    <script>
        $(document).ready(function() {
            $('#offcanvasWithBothOptions').offcanvas('show');
        });
    </script>

    <?php
        require_once '../db_config.php';

        // Fetch the registered students from the database
        $query = mysqli_query($conn, "SELECT *FROM verification_table");
        $result = mysqli_num_rows($query);

        // Fetch the accepted students from the database
        $query2 = mysqli_query($conn, "SELECT *FROM student_table");
        $result2 = mysqli_num_rows($query2);

        // Fetch the total books from the database
        $query3 = mysqli_query($conn, "SELECT *FROM book_table");
        $result3 = mysqli_num_rows($query3);

        // Fetch the total stock of books from the database
        $query4 = mysqli_query($conn, "SELECT SUM(stocks) AS total_stocks FROM inventory_table");
        $result4 = mysqli_fetch_assoc($query4);

        if($result4['total_stocks'] == 0){
            $sum_stocks = 0;
        }
        else{
            $sum_stocks = $result4['total_stocks'];
        }

        // Fetch the students who borrows a book/s from the database
        $query5 = mysqli_query($conn, "SELECT *FROM borrow_table");
        $total_borrow = mysqli_num_rows($query5);

        if(isset($_SESSION['message'])) {
            echo "<script>alert('" . $_SESSION['message'] . "')</script>";
            unset($_SESSION['message']);
        }
    ?>

    <main class="main-content">
        <section class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="height: 80px;">
                <div class="container">
                    <a href="">
                        <img class="img-fluid logo text-center" src="../../img/libra2.png" alt="Logo" style="height: 40px; width: auto;">
                    </a>
                    <!-- <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navmenu">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mx-2">
                                <a href="admin_logout.php" class="btn btn-danger text-light">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <hr style="margin-bottom: 0; margin-top: 0;">
        </section>

        <!-- HOME -->
        <section class="container-fluid content active" id="home">
            <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Student Register</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="total-register">View</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Student Accepted</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result2 ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="accepted-student">View</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Books Available</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result3 ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="books">View</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-sm-3">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Stocks of Book</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="total-stocks">View</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Borrowed Book</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $total_borrow ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="total-borrowed">View</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Available Stocks</h5>
                                    <hr>
                                    <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks - $total_borrow ?></b></h6>
                                    <!-- <button class="btn btn-primary" popovertarget="total-available">View</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </main>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    
    <!-- <script>
        function viewPDF(data, filename) {
            const byteCharacters = atob(data);
            const byteNumbers = new Uint8Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const blob = new Blob([byteNumbers], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            
            const newWindow = window.open(url, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,status=yes');
            if (newWindow) {
                newWindow.document.title = 'COR_' + filename + '.pdf';
            } else {
                alert('Please allow popups for this website');
            }
        }
    </script> -->
    
    <!-- <script>
        const links = document.querySelectorAll('#sidebar a');
        const contents = document.querySelectorAll('.content');

        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.target.getAttribute('data-target');

                // Hide all content sections
                contents.forEach(content => {
                    content.classList.remove('active');
                });

                // Show the target content section
                document.getElementById(target).classList.add('active');
            });
        });
    </script> -->
</body>
</html>