<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



    $service_providers = mysqli_query($conn, "SELECT * FROM `service_providers` ") or die('Query failed: ' . mysqli_error($conn));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Occasion</title>
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
                    <h1 class="m-0" style="color: #DCD4B6">My Occasion</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link" style="color: #CCB17A">Home</a>
                        <a href="services.php" class="nav-item nav-link" style="color: #CCB17A">Services</a>
                        <a href="categories.php" class="nav-item nav-link" style="color: #CCB17A">Store categories</a>
                        <a href="service_providers.php" class="nav-item nav-link" style="color: #557187">Service providers</a>
                    </div>
                   <?php
                        session_start();

                        $user_id_SESSION = $_SESSION['user_id'] ?? null;
                        if ($user_id_SESSION) {
                            echo '<a href="End_User/new_Requests.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background: #DCD4B6">profile</a>
                    ';
                        } else {
                            echo '<a href="login.php" class="btn btn-primary px-3 d-none d-lg-flex" style="background: #DCD4B6">Login</a>
                    ';
                        }
                        ?>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->

        <!-- Property List Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <!-- Title and Intro Section -->
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3" style="color: #DCD4B6">Service Providers</h1>
                    <hr style="width: 100%; height: 3px; color: #557187;">
                    <p>Our experienced team of service providers offers the best services in various fields. They ensure your needs are met with professionalism and quality.</p>
                </div>

                <!-- Search Bar with Button -->
                <div class="row mb-4">
                    <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Service Provider Name...">
                            <button class="btn btn-primary" type="button" onclick="filterProviders()">
                                <i class="fas fa-search"></i> <!-- Search Icon -->
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Providers Section -->
                <div class="row g-4" id="providersList">
                    
                    <?php
                    
                    if (isset($service_providers) && mysqli_num_rows($service_providers) > 0) {
                                while ($service_provider = mysqli_fetch_assoc($service_providers)) {
                                    $type_id  =$service_provider['type_id'];
                                    $service_types = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id  = '$type_id '") or die('Query failed');
                                    $service_type = mysqli_fetch_assoc($service_types);
                                    $img = 'img/' . $service_provider['logo'];
                                    $id=$service_provider['provider_id'];
                                    echo '<!-- Provider 1 -->
                    <div class="col-lg-3 col-md-6 provider-item" data-provider-name="'.$service_provider['store_name'].'">
                        <div class="team-item rounded overflow-hidden wow fadeInUp" data-wow-delay="0.1s">
                            <div class="position-relative">
                                <img class="img-fluid" src="'.$img.'" alt="Sarah Johnson">
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">'.$service_provider['store_name'].'</h5>
                                <small>'.$service_type['name'].'</small>
                                <p class="mt-2">'.$service_provider['description'].'</p>
                                    <a href="services.php?store_id='.$id.'" class="btn btn-primary" style="background: #DCD4B6; margin-bottom: 8px;color:white">View service</a>
                                        
                            </div>
                        </div>
                    </div>';
                                }
                                
                    } else {
                        echo '<!-- Provider 2 -->
                    <div class="col-lg-3 col-md-6 provider-item" data-provider-name="David Lee">
                        <div class="team-item rounded overflow-hidden wow fadeInUp" data-wow-delay="0.3s">
                            
                            <div class="text-center p-4 mt-3">
                                <h2 class="fw-bold mb-0">There are no service providers available</h2>
                                 </div>
                        </div>
                    </div>';
                    }
                    
                    
                    ?>
                    
                    

                    
                    
                    
                    

                    <!-- Provider 3 
                    <div class="col-lg-3 col-md-6 provider-item" data-provider-name="Emily Davis">
                        <div class="team-item rounded overflow-hidden wow fadeInUp" data-wow-delay="0.5s">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/provider.jpg" alt="Emily Davis">
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Emily Davis</h5>
                                <small>Decoration Services</small>
                                <p class="mt-2">Expert in event decoration, providing stylish and custom designs to make your event visually stunning.</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-lg-3 col-md-6 provider-item" data-provider-name="Michael Brown">
                        <div class="team-item rounded overflow-hidden wow fadeInUp" data-wow-delay="0.7s">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/provider.jpg" alt="Michael Brown">
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Michael Brown</h5>
                                <small>Entertainment Services</small>
                                <p class="mt-2">Providing top entertainment services including DJ, live performances, and interactive events for all occasions.</p>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>

        <!-- Property List End -->
        <!-- Footer Start -->
        <div class="container-fluid text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style="background: #DCD4B6;">
            <center>
                <div class="container" style="color: black">
                    All rights reserved &copy; 2024 - My Occasion
                </div>
            </center>
        </div>
        <!-- Footer End -->
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="background: #DCD4B6"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        function filterProviders() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const providers = document.querySelectorAll('.provider-item');

            providers.forEach(provider => {
                const providerName = provider.getAttribute('data-provider-name').toLowerCase();
                if (providerName.includes(filter)) {
                    provider.style.display = ''; // Show the provider
                } else {
                    provider.style.display = 'none'; // Hide the provider
                }
            });
        }
    </script>
</body>

</html>
