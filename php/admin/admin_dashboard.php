<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<style>
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
    .main-content {
        display: flex;
        /* flex-direction: column; */
        align-items: center;
        justify-content: center;
        min-height: 100vh; /* Full height of the viewport */
    }

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
    .dashboard-link:hover, .dashboard-link:focus {
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
        background-color: #e73737;
        border-radius: 5px;
        box-shadow: none;
        cursor: pointer;
    }
    .offcanvas-body::-webkit-scrollbar-track {
        background-color: transparent;
        border-radius: 0;
        box-shadow: none;
    }
</style>

<body>

    <!-- SIDEBAR -->
    <div class="offcanvas offcanvas-start text-light" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" data-bs-backdrop="static" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <a href="admin-login.php" class="navbar-brand text-light">
                <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
            </a>
            <!-- <button type="button" class="btn-close" aria-label="Close"></button> -->
        </div>
        <div class="offcanvas-body" id="sidebar">
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="home">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Home</span>
                </a>
            </div>
            <hr>
            <h5>Manage</h5>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="total-registered">
                    <i class="bi bi-people-fill"></i>
                    <span>Registered</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="total-accepted">
                    <i class="bi bi-person-fill-check"></i>
                    <span>Accepted</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="total-books">
                    <i class="bi bi-book-fill"></i>
                    <span>Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="total-borrowed">
                    <i class="bi bi-bookmark-check-fill"></i>
                    <span>Borrowed Books</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" data-target="stocks">
                    <i class="bi bi-inboxes-fill"></i>
                    <span>Book Stocks</span>
                </a>
            </div>
        </div>
            <hr>
        <div class="offcanvas-footer">
            <div class="dashboard-item">
                <a href="admin-login.php" class="dashboard-link">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
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

    <main class="main-content" style="position: relative;">
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

                    <div class="row">
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

        <!-- REGISTERED STUDENT -->
        <section class="container-fluid content" id="total-registered">
            <div id="total-register" class="container p-3">
                <?php
                    // Fetch the student who registers
                    $query = mysqli_query($conn, "SELECT * FROM verification_table");
                    
                    // Check if the query was successful
                    if ($query) {
                        // Display the rows
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-hover'>";
                        echo "<tr>";
                        echo "<th scope='col'>ID</th>";
                        echo "<th scope='col'>Name</th>";
                        echo "<th scope='col'>Contact Number</th>";
                        echo "<th scope='col'>Email</th>";
                        echo "<th scope='col'>Program</th>";
                        echo "<th scope='col'>Department</th>";
                        echo "<th scope='col'>COR</th>";
                        echo "<th scope='col'>ID</th>";
                        echo "<th scope='col'>Approve</th>";
                        echo "</tr>";
                        echo "<tbody class='table-group-divider'>";
                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td scope='row'>" . $row['student_id'] . "</td>";
                            echo "<td scope='row'>" . $row['full_name'] . "</td>";
                            echo "<td scope='row'>" . $row['contact_num'] . "</td>";
                            echo "<td scope='row'>" . $row['email'] . "</td>";
                            echo "<td scope='row'>" . $row['program'] . "</td>";
                            echo "<td scope='row'>" . $row['department'] . "</td>";
                            if($row['cor_filetype'] === 'pdf'){
                                echo '<td><a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['cor']). '\', \''. htmlspecialchars($row['full_name'], ENT_QUOTES) .'\')">View COR</a></td>';
                            }
                            if($row['id_filetype'] === 'pdf'){
                                echo '<td><a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['id_file']). '\', \''. htmlspecialchars($row['full_name'], ENT_QUOTES) .'\')">View ID</a></td>';
                            }
                            echo "<td>
                                <form action='' method='post'>
                                    <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                    <input type='hidden' name='full_name' value='" . $row['full_name'] . "'>
                                    <input type='hidden' name='contact' value='" . $row['contact_num'] . "'>
                                    <input type='hidden' name='email' value='" . $row['email'] . "'>
                                    <input type='hidden' name='password' value='" . $row['password'] . "'>
                                    <input type='hidden' name='program' value='" . $row['program'] . "'>
                                    <input type='hidden' name='department' value='" . $row['department'] . "'>
                                    <button type='submit' name='approve' value='yes' class='btn btn-success'>Yes</button>
                                    <button type='submit' name='approve' value='no' class='btn btn-danger'>No</button>
                                </form>
                            </td>";
                                if (isset($_POST['approve'])) {
                                    $student_id = $_POST['student_id'];
                                    $full_name = $_POST['full_name'];
                                    $contact = $_POST['contact'];
                                    $email = $_POST['email'];

                                    $password = $_POST['password'];

                                    $program = $_POST['program'];
                                    $department = $_POST['department'];

                                    if ($_POST['approve'] == 'yes') {
                                        // Check if the student_id already exists in the student_table
                                        $check_query = "SELECT * FROM student_table WHERE student_id = '$student_id'";
                                        $check_result = mysqli_query($conn, $check_query);
                                        if (mysqli_num_rows($check_result) == 0) {
                                            // Insert the details into another database table
                                            $query = "INSERT INTO student_table (student_id, full_name, contact_num, email, password, program, department) VALUES ('$student_id', '$full_name', '$contact', '$email', '$password', '$program', '$department')";
                                            mysqli_query($conn, $query);
                                        }

                                        // Delete the details from the current table
                                        $delete_query = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                        $delete_result = mysqli_query($conn, $delete_query);
                                    } elseif ($_POST['approve'] == 'no') {
                                        // Delete the details from the current table
                                        $delete_query = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                        $delete_result = mysqli_query($conn, $delete_query);
                                    }
                                }
                        }
                        if(mysqli_num_rows($query) === 0){
                            echo "<td colspan='9'>No records found</td>";
                        }
                        echo "</tr>";
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "Error fetching data: " . mysqli_error($conn);
                    }
                ?>
                <!-- <button class="btn btn-primary" popovertarget="total-register" popovertargetaction="hide">Close</button> -->
            </div>
        </section>

        <!-- ACCEPTED STUDENT -->
        <section class="container-fluid content" id="total-accepted">
            <div id="accepted-student" class="container p-3">
                    <?php
                        // Fetch the accepted student on the database
                        $query = mysqli_query($conn, "SELECT * FROM student_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover'>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Approve</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>
                                    <button type='button' class='btn btn-success' disabled>Yes</button>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#view-profile-{$row['student_id']}'>View Profile</button>
                                
                                    <div class='modal fade' id='view-profile-{$row['student_id']}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                                <div class='modal-body'>
                                                    <div style='float: left; width: 150px; margin-right: 20px;'>
                                                        <img src='student_picture.jpg' style='width: 100%; height: 150px; border-radius: 10px;'>
                                                        <p style='text-align: center;'><b>" . $row['full_name'] . "</b></p>
                                                    </div>
                                                    <div style='flex: 1; float: left;'>
                                                        <h2>Student Profile</h2>
                                                        <p>Student ID: " . $row['student_id'] . "</p>
                                                        <p>Contact Number: " . $row['contact_num'] . "</p>
                                                        <p>Email: " . $row['email'] . "</p>
                                                        <p>Program: " . $row['program'] . "</p>
                                                        <p>Department: " . $row['department'] . "</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div
                                    </div>

                                </td>";
                            
                            }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='4'>No records found</td>";
                                }
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <!-- <button class="btn btn-primary" popovertarget="accepted-student" popovertargetaction="hide">Close</button> -->
                </div>
        </section>

        <!-- BOOKS AVAILABLE -->
        <section class="container-fluid content" id="total-books">
            <div id="books" class="container p-3">
                    <?php
                        // Fetch the books available
                        $query = mysqli_query($conn, "SELECT * FROM book_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-hover'>";
                            echo "<tr>";
                            echo "<th>Book ID</th>";
                            echo "<th>Title</th>";
                            echo "<th>Author</th>";
                            echo "<th>Genre</th>";
                            echo "<th>Description</th>";
                            echo "</tr>";
                            echo "<tbody class='table-group-divider'>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['book_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['author'] . "</td>";
                                echo "<td>" . $row['genre'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                            }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='5'>No records found</td>";
                            }
                            echo "</tr>";
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <!-- <span><a href="add_books.php"><button type='button' class='btn btn-success'>Add Books</button></a><span> -->
                    <!-- <button class="btn btn-primary" popovertarget="books" popovertargetaction="hide">Close</button> -->
                     <button type="button" class='btn btn-success' data-bs-toggle="modal" data-bs-target="#addBooks">Add Books</button>
            </div>
            <!-- Modal to add Books -->
            <div class="modal fade" id="addBooks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered border-radius-3">
                    <div class="modal-content">
                        <div class="modal-body">
                            <!-- Form for Adding Book -->
                            <form action="" method="post" enctype="multipart/form-data">
                                    <h2 class="text-center">Add Books</h2><br>
                                    
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" placeholder="Title" name="title" id="title" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                        
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" placeholder="Author" name="author" id="author" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                        
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" placeholder="Genre" name="genre" id="genre" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                        
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Description" name="description" id="description" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                            
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button type="submit" name="submit" class="btn btn-primary w-40">Add Book</button>
                                        </div>
                                </form>
                                <?php
                                    // Include the database configuration file
                                    require_once '../db_config.php';
                                    if(isset($_POST['submit'])){
                                        $title = mysqli_real_escape_string($conn, $_POST['title']);
                                        $author = mysqli_real_escape_string($conn, $_POST['author']);
                                        $genre = mysqli_real_escape_string($conn, $_POST['genre']);
                                        $description = mysqli_real_escape_string($conn, $_POST['description']);
                                        // Execute the INSERT query
                                        $query = "INSERT INTO book_table (title, author, genre, description) VALUES ('$title', '$author', '$genre', '$description')";
                                        $result = mysqli_query($conn, $query);
                                        // Check if the query was successful
                                        if($result){
                                            // Set a session variable to store the success message
                                            echo "<script>alert('Book Added SUCCESSFULLY!'); window.location.href = 'admin_dashboard.php';</script>";
                                            exit;
                                        }else {
                                            echo "<script>alert('Error adding book: " . mysqli_error($conn) . "')</script>";
                                        }
                                    }
                                    // Check if the session variable is set
                                    if(isset($_SESSION['success_message'])){
                                        echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
                                        unset($_SESSION['success_message']);
                                    }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
                
        </section>

        <!-- BORROWED BOOKS -->
        <section class="container-fluid content" id="total-borrowed">
             <div id="total-borrowed-books" class="container p-3">
                 <?php
                     // fetch the borrowed books with their details
                     $query = mysqli_query($conn, "SELECT s.full_name, s.email, s.contact_num, s.program, s.department, b.title, br.status, br.date_borrowed, br.due_date, br.penalty
                                                 FROM borrow_table AS br
                                                 INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                 INNER JOIN book_table AS b ON br.book_id = b.book_id");

                     // Check if the query was successful
                     if ($query) {
                         // Display the rows
                         echo "<div class='table-responsive'>";
                         echo "<table class='table table-hover'>";
                         echo "<tr>";
                         echo "<th>Full Name</th>";
                         echo "<th>Email</th>";
                         echo "<th>Contact Number</th>";
                         echo "<th>Program</th>";
                         echo "<th>Department</th>";
                         echo "<th>Book Title</th>";
                         echo "<th>Status</th>";
                         echo "<th>Date Borrowed</th>";
                         echo "<th>Due Date</th>";
                         echo "<th>Penalty</th>";
                         echo "</tr>";
                         echo "<tbody class='table-group-divider'>";
                         while ($row = mysqli_fetch_assoc($query)) {
                             echo "<tr>";
                             echo "<td>" . $row['full_name'] . "</td>";
                             echo "<td>" . $row['email'] . "</td>";
                             echo "<td>" . $row['contact_num'] . "</td>";
                             echo "<td>" . $row['program'] . "</td>";
                             echo "<td>" . $row['department'] . "</td>";
                             echo "<td>" . $row['title'] . "</td>";
                             echo "<td>" . $row['status'] . "</td>";
                             echo "<td>" . $row['date_borrowed'] . "</td>";
                             echo "<td>" . $row['due_date'] . "</td>";
                             echo "<td>" . $row['penalty'] . "</td>";
                             echo "</tr>";
                         }
                         if (mysqli_num_rows($query) === 0) {
                            echo "<td colspan='10'>No records found</td>"; 
                         }
                         echo "</tr>";
                         echo "</tbody>";
                         echo "</table>";
                         echo "</div>";
                     } else {
                         echo "Error fetching data: " . mysqli_error($conn);
                     }
                 ?>
                 <!-- <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button> -->
             </div>
        </section>

        <!-- BOOK STOCKS -->
        <section class="container-fluid content" id="stocks">
            <div id="total-stocks" class="container p-3">
                <?php
                     // fetch the books with their current stocks
                     $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks
                                             FROM book_table AS b
                                             LEFT JOIN inventory_table AS i ON b.book_id = i.book_id");// we're using the COALESCE function to replace NULL values in the stocks column with 0
                     
                     // Check if the query was successful
                     if ($query) {
                        // Initialize variables to keep track of totals
                        $total_stocks = 0;
                        $total_borrowed = 0;
                        $total_available_stocks = 0;

                         // Display the rows
                         echo "<div class='table-responsive'>";
                         echo "<table class='table table-hover'>";
                         echo "<tr>";
                         echo "<th>Book ID</th>";
                         echo "<th>Title</th>";
                         echo "<th>Status</th>";
                         echo "<th>Stocks</th>";
                         echo "<th>Borrowed</th>";
                         echo "<th>Available Stocks</th>";
                         echo "</tr>";
                         echo "<tbody class='table-group-divider'>";
                         while ($row = mysqli_fetch_assoc($query)) {
                            // Get the number of borrowed copies
                            $borrowed_query = "SELECT COUNT(*) as borrowed FROM borrow_table WHERE book_id = '" . $row['book_id'] . "'";
                            $borrowed_result = mysqli_query($conn, $borrowed_query);
                            $borrowed_row = mysqli_fetch_assoc($borrowed_result);
                            $borrowed = $borrowed_row['borrowed'];

                            $available_stocks = $row['stocks'] - $borrowed;

                            // Add values to totals
                            $total_stocks += $row['stocks'];
                            $total_borrowed += $borrowed;
                            $total_available_stocks += $available_stocks;

                            echo "<tr>";
                            echo "<td>" . $row['book_id'] . "</td>";
                            echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                            echo "<td>" . $row['stocks'] . "</td>";
                            echo "<td>" . $borrowed . "</td>";
                            echo "<td>" . $available_stocks . "</td>";
                            }
                            // Display totals
                            echo "<tr>";
                            echo "<td colspan='3'><b>Total</b></td>";
                            echo "<td><b>" . $total_stocks . "</b></td>";
                            echo "<td><b>" . $total_borrowed . "</b></td>";
                            echo "<td><b>" . $total_available_stocks . "</b></td>";
                            echo "</tr>";

                         if(mysqli_num_rows($query) === 0){
                             echo "<td colspan='6'>No records found</td>";
                         }
                         echo "</tr>";
                         echo "</tbody>";
                         echo "</table>";
                         echo "</div>";
                     } else {
                         echo "Error fetching data: " . mysqli_error($conn);
                     }
                ?>
                
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#addStocks">Add Stocks</button>
                <!-- <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button> -->
             </div>

             <!-- Modal for adding Book Stocks -->
             <div class="modal fade" id="addStocks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <?php
                                require_once '../db_config.php';

                                // Check if the form has been submitted
                                if (isset($_POST['add'])) {
                                    $book_id = $_POST['book_id'];
                                    $title = $_POST['title'];
                                    $status = $_POST['status'];
                                    $new_stocks = $_POST['stocks'];

                                    if ($_POST['add'] == 'add') {
                                        // Check if the book_id already exists in the inventory_table
                                        $check_query = "SELECT * FROM inventory_table WHERE book_id = '$book_id'";
                                        $check_result = mysqli_query($conn, $check_query);
                                        
                                        if (mysqli_num_rows($check_result) == 0) {
                                            // Insert the details into the inventory_table
                                            $query = "INSERT INTO inventory_table (book_id, stocks, status) VALUES ('$book_id', '$new_stocks', '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "')";
                                            $result = mysqli_query($conn, $query);

                                            if($result){                                          
                                                // Display an alert message
                                                echo "<script>alert('Stocks added SUCCESSFULLY!'); window.location.href = 'admin_dashboard.php';</script>";
                                                exit;
                                            }
                                            else{    
                                                // Display an alert message
                                                echo "<script>alert('Error adding stocks: " . mysqli_error($conn) . "');  window.location.href = 'admin_dashboard.php';</script>";
                                                exit;
                                            }
                                        } else {
                                            // Update the existing record in the inventory_table
                                            $query = "UPDATE inventory_table SET stocks = stocks + '$new_stocks', status = '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "' WHERE book_id = '$book_id'";
                                            $update = mysqli_query($conn, $query);

                                            if($update){                                          
                                                // Display an alert message
                                                echo "<script>alert('Stocks updated SUCCESSFULLY!'); window.location.href = 'admin_dashboard.php';</script>";
                                                exit;
                                            }
                                            else{    
                                                // Display an alert message
                                                echo "<script>alert('Error updating stocks: " . mysqli_error($conn) . "'); window.location.href = 'admin_dashboard.php';</script>";
                                                exit;
                                            }
                                        }
                                    }
                                }

                                // Fetch the books with their current stocks
                                $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks 
                                                        FROM book_table AS b
                                                        LEFT JOIN inventory_table AS i ON b.book_id = i.book_id"); // we're using the COALESCE function to replace NULL values in the stocks column with 0

                                // Check if the query was successful
                                if ($query) {
                                    // Display the rows
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-hover'>";
                                    echo "<tr>";
                                    echo "<th scope='col'>Book ID</th>";
                                    echo "<th scope='col'>Title</th>";
                                    echo "<th scope='col'>Status</th>";
                                    echo "<th scope='col'>Stocks</th>";
                                    echo "<th scope='col'>Add Stocks</th>";
                                    echo "</tr>";
                                    echo "<tbody class='table-group-divider'>";
                                    $num_rows = mysqli_num_rows($query);
                                    if ($num_rows === 0) {
                                        echo "<td colspan='4'>No records found</td>";
                                    } else {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>";
                                            echo "<td scope='row'>" . $row['book_id'] . "</td>";
                                            echo "<td scope='row'>" . $row['title'] . "</td>";
                                            echo "<td scope='row'>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                                            echo "<td scope='row'>" . $row['stocks'] . "</td>";
                                            
                                            echo "<td scope='row'>
                                                <form action='' method='post'>
                                                    <input type='hidden' name='book_id' value='" . $row['book_id'] . "'>
                                                    <input type='hidden' name='title' value='" . $row['title'] . "'>
                                                    <input type='hidden' name='status' value='" . $row['status'] . "'>
                                                    <div class='input-group'>
                                                        <input class='form-control me-1' type='number' name='stocks' value='0' required style='border-radius: 0.375rem;'>
                                                        <span><button type='submit' name='add' value='add' class='btn btn-success'>Add</button></span>
                                                    </div>
                                                </form>
                                            </td>";
                                        }
                                    }
                                    echo "</tr>";
                                    echo "</tbody>";
                                    echo "</table>";
                                    echo "</div>";
                                } else {
                                    echo "Error fetching data: " . mysqli_error($conn);
                                }

                                // Close the connection
                                mysqli_close($conn);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </secton>
    </main>
    

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    
    <script>
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
    </script>
    
    <script>
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
    </script>
