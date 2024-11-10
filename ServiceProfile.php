<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$service_id = $_GET['service_id'] ?? null;

// Fetch service and ratings data if service_id is available
if ($service_id) {
    $service_query = "SELECT * FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($service_query);
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $services = $stmt->get_result();

    $rating_query = "SELECT * FROM ratings WHERE service_id = ?";
    $stmt = $conn->prepare($rating_query);
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $ratings_result = $stmt->get_result();

    $total_rating = 0;
    $total_reviews = 0;
    while ($rating = $ratings_result->fetch_assoc()) {
        $total_rating += $rating['rating_value'] ?? 1;
        $total_reviews++;
    }
    $stars = ($total_reviews > 0) ? round($total_rating / $total_reviews, 1) : 0;
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

            <!-- Service Profile Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="row g-0 gx-5 align-items-end">
                        <div class="col-lg-6">
                            <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                                <h1 class="mb-3" style="color: #DCD4B6">Service Profile</h1>
                                <hr style="width: 100%;height: 3px;color: #557187;">
                            </div>
                        </div>
                    </div>
                    <div class="row g-5">
                        <?php
                        if ($services && $service = $services->fetch_assoc()) {
                            $img1 = 'img/' . htmlspecialchars($service['image1']);
                            $img2 = 'img/' . htmlspecialchars($service['image2']);
                            $img3 = 'img/' . htmlspecialchars($service['image3']);
                            $id = htmlspecialchars($service['service_id']);
                            $provider_id = htmlspecialchars($service['provider_id']);

                            // Fetch provider details
                            $provider_query = "SELECT * FROM service_providers WHERE provider_id = ?";
                            $stmt = $conn->prepare($provider_query);
                            $stmt->bind_param('i', $provider_id);
                            $stmt->execute();
                            $provider_result = $stmt->get_result();
                            $provider = $provider_result->fetch_assoc();

                            echo '
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                            <!-- Carousel for Service Images -->
                            <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="' . $img1 . '" class="d-block w-100" alt="Service Image 1">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="' . $img2 . '" class="d-block w-100" alt="Service Image 2">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="' . $img3 . '" class="d-block w-100" alt="Service Image 3">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                            <h1 class="mb-3" style="color: #DCD4B6">' . htmlspecialchars($service['service_name']) . '</h1>
                            <p>Service Description: ' . htmlspecialchars($service['service_description']) . '</p>
                            <p>Availability: ' . htmlspecialchars($service['availability']) . '</p>
                            <p>Time Available: ' . htmlspecialchars($service['Time']) . '</p>
                            <p>Location: ' . htmlspecialchars($provider['location']) . '</p>
                            <p>Price: ' . htmlspecialchars($service['price']) . ' SAR per hour</p>
                            <p><strong><i class="fa fa-star"></i> Rating:</strong> ' . $stars . ' stars based on ' . $total_reviews . ' reviews</p>
                            <a href="product.php?service_id=' . $id . '" class="btn btn-primary" style="background: #DCD4B6;">Book Now</a>
                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Service Profile End -->

            <!-- Reviews Section Start -->
            <div class="container-xxl py-5 bg-light">
                <div class="container">
                    <h2 class="mb-4" style="color: #557187;">Customer Reviews</h2>
                    <?php
                    $rating_query = "SELECT * FROM ratings WHERE service_id = ?";
                    $stmt = $conn->prepare($rating_query);
                    $stmt->bind_param('i', $service_id);
                    $stmt->execute();
                    $ratings_result = $stmt->get_result();

                    if ($ratings_result->num_rows > 0) {
                        while ($rating = $ratings_result->fetch_assoc()) {
                            $user_id = $rating['user_id'];
                            $user_query = "SELECT * FROM users WHERE user_id = ?";
                            $stmt = $conn->prepare($user_query);
                            $stmt->bind_param('i', $user_id);
                            $stmt->execute();
                            $user_result = $stmt->get_result();
                            $user = $user_result->fetch_assoc();

                            echo '
                        <div class="review-item">
                <div class="d-flex align-items-start">
                    <img class="rounded-circle me-3" src="img/user (1).png" alt="Reviewer 3" style="width: 60px; height: 60px;">
                    <div>
                        <h5>'. htmlspecialchars($user['username']) . ' <span class="text-muted" style="font-size: 0.8em;">(' . htmlspecialchars($rating['created_at']) . ')</span></h5>
                        <p>';
                         for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $stars) ? '<i class="fa fa-star" style="color: #FFD700;"></i>' : '<i class="far fa-star" style="color: #557187"></i>';
                                    }
                            
                            echo '(' . htmlspecialchars($rating['rating_value']) . ')
                        </p>
                        <p>"' . htmlspecialchars($rating['review']) . '"</p>
                    </div>
                </div>
            </div>';
                        }
                    } else {
                        echo '<p>No reviews yet.</p>';
                    }
                    ?>
                </div>
            </div>
            <!-- Reviews Section End -->

            


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
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="background: #DCD4B6"><i class="bi bi-arrow-up"></i></a>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- WOW JS -->
        <script src="lib/wow/wow.min.js"></script>

        <!-- Owl Carousel JS -->
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>
