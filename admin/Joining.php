<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
    header('location:../index.php');
};
if (isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('location:../index.php');
}

$service_providers_Pending = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE status = 'Pending'") or die('Query failed: ' . mysqli_error($conn));
$service_providers = mysqli_query($conn, "
    SELECT * 
    FROM `service_providers` 
    WHERE (status = 'blocked' OR status = 'Accepted')
") or die('Query failed: ' . mysqli_error($conn));

// Handle Accept/Reject Requests

if (isset($_GET['action']) && isset($_GET['provider_id'])) {
    $provider_id = $_GET['provider_id'];
    $status = $_GET['action'];
    if ($status == 'Accepted') {
        $service_providers = "UPDATE `service_providers` SET `status` = 'Accepted' WHERE `provider_id` = '$provider_id'";
        mysqli_query($conn, $service_providers);
        header('location:Joining.php');
    } elseif ($status == 'Rejected') {
        $update_query = "UPDATE `service_providers` SET `status` = 'blocked' WHERE `provider_id` = '$provider_id'";
        mysqli_query($conn, $update_query);
        header('location:Joining.php');
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>My Occasion - Service Provider Applications</title>
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
            .navbar-brand h1 {
                margin: 0;
            }
            .menu-section {
                background-color: white;
                border-bottom: 3px solid #CCB17A;
            }
            .menu-section .nav > li > a {
                color: #CCB17A;
            }
            .menu-section .nav > li > a.menu-top-active {
                font-weight: bold;
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
                                <li><a href="Joining.php" class="menu-top-active">Joining Requests</a></li>
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

        <!-- Service Providers Joining Requests Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Service Provider Applications</h4>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="row" style="width: 110%">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            New Applications
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Registration/Document Number</th>
                                            <th>Application Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($service_providers_Pending) && mysqli_num_rows($service_providers_Pending) > 0) {
                                            while ($provider = mysqli_fetch_assoc($service_providers_Pending)) {
                                                $img = '../img/' . $provider['logo'];
                                                $type_id = $provider['type_id'];
                                                $service_types = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed');
                                                $service_type = mysqli_fetch_assoc($service_types);
                                                echo '<tr>
                                                <td><img src="' . $img . '" alt="Logo" style="width: 150px;border-radius: 30px"></td>
                                                <td>' . $provider['store_name'] . '</td>
                                                <td>' . $provider['description'] . '</td>
                                                <td>' . $service_type['name'] . '</td>
                                                <td>' . $provider['commercial_registration_number'] . '</td>
                                                <td><span class="label label-warning" id="status-' . $provider['provider_id'] . '">' . $provider['status'] . '</span></td>
                                                
                                                <td>
                                             <a class="btn btn-success " href="Joining.php?provider_id=' . $provider['provider_id'] . '&&action=Accepted">Accept</a> 
                                             <a class="btn btn-danger"  btn-sm" href="Joining.php?provider_id=' . $provider['provider_id'] . '&&action=Rejected">Reject</a> 
 
                                              </td>
                                            </tr>';
                                            }
                                        } else {
                                            echo '<tr>
                                            <td colspan="7" class="text-center">There are no pending applications</td>
                                        </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Old Applications
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Registration/Document Number</th>
                                            <th>Application Status</th>
                                            <th>Actions</th>
                                            <th>Reason for rejection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($service_providers) && mysqli_num_rows($service_providers) > 0) {
                                            while ($provider = mysqli_fetch_assoc($service_providers)) {
                                                $img = '../img/' . $provider['logo'];
                                                $type_id = $provider['type_id'];
                                                $service_types = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed');
                                                $service_type = mysqli_fetch_assoc($service_types);
                                                echo '<tr>
                                                <td><img src="' . $img . '" alt="Logo" style="width: 150px;border-radius: 30px"></td>
                                                <td>' . $provider['store_name'] . '</td>
                                                <td>' . $provider['description'] . '</td>
                                                <td>' . $service_type['name'] . '</td>
                                                <td>' . $provider['commercial_registration_number'] . '</td>
                                                <td><span class="label label-warning" id="status-' . $provider['provider_id'] . '">' . $provider['status'] . '</span></td>
                                                <td>
                                             <a class="btn btn-success " href="Joining.php?provider_id=' . $provider['provider_id'] . '&&action=Accepted">Accept</a> 
                                             
                                             <a class="btn btn-danger"  btn-sm" href="Joining.php?provider_id=' . $provider['provider_id'] . '&&action=Rejected">Reject</a> 
<a class="btn btn-success " href="reason.php?provider_id=' . $provider['provider_id'] . '">reason</a>                                              
</td>';
                                                if ($provider['reason_rejection']>0){
                                                    echo ' <td>' . $provider['reason_rejection'] . '</td>';
                                                } else {
                                                    echo ' <td>no reason to rejection</td>';
                                                }
                                             
                                              echo '   </tr>';
                                            }
                                        } else {
                                            echo '<tr>
                                            <td colspan="7" class="text-center">There are no pending applications</td>
                                        </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <section class="footer-section" style="border-top:  3px solid #CCB17A;">
            <div class="container" style="text-align: center">
                <div class="row">
                    <div class="col-md-12">
                        All rights reserved &copy; 2024 - My Occasion
                    </div>
                </div>
            </div>
        </section>

        <!-- SCRIPTS -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/custom.js"></script>

        <script>
            function updateStatus(provider_id, action) {
                $.ajax({
                    url: 'Joining.php',
                    type: 'POST',
                    data: {
                        provider_id: provider_id,
                        action: action
                    },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            document.getElementById('status-' + provider_id).textContent = res.status;
                        } else {
                            alert('Error: ' + res.error);
                        }
                    },
                    error: function () {
                        alert('Failed to update status.');
                    }
                });
            }
        </script>
    </body>
</html>
