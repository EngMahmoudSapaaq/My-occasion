<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch service types from the database
$type_query = "SELECT * FROM `service_type`";
$type_result = mysqli_query($conn, $type_query);

if (!$type_result) {
    die('Query failed: ' . mysqli_error($conn));
}
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
                        <a href="categories.php" class="nav-item nav-link" style="color: #557187">Store Categories</a>
                        <a href="service_providers.php" class="nav-item nav-link" style="color: #CCB17A">Service Providers</a>
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
                <div class="row g-0 gx-5 align-items-end">
                    <div class="col-lg-6">
                        <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                            <h1 class="mb-3" style="color: #DCD4B6">Categories</h1>
                            <hr style="width: 100%; height: 3px; color: #557187;">
                            <p>Explore the finest shops, restaurants, and cafes across the Kingdom of Saudi Arabia. Whether you're planning a celebration or a professional event, find the perfect location that meets your needs.</p>
                        </div>
                    </div>

                    <!-- Search Bar with Button -->
                    <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                        <div class="input-group">
                            <input type="text" id="categorySearchInput" class="form-control" placeholder="Search by Category Name...">
                            <button class="btn btn-primary" type="button" onclick="filterCategories()" style="background: #DCD4B6;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Categories Section -->
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4" id="categoriesList">
                            
                            
                             <?php
                                if (mysqli_num_rows($type_result) > 0) {
                                    while ($row = mysqli_fetch_assoc($type_result)) {
                                        $img = 'img/' . $row['image'];
                                        $id = $row['type_id'];

                                        // Get the count of service providers for each service type
                                        $provider_query = "SELECT COUNT(*) as total FROM `service_providers` WHERE type_id = '$id'";
                                        $provider_result = mysqli_query($conn, $provider_query);
                                        $provider_data = mysqli_fetch_assoc($provider_result);
                                        $total = $provider_data['total'];

                                        echo '
                                            
<div class="col-lg-4 col-md-6 category-item" data-category-name="' . $row['name'] . '">
                                <div class="property-item rounded overflow-hidden wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="position-relative overflow-hidden">
                                        <a href="stores.php?storetype=' . $id . '"><img class="img-fluid" style="height: 275px;width: 100%" src="' . $img . '" alt=""></a>
                                        <div class="rounded position-absolute start-0 top-0 m-4 py-1 px-3" style="background: #DCD4B6;color: black">' . $row['name'] . '</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">' . $row['name'] . '</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">' . $total . ' Stores</h5>
                                        <a href="stores.php?storetype=' . $id . '" class="btn btn-primary mt-3" style="background: #DCD4B6;">View Stores</a>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                            ';
                                    }
                                } else {
                                    echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">There are no stores</h5>
                                    </div>
                                </div>
                            </div>';
                                }
                                ?>
                            
                            
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Property List End -->
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

    <!-- JavaScript to filter categories -->
    <script>
        function filterCategories() {
            let input = document.getElementById('categorySearchInput').value.toLowerCase();
            let categoryItems = document.getElementsByClassName('category-item');

            for (let i = 0; i < categoryItems.length; i++) {
                let categoryName = categoryItems[i].getAttribute('data-category-name').toLowerCase();
                if (categoryName.includes(input)) {
                    categoryItems[i].style.display = "";
                } else {
                    categoryItems[i].style.display = "none";
                }
            }
        }
    </script>

    <!-- Libraries for animation -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Main JavaScript -->
    <script src="js/main.js"></script>
</body>

</html>
