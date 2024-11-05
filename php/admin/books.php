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
    <script src="../../js/bootstrap.bundle.min.js"></script>
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
    table {
    width: 100%; /* Or a specific width */
    table-layout: fixed; /* Enforces fixed column widths */
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
    #total-books {
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
    .button{
        border-radius: 30px !important;
    }
    form select, form select option{
        cursor: pointer;
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
                <a href="admin_dashboard.php" class="dashboard-link">
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
                <a href="attendance_logs.php" class="dashboard-link">
                    <i class="bi bi-clock"></i>
                    <span>Attendance Logs</span>
                </a>
            </div>
            <div class="dashboard-item">
                <a href="#" class="dashboard-link" id="active">
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

            // Scroll to active link when the offcanvas is shown
            document.getElementById('offcanvasWithBothOptions').addEventListener('shown.bs.offcanvas', function () {
                const activeLink = document.getElementById('active');
                if (activeLink) {
                    // Get the height of the offcanvas body
                    const offcanvasBody = document.getElementById('sidebar');
                    const offcanvasBodyHeight = offcanvasBody.clientHeight;
                    
                    // Get the position of the active link relative to the top of the offcanvas
                    const activeLinkRect = activeLink.getBoundingClientRect();
                    const activeLinkTop = activeLinkRect.top + window.scrollY;

                    // Calculate the desired scroll position to center the active link
                    const desiredScrollPosition = activeLinkTop - (offcanvasBodyHeight / 2) + (activeLinkRect.height / 2);

                    // Smoothly scroll to the calculated position
                    offcanvasBody.scrollTo({ top: desiredScrollPosition, behavior: 'smooth' });
                }
            });
        </script>
    </div>
    <!-- END SIDEBAR -->    

    <script>
        $(document).ready(function() {
            $('#offcanvasWithBothOptions').offcanvas('show');
        });
    </script>

    <main class="main-content">
        <section class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="height: 80px;">
                <div class="container">
                    <a href=""><h1 class="navbar-brand fs-1">Book Inventory</h1></a>
                    <!-- <button class="navbar-toggler bg-danger text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navmenu">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item mx-2">
                                <a href="admin_logout.php" class="btn btn-danger text-light button">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <hr style="margin-bottom: 0; margin-top: 0;">
        </section>

        <!-- BOOKS AVAILABLE -->
        <section class="container-fluid content active" id="total-books">
            <?php
                require_once '../db_config.php';

                // Initialize genres array if it doesn't exist
                if (!isset($_SESSION['categories'])) {
                    $_SESSION['categories'] = [];
                }

                // Handle form submission
                if (isset($_POST['submit'])) {
                    $title = mysqli_real_escape_string($conn, $_POST['title']);
                    $author = mysqli_real_escape_string($conn, $_POST['author']);
                    $date_added = date('Y-m-d');  // Current date
                    $time_added = date('H:i:s');   // Current time

                    // Allow certain file formats
                    $allowTypesImage = ['jpg', 'jpeg', 'png'];

                    // Check if a new genre is added
                    if (isset($_POST['newCategory']) && !empty($_POST['newCategory'])) {
                        $newCategory = trim($_POST['newCategory']);
                        $_SESSION['categories'][] = $newCategory; // Add new genre to the session array
                        $category = $newCategory; // Use the new genre for the book insert
                    } else {
                        $category = mysqli_real_escape_string($conn, $_POST['category']);
                    }

                    // Insert the book into the book table
                    $description = mysqli_real_escape_string($conn, $_POST['description']);

                    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                        $filePic = basename($_FILES["cover"]["name"]);
                        $fileTypePic = strtolower(pathinfo($filePic, PATHINFO_EXTENSION));

                        if (in_array($fileTypePic, $allowTypesImage)) {
                            // Get the file contents
                            $picContent = file_get_contents($_FILES['cover']['tmp_name']);

                            // Prepare the SQL statement
                            $stmt = $conn->prepare("INSERT INTO book_table (title, author, category, description, book_cover, cover_filetype, date_added, time_added) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssssss", $title, $author, $category, $description, $picContent, $fileTypePic, $date_added, $time_added);

                            // Redirect with a query parameter to show the alert
                            if ($stmt->execute()) {
                                echo "<script>window.location.href = 'books.php?alert=success&message=" . urlencode('Book added!') . "';</script>";
                            } else {
                                echo "<script>window.location.href = 'books.php?alert=danger&message=" . urlencode('ERROR: Book not added!') . "';</script>";
                            }
                            exit;
                        } else {
                            echo "<script>alert('Sorry only JPG, JPEG, and PNG are allowed'); window.location.href = 'books.php';</script>";
                            exit;
                        }
                    } else {
                        echo "<script>alert('Error uploading book!); window.location.href = 'books.php';</script>";
                        exit;
                    }
                }

                // Retrieve genres for dropdown
                $categories = array_unique($_SESSION['categories']);

                // Fetch the books available
                // $query = mysqli_query($conn, "SELECT * FROM book_table");

                // Capture search and filter inputs
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $selected_category = isset($_GET['category']) ? $_GET['category'] : '';

                // Fetch distinct programs
                $category_query = mysqli_query($conn, "SELECT DISTINCT category FROM book_table");
                $categories = [];
                while ($row = mysqli_fetch_assoc($category_query)) {
                    $categories[] = $row['category'];
                }

                // Build the SQL query based on search and category
                $where_clauses = [];

                if (!empty($search)) {
                    $search = mysqli_real_escape_string($conn, $search);
                    $where_clauses[] = "(title LIKE '%$search%' OR author LIKE '%$search%')";
                }

                if (!empty($selected_category)) {
                    $where_clauses[] = "category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                }

                $where_query = '';
                if (count($where_clauses) > 0) {
                    $where_query = 'WHERE ' . implode(' AND ', $where_clauses);
                }

            ?>
            <div id="books" class="container p-3">
                <div class="container">
                    <form class="d-flex" method="GET">
                        <input class="form-control me-2 w-50 me-5" type="search" name="search" placeholder="Search for Title or Author" aria-label="Search" value="<?= htmlspecialchars($search) ?>">

                        <select name="category" class="form-select w-25 ms-5 me-3">
                            <option value="">All Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category === $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button class="btn btn-outline-danger" type="submit">Search</button>
                    </form>
                </div>

                <div class="container p-3">
                    <div class='table-responsive'>
                        <table class='table table-hover caption-top'>
                            <thead>
                            <tr>
                                <!-- <th>Book ID</th> -->
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Date Added</th>
                                <th>Time Added</th>
                                <th>Description</th>
                                <th>Update</th>
                            </tr>
                            </thead>
                                <?php
                                // Set the number of records per page
                                $records_per_page = 3;

                                // Get the current page from the session or set it to 1
                                if (!isset($_SESSION['bcurrent_page'])) {
                                    $_SESSION['bcurrent_page'] = 1;
                                }

                                // Handle next and previous button clicks via GET parameters
                                if (isset($_GET['page'])) {
                                    $_SESSION['bcurrent_page'] = (int)$_GET['page'];
                                }

                                // Fetch the total number of books based on the filter
                                $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM book_table $where_query");
                                $total_row = mysqli_fetch_assoc($total_query);
                                $total_books = $total_row['total'];
                                $total_pages = ceil($total_books / $records_per_page);

                                // Fetch the students for the current page based on the filter
                                $start_from = ($_SESSION['bcurrent_page'] - 1) * $records_per_page;
                                $query = mysqli_query($conn, "SELECT * FROM book_table $where_query LIMIT $start_from, $records_per_page");
                                
                                // echo "Total: $total_books";
                                echo "<caption>Total: $total_books</caption>";
                                echo "<tbody class='table-group-divider'>";

                                if ($query) {
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        // Assuming $row['time_added'] contains the time string
                                        $timeString = $row['time_added'];

                                        // Create a DateTime object from the time string
                                        $dateTime = DateTime::createFromFormat('H:i:s', $timeString);

                                        // Format the time into a more readable format
                                        $formattedTime = $dateTime ? $dateTime->format('g:i A') : 'Invalid time';

                                        echo "<tr>";
                                        // echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
                                        echo "<td>" . htmlspecialchars($formattedTime) . "</td>";
                                        echo "<td style='width: 300px; height: 45px; overflow: hidden;'>" . 
                                            "<div style='height: 45px; overflow-y: auto; overflow-x: hidden;'>" . 
                                            nl2br(htmlspecialchars($row['description'])) . 
                                            "</div></td>";
                                        echo "<td>
                                                <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#view-profile-{$row['book_id']}'>View Book</button>
                                                <div class='modal fade' id='view-profile-{$row['book_id']}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                    <div class='modal-dialog modal-dialog-centered'>
                                                        <div class='modal-content'>

                                                            <div class='modal-header'>
                                                                <h1 class='modal-title fs-5'>Book Information</h1>
                                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            
                                                            <div class='modal-body'>
                                                                <div style='display: flex;'>
                                                                    <div style='width: 150px; margin-right: 20px;'>
                                                                        <img src='data:image/jpeg;base64," . base64_encode($row['book_cover']) . "' alt='No Available Book Cover' style='width: 100%; height: 350px; border-radius: 10px; object-fit: cover;'>
                                                                        <p style='text-align: center;'><b>" . $row['title'] . "</b></p>
                                                                    </div>
                                                                    <div style='flex: 1; text-align: left;'>
                                                                        <form id='updateForm' action='' method='post'>
                                                                            <input type='hidden' name='bookId' value='" . $row['book_id'] . "'>
                                                                            <div class='mb-1'>
                                                                                <label for='contact_num' class='form-label'>Title</label>
                                                                                <input type='text' class='form-control' name='title' value='" . htmlspecialchars($row['title']) . "' required>
                                                                            </div>
                                                                            <div class='mb-1'>
                                                                                <label for='email' class='form-label'>Author</label>
                                                                                <input type='text' class='form-control' name='author' value='" . htmlspecialchars($row['author']) . "' required>
                                                                            </div>
                                                                            <div class='mb-1'>
                                                                                <label for='category' class='form-label'>Category</label>
                                                                                <input type='text' class='form-control' name='category' value='" . htmlspecialchars($row['category']) . "' required>
                                                                            </div>
                                                                            <div class='mb-2'>
                                                                                <label for='email' class='form-label'>Description</label>
                                                                                <textarea class='form-control' name='desc' required>" . htmlspecialchars($row['description']) . "</textarea>                                                                            </div>
                                                                            <button type='submit' name='bookUpdate' class='btn btn-success'>Update</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>";
                                        echo "</tr>";

                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            $bookId = $_POST['bookId'];
                                            $title = $_POST['title'];
                                            $author = $_POST['author'];
                                            $category = $_POST['category'];
                                            $desc = $_POST['desc'];

                                            // Retrieve the current values from the database
                                            $currentValuesStmt = $conn->prepare("SELECT title, author, category, description FROM book_table WHERE book_id = ?");
                                            $currentValuesStmt->bind_param("i", $bookId);
                                            $currentValuesStmt->execute();
                                            $currentValuesStmt->bind_result($current_title, $current_author, $current_category, $current_desc);
                                            $currentValuesStmt->fetch();
                                            $currentValuesStmt->close();

                                            // Check if any of the submitted values are different from the current values
                                            $isChanged = false;

                                            if ($title !== $current_title) {
                                                $isChanged = true;
                                            }
                                            if ($author !== $current_author) {
                                                $isChanged = true;
                                            }
                                            if ($category !== $current_category) {
                                                $isChanged = true;
                                            }
                                            if ($desc !== $current_desc) {
                                                $isChanged = true;
                                            }

                                            if ($isChanged) {
                                                // Prepare and execute the update query
                                                $updateStmt = $conn->prepare("UPDATE book_table SET title = ?, author = ?, category = ?, description = ? WHERE book_id = ?");
                                                $updateStmt->bind_param("ssssi", $title, $author, $category, $desc, $bookId);

                                                if ($updateStmt->execute()) {
                                                    echo "<script>window.location.href = 'books.php?alert=success&message=" . urlencode('Book updated!') . "';</script>";
                                                } else {
                                                    echo "<script>window.location.href = 'books.php?alert=danger&message=" . urlencode('ERROR: Book not updated!') . "';</script>";
                                                }
                                                exit;
                                                
                                                $updateStmt->close();
                                            } else {
                                                // If no changes, redirect without alert
                                                echo "<script>window.location.href = 'books.php';</script>";
                                            }
                                        }
                                    }

                                    if (mysqli_num_rows($query) === 0) {
                                        echo "<tr><td colspan='5'>No records found</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class='btn btn-success mb-3' data-bs-toggle="modal" data-bs-target="#addBooks">Add Books</button>
                    <?php
                        // Display pagination buttons
                        echo "<div class='pagination-buttons'>";
                        echo "<form action='' method='get'>"; // Change to GET method

                        // Include search and status in the pagination links
                        $filter_params = '';
                        if (!empty($search)) {
                            $filter_params .= '&search=' . urlencode($search);
                        }
                        if (!empty($selected_category)) {
                            $filter_params .= '&category=' . urlencode($selected_category);
                        }

                        // Pagination buttons
                        if ($_SESSION['bcurrent_page'] > 1) {
                            echo "<a href='?page=" . ($_SESSION['bcurrent_page'] - 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&lt;</a>";
                        } else {
                            echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&lt;</button>";
                        }
                        echo "<span> Page " . $_SESSION['bcurrent_page'] . " of $total_pages </span>";
                        if ($total_books > $records_per_page && $_SESSION['bcurrent_page'] < $total_pages) {
                            echo "<a href='?page=" . ($_SESSION['bcurrent_page'] + 1) . $filter_params . "' class='btn btn-danger' style='width: 50px;'>&gt;</a>";
                        } else {
                            echo "<button type='button' class='btn btn-danger' style='width: 50px;' disabled>&gt;</button>";
                        }
                        echo "</form>";
                        echo "</div>";
                        } else {
                            $_SESSION['alert'] = ['message' => 'Error fetching data: ' . mysqli_error($conn), 'type' => 'danger'];
                        }
                    ?>
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
                            const message = decodeURIComponent(urlParams.get('message'));
                            appendAlert(message, alertType);
                            
                            // Clear the alert parameter from the URL
                            urlParams.delete('alert');
                            window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
                        }
                </script>
            </div>

            <!-- Modal to add Books -->
            <div class="modal fade" id="addBooks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered border-radius-3">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <h2 class="text-center">Add Books</h2><br>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Title" name="title" id="title" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                </div>

                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Author" name="author" id="author" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                </div>

                                <div class="input-group mb-2">
                                    <select name="category" id="category" class="form-select" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                                        <?php endforeach; ?>
                                        <option value="new">Add new category...</option>
                                    </select>
                                </div>

                                <div class="input-group mb-3" id="new-category-input" style="display: none;">
                                    <input type="text" class="form-control" placeholder="New Category" name="newCategory" id="newCategory" required>
                                </div>
                                
                                <script>
                                    document.getElementById('category').addEventListener('change', function() {
                                        const newGenreInput = document.getElementById('new-category-input');
                                        const newCategoryField = document.getElementById('newCategory');
                                        
                                        if (this.value === 'new') {
                                            newGenreInput.style.display = 'block';
                                            newCategoryField.required = true; // Set required when showing
                                            newCategoryField.style.width = '100%';
                                        } else {
                                            newGenreInput.style.display = 'none';
                                            newCategoryField.required = false; // Remove required when hiding
                                        }
                                    });
                                </script>

                                <div class="input-group mb-2">
                                    <textarea type="text" class="form-control" placeholder="Description" name="description" id="description" autocomplete="off" required style="border-radius: 0.375rem; width: auto;"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="cover" class="form-label">Upload Book Cover</label>
                                    <input type="file" class="form-control" name="cover" id="cover" accept="image/*" required>
                                </div>

                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" name="submit" class="btn btn-danger w-40">Add Book</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
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