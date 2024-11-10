<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $service_providers = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    if (mysqli_num_rows($admin) > 0) {
        $admin_data = mysqli_fetch_assoc($admin);
        $_SESSION['user_id'] = $admin_data['admin_Id'];
        header('location: admin/home.php');
    }elseif (mysqli_num_rows($users) > 0) {
        $row = mysqli_fetch_assoc($users);
        $_SESSION['user_id'] = $row['user_id'];
        header('location: End_User/new_Requests.php');
    }elseif (mysqli_num_rows($service_providers) > 0) {
        $row = mysqli_fetch_assoc($service_providers);
        $status=$row['status'];
        if ($status=='accepted'){
            $_SESSION['user_id'] = $row['provider_id'];
        header('location: Service_Provider/service_Management.php');
        }elseif ($status=='blocked'){
             $message[] = 'This account has been blocked by the admin!';
        } else {
            $message[] = 'This account is new and has not been viewed by the admin yet!';
        }
        
    } else {
        $message[] = 'incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Login - My Occasion</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <style>
            .message{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:red;
                color:white;
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <div class="container-xxl bg-white p-0">
            <!-- Spinner Start -->
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Spinner End -->

            <!-- Navbar Start -->
            <div class="container-fluid nav-bar bg-transparent">
                <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                    <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                        <div class="icon p-2 me-2">
                            <img class="img-fluid" src="img/team.png" alt="Icon" style="width: 30px; height: 30px;">
                        </div>
                        <h1 class="m-0" style="color: #DCD4B6">My occasion</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="index.php" class="nav-item nav-link " style="color: #CCB17A">Home</a>
                            <a href="services.php" class="nav-item nav-link " style="color: #CCB17A">Services</a>

                            <a href="categories.php" class="nav-item nav-link" style="color: #CCB17A">Store categories</a>
                            <a href="service_providers.php" class="nav-item nav-link" style="color: #CCB17A">Service providers</a>

                        </div>
                        <a href="registerUser.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background:  #DCD4B6">register</a>
                    </div>
                </nav>
            </div>
            <!-- Navbar End -->

            <!-- Login Form Start -->
            <div class="container d-flex justify-content-center align-items-center vh-100">
                <div class="col-md-6">
                    <div class="bg-light rounded p-5">
                        <h2 class="text-center mb-4" style="color: #DCD4B6;">Login</h2>
                        <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '<div class="message">' . $message . '</div>';
                            }
                        }
                        ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control"  type="email" name="email" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" type="password" name="password" id="password" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <input class="btn btn-primary" style="background: #DCD4B6;" id="submitButton" value="Login" name="submit" type="submit">
                               </div>
                            <p class="text-center mt-3">Don't have an account? <a href="registerUser.php" style="color: #CCB17A;">Register</a></p>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Login Form End -->

            <!-- Footer Start -->
            <div class="container-fluid  text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style="background: #DCD4B6;">
                <center>
                    <div class="container" style="color: black">
                        All rights reserved &copy; 2024 - My occasion
                    </div>
                </center>
            </div>
            <!-- Footer End -->

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="background: #DCD4B6;"><i class="bi bi-arrow-up"></i></a>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>
