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

// Update store type functionality
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'] ?? null;

    if (isset($_POST['submit1'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $Price = mysqli_real_escape_string($conn, $_POST['Price']);
        $availabilityDate = mysqli_real_escape_string($conn, $_POST['availabilityDate']);


        $image1 = $_FILES['image1']['name'];
        $image_size1 = $_FILES['image1']['size'];
        $image_tmp_name1 = $_FILES['image1']['tmp_name'];
        $image_folder1 = '../img/' . $image1;
        //--------------------------------
        $image2 = $_FILES['image2']['name'];
        $image_size2 = $_FILES['image2']['size'];
        $image_tmp_name2 = $_FILES['image2']['tmp_name'];
        $image_folder2 = '../img/' . $image2;
        //--------------------------------
        $image3 = $_FILES['image3']['name'];
        $image_size3 = $_FILES['image3']['size'];
        $image_tmp_name3 = $_FILES['image3']['tmp_name'];
        $image_folder3 = '../img/' . $image3;
        //--------------------------------
        if (!empty($_POST['user_Available'])) {
            foreach ($_POST['user_Available'] as $check) {
                $available = $check;
            }
        }

        // Prepare the update query
        $update_query = "UPDATE `services` SET `service_name` = '$name', `service_description` = '$description', `price` = '$Price', `availability` = '$available', `image1` = '$image1', `image2` = '$image2', `image3` = '$image3', `Time` = '$availabilityDate'"
                . " WHERE `service_id` = '$edit_id'";

        if (mysqli_query($conn, $update_query)) {
            header('Location: service_Management.php');
            exit();
        } else {
            die('Query failed: ' . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="Service Management for Providers" />
        <meta name="author" content="" />
        <title>Service Management</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="../admin/assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /><style>
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
            .content-wrapper {
                padding: 20px;
                background-color: white;
            }
            .header-line {
                color: #CCB17A;
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
                                <li><a href="service_Management.php" class="menu-top-active">Service Management</a></li>
                                <li><a href="admin_chat.php" class="" >Chat With admin </a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle " id="ddlmenuItem" data-toggle="dropdown">Requests<i class="fa fa-angle-down"></i></a>
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

        <!-- Types of Stores Management Section -->
        <div class="container" style="margin-bottom: 150px; margin-top: 50px; width: 800px;">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Edit Service</h4>
                </div>
            </div>

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Service</h5>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="serviceLogo">Service Logo1</label>
                            <input type="file" name="image1" class="form-control" id="serviceLogo" />
                        </div>
                        <div class="form-group">
                            <label for="serviceLogo">Service Logo2</label>
                            <input type="file" name="image2" class="form-control" id="serviceLogo" />
                        </div>
                        <div class="form-group">
                            <label for="serviceLogo">Service Logo3</label>
                            <input type="file" name="image3" class="form-control" id="serviceLogo" />
                        </div>
                        <div class="form-group">
                            <label for="serviceName">Service Name</label>
                            <input type="text" name="name" class="form-control" id="serviceName" placeholder="Enter service name" />
                        </div>
                        <div class="form-group">
                            <label for="serviceDescription">Service Description</label>
                            <textarea class="form-control" name="description" id="serviceDescription" placeholder="Enter service description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="servicePrice">Price</label>
                            <input type="text" name="Price" class="form-control" id="servicePrice" placeholder="Enter service price" />
                        </div>

                        <div class="form-group"  name="user_Available[]">
                            <label for="serviceAvailability">Availability</label>
                            <select class="form-control"  name="user_Available[]" id="serviceAvailability">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deliveryDate" class="form-label">availability Date</label>
                            <input type="date" name="availabilityDate" class="form-control" id="deliveryDate" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-primary" style="background: #DCD4B6;" value="Save" name="submit1" type="submit">
                    </div>
                </form>
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

        <!-- SCRIPTS -->
        <script src="../admin/assets/js/jquery-1.10.2.js"></script>
        <script src="../admin/assets/js/bootstrap.js"></script>
        <script src="../admin/assets/js/custom.js"></script>
    </body>
</html>