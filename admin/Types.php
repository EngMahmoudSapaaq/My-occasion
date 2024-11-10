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

$service_types = mysqli_query($conn, "SELECT * FROM `service_type`") or die('Query failed: ' . mysqli_error($conn));

//-------------------------------------------------

if (isset($_POST['submit1'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../img/' . $image;
    $insert = mysqli_query($conn, "INSERT INTO `service_type`(name,image, admin_id)"
            . " VALUES('$name','$image', '$admin_id')") or die('query failed');

    if ($insert) {
        move_uploaded_file($image_tmp_name, $image_folder);
        header('location:Types.php');
    }
}
//-------------------------------------------------

if (isset($_GET['delete_id'] )) {
    
    $delete_id = $_GET['delete_id'] ?? null;
    mysqli_query($conn ,"DELETE FROM service_type WHERE type_id = '$delete_id'");
     header('location:Types.php');
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>My Occasion - Types of Stores Management</title>
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
                                <li><a href="Joining.php" >Joining Requests</a></li>
                                <li><a href="Types.php" class="menu-top-active">Types of Services</a></li>

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

        <!-- Types of Stores Management Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Types of Stores Management</h4>
                </div>
            </div>

            <!-- Add New Type Button -->
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary pull-right" style="background: #DCD4B6" data-toggle="modal" data-target="#addStoreTypeModal">Add New Store Type</button>
                </div>
            </div>
            <br>
                <!-- Store Types Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Manage Store Types
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Logo</th>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($service_types) && mysqli_num_rows($service_types) > 0) {
                                                while ($service_type = mysqli_fetch_assoc($service_types)) {
                                                    $img = '../img/' . $service_type['image'];
                                                    echo '<tr>
                                        <td><img src="' . $img . '" alt="Logo" style="width: 150px;border-radius: 30px"></td>
                                        <td>' . $service_type['name'] . '</td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="edit_type.php?edit_id='.$service_type['type_id'].'"> Edit</a>
                                            <a class="btn btn-danger btn-sm" href="Types.php?delete_id='.$service_type['type_id'].'"> Delete</a>
                                        </td>
                                    </tr>';
                                                }
                                            } else {
                                                echo '<tr>
                                        <td></td>
                                        <td>there are no data</td>
                                        <td>
                                            </td>
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

        <!-- Add Store Type Modal -->
        <div class="modal fade" id="addStoreTypeModal" tabindex="-1" role="dialog" aria-labelledby="addStoreTypeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStoreTypeModalLabel">Add New Store Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="storeLogo"> Image</label>
                                <input type="file" name="image" class="form-control" id="storeLogo" />
                            </div>
                            <div class="form-group">
                                <label for="storeName">Name</label>
                                <input type="text" name="name" class="form-control" id="storeName" placeholder="Enter store type name" />
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

        <!-- Edit Store Type Modal -->
        <div class="modal fade" id="editStoreTypeModal" tabindex="-1" role="dialog" aria-labelledby="editStoreTypeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStoreTypeModalLabel">Edit Store Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="editStoreLogo">Logo Image</label>
                                <input type="file" class="form-control" id="editStoreLogo" />
                            </div>
                            <div class="form-group">
                                <label for="editStoreName">Name</label>
                                <input type="text" class="form-control" id="editStoreName" placeholder="Enter store type name" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" style="background: #DCD4B6">Save Changes</button>
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
    </body>
</html>
