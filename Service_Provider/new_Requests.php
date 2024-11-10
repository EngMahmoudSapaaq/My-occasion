<?php
include '../config.php';
session_start();
$provider_id = $_SESSION['user_id'];
if (!isset($provider_id)) {
    header('location:../index.php');
};
if (isset($_GET['logout'])) {
    unset($provider_id);
    session_destroy();
    header('location:../index.php');
}
$requests = mysqli_query($conn, "SELECT * FROM `requests` WHERE provider_id = '$provider_id' AND request_status = 'Pending'") or die('query failed');


if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $status = $_GET['action'];
    if($status=='Accepted'){
         $update_query = "UPDATE `requests` SET `request_status` = 'Accepted' WHERE `order_id` = '$order_id'";
    mysqli_query($conn, $update_query);
    header('location:new_Requests.php');
    }elseif ($status=='Rejected') {
        $update_query = "UPDATE `requests` SET `request_status` = 'Cancelled' WHERE `order_id` = '$order_id'";
    mysqli_query($conn, $update_query);
    header('location:new_Requests.php');
    }
   
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="Order Management for Providers" />
        <meta name="author" content="" />
        <title>Order Management</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="../admin/assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <style>
            body {
                background-color: white;
                color: #CCB17A;
                font-family: 'Open Sans', sans-serif;
            }
            .navbar, .menu-section {
                background-color: white;
            }
            .navbar-brand, .menu-section .nav > li > a {
                color: #CCB17A;
            }
            .menu-section .nav > li > a.menu-top-active {
                font-weight: bold;
            }
            .content-wrapper {
                padding: 20px;
            }
            .header-line {
                color: #CCB17A;
                font-weight: bold;
            }
            .btn-primary, .btn-accept, .btn-reject {
                color: white;
                border: none;
            }
            .btn-accept {
                background-color: #28a745;
            }
            .btn-reject {
                background-color: #dc3545;
            }
            .table-responsive img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: 10px;
            }
            .table th, .table td {
                text-align: center;
                vertical-align: middle;
            }
            .menu-section {
                background-color: white;
                border-bottom: 3px solid #CCB17A;
            }
        </style>
    </head>
    <body>
        <!-- Navbar Section -->
        <div class="navbar navbar-inverse set-radius-zero">
            <div class="container">
                <a href="service_Management.php" class="navbar-brand">
                    <h1 style="color: #DCD4B6"><img src="../img/team.png" alt="Icon" style="width: 30px; height: 30px;"> My Occasion</h1>
                </a>
            </div>
        </div>

        <!-- Menu Section -->
        <section class="menu-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="service_Management.php" >Service Management</a></li>
                                <li><a href="admin_chat.php" class="" >Chat With admin </a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle menu-top-active" id="ddlmenuItem" data-toggle="dropdown">Requests<i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="new_Requests.php">New Requests</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="requests_accepted.php">Requests accepted</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="profile.php" title="Profile" class="btn  pull-right" style="margin-top: 10px;height: 20px;margin-left: 20px;background: #DCD4B6;color: #ffffff"><i class="fa fa-user"></i> Profile</a>

                                </li>
                                <li>
                                    <a href="../login.php" title="LOG ME OUT" class="btn btn-danger" style="margin-left: 20px;color: #FFFFFF;height: 20px;margin-top: 10px;background: #ED6C6C">LOG ME OUT</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Management Section -->
        <div class="container" style="margin-bottom: 250px">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">New Requests</h4>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Orders
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Service Image</th>
                                            <th>Service Name</th>
                                            <th>User Name</th>
                                            <th>Order Date</th>
                                            <th>Delivery Date</th>
                                            <th>Additional Description </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example Order Row -->

                                        <?php
                                        if (isset($requests) && mysqli_num_rows($requests) > 0) {
                                            while ($request = mysqli_fetch_assoc($requests)) {

                                                $service_id = $request['service_id'];
                                                $services = mysqli_query($conn, "SELECT * FROM `services` WHERE service_id = '$service_id'") or die('Query failed');
                                                $service = mysqli_fetch_assoc($services);

                                                $user_id = $request['user_id'];
                                                $users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
                                                $user = mysqli_fetch_assoc($users);
                                                $img = '../img/' . $service['image1'];


                                                echo '<tr>
                                            <td><img src="' . $img . '" alt="Service Image"></td>
                                            <td>' . $service['service_name'] . '</td>
                                            <td>' . $user['username'] . '</td>
                                            <td>' . $request['order_Date'] . '</td>
                                            <td>' . $request['delivery_date'] . '</td>
                                            <td>' . $request['additional_description'] . '</td>
                                            <td>
                                             <a class="btn btn-success " href="new_Requests.php?order_id=' . $request['order_id'] . '&&action=Accepted">Accept</a> 
                                             <a class="btn btn-danger"  btn-sm" href="new_Requests.php?order_id=' . $request['order_id'] . '&&action=Rejected">Reject</a> 
 
                                              </td>
                                              
                                        </tr>';
                                            }
                                        } else {
                                            echo '<tr>
                                        <td></td>
                                        <td>there are no data</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>';
                                        }
                                        ?>
                                             

                                        <!-- Repeat for other orders as needed -->
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
        <script src="../admin/assets/js/jquery-1.10.2.js"></script>
        <script src="../admin/assets/js/bootstrap.js"></script>
        <script src="../admin/assets/js/custom.js"></script>
    </body>
</html>
