<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['user_id'];

if(!isset($admin_id )){
   header('location:../index.php');
};

if (isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('location:../index.php');
}

$service_providers_total = 0;
$request_total = 0;
$rating_total = 0;
$users_total = 0;
$Number_of_Services = 0;

$service_providers = mysqli_query($conn, "SELECT * FROM `service_providers`") or die('Query failed: ' . mysqli_error($conn));
$requests = mysqli_query($conn, "SELECT * FROM `requests`") or die('Query failed: ' . mysqli_error($conn));
$ratings = mysqli_query($conn, "SELECT * FROM `ratings`") or die('Query failed: ' . mysqli_error($conn));
$users = mysqli_query($conn, "SELECT * FROM `users`") or die('Query failed: ' . mysqli_error($conn));

$service_providers_total = mysqli_num_rows($service_providers);
$request_total = mysqli_num_rows($requests);
$rating_total = mysqli_num_rows($ratings);
$users_total = mysqli_num_rows($users);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title>My Occasion</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

        <style>
            body {
                background-color: white;
                color: #CCB17A;
                font-family: 'Open Sans', sans-serif;
            }

            .navbar {
                background-color: white;
            }

            .navbar-brand {
                color: #CCB17A;
            }

            .menu-section {
                background-color: white;
                border-bottom: 3px solid #CCB17A;
            }

            .content-wrapper {
                padding: 20px;
                background-color: white;
            }

            .alert {
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
                text-align: center;
                color: white;
            }

            .alert-info,
            .alert-success,
            .alert-warning,
            .alert-danger {
                background-color: white;
                color: #CCB17A;
            }

            .panel-primary {
                border-color: #CCB17A;
            }

            .panel-primary .panel-heading {
                background-color: #CCB17A;
                color: white;
            }

            .chart-container {
                background: #fff;
                border-radius: 8px;
                padding: 10px;
            }
        </style>
    </head>

    <body>
        <div class="navbar navbar-inverse set-radius-zero">
            <div class="container">
                <a href="home.php" class="navbar-brand d-flex align-items-center text-center">
                    <h1 class="m-0" style="color: #CCB17A">
                        <img class="img-fluid" src="../img/team.png" alt="Icon" style="width: 30px; height: 30px;"> My Occasion
                    </h1>
                </a>
                <div class="right-div">
                    <a href="home.php?logout" class="btn btn-danger pull-right">LOG ME OUT</a>
                </div>
            </div>
        </div>

        <section class="menu-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="home.php" class="menu-top-active">DASHBOARD</a></li>
                                <li><a href="Joining.php">Joining Requests</a></li>
                                <li><a href="Types.php">Types of Services</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Help Chat<i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="service-providers.php">Service Providers</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="Users.php">Users</a></li>
                                    </ul>
                                </li>
                                <li><a href="reports.php">Reports</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">ADMIN DASHBOARD</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="alert alert-info text-center">
                            <i class="fa fa-history fa-5x"></i>
                            <h3><?php echo $service_providers_total; ?>+ New Providers</h3>
                            <p>Joined in the last month</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success text-center">
                            <i class="fa fa-tasks fa-5x"></i>
                            <h3><?php echo $request_total; ?>+ Total Bookings</h3>
                            <p>In the last 3 months</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning text-center">
                            <i class="fa fa-star fa-5x"></i>
                            <h3><?php echo $rating_total; ?>+ Ratings</h3>
                            <p>From our users</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-danger text-center">
                            <i class="fa fa-users fa-5x"></i>
                            <h3><?php echo $users_total; ?>+ New users</h3>
                            <p>Joined in the last month</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="../img/12.JPG" alt="" style="height: 500px;width: 100%" />
                                </div>
                                <div class="item">
                                    <img src="../img/13.JPG" alt="" style="height: 500px;width: 100%" />
                                </div>
                                <div class="item">
                                    <img src="../img/10.JPG" alt="" style="height: 500px;width: 100%" />
                                </div>
                            </div>
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example" data-slide-to="1"></li>
                                <li data-target="#carousel-example" data-slide-to="2"></li>
                            </ol>
                            <a class="left carousel-control" href="#carousel-example" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="panel panel-primary" style="height: 510px;width: 400px">
                            <div class="panel-heading">
                                Service Providers Overview
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Provider Name</th>
                                            <th>Location</th>
                                            <th>Number of Services</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($service_providers_total > 0) {
                                            mysqli_data_seek($service_providers, 0); // Reset result pointer
                                            while ($provider = mysqli_fetch_assoc($service_providers)) {
                                                $id = $provider['provider_id'];
                                                $services = mysqli_query($conn, "SELECT * FROM `services` WHERE provider_id = '$id'") or die('Query failed: ' . mysqli_error($conn));
                                                $Number_of_Services = mysqli_num_rows($services);


                                                echo "<tr> <td>{$provider['store_name']}</td> <td>{$provider['location']}</td> <td>$Number_of_Services</td> </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No service providers found.</td></tr>";
                                        }
                                        ?> 
                                    </tbody>
                                </table> 
                            </div> 
                        </div>
                    </div>
                </div>
                                <!-- Graphs Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Key Metrics Overview
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <!-- Service Provider Growth Chart -->
                                    <div class="col-md-6 mb-4">
                                        <div class="chart-container">
                                            <canvas id="providerGrowthChart" style="width: 100%; height: 520px;"></canvas>
                                            <p class="text-center mt-2">New Service Providers Over Time</p>
                                        </div>
                                    </div>
                                    <!-- Revenue Distribution by Service -->
                                    <div class="col-md-6 mb-4">
                                        <div class="chart-container">
                                            <canvas id="revenueDistributionChart" style="width: 100%; height: 480px;"></canvas>
                                            <p class="text-center mt-2">Revenue by Service Category</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Booking Trends Chart -->
                                    <div class="col-md-6 mb-4">
                                        <div class="chart-container">
                                            <canvas id="bookingTrendsChart" style="width: 100%; height: 520px;"></canvas>
                                            <p class="text-center mt-2">Booking Trends (Last 6 Months)</p>
                                        </div>
                                    </div>
                                    <!-- System Performance Gauge -->
                                    <div class="col-md-6 mb-4">
                                        <div class="chart-container">
                                            <canvas id="performanceGauge" style="width: 100%; height: 480px;"></canvas>
                                            <p class="text-center mt-2">System Performance Metrics</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <!-- Footer Section -->
        <section class="footer-section" style="border-top:  3px solid #CCB17A;color: black">
            <div class="container" style="text-align: center">
                <div class="row">
                    <div class="col-md-12">
                        All rights reserved &copy; 2024 - My Occasion
                    </div>
                </div>
            </div>
        </section>
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
        <!-- Include Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Service Provider Growth (Line Chart)
            var providerGrowthCtx = document.getElementById('providerGrowthChart').getContext('2d');
            var providerGrowthChart = new Chart(providerGrowthCtx, {
                type: 'line',
                data: {
                    labels: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [{
                            label: 'New Providers',
                            data: [<?php if ($service_providers_total>0){echo $service_providers_total;}else{    echo 0;} ?>, 0, 0, 0, 0, 0],
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: '#CCB17A',
                            borderWidth: 1
                        }]
                }
            });

            // Revenue Distribution (Pie Chart)
            var revenueDistributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
            var revenueDistributionChart = new Chart(revenueDistributionCtx, {
                type: 'pie',
                data: {
                    labels: ['requests', 'ratings', 'users', 'Providers'],
                    datasets: [{
                            data: [<?php if ($request_total>0){echo $request_total;}else{    echo 0;} ?>, <?php if ($rating_total>0){echo $rating_total;}else{    echo 0;} ?>, <?php if ($users_total>0){echo $users_total;}else{    echo 0;} ?>,<?php if ($service_providers_total>0){echo $service_providers_total;}else{    echo 0;} ?>],
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', 'red']
                        }]
                }
            });

            // Booking Trends (Bar Chart)
            var bookingTrendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
            var bookingTrendsChart = new Chart(bookingTrendsCtx, {
                type: 'bar',
                data: {
                    labels: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [{
                            label: 'Bookings',
                            data: [<?php if ($request_total>0){echo $request_total;}else{    echo 0;} ?>, 0, 0, 0, 0, 0],
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                            borderColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                            borderWidth: 1
                        }]
                }
            });

            // Performance Gauge (Doughnut Chart)
            var performanceCtx = document.getElementById('performanceGauge').getContext('2d');
            var performanceChart = new Chart(performanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Uptime', 'Downtime'],
                    datasets: [{
                            data: [<?php if ($request_total>0){echo 100-$request_total;}else{    echo 1;} ?>, <?php if ($request_total>0){echo $request_total;}else{    echo 0;} ?>],
                            backgroundColor: ['#4CAF50', '#FF6384']
                        }]
                }
            });
        </script>
    </body>
</html>