<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    header div h4{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    }

    section form input{
        border-radius: 5px;
    }
    form{
        padding: 45px 40px;
        border-radius: 56px;
        width: 400px;
    }
    section{
        padding: 90px 40px;
    }
</style>

<body>
    
    <header class="container-fluid">
        <div class="bg-dark""><h4 class="bg-secondary text-light justify-content-center d-flex align-items-center">Logo</h4></div>
    </header>

    <main>
        <div class="container-fluid">
                <section class="bg-success">
                    <div class="row">
                        <div class="text-container d-flex justify-content-center flex-column col-lg-6 col-md-6 col-sm-12 col-12 
                        mt-lg-5 mt-md-5 mt-sm-5 mt-5 mb-5">
                            <a href="admin-student.php" class="text-dark"><h1 style="font-size: 4rem;">LibraLink</h1></a>
                            <h4>Integrated System for Student Logging,<br>
                            Borrowing, and Inventory Management</h4>
                        </div>

                        <div class="form-container col-lg-6 col-md-6 col-sm-12 col-12 d-flex align-items-center justify-content-center">
                            <form action="" class="bg-warning">
                                <h2>Welcome, Ka-Spartan</h2><br>
                                <div class="content">
                                    <div class="mb-4">
                                        <div class="input-group">
                                        <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Username" style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" style="border-radius: 0.375rem; width: auto;">
                                        </div>
                                    </div>

                                    <div class="mb-5 d-flex align-items-center justify-content-center">
                                        <!-- <a href="home.html"> -->
                                        <button type="submit" class="btn btn-primary w-40">Login</button>
                                        <!-- </a> -->
                                    </div>

                                    <div style="font-size: 0.80rem;">
                                        <a class="float-start link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="#">Don't have account? Register here</a>
                                        <span><a class="float-end link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="#">Forgot Password?</a></span>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </section>
        </div>
    </main>

    <script src="../js/bootstrap.min.js"></script>
</body>
</html>