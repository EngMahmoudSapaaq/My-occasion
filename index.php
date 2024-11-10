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
$services = mysqli_query($conn, "SELECT * FROM `services`") or die('Query failed: ' . mysqli_error($conn));

//---------------------------------------------
$restaurants = 0;
$Shops = 0;
$Events = 0;
$Bars = 0;
$Cafés = 0;
$Halls = 0;
$Photographys = 0;
$Theaters = 0;

$service_restaurants = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'restaurant'") or die('query failed');
$service_Shops = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'shop'") or die('query failed');
$service_Events = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'Event'") or die('query failed');
$service_Bars = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'bar'") or die('query failed');
$service_Cafés = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'café'") or die('query failed');
$service_Halls = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'hall'") or die('query failed');
$service_Photographys = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'Photography'") or die('query failed');
$service_Theaters = mysqli_query($conn, "SELECT * FROM `service_type` WHERE name = 'Theater'") or die('query failed');


$restaurants = mysqli_num_rows($service_restaurants);
$Shops = mysqli_num_rows($service_Shops);
$Events = mysqli_num_rows($service_Events);
$Bars = mysqli_num_rows($service_Bars);
$Cafés = mysqli_num_rows($service_Cafés);
$Halls = mysqli_num_rows($service_Halls);
$Photographys = mysqli_num_rows($service_Photographys);
$Theaters = mysqli_num_rows($service_Theaters);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>My occasion</title>
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
                        <h1 class="m-0" style="color: #DCD4B6">My occasion</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="index.php" class="nav-item nav-link " style="color: #557187">Home</a>
                            <a href="services.php" class="nav-item nav-link " style="color: #CCB17A">Services</a>
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
            <!-- Header Start -->
            <div class="container-fluid header bg-white p-0" style="margin-top: 20px">
                <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                    <div class="col-md-6 p-5 mt-lg-5">
                        <h1 class="display-5 animated fadeIn mb-4" >Find the Perfect <span class="text-primary">Venue</span> for Your <span class="text-primary">Events</span> and <span class="text-primary">Parties</span></h1>
                        <p class="animated fadeIn mb-4 pb-2">Discover top stores, shops, and restaurants available for hosting your events and parties. From intimate gatherings to grand celebrations, find the ideal location to make your occasion unforgettable.</p>
                        <a href="#venues" class="btn btn-primary py-3 px-5 me-3 animated fadeIn" style="background: #DCD4B6">Browse Venues</a>
                    </div>
                    <div class="col-md-6 animated fadeIn">
                        <div class="owl-carousel header-carousel">
                            <div class="owl-carousel-item">
                                <img class="img-fluid" src="img/12.JPG" alt="">
                            </div>
                            <div class="owl-carousel-item">
                                <img class="img-fluid" src="img/13.JPG" alt="">
                            </div>
                            <div class="owl-carousel-item">
                                <img class="img-fluid" src="img/10.JPG" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header End -->
            <!-- Search Start -->
            <div class="container-fluid mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;background: #DCD4B6">
                <div class="container">
                    <form method="GET" action="">
                        <div class="row g-2">
                            <div class="col-md-10">
                                <div class="row g-2">
                                    <div class="col-md-4" style="width: 250px">
                                        <input type="text" name="serviceName" id="serviceName" class="form-control border-0 py-3" placeholder="Service Name">
                                    </div>
                                    <div class="col-md-4" style="width: 250px">
                                        <input type="date" name="serviceDate" id="serviceDate" class="form-control border-0 py-3" placeholder="Service Date">
                                    </div>
                                    <div class="col-md-4" style="width: 250px">
                                        <select name="serviceProvider" id="serviceProvider" class="form-select border-0 py-3">
                                            <option selected value="">Service Provider</option>
                                            <?php
                                            $service_providers = mysqli_query($conn, "SELECT * FROM `service_providers`") or die('Query failed');
                                            if (mysqli_num_rows($service_providers) > 0) {
                                                while ($row = mysqli_fetch_assoc($service_providers)) {
                                                    echo '<option value="' . htmlspecialchars($row['store_name']) . '">' . htmlspecialchars($row['store_name']) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="" disabled>No service providers available</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4" style="width: 250px">
                                        <select name="serviceLocation" id="serviceLocation" class="form-select border-0 py-3">
                                            <option selected value="">Location</option>
                                            <?php
                                            $printed_locations = [];
                                            $service_providers = mysqli_query($conn, "SELECT * FROM `service_providers`") or die('Query failed');
                                            if (mysqli_num_rows($service_providers) > 0) {
                                                while ($row = mysqli_fetch_assoc($service_providers)) {
                                                    $location = htmlspecialchars($row['location']);
                                                    if (!in_array($location, $printed_locations)) {
                                                        echo '<option value="' . $location . '">' . $location . '</option>';
                                                        $printed_locations[] = $location;
                                                    }
                                                }
                                            } else {
                                                echo '<option value="" disabled>No locations available</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-dark border-0 w-100 py-3" style="background: #CCB17A">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Search End -->
            <?php
// Capture the search inputs from the form
            $serviceName = isset($_GET['serviceName']) ? mysqli_real_escape_string($conn, $_GET['serviceName']) : '';
            $serviceDate = isset($_GET['serviceDate']) ? mysqli_real_escape_string($conn, $_GET['serviceDate']) : '';
            $serviceProvider = isset($_GET['serviceProvider']) ? mysqli_real_escape_string($conn, $_GET['serviceProvider']) : '';
            $serviceLocation = isset($_GET['serviceLocation']) ? mysqli_real_escape_string($conn, $_GET['serviceLocation']) : '';

// Build the query with dynamic conditions
            $query = "SELECT * FROM `services` s 
          JOIN `service_providers` sp ON s.provider_id = sp.provider_id 
          WHERE 1";  // Use WHERE 1 to append conditions dynamically
// Append conditions if search fields are provided
            if (!empty($serviceName)) {
                $query .= " AND s.service_name LIKE '%$serviceName%'";
            }

            if (!empty($serviceDate)) {
                $query .= " AND DATE(s.Time) != '$serviceDate' AND DATE(s.time1) != '$serviceDate'";
            }

            if (!empty($serviceProvider)) {
                $query .= " AND sp.store_name = '$serviceProvider'";
            }

            if (!empty($serviceLocation)) {
                $query .= " AND sp.location = '$serviceLocation'";
            }

// Execute the query
            $services = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));
            ?>

            <!-- Display Search Results -->
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="scrollable-services d-flex flex-wrap" id="serviceResults" style="max-height: 530px; overflow-y: auto;">
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

        echo '<div class="col-lg-4 col-md-6 col-sm-12 p-2" data-service="' . $service['service_name'] . '" data-date="' . $provider['store_name'] . '" data-provider="' . $service['Time'] . '" data-location="' . $provider['location'] . '">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a><img class="img-fluid" style="height: 275px;width: 100%" src="' . $img . '" alt="Service Image"></a>
                                    <div class="rounded position-absolute start-0 top-0 m-4 py-1 px-3" style="background: #DCD4B6;color: black">Service</div>
                                    <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">' . $type['name'] . '</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <p class="d-block h5 mb-2" style="color: #557187;"><b>Service Name:</b> ' . $service['service_name'] . '</p>
                                    <p>The provider  on vacation on : <br><b style="color: red">' . $service['Time'] . '  //  ' . $service['time1'] . '</b></p>
                                    <p>Service provider: ' . $provider['store_name'] . '</p>
                                    <p>Location: ' . $provider['location'] . '</p>
                                    <div class="d-flex">
                                        <a href="product.php?service_id=' . $id . '" class="btn btn-primary" style="background: #DCD4B6; margin-right: 10px;">Booking</a>
                                        <a href="ServiceProfile.php?service_id=' . $id . '" class="btn btn-secondary" style="background: #CCB17A; color: white;">More Details</a>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>';
    }
} else {
    // Show message if no results are found
    echo '<div class="col-lg-12 text-center">
        <div class="p-4 pb-0">
                        <h5 class="text-danger">No search results found</h5>
                        </div>
                      </div>';
}
?>
                    </div>
                </div>
            </div>







            <!-- Category Start -->
            <div class="container-xxl py-5" id="venues" >
                <div class="container">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="mb-3" style="color: #DCD4B6">Explore Our <span class="text-primary">Event Venues</span></h1>
                        <p>Discover a diverse range of venues perfect for hosting your next party or event. From trendy restaurants to stylish shops and versatile event spaces, find the ideal location to make your celebration unforgettable.</p>
                    </div>
                    <div class="row g-4">
                        <?php