</body>
</html>


    <!-- <header class="sticky-top z-1" style="backdrop-filter: blur(5px);">
        <!-- Navbar -->
        <!-- <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container">
                <a href="admin-login.php" class="navbar-brand">
                    <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navmenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2">
                            <a href="admin-login.php" class="btn btn-primary text-light">LOGOUT</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> -->
    <!-- </header> -->

        <!-- <header class="sticky-top z-1" style="backdrop-filter: blur(5px);">
        <!-- Navbar -->
        <!-- <nav class="navbar navbar-expand-lg" style="background: none;">
            <div class="container">
                <a href="admin-login.php" class="navbar-brand">
                    <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navmenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-2">
                            <a href="admin-login.php" class="btn btn-primary text-light">LOGOUT</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> -->
    <!-- </header> -->

    <!-- <div class="offcanvas offcanvas-start text-light" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" data-bs-backdrop="static" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
                <a href="admin-login.php" class="navbar-brand text-light">
                    <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
                </a>
            <!-- <button type="button" class="btn-close" aria-label="Close"></button> -->
        <!-- </div>
        <div class="offcanvas-body" id="sidebar">
            <a href="#" class="text-light text-decoration-none" data-target="home">Home</a><br>
            <hr>
            <h5>Manage</h5>
            <a href="#" class="text-light text-decoration-none" data-target="total-registered">Registered</a><br>
            <a href="#" class="text-light text-decoration-none" data-target="total-accepted">Accepted</a><br>
            <!-- <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Students
                </a>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" data-target="total-registered">Registered</a></li>
                    <li><a href="#" data-target="total-accepted">Accepted</a></li>
                </ul>
            </div> -->
            <!-- <a href="#" class="text-light text-decoration-none" data-target="total-books">Books</a><br>
            <a href="#" class="text-light text-decoration-none" data-target="total-borrowed">Borrowed Books</a><br>
            <a href="#" class="text-light text-decoration-none" data-target="stocks">Book Stocks</a>
        </div>
    </div> -->

    <!-- <main>
        <section class="text-dark m-5 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Student Register</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result ?></b></h6>
                                <button class="btn btn-primary" popovertarget="total-register">View</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Student Accepted</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result2 ?></b></h6>
                                <button class="btn btn-primary" popovertarget="accepted-student">View</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Books Available</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result3 ?></b></h6>
                                <button class="btn btn-primary" popovertarget="books">View</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Stocks of Book</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks ?></b></h6>
                                <button class="btn btn-primary" popovertarget="total-stocks">View</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Borrowed Book</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $total_borrow ?></b></h6>
                                <button class="btn btn-primary" popovertarget="total-borrowed">View</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-md-3 mb-sm-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Available Stocks</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $sum_stocks - $total_borrow ?></b></h6>
                                <button class="btn btn-primary" popovertarget="total-available">View</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
                <div popover id="total-register" class="container p-3">
                    <?php
                        // Fetch the student who registers
                        $query = mysqli_query($conn, "SELECT * FROM verification_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Contact Number</th>";
                            echo "<th>Email</th>";
                            echo "<th>Program</th>";
                            echo "<th>Department</th>";
                            echo "<th>COR</th>";
                            echo "<th>ID</th>";
                            echo "<th>Approve</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['contact_num'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['program'] . "</td>";
                                echo "<td>" . $row['department'] . "</td>";
                                if($row['cor_filetype'] === 'pdf'){
                                    echo '<td><a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['cor']). '\', \''. htmlspecialchars($row['full_name'], ENT_QUOTES) .'\')">View COR</a></td>';
                                }
                                if($row['id_filetype'] === 'pdf'){
                                    echo '<td><a href="#" class="view-pdf-link-2" onclick="viewPDF(\''. base64_encode($row['id_file']). '\', \''. htmlspecialchars($row['full_name'], ENT_QUOTES) .'\')">View ID</a></td>';
                                }
                                echo "<td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                        <input type='hidden' name='full_name' value='" . $row['full_name'] . "'>
                                        <input type='hidden' name='contact' value='" . $row['contact_num'] . "'>
                                        <input type='hidden' name='email' value='" . $row['email'] . "'>
                                        <input type='hidden' name='password' value='" . $row['password'] . "'>
                                        <input type='hidden' name='program' value='" . $row['program'] . "'>
                                        <input type='hidden' name='department' value='" . $row['department'] . "'>
                                        <button type='submit' name='approve' value='yes' class='btn btn-success'>Yes</button>
                                        <button type='submit' name='approve' value='no' class='btn btn-danger'>No</button>
                                    </form>
                                </td>";
                                    if (isset($_POST['approve'])) {
                                        $student_id = $_POST['student_id'];
                                        $full_name = $_POST['full_name'];
                                        $contact = $_POST['contact'];
                                        $email = $_POST['email'];

                                        $password = $_POST['password'];

                                        $program = $_POST['program'];
                                        $department = $_POST['department'];

                                        if ($_POST['approve'] == 'yes') {
                                            // Check if the student_id already exists in the student_table
                                            $check_query = "SELECT * FROM student_table WHERE student_id = '$student_id'";
                                            $check_result = mysqli_query($conn, $check_query);
                                            if (mysqli_num_rows($check_result) == 0) {
                                                // Insert the details into another database table
                                                $query = "INSERT INTO student_table (student_id, full_name, contact_num, email, password, program, department) VALUES ('$student_id', '$full_name', '$contact', '$email', '$password', '$program', '$department')";
                                                mysqli_query($conn, $query);
                                            }

                                            // Delete the details from the current table
                                            $delete_query = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                            $delete_result = mysqli_query($conn, $delete_query);
                                        } elseif ($_POST['approve'] == 'no') {
                                            // Delete the details from the current table
                                            $delete_query = "DELETE FROM verification_table WHERE student_id = '$student_id'";
                                            $delete_result = mysqli_query($conn, $delete_query);
                                        }
                                    }
                            }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='9'>No records found</td>";
                            }
                            echo "</tr>";
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <button class="btn btn-primary" popovertarget="total-register" popovertargetaction="hide">Close</button>
                </div>

                <div popover id="accepted-student" class="container p-3">
                    <?php
                        // Fetch the accepted student on the database
                        $query = mysqli_query($conn, "SELECT * FROM student_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Contact Number</th>";
                            echo "<th>Email</th>";
                            echo "<th>Program</th>";
                            echo "<th>Department</th>";
                            echo "<th>Approve</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['contact_num'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['program'] . "</td>";
                                echo "<td>" . $row['department'] . "</td>";
                                echo "<td><button type='button' class='btn btn-success' disabled>Yes</button></td>";
                            }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='9'>No records found</td>";
                                }
                            echo "</tr>";
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <button class="btn btn-primary" popovertarget="accepted-student" popovertargetaction="hide">Close</button>
                </div>

                <div popover id="books" class="container p-3">
                    <?php
                        // Fetch the books available
                        $query = mysqli_query($conn, "SELECT * FROM book_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>Book ID</th>";
                            echo "<th>Title</th>";
                            echo "<th>Author</th>";
                            echo "<th>Genre</th>";
                            echo "<th>Description</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['book_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['author'] . "</td>";
                                echo "<td>" . $row['genre'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                            }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='5'>No records found</td>";
                            }
                            echo "</tr>";
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <span><a href="add_books.php"><button type='button' class='btn btn-success'>Add Books</button></a><span>
                    <button class="btn btn-primary" popovertarget="books" popovertargetaction="hide">Close</button>
                </div>

                <div popover id="total-stocks" class="container p-3">
                    <?php
                        // fetch the books with their current stocks
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks
                                                FROM book_table AS b
                                                LEFT JOIN inventory_table AS i ON b.book_id = i.book_id");// we're using the COALESCE function to replace NULL values in the stocks column with
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>Book ID</th>";
                            echo "<th>Title</th>";
                            echo "<th>Status</th>";
                            echo "<th>Stocks</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['book_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                                echo "<td>" . $row['stocks'] . "</td>";
                                }
                            if(mysqli_num_rows($query) === 0){
                                echo "<td colspan='4'>No records found</td>";
                            }
                            echo "</tr>";
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <span><a href="add_stocks.php"><button type='button' class='btn btn-success'>Add Stocks</button></a><span>
                    <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button>
                </div>

                <div popover id="total-borrowed" class="container p-3">
                    <?php
                        // fetch the borrowed books with their details
                        $query = mysqli_query($conn, "SELECT s.full_name, s.email, s.contact_num, s.program, s.department, b.title, br.status, br.date_borrowed, br.due_date
                                                    FROM borrow_table AS br
                                                    INNER JOIN student_table AS s ON br.student_id = s.student_id
                                                    INNER JOIN book_table AS b ON br.book_id = b.book_id");

                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>Full Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Contact Number</th>";
                            echo "<th>Program</th>";
                            echo "<th>Department</th>";
                            echo "<th>Book Title</th>";
                            echo "<th>Status</th>";
                            echo "<th>Date Borrowed</th>";
                            echo "<th>Due Date</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['contact_num'] . "</td>";
                                echo "<td>" . $row['program'] . "</td>";
                                echo "<td>" . $row['department'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>" . $row['date_borrowed'] . "</td>";
                                echo "<td>" . $row['due_date'] . "</td>";
                                echo "</tr>";
                            }
                            if (mysqli_num_rows($query) === 0) {
                                echo "<tr>";
                                echo "<td colspan='9'>No records found</td>";
                                echo "</tr>";
                            }
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <button class="btn btn-primary" popovertarget="total-stocks" popovertargetaction="hide">Close</button>
                </div>

                <div popover id="total-available" class="container p-3">
                    <?php
                        // fetch the books with their current stocks
                        $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks
                                                    FROM book_table AS b
                                                    LEFT JOIN inventory_table AS i ON b.book_id = i.book_id");

                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>Book Title</th>";
                            echo "<th>Stocks</th>";
                            echo "<th>Borrowed</th>";
                            echo "<th>Available Stocks</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                // Get the number of borrowed copies
                                $borrowed_query = "SELECT COUNT(*) as borrowed FROM borrow_table WHERE book_id = '" . $row['book_id'] . "'";
                                $borrowed_result = mysqli_query($conn, $borrowed_query);
                                $borrowed_row = mysqli_fetch_assoc($borrowed_result);
                                $borrowed = $borrowed_row['borrowed'];

                                $available_stocks = $row['stocks'] - $borrowed;

                                echo "<tr>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['stocks'] . "</td>";
                                echo "<td>" . $borrowed . "</td>";
                                echo "<td>" . $available_stocks . "</td>";
                                echo "</tr>";
                            }
                            if (mysqli_num_rows($query) === 0) {
                                echo "<tr>";
                                echo "<td colspan='4'>No records found</td>";
                                echo "</tr>";
                            }
                            echo "</table> <br>";
                        } else {
                            echo "Error fetching data: " . mysqli_error($conn);
                        }
                    ?>
                    <button class="btn btn-primary" popovertarget="total-available" popovertargetaction="hide">Close</button>
                </div>
        </section>
    </main> -->