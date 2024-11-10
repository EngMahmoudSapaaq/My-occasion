<?php
include '../config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:../index.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location:../index.php');
    exit;
}

// Fetch requests with service and user details using JOINs
$requests_query = "
    SELECT r.*, s.service_name, s.service_description, s.price, s.image1, u.username
    FROM `requests` r
    JOIN `services` s ON r.service_id = s.service_id
    JOIN `users` u ON r.user_id = u.user_id
    WHERE r.user_id = '$user_id'
    AND (r.request_status = 'Pending' OR r.request_status = 'Accepted')
";
$requests = mysqli_query($conn, $requests_query) or die('Query failed: ' . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Available Services</title>
        <!-- BOOTSTRAP CORE STYLE -->
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE -->
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE -->
        <link href="../admin/assets/css/style.css" rel="stylesheet" />
        <style>
            .btn-primary { 
                background-color: #DCD4B6; 
                color: white; 
                border: none; 
                border-radius: 5px; 
                padding: 5px 10px;
                cursor: pointer;
            }
            .btn-primary:hover {
                background-color: #c0b095;
                color: white;
            }
            .menu-section { 
                background-color: white; 
                border-bottom: 3px solid #CCB17A; 
            }
            .footer-section { 
                border-top: 3px solid #CCB17A; 
            }
            .timer-button {
                background: #DCD4B6;
                border: none;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                cursor: pointer;
            }
            .timer-button:disabled {
                background: #a9a9a9;
                cursor: not-allowed;
            }
            .label-success { 
                background-color: #5cb85c; 
            }
            .label-warning { 
                background-color: #f0ad4e; 
            }
        </style>
    </head>
    <body>

        <!-- Navbar Section -->
        <div class="navbar navbar-inverse set-radius-zero">
            <div class="container">
                <a href="../index.php" class="navbar-brand">
                    <h1 style="color: #DCD4B6">
                        <img src="../img/team.png" alt="Icon" style="width: 30px; height: 30px;"> My Occasion
                    </h1>
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
                                <li><a href="../index.php">Home</a></li>
                                <li><a href="../services.php">Services</a></li>
                                <li><a href="../categories.php">Store categories</a></li>
                                <li><a href="../service_providers.php">Service providers</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle menu-top-active" id="ddlmenuItem" data-toggle="dropdown">
                                        My Requests <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="new_Requests.php">New Requests</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="Completed_Requests.php">accepted and Completed</a></li>
                                    </ul>
                                </li>
                                <li><a href="admin_chat.php">Chat with Admin</a></li>
                                <li>
                                    <a href="Completed_Requests.php?logout" title="LOG ME OUT" class="btn btn-danger" style="margin-left: 20px; color: #FFFFFF; height: 20px; margin-top: 10px; background: #ED6C6C;">
                                        LOG ME OUT
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Browsing Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">New Requests</h4>
                </div>
            </div>

            <!-- Services Table -->
            <div class="row" style="margin-bottom: 250px">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Available Services
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Service Name</th>
                                            <th>User Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Payment Status</th>
                                            <th>Timer</th>
                                            <th>Order Status</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
if (mysqli_num_rows($requests) > 0) {
    $rows_displayed = false; // Flag to track if any rows are displayed

    while ($request = mysqli_fetch_assoc($requests)) {
        // Extract and sanitize variables
        $service_id = htmlspecialchars($request['service_id'], ENT_QUOTES, 'UTF-8');
        $service_name = htmlspecialchars($request['service_name'], ENT_QUOTES, 'UTF-8');
        $service_description = htmlspecialchars($request['service_description'], ENT_QUOTES, 'UTF-8');
        $service_price = htmlspecialchars($request['price'], ENT_QUOTES, 'UTF-8');
        $username = htmlspecialchars($request['username'], ENT_QUOTES, 'UTF-8');
        $service_image = '../img/' . htmlspecialchars($request['image1'], ENT_QUOTES, 'UTF-8');

        // Fetch payment details
        $payments_query = "SELECT * FROM `payments` WHERE user_id = '$user_id' AND service_id = '$service_id'";
        $payments = mysqli_query($conn, $payments_query) or die('Query failed: ' . mysqli_error($conn));
        $payment_count = mysqli_num_rows($payments);

        // Calculate remaining time
        $request_time = strtotime($request['Time']);
        $current_time = time();
        $time_diff = $current_time - $request_time;
        $two_hours_in_seconds = 60 * 60 * 1; // Two hours in seconds
        $remaining_time = max($two_hours_in_seconds - $time_diff, 0);

        // Determine payment and order status
        $payment_status = ($payment_count > 0) ? 'Done' : 'Pending';
        $order_status = htmlspecialchars($request['request_status'], ENT_QUOTES, 'UTF-8');

        // Skip row if both payment and order status are acceptable
        if ($payment_status == 'Done' && $order_status == 'Accepted') {
            continue; // Skip this row
        }

        // If we reach here, it means the row will be displayed, so set flag to true
        $rows_displayed = true;

        // Initialize variables for display
        $payment_label = '';
        $timer_display = '';
        $payment_button = '';

        if ($payment_status == 'Pending' && $order_status == 'Accepted') {
            // Payment pending and order accepted
            $payment_label = '<span class="label label-warning">Pending</span>';
            $timer_id = 'timer' . $service_id;
            $payBtn_id = 'payBtn' . $service_id;
            $timer_display = '<span class="btn timer-button" id="' . $timer_id . '"></span>';
            $payment_button = '<a id="' . $payBtn_id . '" href="Payment.php?service_id=' . $service_id . '" class="btn btn-primary">Pay Now</a>';
        } elseif ($payment_status == 'Pending' && $order_status == 'Pending') {
            // Payment pending and order pending
            $payment_label = '<span class="label label-success">Pending</span>';
            $timer_display = '<span class="btn timer-button">00:00:00</span>';
            $payment_button = '<a id="payBtn' . $service_id . '" class="btn btn-primary">---</a>';
        } else {
            // Payment done
            $payment_label = '<span class="label label-success">Done</span>';
            $timer_display = '<span class="btn timer-button">00:00:00</span>';
            $payment_button = '<a id="payBtn' . $service_id . '" class="btn btn-primary">---</a>';
        }

        echo '<tr>
            <td><img src="' . $service_image . '" alt="Service Logo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"></td>
            <td>' . $service_name . '</td>
            <td>' . $username . '</td>
            <td>' . $service_description . '</td>
            <td>' . $service_price . ' SAR</td>
            <td>' . $payment_label . '</td>
            <td>' . $timer_display . '</td>
            <td><span class="label label-warning">' . $order_status . '</span></td>
            <td>' . $payment_button . '</td>
        </tr>';

        // JavaScript for countdown timer if payment is pending and order is accepted
        if ($payment_status == 'Pending' && $order_status == 'Accepted') {
            echo '<script>
                window.addEventListener("load", function() {
                    startCountdown(' . $remaining_time . ', document.getElementById("' . $timer_id . '"), "' . $payBtn_id . '");
                });
            </script>';
        }
    }

    // If no rows were displayed, show the "No requests found." message
    if (!$rows_displayed) {
        echo '<tr>
                <td colspan="9" style="text-align: center;">No requests found.</td>
              </tr>';
    }
} else {
    // No requests at all in the database
    echo '<tr>
            <td colspan="9" style="text-align: center;">No requests found.</td>
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

            <!-- Footer Section -->
            <section class="footer-section">
                <div class="container" style="text-align: center;">
                    <div class="row">
                        <div class="col-md-12">
                            All rights reserved &copy; 2024 - My Occasion
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- SCRIPTS -->
        <script src="../admin/assets/js/jquery-1.10.2.js"></script>
        <script src="../admin/assets/js/bootstrap.js"></script>
        <script src="../admin/assets/js/custom.js"></script>
        <script>
            function startCountdown(duration, display, buttonId) {
                var timer = duration, hours, minutes, seconds;
                var interval = setInterval(function () {
                    hours = parseInt(timer / 3600, 10);
                    minutes = parseInt((timer % 3600) / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = hours + ":" + minutes + ":" + seconds;

                    if (--timer < 0) {
                        clearInterval(interval);
                        document.getElementById(buttonId).style.display = 'none'; // Hide payment button when time expires
                    }
                }, 1000);
            }
        </script>
    </body>
</html>