if (mysqli_num_rows($service_restaurants) > 0) {
    while ($service_restaurant = mysqli_fetch_assoc($service_restaurants)) {
        $id=$service_restaurant['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Restaurant</h6>
                                    <span>'. $restaurants.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Restaurant</h6>
                                    <span>'.$restaurants .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }
                     if (mysqli_num_rows($service_Shops) > 0) {
    while ($service_restaurant = mysqli_fetch_assoc($service_Shops)) {
        $id=$service_Shop['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Shop</h6>
                                    <span>'. $Shops.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Shop</h6>
                                    <span>'.$Shops .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }   
     if (mysqli_num_rows($service_Events) > 0) {
    while ($service_Event = mysqli_fetch_assoc($service_Events)) {
        $id=$service_Event['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Event Space</h6>
                                    <span>'. $Events.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Event Space</h6>
                                    <span>'.$Events .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }   
    
    if (mysqli_num_rows($service_Bars) > 0) {
    while ($service_Bar = mysqli_fetch_assoc($service_Bars)) {
        $id=$service_Bar['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Bar</h6>
                                    <span>'. $Bars.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Bar</h6>
                                    <span>'.$Bars .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }   
    if (mysqli_num_rows($service_Cafés) > 0) {
    while ($service_Café = mysqli_fetch_assoc($service_Cafés)) {
        $id=$service_Café['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Café</h6>
                                    <span>'. $Cafés.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Café</h6>
                                    <span>'.$Cafés .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }  
     if (mysqli_num_rows($service_Halls) > 0) {
    while ($service_Hall = mysqli_fetch_assoc($service_Halls)) {
        $id=$service_Hall['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Hall</h6>
                                    <span>'. $Halls.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Hall</h6>
                                    <span>'.$Halls .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }  
    if (mysqli_num_rows($service_Photographys) > 0) {
    while ($service_Photography = mysqli_fetch_assoc($service_Photographys)) {
        $id=$service_Photography['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Photography</h6>
                                    <span>'. $Photographys.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Photography</h6>
                                    <span>'.$Photographys .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }  
    if (mysqli_num_rows($service_Theaters) > 0) {
    while ($service_Theater = mysqli_fetch_assoc($service_Theaters)) {
        $id=$service_Photography['type_id'];
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=' . $id . '">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Theater</h6>
                                    <span>'. $Theaters.' Stores</span>
                                </div>
                            </a>
                        </div>';
        
    }
    } else {
        echo '<div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s" >
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="stores.php?storetype=0">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="img/apartment.png" alt="Icon">
                                    </div>
                                    <h6>Theater</h6>
                                    <span>'.$Theaters .' Stores</span>
                                </div>
                            </a>
                        </div>';
    }  
                        ?>
                        
                        
                        
                    </div>
                </div>
            </div>
            <!-- Category End -->
            <!-- About Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="about-img position-relative overflow-hidden p-5 pe-0">
                                <img class="img-fluid w-100" src="img/4.JPG">
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                            <h1 class="mb-4" style="color: #DCD4B6">Discover Premier Venues for Your Special Events</h1>
                            <p class="mb-4" >
                                Uncover exceptional locations perfect for your events and gatherings. From elegant restaurants to versatile shops and charming stores, explore our curated selection of venues designed to make your occasions truly memorable.
                            </p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-3"></i> Diverse venue options for any type of event</li>
                                <li><i class="fa fa-check text-primary me-3"></i> Prime locations including restaurants, shops, and stores</li>
                                <li><i class="fa fa-check text-primary me-3"></i> Seamless booking process with flexible arrangements</li>
                            </ul>
                            <a class="btn btn-primary py-3 px-5 mt-3" style="background: #DCD4B6" href="#">Find Your Venue</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About End -->
            <!-- Property List Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="row g-0 gx-5 align-items-end">
                        <div class="col-lg-6">
                            <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                                <h1 class="mb-3" style="color: #DCD4B6">categories</h1>
                                <hr style="width: 100%;height: 3px;color: #557187">
                                <p>Explore the finest shops, restaurants, and cafes across the Kingdom of Saudi Arabia. Whether you're planning a celebration or a professional event, find the perfect location that meets your needs.</p>
                            </div>
                        </div>

                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
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
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href="stores.php?storetype=' . $id . '">
                                            <img class="img-fluid" style="height: 275px;width: 100%" src="' . $img . '" alt="">
                                        </a>
                                        <div class="rounded position-absolute start-0 top-0 m-4 py-1 px-3" style="background: #DCD4B6;color: black">' . $row['name'] . '</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">' . $row['name'] . '</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">' . $total . ' Stores</h5>
                                        <a href="stores.php?storetype=' . $id . '" class="btn btn-primary mt-3" style="background: #DCD4B6;">View Stores</a>
                                    </div>
                                </div>
                            </div>';
    }
} else {
    echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">There are no categories</h5>
                                    </div>
                                </div>
                            </div>';
}
?>
                            </div>
                        </div>
                    </div>
                    <!-- Category End -->
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

<!-- Template Javascript -->
<script src="js/main.js"></script>
<script>
    document.getElementById('searchButton').addEventListener('click', function () {
        // Get the search input values
        const serviceName = document.getElementById('serviceName').value.toLowerCase();
        const serviceDate = document.getElementById('serviceDate').value;
        const serviceProvider = document.getElementById('serviceProvider').value;
        const serviceLocation = document.getElementById('serviceLocation').value;

        // Get all service items
        const services = document.querySelectorAll('#serviceResults > div');

        // Filter services based on the input values
        services.forEach(function (service) {
            const serviceNameMatch = service.dataset.service.toLowerCase().includes(serviceName);
            const serviceDateMatch = serviceDate === '' || service.dataset.date === serviceDate;
            const serviceProviderMatch = serviceProvider === '' || service.dataset.provider === serviceProvider;
            const serviceLocationMatch = serviceLocation === '' || service.dataset.location === serviceLocation;

            // Show or hide the service based on matching criteria
            if (serviceNameMatch && serviceDateMatch && serviceProviderMatch && serviceLocationMatch) {
                service.style.display = 'block';
            } else {
                service.style.display = 'none';
            }
        });
    });
</script>
</body>

</html>