<?php
include 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$storetype = $_GET['storetype'];
// Fetch service types from the database
if ($storetype>0) {
    $stores = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE type_id  = '$storetype' ") or die('query failed');

    if (!$stores) {
        die('Query failed: ' . mysqli_error($conn));
    }
}
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
                            <a href="services.php" class="nav-item nav-link" style="color: #CCB17A">Services</a>
                            <a href="categories.php" class="nav-item nav-link" style="color: #557187">Store categories</a>
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
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="row g-0 gx-5 align-items-end">
                        <div class="col-lg-6">
                            <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                                <h1 class="mb-3" style="color: #DCD4B6">Stores</h1>
                                <hr style="width: 100%;height: 3px;color: #557187">
                                <p>Explore the finest shops, restaurants, and cafes across the Kingdom of Saudi Arabia. Whether you're planning a celebration or a professional event, find the perfect location that meets your needs.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                            <div class="input-group">
                                <input type="text" id="categorySearchInput" class="form-control" placeholder="Search by Store Name...">
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



                                <?php
                                if (isset($stores) && mysqli_num_rows($stores) > 0) {
                                    while ($store = mysqli_fetch_assoc($stores)) {
                                        $img = 'img/' . $store['logo'];
                                        $id = $store['provider_id'];
                                        $type_id=$store['type_id'];
                                        $types = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id  = '$type_id' ") or die('query failed');
                                        $type = mysqli_fetch_assoc($types);
                                        
                                        echo '<!-- Store 1 -->
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s" data-store-name="'.$store['store_name'].'">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href="services.php?store_id='.$id.'"><img class="img-fluid" style="height: 275px;width: 100%" src="'.$img.'" alt="Grand Ballroom"></a>
                                            <div class="rounded position-absolute start-0 top-0 m-4 py-1 px-3" style="background: #DCD4B6;color: black">'.$type['name'].'</div>
                                            <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">'.$type['name'].'</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <a class="d-block h5 mb-2" style="color: #557187" >'.$store['store_name'].'</a>
                                            <p><strong>Type:</strong>'.$type['name'].'</p>
                                            <p><strong>Description:</strong>'.$store['description'].'</p>
                                            <p><strong>Phone:</strong>'.$store['phone'].'</p>
                                            <p><strong>Self-employment Document:</strong> '.$store['commercial_registration_number'].'</p>
                                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i>'.$store['location'].'</p>
                                            <a href="services.php?store_id='.$id.'" class="btn btn-primary" style="background: #DCD4B6; margin-left: 120px; margin-bottom: 8px">View service</a>
                                        </div>
                                    </div>
                                </div>';
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

                                




                                <!-- Store 2 
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s" data-store-name="Elegant Wedding Hall in Jeddah">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href="services.php"><img class="img-fluid" style="height: 275px;width: 100%" src="img/11.JPG" alt="Elegant Wedding Hall"></a>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <a class="d-block h5 mb-2" style="color: #557187">Elegant Wedding Hall in Jeddah</a>
                                            <p><strong>Type:</strong> Event Hall</p>
                                            <p><strong>Description:</strong> A beautiful wedding hall perfect for elegant ceremonies in Jeddah.</p>
                                            <a href="services.php" class="btn btn-primary" style="background: #DCD4B6; margin-left: 150px; margin-bottom: 8px">View service</a>
                                        </div>
                                    </div>
                                </div>

                               
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s" data-store-name="Modern Conference Center in Dammam">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href="services.php"><img class="img-fluid" style="height: 275px;width: 100%" src="img/12.JPG" alt="Modern Conference Center"></a>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <a class="d-block h5 mb-2" style="color: #557187">Modern Conference Center in Dammam</a>
                                            <p><strong>Type:</strong> Event Hall</p>
                                            <p><strong>Description:</strong> A state-of-the-art conference center for corporate events and meetings in Dammam.</p>
                                            <a href="services.php" class="btn btn-primary" style="background: #DCD4B6; margin-left: 150px; margin-bottom: 8px">View service</a>
                                        </div>
                                    </div>
                                </div>-->

                                <!-- Add more stores here -->
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

        <!-- JavaScript Code -->
        <script>
            function filterCategories() {
                var input = document.getElementById('categorySearchInput');
                var filter = input.value.toLowerCase();
                var storeList = document.getElementById('storeList');
                var stores = storeList.getElementsByClassName('col-lg-4');

                for (var i = 0; i < stores.length; i++) {
                    var storeName = stores[i].getAttribute('data-store-name').toLowerCase();
                    if (storeName.indexOf(filter) > -1) {
                        stores[i].style.display = "";
                    } else {
                        stores[i].style.display = "none";
                    }
                }
            }
        </script>
    </body>

</html>
