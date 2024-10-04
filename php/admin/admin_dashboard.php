<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .navbar{
        position:absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        backdrop-filter: blur(5px);
    }
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
    .bg {
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
    ::backdrop {
        backdrop-filter: blur(3px);
    }
    table th,td{
        padding: 10px;
        text-align: center;
        border: 1px solid black;
    }
    form{
        padding: 35px 40px;
        width: 400px;
    }
</style>

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark py-4">
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

        if(isset($_SESSION['message'])) {
            echo "<script>alert('" . $_SESSION['message'] . "')</script>";
            unset($_SESSION['message']);
        }
    ?>

    <main class="bg">
        <section class="text-dark p-5 d-flex align-items-center justify-content-center vh-100" style="position: relative;">
            <div class="container overflow-hidden">
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
                                <h5 class="card-title">Total Books</h5>
                                <hr>
                                <h6 class="card-text mb-4 text-center fs-3"><b><?php echo $result3 ?></b></h6>
                                <button class="btn btn-primary" popovertarget="books">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div popover id="total-register">
                    <?php
                        // Fetch the classes created by the teacher from the database
                        $query = mysqli_query($conn, "SELECT * FROM verification_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
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
                                                $query = "INSERT INTO student_table (student_id, full_name, email, password, program, department) VALUES ('$student_id', '$full_name', '$email', '$password', '$program', '$department')";
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
                <div popover id="accepted-student">
                    <?php
                        // Fetch the classes created by the teacher from the database
                        $query = mysqli_query($conn, "SELECT * FROM student_table");
                        
                        // Check if the query was successful
                        if ($query) {
                            // Display the rows
                            echo "<table style='width: 100%; border: 1px solid black; border-collapse: collapse;'>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Program</th>";
                            echo "<th>Department</th>";
                            echo "<th>Approve</th>";
                            echo "</tr>";
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
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
                <div popover id="books">
                    <?php
                        // Fetch the classes created by the teacher from the database
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