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

$services = mysqli_query($conn, "SELECT * FROM `services` WHERE provider_id = '$provider_id'") or die('Query failed: ' . mysqli_error($conn));

//-------------------------------------------------

if (isset($_POST['submit1'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $Price = mysqli_real_escape_string($conn, $_POST['Price']);
    $availabilityDate = mysqli_real_escape_string($conn, $_POST['availabilityDate']);
    $availabilityDate2 = mysqli_real_escape_string($conn, $_POST['availabilityDate2']);

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

    $insert = mysqli_query($conn, "INSERT INTO `services`(provider_id,service_name, service_description,price,availability,image1,image2,image3,Time,time1)"
            . " VALUES('$provider_id','$name', '$description','$Price','$available', '$image1', '$image2', '$image3', '$availabilityDate', '$availabilityDate2')") or die('query failed');

    if ($insert) {
        move_uploaded_file($image_tmp_name1, $image_folder1);
        move_uploaded_file($image_tmp_name2, $image_folder2);
        move_uploaded_file($image_tmp_name3, $image_folder3);
        header('location:service_Management.php');
    }
}
//-------------------------------------------------

if (isset($_GET['delete_id'])) {

    $delete_id = $_GET['delete_id'] ?? null;
    mysqli_query($conn, "DELETE FROM services WHERE service_id = '$delete_id'");
    header('location:service_Management.php');
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
            }
            .btn-primary {
                background-color: #DCD4B6;
                color: white;
                border: none;
            }
            .table-responsive img {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 15px;
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

        <!-- Service Management Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Service Management</h4>
                </div>
            </div>

            <!-- Add New Service Button -->
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary pull-right" style="background: #DCD4B6;" data-toggle="modal" data-target="#addServiceModal">Add New Service</button>
                </div>
            </div>
            <br>

                <!-- Services Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Manage Services
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Logo</th>
                                                <th>Service Name</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Type</th>
                                                <th>Availability</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($services) && mysqli_num_rows($services) > 0) {
                                                while ($service = mysqli_fetch_assoc($services)) {
                                                    $img = '../img/' . $service['image1'];
                                                    $provider_id = $service['provider_id'];

                                                    $provider_result = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE provider_id = '$provider_id'") or die('Query failed');
                                                    $provider = mysqli_fetch_assoc($provider_result);

                                                    $type_id = $provider['type_id'];
                                                    $type_result = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed');
                                                    $type = mysqli_fetch_assoc($type_result);

                                                    echo '<tr>
                                        <td><img src="' . $img . '" alt="Service Logo"></td>
                                        <td>' . $service['service_name'] . '</td>
                                        <td>' . $service['service_description'] . '</td>
                                        <td>' . $service['price'] . ' SAR</td>
                                        <td>' . $type['name'] . '</td>
                                        <td>' . $service['availability'] . '</td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="edit_service.php?edit_id=' . $service['service_id'] . '"> Edit</a>
                                            <a class="btn btn-danger btn-sm" href="service_Management.php?delete_id=' . $service['service_id'] . '"> Delete</a>
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

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Add Service Modal -->
        <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                                <label for="deliveryDate" class="form-label">Day off 1</label>
                                <input type="date" name="availabilityDate" class="form-control" id="deliveryDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="deliveryDate" class="form-label">Day off 2</label>
                                <input type="date" name="availabilityDate2" class="form-control" id="deliveryDate" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input class="btn btn-primary" style="background: #DCD4B6;"  value="Save" name="submit1" type="submit">

                        </div>
                    </form>
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
