<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['user_id'];

if(!isset($admin_id)){
   header('location:../index.php');
}
if (isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('location:../index.php');
}
$chats = null;
$users = mysqli_query($conn, "SELECT * FROM `users`") or die('Query failed: ' . mysqli_error($conn));
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'] ?? null;
    $chats = mysqli_query($conn, "SELECT *
    FROM `chats`
    WHERE admin_id = '$admin_id'
      AND user_id ='$user_id'
      AND (provider_id = 0 OR provider_id IS NULL)
    ORDER BY provider_id DESC;") or die('Query failed: ' . mysqli_error($conn));

    if (isset($_POST['submit'])) {
        $massege = mysqli_real_escape_string($conn, $_POST['massege']);
        if (isset($massege) && $massege > 0) {
            $insert = mysqli_query($conn, "INSERT INTO `chats`(message, user_id, admin_id ,sender) VALUES('$massege', '$user_id', '$admin_id', 'admin')") or die('query failed');
            header('location: Users.php?user_id=' . $user_id . '');
        } else {
            header('location: Users.php?user_id=' . $user_id . '');
        }
    }
} else {
    $massege[] = 'welcome to our website';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>My Occasion - Chat Page</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .main-container {
            display: flex;
            height: 80vh; /* Adjust height as needed */
        }
        .users-list {
            width: 25%;
            background-color: #DCD4B6;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #CCB17A;
        }
        .user-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-container {
            width: 75%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            max-height: 80vh; /* Limit height to allow scrolling */
            overflow-y: auto; /* Enable vertical scrolling */
        }
        .messages {
            flex-grow: 1; /* Allow this to take up remaining space */
            overflow-y: auto; /* Enable scrolling for messages */
        }
        .message {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .message img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .message-content {
            max-width: 60%;
            padding: 10px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.4;
        }
        .message-left {
            justify-content: flex-start;
            margin-right: auto;
        }
        .message-left .message-content {
            background-color: #557187;
            color: white;
            margin-left: 10px;
        }
        .message-right {
            justify-content: flex-end;
            margin-left: auto;
        }
        .message-right .message-content {
            background-color: #CCB17A;
            color: white;
            margin-right: 10px;
        }
        .chat-input {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .chat-input input {
            width: 90%;
            padding: 10px;
            border: 2px solid #CCB17A;
            border-radius: 5px;
            font-size: 14px;
        }
        .chat-input button {
            padding: 10px;
            background-color: #CCB17A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
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
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="home.php">DASHBOARD</a></li>
                            <li><a href="Joining.php">Joining Requests</a></li>
                            <li><a href="Types.php">Types of Services</a></li>
                            <li>
                                <a href="#" class="dropdown-toggle menu-top-active" id="ddlmenuItem" data-toggle="dropdown">Help Chat<i class="fa fa-angle-down"></i></a>
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

    <!-- Main Content Area -->
    <div class="main-container">
        <!-- Users List -->
        <div class="users-list">
            <?php
            if (isset($users) && mysqli_num_rows($users) > 0) {
                while ($user = mysqli_fetch_assoc($users)) {
                    $id = $user['user_id'];
                    echo '<div class="user-item">
                    <a href="Users.php?user_id=' . $id . '" style="color: black;text-decoration: none">
                        <div><img src="../img/profile.png" alt="User 2">' . $user['username'] . '</div>
                    </a>
                </div>';
                }
            } else {
                echo '<div class="user-item">
                <a href="Users.php" style="color: black;text-decoration: none">
                    <div><img src="../img/profile.png" alt="User 2">There are no users</div>
                </a>
            </div>';
            }
            ?>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <div class="messages">
                <!-- Messages -->
                <?php
                if (!isset($message)) {
                    // Check if chat results exist
                    if ($chats && mysqli_num_rows($chats) > 0) {
                        while ($chat = mysqli_fetch_assoc($chats)) {
                            $sender = $chat['sender'];

                            // Determine if the sender is 'admin' or 'user' and display accordingly
                            if ($sender === 'admin') {
                                echo '
                <div class="message message-right">
                    <div class="message-content">' . htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8') . '</div>
                    <img src="../img/user (1).png" alt="Admin">
                </div>';
                            } else {
                                echo '
                <div class="message message-left">
                    <img src="../img/profile.png" alt="User">
                    <div class="message-content">' . htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8') . '</div>
                </div>';
                            }
                        }
                    } else {
                        // Display if no chats exist
                        echo '
        <div style="text-align: center; margin-bottom: 500px">
            <h1 style="color: #DCD4B6">No chat yet!</h1>
        </div>';
                    }
                } else {
                    // If $message array exists, display each message
                    foreach ($message as $msg) {
                        echo '
        <div style="text-align: center; margin-bottom: 300px">
            <h1 style="color: #DCD4B6">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</h1>
        </div>';
                    }
                }
                ?>
            </div>

            <!-- Chat input area -->
            <form method="post" enctype="multipart/form-data">
                <div class="chat-input">
                    <input type="text" name="massege" placeholder="Type your message...">
                    <input class="btn " style="background: #CCB17A; width: 100px" id="submitButton" value="Send" name="submit" type="submit">
                </div>
            </form>

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
</body>
</html>
