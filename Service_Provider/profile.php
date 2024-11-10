<?php
include '../config.php';
session_start();
$provider_id = $_SESSION['user_id'];

if (!isset($provider_id)) {
    header('location:../index.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location:../index.php');
    exit();
}

// Fetch service provider information
$service_providers = mysqli_query($conn, "SELECT * FROM `service_providers` WHERE provider_id = '$provider_id'") or die('Query failed: ' . mysqli_error($conn));
$service_provider = mysqli_fetch_assoc($service_providers);
$img = '../img/' . $service_provider['logo'];

// Fetch service type information
$type_id = $service_provider['type_id'];
$type_result = mysqli_query($conn, "SELECT * FROM `service_type` WHERE type_id = '$type_id'") or die('Query failed: ' . mysqli_error($conn));
$type = mysqli_fetch_assoc($type_result);

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $document = mysqli_real_escape_string($conn, $_POST['document']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $servicetype = !empty($_POST['user_type']) ? mysqli_real_escape_string($conn, $_POST['user_type'][0]) : $service_provider['type_id'];

    // Handling image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../img/' . $image;

        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = $service_provider['logo']; // If no new image is uploaded, keep the old one
    }

    // Update query
    $update_query = "UPDATE `service_providers` SET 
                        `store_name` = '$name',
                        `logo` = '$image',
                        `description` = '$description',
                        `commercial_registration_number` = '$document',
                        `type_id` = '$servicetype',
                        `location` = '$location'
                    WHERE `provider_id` = '$provider_id'";

    if (mysqli_query($conn, $update_query)) {
        $message1[] = 'Account has been updated successfully';
    } else {
        $message[] = 'Profile update failed!';
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
        <title>Service Provider Profile Management</title>
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet" />
        <link href="../admin/assets/css/style.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <style>
            body { background-color: white; color: #CCB17A; font-family: 'Open Sans', sans-serif; }
            .navbar { background-color: white; }
            .navbar-brand h1 { color: #DCD4B6; }
            .profile-container { background-color: #f9f9f9; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); margin-top: 40px; }
            .profile-item { margin-bottom: 20px; }
            .profile-container img { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); }
            .profile-container h3 { font-size: 24px; font-weight: bold; }
            .profile-container p { font-size: 16px; }
            .edit-btn { background: #DCD4B6; color: white; border: none; padding: 10px 20px; border-radius: 30px; margin-top: 20px; }
            .modal-header, .modal-footer { background-color: #DCD4B6; color: white; }
            .menu-section { background-color: white; border-bottom: 3px solid #CCB17A; }
            .message, .message1 { margin:10px 0; width: 100%; border-radius: 5px; padding:10px; text-align: center; font-size: 20px; }
            .message { background-color:red; color:white; }
            .message1 { background-color:green; color:white; }
        </style>
    </head>
    <body>
        <!-- Navbar -->
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
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Requests<i class="fa fa-angle-down"></i></a>
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

        <!-- Profile Section -->
        <div class="row" >
            <div class="col-md-12">
                <h4 class="header-line" style="margin-left: 150px;width: 1200px">Service Provider Profile</h4>
            </div>
        </div>

        <!-- Profile Management Section -->
        <div class="container profile-container" style="margin-bottom: 200px">
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . $msg . '</div>';
                }
            }
            if (isset($message1)) {
                foreach ($message1 as $msg) {
                    echo '<div class="message1">' . $msg . '</div>';
                }
            }
            ?>
            <div class="row">
                <div class="col-md-4 text-center">
                    <!-- Profile Picture -->
                    <img src="<?php echo $img; ?>" style="width: 300px;height: 300px;border: 3px solid #CCB17A;" alt="Profile Logo">
                </div>
                <div class="col-md-8">
                    <!-- Profile Details -->
                    <div class="profile-item"><h3>Provider Name: <?php echo $service_provider['store_name']; ?></h3></div>
                    <div class="profile-item"><p>Description: <?php echo $service_provider['description']; ?></p></div>
                    <div class="profile-item"><p>Location: <?php echo $service_provider['location']; ?></p></div>
                    <div class="profile-item"><p>Service Type: <?php echo $type['name']; ?></p></div>
                    <div class="profile-item"><p>Commercial Registration Number: <?php echo $service_provider['commercial_registration_number']; ?></p></div>
                    <!-- Edit Button -->
                    <button class="edit-btn" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Profile Edit Form -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Provider Name:</label>
                                <input type="text" name="name" value="<?php echo $service_provider['store_name']; ?>" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="description" required class="form-control"><?php echo $service_provider['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Location:</label>
                                <input type="text" name="location" value="<?php echo $service_provider['location']; ?>" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Commercial Registration Number:</label>
                                <input type="text" name="document" value="<?php echo $service_provider['commercial_registration_number']; ?>" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Service Type:</label>
                                <select name="user_type[]" class="form-control">
                                    <?php
                                    $type_query = mysqli_query($conn, "SELECT * FROM `service_type`");
                                    while ($row = mysqli_fetch_assoc($type_query)) {
                                        $selected = ($row['type_id'] == $type['type_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['type_id'] . "' $selected>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Profile Picture:</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" style="color: black" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
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
        <!-- Scripts -->
        <script src="../admin/assets/js/jquery-1.10.2.js"></script>
        <script src="../admin/assets/js/bootstrap.js"></script>
        <script src="../admin/assets/js/custom.js"></script>
    </body>
</html>
