<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        /* backdrop-filter: blur(5px); */
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
    section form input{
        border-radius: 5px;
    }
    form{
        padding: 45px 40px;
        border-radius: 56px;
        width: 400px;
    }
</style>

<body>
    
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="admin_dashboard.php" class="navbar-brand">
                <img class="logo" src="../../img/bsulogo.png" alt="Logo">LIBRALINK
            </a>
        </div>
    </nav>

    <main class="bg">
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="form-container col-12 d-flex align-items-center justify-content-center">
                        <?php
                            // Include the database configuration file
                            require_once '../db_config.php';

                            if(isset($_SESSION['message'])) {
                                echo "<script>alert('" . $_SESSION['message'] . "')</script>";
                                unset($_SESSION['message']);
                            }

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
                                    $_SESSION['message'] = 'Book Added Succesfuly!';
                                    header('Location: admin_dashboard.php');
                                    exit;
                                }else {
                                    echo "<script>alert('Error adding book: " . mysqli_error($conn) . "')</script>";
                                }
                            }
                        ?>
                        <form action="add_books.php" method="post" enctype="multipart/form-data" style="background: rgba(97, 97, 97, 0.2); backdrop-filter: blur(5px);">
                            <h2>Add Books</h2><br>
                            <div class="content">
                                <div class="mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Title" name="title" id="title" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Author" name="author" id="author" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Genre" name="genre" id="genre" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Description" name="description" id="description" autocomplete="off" required style="border-radius: 0.375rem; width: auto;">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="submit" name="submit" class="btn btn-primary w-40">Add Book</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>