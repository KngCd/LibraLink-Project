<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    ::backdrop {
        backdrop-filter: blur(3px);
    }
    table, th,td{
        padding: 10px;
        text-align: center;
        border: 1px solid black;
    }
    #total-register, #accepted-student, #books, #total-stocks, #total-borrowed, #total-available{
        border-radius: 10px;
        border: 1px solid lightgray;
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

    <?php
        require_once '../db_config.php';

        // Fetch the classes created by the teacher from the database
        $query = mysqli_query($conn, "SELECT *FROM verification_table");
        $result = mysqli_num_rows($query);

        $query2 = mysqli_query($conn, "SELECT *FROM student_table");
        $result2 = mysqli_num_rows($query2);

        $query3 = mysqli_query($conn, "SELECT *FROM book_table");
        $result3 = mysqli_num_rows($query3);

        $query4 = mysqli_query($conn, "SELECT SUM(stocks) AS total_stocks FROM inventory_table");
        $result4 = mysqli_fetch_assoc($query4);
        if($result4['total_stocks'] == 0){
            $sum_stocks = 0;
        }
        else{
            $sum_stocks = $result4['total_stocks'];
        }

        $query5 = mysqli_query($conn, "SELECT *FROM borrow_table");
        $total_borrow = mysqli_num_rows($query5);

        if(isset($_SESSION['message'])) {
            echo "<script>alert('" . $_SESSION['message'] . "')</script>";
            unset($_SESSION['message']);
        }
    ?>

    <main>
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
                                <h5 class="card-title">Total Books Currently Available</h5>
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
    </main>
    

    <script src="../../js/bootstrap.min.js"></script>
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
</body>
</html>