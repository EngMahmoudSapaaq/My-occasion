<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['user_id'];

if(!isset($admin_id )){
   header('location:../index.php');
};
// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Update store type functionality
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'] ?? null;
    
    if (isset($_POST['submit1'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $image = $_FILES['image']['name'] ?? '';
        $image_tmp_name = $_FILES['image']['tmp_name'] ?? '';
        
        // Upload image if exists
        if ($image) {
            $image_folder = '../img/' . $image;
            move_uploaded_file($image_tmp_name, $image_folder);
        }

        // Prepare the update query
        $update_query = "UPDATE `service_type` SET `name` = '$name', `image` = '$image' WHERE `type_id` = '$edit_id'";
        
        if (mysqli_query($conn, $update_query)) {
            header('Location: Types.php');
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
    <title>My Occasion - Types of Stores Management</title>
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
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="home.php">DASHBOARD</a></li>
                        <li><a href="Joining.php">Joining Requests</a></li>
                        <li><a href="Types.php" class="menu-top-active">Types of Services</a></li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">
                                Help Chat<i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li><a role="menuitem" tabindex="-1" href="service-providers.php">Service Providers</a></li>
                                <li><a role="menuitem" tabindex="-1" href="Users.php">Users</a></li>
                            </ul>
                        </li>
                        <li><a href="reports.php">Reports</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Types of Stores Management Section -->
    <div class="container" style="margin-bottom: 150px; margin-top: 50px; width: 800px;">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Edit Store Type</h4>
            </div>
        </div>

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Store Type</h5>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="storeLogo">Image</label>
                        <input type="file" name="image" class="form-control" id="storeLogo" />
                    </div>
                    <div class="form-group">
                        <label for="storeName">Name</label>
                        <input type="text" name="name" class="form-control" id="storeName" placeholder="Enter store type name" required />
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
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
