<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();

$user_id_SESSION = $_SESSION['user_id'] ?? null;
$service_id = $_GET['service_id'] ?? null;

if (isset($_POST['submit'])) {
    if ($user_id_SESSION) {
    $deliveryDate = mysqli_real_escape_string($conn, $_POST['deliveryDate']);
    $additionalDescription = mysqli_real_escape_string($conn, $_POST['additionalDescription']);

    if (!empty($_POST['user_type'])) {
        foreach ($_POST['user_type'] as $check) {
            $region = $check;
        }
    }

    //---------------------
   
    
        $services = mysqli_query($conn, "SELECT * FROM `services` WHERE service_id = '$service_id'") or die('query failed');
        $service = mysqli_fetch_assoc($services);
        $provider_id=$service['provider_id'];
        $time1=$service['Time'];
        $time2=$service['time1'];
        
        //---------------------------------
        if ($deliveryDate==$time1 || $deliveryDate==$time2){
             $message[] = 'The provider of this service will be on vacation on this time please change it .';
        } else {
           $insert = mysqli_query($conn, "INSERT INTO `requests`(user_id,service_id, request_status, delivery_date ,additional_description,Region,provider_id)"
                . " VALUES('$user_id_SESSION','$service_id', 'Pending', '$deliveryDate', '$additionalDescription','$region','$provider_id')") or die('query failed');

        if ($insert) {
            // Trigger success modal after successful insertion
            echo '<script>
                    window.onload = function() {
                        var successModal = new bootstrap.Modal(document.getElementById("successModal"));
                        successModal.show();
                    };
                  </script>';
        } else {
            $message[] = 'Failed to send request';
        } 
        }
        
    
    } else {
        echo '<script>
        
        alert(\'You must log in first !\');
            </script>';
    }
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Payment - My Occasion</title>
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
            .modal-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                padding: 15px;
                border-radius: 10px;
                text-align: center;
                margin-top: 110px;
            }

            .modal-success i {
                font-size: 50px;
                margin-bottom: 20px;
            }

            .message {
                margin: 10px 0;
                width: 100%;
                border-radius: 5px;
                padding: 10px;
                text-align: center;
                background-color: red;
                color: white;
                font-size: 20px;
            }

            .message1 {
                margin: 10px 0;
                width: 100%;
                border-radius: 5px;
                padding: 10px;
                text-align: center;
                background-color: green;
                color: white;
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <div class="container-xxl bg-white p-0">
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Navbar -->
             <div class="container-fluid nav-bar bg-transparent">
                <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                    <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                        <div class="icon p-2 me-2">
                            <img class="img-fluid" src="img/team.png" alt="Icon" style="width: 30px; height: 30px;">
                        </div>
                        <h1 class="m-0" style="color: #DCD4B6">My Occasion</h1>
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

                         <?php
                        
                        if ($user_id_SESSION) {
                            echo '<a href="End_User/new_Requests.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background: #DCD4B6">profile</a>
                    ';
                        } else {
                            echo '<a href="login.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background: #DCD4B6">Login</a>
                    ';
                        }
                        ?></div>
                </nav>
            </div>
            <!-- Payment Form Start -->
            <div class="container d-flex justify-content-center align-items-center vh-100" style="margin-top: 20px">
                <div class="col-md-6">
                    <div class="bg-light rounded p-5">
                        <h2 class="text-center mb-4" style="color: #DCD4B6">Order</h2>

                        <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '<div class="message">' . $message . '</div>';
                            }
                        }
                        ?>

                        <form method="post" enctype="multipart/form-data">
                            <?php
                        
                        if ($user_id_SESSION) {
                            $services_data = mysqli_query($conn, "SELECT * FROM `services` WHERE service_id = '$service_id'") or die('query failed');
        $services_data_view = mysqli_fetch_assoc($services_data);
                            
                            echo '<div class="mb-3">
                                <label for="deliveryDate" class="form-label">The provider of this service will be on vacation on :</label><br>
                                <label for="deliveryDate" class="form-label" style="color: red">'.$services_data_view['Time'].'</label> //
                                <label for="deliveryDate" class="form-label" style="color: red">'.$services_data_view['time1'].'</label>
                                
                            </div>';
                        }
                        ?>
                            
                            <div class="mb-3">
                                <label for="deliveryDate" class="form-label" >Delivery Date</label>
                                <input type="date" name="deliveryDate" class="form-control" id="deliveryDate" required>
                            </div>

                            <div class="mb-3" name="user_type[]">
                                <label for="region" class="form-label">Region</label>
                                <select class="form-select" id="region" name="user_type[]" required>
                                    <option value="" selected disabled>Select region</option>
                                    <option value="Riyadh">Riyadh</option>
                                    <option value="Mecca">Mecca</option>
                                    <option value="Jeddah">Jeddah</option>
                                    <option value="Abha">Abha</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="additionalDescription" class="form-label">Additional Description (optional)</label>
                                <textarea class="form-control" name="additionalDescription" id="additionalDescription" rows="3" placeholder="Enter any special requests or notes about the product..."></textarea>
                            </div>

                            <div class="d-grid">
                                <input class="btn btn-primary" style="background: #DCD4B6;" value="Book Now" name="submit" type="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Payment Form End -->

            <!-- Success Modal -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body modal-success">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            <h4>Booking was completed successfully!</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            <div class="container-fluid  text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style="background: #DCD4B6;">
                <center>
                    <div class="container" style="color: black">
                        All rights reserved &copy; 2024 - My occasion
                    </div>
                </center>
            </div>
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
