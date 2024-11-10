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
$requests = mysqli_query($conn, "SELECT * FROM `requests` WHERE provider_id = '$provider_id' AND request_status = 'Accepted' or request_status = 'Completed'") or die('query failed');
$payment_value = '';


if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $update_query = "UPDATE `requests` SET `request_status` = 'Completed' WHERE `order_id` = '$order_id'";
    mysqli_query($conn, $update_query);
    header('location:requests_accepted.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Order Management for Providers">
        <meta name="author" content="">
        <title>Order Management</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet">
        <!-- FONT AWESOME STYLE  -->
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet">
        <!-- CUSTOM STYLE  -->
        <link href="../admin/assets/css/style.css" rel="stylesheet">
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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
            /* Custom modal styling */
            .modal-header {
                background-color: #CCB17A;
                color: white;
            }
            .modal-footer .btn {
                background-color: #CCB17A;
                color: white;
            }
            .search-container {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }

            .search-input {
                border: 2px solid #CCB17A;
                border-right: none;
                border-radius: 30px 0 0 30px;
                padding: 10px 15px;
                width: 300px;
                outline: none;
            }

            .search-button {
                background-color: #CCB17A;
                border: 2px solid #CCB17A;
                border-left: none;
                border-radius: 0 30px 30px 0;
                padding: 10px 15px;
                cursor: pointer;
            }

            .search-button i {
                color: white;
            }
        </style>
    </head>
    <body>
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Orders</h4>
                </div>
            </div>
            <div class="search-container">
                <input type="text" id="categorySearchInput" class="search-input" placeholder="Search by order number...">
                <button class="search-button" type="button" onclick="filterCategories()">
                    <img src="../img/magnifying-glass.png" style="width: 30px" />
                </button>
            </div>
            <!-- Orders Table -->
            <div class="row" style="margin-bottom: 250px">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Orders
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
                                            <th>Payment status</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

        $payments_query = "SELECT * FROM `payments` WHERE user_id = '$user_id' AND service_id = '$service_id'";
        $payments = mysqli_query($conn, $payments_query) or die('Query failed: ' . mysqli_error($conn));

        if (isset($payments) && mysqli_num_rows($payments) > 0) {
            $payment = mysqli_fetch_assoc($payments);
            $payment_value = $payment['payment_status'] ?? null;
        } else {
            $payment_value = 'Pending';
        }
        $status = $request['request_status'] ?? NULL;

        if (mysqli_num_rows($payments) > 0) {
            echo ' <tr>
                                            <td><img src="' . $img . '" alt="Service Image"></td>
                                            <td>' . $service['service_name'] . '</td>
                                            <td>' . $user['username'] . '</td>
                                            <td>' . $request['order_Date'] . '</td>
                                            <td>' . $request['delivery_date'] . '</td>'
            . '<td><span class="label label-success">Done</span></td>';


            if ($status == 'Accepted') {
                echo '<td><span class="label label-success">Accepted</span></td>
                                           ';
            } elseif ($status == 'Completed') {
                echo '<td><span class="label label-success">Completed</span></td>';
            }


            echo ' <td>
                                                <a href="requests_accepted.php?order_id=' . $request['order_id'] . '"  class="btn btn-accept btn-sm">Mark Completed</a>  
                                            
                                                <a href="Users.php?user_id=' . $request['user_id'] . '" class="btn btn-primary" style="background: #DCD4B6;">chat Now</a>  
                                            </td>
                                        </tr>';
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Complete Order Modal -->
            <div id="completeModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Confirm Completion</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to mark this order as completed?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">Yes, Complete</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <section class="footer-section" style="border-top: 3px solid #CCB17A;">
            <div class="container" style="text-align: center;">
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
