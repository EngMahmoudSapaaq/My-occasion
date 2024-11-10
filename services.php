<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$store_id = $_GET['store_id'] ?? null;

// Fetch service types from the database if store_id is available
if ($store_id) {
    $services = mysqli_query($conn, "SELECT * FROM `services` WHERE provider_id = '$store_id'") or die('Query failed: ' . mysqli_error($conn));
} else {
        $services = mysqli_query($conn, "SELECT * FROM `services`") or die('Query failed: ' . mysqli_error($conn));

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
        .filter-card {
            background-color: #DCD4B6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-xxl bg-white p-0">
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
                            <a href="index.php" class="nav-item nav-link" style="color: #CCB17A">Home</a>
                            <a href="services.php" class="nav-item nav-link" style="color: #557187">Services</a>
                            <a href="categories.php" class="nav-item nav-link" style="color: #CCB17A">Store categories</a>
                            <a href="service_providers.php" class="nav-item nav-link" style="color: #CCB17A">Service providers</a>
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
        <div class="container-xxl py-5" style="margin-bottom: 200px">
            <div class="container">
                <div class="row g-0 gx-5 align-items-end">
                    <div class="col-lg-6">
                        <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                            <h1 class="mb-3" style="color: #DCD4B6">Services</h1>
                            <hr style="width: 205%;height: 3px;color: #557187">
                            <p>Explore the finest shops, restaurants, and cafes across the Kingdom of Saudi Arabia.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                        <div class="input-group">
                            <select class="form-control" id="serviceType">
                                <option value="">All Services</option>
                                <?php
                                $types = mysqli_query($conn, "SELECT * FROM `service_type`") or die('Query failed');
                                if (mysqli_num_rows($types) > 0) {
                                    while ($row = mysqli_fetch_assoc($types)) {
                                        echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>No service types available</option>';
                                }
                                ?>
                            </select>
                            <input type="text" id="providerNameInput" class="form-control" placeholder="Provider Name...">
                            <input type="text" id="locationInput" class="form-control" placeholder="Location">
                            <button class="btn btn-primary" type="button" onclick="filterCategories()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <hr>
                        <div class="input-group">
                            <select class="form-control" id="ratingInput">
                                <option value="">All Ratings</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                            <input type="number" id="minPriceInput" class="form-control" placeholder="Min Price...">
                            <input type="number" id="maxPriceInput" class="form-control" placeholder="Max Price">
                            <button class="btn btn-primary" type="button" onclick="filterCategories()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4" id="storeList">
                            <!-- Example Service Cards for Filtering -->
                            <?php
                            if (isset($services) && mysqli_num_rows($services) > 0) {
                                while ($service = mysqli_fetch_assoc($services)) {
                                    $img = 'img/' . htmlspecialchars($service['image1']);
                                    $id = htmlspecialchars($service['service_id']);
                                    $provider_id = htmlspecialchars($service['provider_id']);
                                    
                                    $providers = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE provider_id = '$provider_id'") or die('Query failed');
                                    $provider = mysqli_fetch_assoc($providers);
                                    
                                    $type_id = htmlspecialchars($provider['type_id']);
                                    $types = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed');
                                    $type = mysqli_fetch_assoc($types);
                                    
                                    // Rating logic
                                    $ratings = mysqli_query($conn, "SELECT * FROM `ratings` WHERE service_id = '$id'") or die('Query failed');
                                    $total_rating = 0;
                                    $stars = 0;
                                    $total_reviews = 0;
                                    
                                    while ($rating = mysqli_fetch_assoc($ratings)) {
                                        $total_rating += $rating['rating_value'] ?? 1;
                                        $total_reviews++;
                                    }
                                    $stars = ($total_reviews > 0) ? round($total_rating / $total_reviews, 1) : 0;
                                    
                                    echo '<div class="col-lg-4 col-md-6" data-service-type="' . htmlspecialchars($type['name']) . '" data-provider-name="' . htmlspecialchars($provider['store_name']) . '" data-location="' . htmlspecialchars($provider['location']) . '" data-rating="' . $stars . '" data-price="' . htmlspecialchars($service['price']) . '">
                                        <div class="property-item rounded overflow-hidden">
                                            <div class="position-relative overflow-hidden">
                                                <a><img class="img-fluid" style="height: 275px;width: 100%" src="' . $img . '" alt="Trendy Cafe in Dammam"></a>
                                                <div class="rounded position-absolute start-0 top-0 m-4 py-1 px-3" style="background: #DCD4B6;color: black">Service</div>
                                                <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">' . htmlspecialchars($type['name']) . '</div>
                                            </div>
                                            <div class="p-4 pb-0">
                                                <h5 class="text-primary mb-3">' . htmlspecialchars($service['price']) . ' SAR</h5>
                                                <p class="d-block h5 mb-2" style="color: #557187;">' . htmlspecialchars($service['service_name']) . '</p>
                                                <p>Service Description: ' . htmlspecialchars($service['service_description']) . '</p>
                                                <p>Service Type: ' . htmlspecialchars($type['name']) . '</p>
                                                <p>Availability: ' . htmlspecialchars($service['availability']) . '</p>
                                                <p>The provider  on vacation on : <br><b style="color: red">' . $service['Time'] . '  //  ' . $service['time1'] . '</b></p>
                                                <p>Location: ' . htmlspecialchars($provider['location']) . '</p>
                                                <p>Price Offered: ' . htmlspecialchars($service['price']) . ' SAR per hour</p>

                                                <!-- Ratings Section -->
                                                <p>
                                                    <span>';
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $stars) ? '<i class="fas fa-star" style="color: #557187"></i>' : '<i class="far fa-star" style="color: #557187"></i>';
                                    }
                                    echo ' (' . $stars . ' Stars based on '.$total_reviews.' reviews)</span>
                                                </p>
                                                 <div class="d-flex">
                                                <a href="product.php?service_id='.$id.'" class="btn btn-primary" style="background: #DCD4B6; margin-right: 10px;">Booking</a>
                                                <a href="ServiceProfile.php?service_id='.$id.'" class="btn btn-secondary" style="background: #CCB17A; color: white;">More Details</a>

                                            </div>
                                            <br>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">There are no services</h5>
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
            <div class="container-fluid text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style="background: #DCD4B6;">
                <center>
                    <div class="container" style="color: black">
                        All rights reserved &copy; 2024 - My occasion
                    </div>
                </center>
            </div>
            <!-- Footer End -->
    </div>
    

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterCategories() {
            let serviceType = $("#serviceType").val().toLowerCase();
            let providerName = $("#providerNameInput").val().toLowerCase();
            let location = $("#locationInput").val().toLowerCase();
            let rating = $("#ratingInput").val();
            let minPrice = parseFloat($("#minPriceInput").val()) || 0;
            let maxPrice = parseFloat($("#maxPriceInput").val()) || Infinity;

            $("#storeList div").filter(function () {
                let typeMatch = $(this).data("service-type").toLowerCase().indexOf(serviceType) > -1;
                let nameMatch = $(this).data("provider-name").toLowerCase().indexOf(providerName) > -1;
                let locationMatch = $(this).data("location").toLowerCase().indexOf(location) > -1;
                let ratingMatch = (rating === "" || $(this).data("rating") >= rating);
                let priceMatch = $(this).data("price") >= minPrice && $(this).data("price") <= maxPrice;

                $(this).toggle(typeMatch && nameMatch && locationMatch && ratingMatch && priceMatch);
            });
        }
    </script>
</body>
</html>
