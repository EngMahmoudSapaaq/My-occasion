<?php
include 'config.php';

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    
     if (!empty($_POST['user_type'])) {
        foreach ($_POST['user_type'] as $check) {
            $region = $check;
        }
     }

    //---------------------
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'user already exist';
    } else {
        if ($password != $cpassword) {
            $message[] = 'confirm password not matched!';
        } else {
            $insert = mysqli_query($conn, "INSERT INTO `users`(username, email, password ,phone_number, region) VALUES('$name', '$email', '$password', '$phone', '$region')") or die('query failed');

            if ($insert) {
                $message1[] = ' An account has been created successfully';
            } else {
                $message[] = 'registeration failed!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Register - My Occasion</title>
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
            .message1{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:green;
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

                        <a href="login.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background:  #DCD4B6">Login</a>
                    </div>
                </nav>
            </div>
            <!-- Navbar End -->

            <!-- Register Form Start -->
            <div class="container d-flex justify-content-center align-items-center vh-100" style="margin-bottom: 150px;margin-top: 150px">
                <div class="col-md-6">
                    <div class="bg-light rounded p-5">
                        <h2 class="text-center mb-4" style="color: #DCD4B6;">register for User</h2>
                        <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '<div class="message">' . $message . '</div>';
                            }
                        } elseif (isset($message1)) {
                            foreach ($message1 as $message1) {
                                echo '<div class="message1">' . $message1 . '</div>';
                            }
                        }
                        ?>
                        <form method="post" enctype="multipart/form-data">
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text"  name="name" class="form-control" id="username" placeholder="Enter your username" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">confirm Password</label>
                                <input type="password" name="cpassword" class="form-control" id="password" placeholder="Enter your password" required>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter your phone number" required>
                            </div>

                            <!-- User Type -->
                            <!-- Region -->
                            <div class="mb-3" name="user_type[]">
                                <label for="region" class="form-label">Region</label>
                                <select class="form-select" id="region" name="user_type[]" required>
                                    <option value="" selected disabled>Select region</option>
                                    <option value="Riyadh">Riyadh</option>
                                    <option value="Mecca">Mecca</option>
                                    <option value="Jeddah">Jeddah</option>
                                    <option value="Abha">Abha</option>
                                    <!-- Add more regions as needed -->
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <input class="btn btn-primary" style="background: #DCD4B6;" id="submitButton" value="Register" name="submit" type="submit">
                            </div>

                            <p class="text-center mt-3">Already have an account? <a href="login.php" style="color: #CCB17A;">Login</a></p>
                            <p class="text-center mt-3">Do you want to register as a service provider?<a href="registerProvider.php" style="color: #CCB17A;"> register</a></p>

                        </form>
                    </div>
                </div>
            </div>
            <!-- Register Form End -->

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
