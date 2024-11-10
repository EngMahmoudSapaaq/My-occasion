<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
    header('location:../index.php');
};
// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('location:../login.php');
    exit();
}

// Initialize variables
$total_profit = 0;
$total_profit_fin = 0;
$services_query = "SELECT * FROM `services`";
$services_result = mysqli_query($conn, $services_query) or die('Query failed: ' . mysqli_error($conn));

// Handling profit percentage submission
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>My Occasion - Profit Display</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
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
            .menu-section .nav > li > a {
                color: #CCB17A;
            }
            .content-wrapper {
                padding: 20px;
                background-color: white;
            }
            .header-line {
                color: #CCB17A;
            }
        </style>
    </head>
    <body>
        <!-- Navbar Section -->
        <div class="navbar navbar-inverse set-radius-zero">
            <div class="container">
                <a href="home.php" class="navbar-brand">
                    <h1 style="color: #DCD4B6">
                        <img src="../img/team.png" alt="Icon" style="width: 30px; height: 30px;"> My Occasion
                    </h1>
                </a>
                <div class="right-div">
                    <a href="home.php?logout" class="btn btn-danger pull-right">LOG ME OUT</a>
                </div>
            </div>
        </div>

        <!-- Menu Section -->
        <section class="menu-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="home.php">DASHBOARD</a></li>
                                <li><a href="Joining.php">Joining Requests</a></li>
                                <li><a href="Types.php">Types of Services</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help Chat <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="service-providers.php">Service Providers</a></li>
                                        <li><a href="Users.php">Users</a></li>
                                    </ul>
                                </li>
                                <li><a href="reports.php" class="menu-top-active">Reports</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profit Display Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Profit Display</h4>
                </div>
            </div>

            <!-- Profit Entry and Update Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search</div>
                        <div class="panel-body">
                            <form id="searchForm" style="margin-left: 400px">
                                <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="input-group">
                                        <div class="col-md-4" style="width: 250px">
                                            <input type="text" id="searchInput" class="form-control border-0 py-3" placeholder="Search by Service Name">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="searchButton" class="btn btn-dark border-0 w-100 py-3" style="background: #CCB17A;color: white">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Service Profits</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Service Type</th>
                                            <th>Provider Name</th>
                                            <th>Service Name</th>
                                            <th>Service Price (SAR)</th>
                                            <th>Profit Percentage (%)</th>
                                            <th>Profit (SAR)</th>
                                            <th>Booking for service</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="profitTableBody">
                                        <?php
                                        if (mysqli_num_rows($services_result) > 0) {
                                            while ($service = mysqli_fetch_assoc($services_result)) {
                                                $img = '../img/' . $service['image1'];
                                                $provider_id = $service['provider_id'];
                                                $service_id = $service['service_id'];

                                                $provider_result = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE provider_id = '$provider_id'") or die('Query failed');
                                                $provider = mysqli_fetch_assoc($provider_result);

                                                $type_id = $provider['type_id'];
                                                $type_result = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed');
                                                $type = mysqli_fetch_assoc($type_result);

                                                $requests = mysqli_query($conn, "SELECT * FROM `requests` WHERE service_id = '$service_id'") or die('Query failed');
                                                if (mysqli_num_rows($requests) > 0) {
                                                    $requests_num = mysqli_num_rows($requests);
                                                } else {
                                                    $requests_num = 0;
                                                }

                                                $total_profit = ($service['price'] * $service['profit'] / 100);
                                                $total_profit_fin += $total_profit;
                                                echo '<tr>
                                            <td><img src="' . $img . '" alt="Logo" style="width: 100px;"></td>
                                            <td>' . $type['name'] . '</td>
                                            <td>' . $provider['store_name'] . '</td>
                                            <td class="service-name">' . $service['service_name'] . '</td>
                                            <td>' . $service['price'] . '</td>';
                                                if ($service['profit'] > 0) {
                                                    echo '<td>' . $service['profit'] . '</td>
                                                <td>' . $total_profit . '</td>';
                                                } else {
                                                    echo '<td>no profit</td>
                                                <td>no total profit</td>';
                                                }

                                                echo '<td>' . $requests_num . '</td>
                                                <td>
                                                <a class="btn btn-success " href="profit.php?service_id=' . $service['service_id'] . '">Add Profit</a> 
                                             </td>
                                            </tr>';
                                            }
                                        } else {
                                            echo "<tr>
                                            <td colspan='9'>No data available</td>
                                        </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Profit Section -->
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <div class="panel panel-info">
                        <div class="panel-heading">Total Profit</div>
                        <div class="panel-body">
                            <h3 id="totalProfitDisplay"><?php echo $total_profit_fin; ?> SAR</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <section class="footer-section" style="border-top: 3px solid #CCB17A;">
            <div class="container" style="text-align: center">
                <div class="row">
                    <div class="col-md-12">
                        All rights reserved &copy; 2024 - My Occasion
                    </div>
                </div>
            </div>
        </section>

        <!-- Scripts -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="assets/js/bootstrap.js"></script>

        <!-- Search Functionality Script -->
        <script>
            document.getElementById('searchButton').addEventListener('click', function() {
                var searchValue = document.getElementById('searchInput').value.toLowerCase();
                var rows = document.querySelectorAll('#profitTableBody tr');

                rows.forEach(function(row) {
                    var serviceName = row.querySelector('.service-name').textContent.toLowerCase();
                    if (serviceName.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
    </body>
</html>
