<?php
include '../config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:../index.php');
};
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../index.php');
}
$requests = mysqli_query($conn, "SELECT * FROM `requests` WHERE user_id = '$user_id' AND request_status = 'Completed'") or die('query failed');
$Accepted_requests= mysqli_query($conn, "SELECT * FROM `requests` WHERE user_id = '$user_id' AND request_status = 'Accepted'") or die('query failed');

$rating_value=0;

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
            }

            .modal-content {
                padding: 20px;
            }

            .star-rating {
                direction: rtl;
                font-size: 30px;
            }

            .star-rating input {
                display: none;
            }

            .star-rating label {
                color: #ddd;
                font-size: 30px;
                padding: 0 5px;
                cursor: pointer;
            }

            .star-rating input:checked ~ label,
            .star-rating input:hover ~ label {
                color: gold;
            }
            .menu-section {
                background-color: white;
                border-bottom: 3px solid #CCB17A;
            }
            .footer-section {
                border-top: 3px solid #CCB17A;
            }
        </style>
    </head>
    <body>
         <!-- Navbar Section -->
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

        <!-- Service Browsing Section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Accepted And Completed Requests</h4>
                </div>
            </div>



            <!-- Services Table -->
            <div class="row" style="margin-bottom: 250px">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Accepted Requests
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
                                            <th>Payment status</th>
                                            <th>Order status</th>
                                            <th>chat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($Accepted_requests) && mysqli_num_rows($Accepted_requests) > 0) {
                                            while ($Accepted_request = mysqli_fetch_assoc($Accepted_requests)) {

                                                $service_id = $Accepted_request['service_id'];
                                                $services = mysqli_query($conn, "SELECT * FROM `services` WHERE service_id = '$service_id'") or die('Query failed');
                                                $service = mysqli_fetch_assoc($services);

                                                $user_id = $Accepted_request['user_id'];
                                                $users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
                                                $user = mysqli_fetch_assoc($users);
                                                $img = '../img/' . $service['image1'];
                                                
                                                $payments = mysqli_query($conn, "SELECT * FROM `payments` WHERE user_id = '$user_id' AND service_id = '$service_id'") or die('Query failed');
                                                
                                                
                                                //-------------------------------------
                                                echo '<tr>
                                            <td><img src="'.$img.'" alt="Service Logo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"></td>
                                             <td>' . $service['service_name'] . '</td>
                                            <td>' . $user['username'] . '</td>
                                            <td>' . $service['service_description'] . '</td>
                                            <td>' . $service['price'] . ' SAR</td>';
                                                if (isset($payments) && mysqli_num_rows($payments) > 0) {
                                                    echo '<td><span class="label label-success">Done</span></td>'
                                                    . '<td><span class="label label-primary">accepted</span></td>'
                                                            . ' <td><a href="service-providers.php?provider_id='.$Accepted_request['provider_id'].'" class="btn btn-primary"  style="background: #DCD4B6;">chat Now</a></td></tr>';
                                                } else {
                                                    echo '<td><span class="label label-warning">Pending</span></td>'
                                                      . '<td><span class="label label-primary">accepted</span></td>'
                                                            . ' <td><a href="service-providers.php?provider_id='.$Accepted_request['provider_id'].'" class="btn btn-primary"  style="background: #DCD4B6;">chat Now</a></td></tr>';
                                                
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
                 <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Completed Requests
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
                                            <th>Payment status</th>
                                            <th>Order status</th>
                                            <th>Rateing</th>
                                            <th>Rateing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example Service Row -->
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
                                                
                                                $payments = mysqli_query($conn, "SELECT * FROM `payments` WHERE user_id = '$user_id' AND service_id = '$service_id'") or die('Query failed');
                                                $ratings = "SELECT COUNT(*) as total FROM `ratings` WHERE service_id = '$service_id'";
                                        if (isset($ratings)) {
                                             $rating = mysqli_query($conn, $ratings);
                                        $rating_data = mysqli_fetch_assoc($rating);
                                        $rating_value = ($rating_data['total']);
                                        } else {
                                            $rating_value=0;
                                        }
                                               
                                                 
                                                //-------------------------------------
                                                echo '<tr>
                                            <td><img src="'.$img.'" alt="Service Logo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"></td>
                                             <td>' . $service['service_name'] . '</td>
                                            <td>' . $user['username'] . '</td>
                                            <td>' . $service['service_description'] . '</td>
                                            <td>' . $service['price'] . ' SAR</td>';
                                                if (isset($payments) && mysqli_num_rows($payments) > 0) {
                                                    echo '<td><span class="label label-success">Done</span></td>'
                                                    . '<td><span class="label label-primary">Completed</span></td>'
                                                            . '<td>'.$rating_value.' users</td>'
                                                            . ' <td><a href="rate.php?service_id='.$service_id.'" class="btn btn-primary"  style="background: #DCD4B6;">Rate Now</a></td></tr>';
                                                } else {
                                                    echo '<td><span class="label label-warning">Pending</span></td>'
                                                      . '<td><span class="label label-primary">Completed</span></td>'
                                                           . '<td>'.$rating_value.' users</td>'
                                                         . ' <td><a href="rate.php?service_id='.$service_id.'" class="btn btn-primary"  style="background: #DCD4B6;">Rate Now</a></td></tr>';
                                                
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
            

            <!-- Rating Dialog Modal -->
            <div id="ratingModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rate Service</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="ratingForm">
                                <div class="form-group">
                                    <label>Rate the service:</label>
                                    <div class="star-rating">
                                        <input type="radio" name="stars" id="star-5" value="5"><label for="star-5">&#9733;</label>
                                        <input type="radio" name="stars" id="star-4" value="4"><label for="star-4">&#9733;</label>
                                        <input type="radio" name="stars" id="star-3" value="3"><label for="star-3">&#9733;</label>
                                        <input type="radio" name="stars" id="star-2" value="2"><label for="star-2">&#9733;</label>
                                        <input type="radio" name="stars" id="star-1" value="1"><label for="star-1">&#9733;</label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary"   style="background: #DCD4B6;">Submit Rating</button>
                            </form>
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
                function openRatingDialog() {
                    $('#ratingModal').modal('show');
                }

                function submitRating() {
                    // Simulate rating submission
                    setTimeout(function () {
                        $('#ratingModal').modal('hide'); // Close the modal
                        $('#successAlert').fadeIn();     // Show success alert
                        setTimeout(function () {
                            $('#successAlert').fadeOut();  // Hide success alert after 3 seconds
                        }, 3000);
                    }, 500);  // Simulate a short delay
                }
            </script>
    </body>
</html>
