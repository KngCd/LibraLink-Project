<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin or Student</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    section{
        width: auto;
        border-radius: 50%;
        height: 500px;
        cursor: pointer;
    }
    section a{
        text-decoration: none;
        color: black;
    }
</style>

<body>

    <header>
        <div class="title bg-primary text-center">
            <a style="color: black" href="../index.html"><h1>LibraLink</h1></a>
            <h4>Integrated System for Student Logging,<br>
                 Borrowing, and Inventory Management</h4>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            <div class="row px-5 py-4">
                <section class="d-flex justify-content-center align-items-center 
                bg-secondary text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div><a href="admin-login.php"><h1 style="font-size: 5rem;">ADMIN</h1></a></div>
                </section>

                <section class="d-flex justify-content-center align-items-center 
                bg-secondary text-center col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div><a href="student-login.php"><h1 style="font-size: 5rem;">STUDENT</h1></a></div>
                </section>
            </div>
        </div>
    </main>

    
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>