<?php
include '../config.php';
session_start();

// Check if the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:../index.php');
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('location:../index.php');
    exit();
}

// Fetch user details
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
$user = mysqli_fetch_assoc($user_query);

// Get the service_id from the query string if available
$service_id = $_GET['service_id'] ?? null;

// Handle form submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);

    // Validate card number (should be 16 digits)
    if (strlen($cardNumber) !== 14) {
        $message[] = 'The card number must be exactly 14 digits.';
    } else {
        // Insert payment information into the database
        $insert = mysqli_query($conn, "INSERT INTO `payments`(user_id, service_id, card_Number, CVV) VALUES('$user_id', '$service_id', '$cardNumber', '$cvv')") or die('Query failed');

        if ($insert) {
             $message1[] = ' Payment was completed successfully!';
             
        } else {
            $message[] = 'Failed to process the payment. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Book a Service</title>
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

            .navbar-brand {
                color: #CCB17A;
                font-weight: bold;
            }

            .menu-section {
                background-color: #f7f7f7;
                border-bottom: 3px solid #CCB17A;
                padding: 10px;
            }

            .menu-section ul.nav>li>a {
                color: #CCB17A;
            }

            .content-wrapper {
                padding: 40px 20px;
            }

            .header-line {
                color: #CCB17A;
                font-size: 24px;
                font-weight: bold;
            }

            .reservation-form {
                background-color: #f9f9f9;
                padding: 30px;
                border-radius: 10px;
                border: 1px solid #CCB17A;
            }

            .btn-primary {
                background-color: #DCD4B6;
                color: white;
                border: none;
                width: 100%;
            }

            .btn-primary:hover {
                background-color: #CCB17A;
            }
             .menu-section {
                background-color: white;
                border-bottom: 3px solid #CCB17A;
            }

            .modal-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                padding: 15px;
                border-radius: 10px;
                text-align: center;
                margin-top: 110px;
            }
            .footer-section {
                border-top: 3px solid #CCB17A;
            }

            .modal-success i {
                font-size: 50px;
                margin-bottom: 20px;
            }

            .message{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:red;
                color:white;
                font-size: 20px;
            }
            .message1{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:green;
                color:white;
                font-size: 20px;
            }
        </style>
    </head>

    <body>

    <div class="navbar navbar-inverse set-radius-zero">
            <div class="container">
                <a href="../index.php" class="navbar-brand">
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
                                <li><a href="../index.php" class="">Home</a></li>
                                <li><a href="../services.php" class="">Services</a></li>
                                <li><a href="../categories.php" class="">Store categories</a></li>
                                <li><a href="../service_providers.php" class="">Service providers</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle menu-top-active" id="ddlmenuItem" data-toggle="dropdown">My Requests<i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="new_Requests.php">New Requests</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="Completed_Requests.php">accepted and Completed</a></li>
                                    </ul>
                                </li>
                                <li><a href="admin_chat.php" >Chat with Admin</a></li>
                                <li>
                                    <a href="Completed_Requests.php?logout" title="LOG ME OUT" class="btn btn-danger" style="margin-left: 20px;color: #FFFFFF;height: 20px;margin-top: 10px;background: #ED6C6C">LOG ME OUT</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <div class="container content-wrapper">
            <h4 class="header-line">Payment</h4>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="reservation-form">
                        <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '<div class="message">' . $message . '</div>';
                            }
                        } elseif (isset($message1)) {
                            foreach ($message1 as $message1) {
                                echo '<div class="message1">' . $message1 . '</div>';
                            }
                        }
                        ?>
                        <form method="post">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= $user['username'] ?>" id="fullName">
                            </div>

                            <!-- Card Number -->
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number</label>
                                <input type="text" name="cardNumber" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="16" required>
                            </div>

                            <!-- Expiration Date -->
                            <div class="mb-3">
                                <label for="expiryDate" class="form-label">Expiration Date</label>
                                <input type="month" name="month" class="form-control" id="expiryDate" required>
                            </div>

                            <!-- CVV -->
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-control" id="cvv" placeholder="123" maxlength="3" required>
                            </div>
                            <br>
                            <button  type="submit" name="submit" class="btn btn-primary">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       

<!-- Success Modal -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body modal-success">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            <h4>Payment was completed successfully!</h4>
                        </div>
                    </div>
                </div>
            </div>

           <section class="footer-section">
                <div class="container" style="text-align: center;">
                    <div class="row">
                        <div class="col-md-12">
                            All rights reserved &copy; 2024 - My Occasion
                        </div>
                    </div>
                </div>
            </section>
            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="background: #DCD4B6;"><i class="bi bi-arrow-up"></i></a>
      

       <!-- SCRIPTS -->
        <script src="../admin/assets/js/jquery-1.10.2.js"></script>
        <script src="../admin/assets/js/bootstrap.js"></script>
        <script src="../admin/assets/js/custom.js"></script>
    </body>
</html>
