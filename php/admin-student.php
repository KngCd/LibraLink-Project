<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin or Student</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
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
    /* .navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        /* backdrop-filter: blur(5px); */
    /* } */
    .navbar-brand{
        /* font-family: 'Times New Roman', Times, serif; */
        font-size: 30px;
        color: black;
    }
    .logo {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .logo:hover {
        transform: scale(1.1);
    }
    body {
        background: linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)), url(../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
</style>

<body>

    <main>
        <div class="container">
            <div class="row">
                <!-- Navbar -->
                <section class="col-12 mt-2">
                    <!-- <nav class="navbar navbar-expand-lg navbar-dark py-4"> -->
                        <div class="container d-flex align-items-center justify-content-between">
                            <a href="../index.html" class="navbar-brand d-xxl-block d-xl-block d-lg-block d-md-none d-sm-none d-none">
                                <img class="img-fluid logo" src="../img/bsulogo.png" alt="Logo" style="height: 178px; width: 188px;">
                            </a>
                            <a href="../index.html" class="navbar-brand fs-xxl-2 fs-xl-2 fs-lg-4 fs-md-4 fs-sm-5 fs-2">
                                <img class="img-fluid logo" src="../img/libra2-cropped.png" alt="Logo" style="max-height: 150px; width: auto;">
                                <p class="text-center" style="font-weight: 650;">Integrated System for Student Logging, <br>Borrowing, and Inventory Management</p>
                            </a>
                            <a href="../index.html" class="navbar-brand d-xxl-block d-xl-block d-lg-block d-md-none d-sm-none d-none">
                                <img class="img-fluid logo" src="../img/redspartan.png" alt="Logo" style="height: 230px; width: 200px;">
                            </a>
                        </div>
                    <!-- </nav> -->
                </section>

                <section class="col-12 mt-4 d-flex align-items-center justify-content-center">
                    <div class="container">
                        <div class="row">
                            <!-- For Admin Login -->
                            <div class="d-flex justify-content-center align-items-center 
                            text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="card p-4" style="border-radius: 50%; border: 1px solid black;">
                                    <a class="logo" href="admin/admin-login.php">
                                        <img src="../img/admin.webp" class="card-img-top" alt="Admin" title="Admin">
                                    </a>
                                    <!-- <div class="card-body">
                                        <h1 class="card-title">Admin</h1>
                                        <a href="admin/admin-login.php" class="btn btn-danger">Click Here!</a>
                                    </div> -->
                                </div>
                            </div>
                            <!-- For Student Login -->
                            <div class="d-flex justify-content-center align-items-center 
                            text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="card p-2" style="border-radius: 50%; border: 1px solid black;">
                                    <a class="logo" href="student/student-login.php">
                                        <img src="../img/student2.webp" class="card-img-top" alt="Student" title="Student">
                                    </a>
                                    <!-- <div class="card-body">
                                        <h1 class="card-title">Student</h1>
                                        <a href="student/student-login.php" class="btn btn-danger">Click Here!</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer class="text-center mt-3 p-3 fs-4 text-light" style="background-color: #dd2222;">
        Batangas State University - The National Engineering University - Lipa Campus
    </footer>

    
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>