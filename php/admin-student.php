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
    .navbar{
        position: fixed;
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
        background: linear-gradient(rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.75)), url(../img/bsu.jpg);
        background-size: cover;
        background-attachment: fixed;
    }
</style>

<body>

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark py-4">
        <div class="container">
            <a href="../index.html" class="navbar-brand">
                <img class="logo" src="../img/bsulogo.png" alt="Logo">LIBRALINK
            </a>
        </div>
    </nav>

    <section class="bg vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <!-- For Admin Login -->
                <div class="d-flex justify-content-center align-items-center 
                 text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div class="card" style="width: 18rem;">
                        <img src="../img/admin.webp" class="card-img-top" alt="Admin">
                        <div class="card-body">
                            <h1 class="card-title">Admin</h1>
                            <a href="admin/admin-login.php" class="btn btn-primary">Click Here!</a>
                        </div>
                    </div>
                </div>
                <!-- For Student Login -->
                <div class="d-flex justify-content-center align-items-center 
                 text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div class="card" style="width: 18rem;">
                        <img src="../img/student2.webp" class="card-img-top" alt="Student">
                        <div class="card-body">
                            <h1 class="card-title">Student</h1>
                            <a href="student/student-login.php" class="btn btn-primary">Click Here!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>