<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
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
    body{
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    section form input{
        border-radius: 5px;
    }
    table, th,td{
        padding: 10px;
        text-align: center;
        border: 1px solid black;
        background: white;
    }
</style>

<body>
    
    <header class="sticky-top z-1" style="backdrop-filter: blur(5px);">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" style="background: none;">
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
        </nav>
    </header>

    <main>
        <section class="m-5 d-flex align-items-center justify-content-center">
            <div class="container bg-light p-3 border border-secondary-subtle rounded">
                <div class="row">
                    <div class="col-12">
                            <?php
                                require_once '../db_config.php';

                                // Check if the form has been submitted
                                if (isset($_POST['submit'])) {
                                    $book_id = $_POST['book_id'];
                                    $title = $_POST['title'];
                                    $status = $_POST['status'];
                                    $new_stocks = $_POST['stocks'];
                                    $token = $_POST['token'];

                                    if ($_POST['submit'] == 'confirm') {
                                        // Check if the book_id already exists in the inventory_table
                                        $check_query = "SELECT * FROM inventory_table WHERE book_id = '$book_id'";
                                        $check_result = mysqli_query($conn, $check_query);
                                        
                                        if (mysqli_num_rows($check_result) == 0) {
                                            // Insert the details into the inventory_table
                                            $query = "INSERT INTO inventory_table (book_id, stocks, status) VALUES ('$book_id', '$new_stocks', '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "')";
                                            mysqli_query($conn, $query);

                                        } else {
                                            // Update the existing record in the inventory_table
                                            $query = "UPDATE inventory_table SET stocks = stocks + '$new_stocks', status = '" . ($new_stocks > 0 ? 'Available' : 'Not Available') . "' WHERE book_id = '$book_id'";
                                            mysqli_query($conn, $query);
                                        }
                                    }

                                    // Display an alert message
                                    echo "<script>alert('Stocks added successfully!');</script>";
                                    // Redirect to the same page to prevent form resubmission
                                    header('Location: ' . $_SERVER['PHP_SELF']);
                                    exit;
                                }

                                // Fetch the books with their current stocks
                                $query = mysqli_query($conn, "SELECT b.book_id, b.title, i.status, COALESCE(i.stocks, 0) AS stocks 
                                                        FROM book_table AS b
                                                        LEFT JOIN inventory_table AS i ON b.book_id = i.book_id"); // we're using the COALESCE function to replace NULL values in the stocks column with 0

                                // Check if the query was successful
                                if ($query) {
                                    // Display the rows
                                    echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                                    echo "<tr>";
                                    echo "<th>Book ID</th>";
                                    echo "<th>Title</th>";
                                    echo "<th>Status</th>";
                                    echo "<th>Stocks</th>";
                                    echo "<th>Add Stocks</th>";
                                    echo "</tr>";
                                    $num_rows = mysqli_num_rows($query);
                                    if ($num_rows === 0) {
                                        echo "<td colspan='4'>No records found</td>";
                                    } else {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['book_id'] . "</td>";
                                            echo "<td>" . $row['title'] . "</td>";
                                            echo "<td>" . ($row['stocks'] == 0 ? 'Not Available' : $row['status']) . "</td>";
                                            echo "<td>" . $row['stocks'] . "</td>";
                                            
                                            echo "<td>
                                                <form action='' method='post'>
                                                    <input type='hidden' name='book_id' value='" . $row['book_id'] . "'>
                                                    <input type='hidden' name='title' value='" . $row['title'] . "'>
                                                    <input type='hidden' name='status' value='" . $row['status'] . "'>
                                                    <div class='input-group'>
                                                        <input class='form-control me-1' type='number' name='stocks' value='0' required style='border-radius: 0.375rem; width: 20px;'>
                                                        <span><button type='submit' name='submit' value='confirm' class='btn btn-success'>Confirm</button></span>
                                                    </div>
                                                </form>
                                                </td>";
                                        }
                                    }
                                    echo "</tr>";
                                    echo "</table> <br>";
                                } else {
                                    echo "Error fetching data: " . mysqli_error($conn);
                                }

                                // Close the connection
                                mysqli_close($conn);
                                ?>
                        <a href="admin_dashboard.php"><button type='button' class='btn btn-primary'>Back to Dashboard</button></a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>