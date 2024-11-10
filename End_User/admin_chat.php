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
$chats = mysqli_query($conn, "SELECT *
FROM `chats`
WHERE admin_id = '1'
  AND user_id ='$user_id'
  AND (provider_id = 0 OR provider_id IS NULL)
ORDER BY provider_id DESC;") or die('Query failed: ' . mysqli_error($conn));

if (isset($_POST['submit'])) {

    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if (!empty($message)) {
        $insert = mysqli_query($conn, "INSERT INTO `chats`(message, user_id, admin_id ,sender) VALUES('$message', '$user_id', '1', 'user')") or die('query failed');
        header('location: admin_chat.php');
    } else {
        header('location: admin_chat.php');
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
        <title>My Occasion - Chat Page</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="../admin/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="../admin/assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="../admin/assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
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

            .chat-container {
                display: flex;
                flex-direction: column;
                height: 80vh;
            }
            .user-list {
                display: flex;
                overflow-x: auto;
                padding: 10px;
                background-color: #f9f9f9;
                border-bottom: 1px solid #ddd;
            }
            .user-item {
                display: flex;
                align-items: center;
                margin-right: 15px;
                border: 2px solid #DCD4B6;
                padding: 5px;
                border-radius: 8px;
            }
            .user-item img {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                margin-right: 10px;
            }
            .status-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-left: 10px;
            }
            .active {
                background-color: green;
            }
            .inactive {
                background-color: red;
            }
            .chat-content {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            .messages {
                flex: 1;
                overflow-y: auto;
                padding: 10px;
                border-bottom: 1px solid #ddd;
                max-height: 60vh; /* Set maximum height for scrollable area */
            }
            .message {
                border-bottom: 1px solid #ddd;
                padding: 5px 0;
                margin-bottom: 15px;
            }
            .message.user {
                text-align: right;
                background-color: #e9f5ff;
                padding: 8px;
                border-radius: 5px;
            }
            .message:not(.user) {
                background-color: #f5f5f5;
                padding: 8px;
                border-radius: 5px;
            }
            .chat-input-container {
                padding: 10px;
                border-top: 1px solid #ddd;
                background-color: #fff;
                display: flex;
                align-items: center;
            }
            .chat-input-container input {
                flex: 1;
                margin-right: 10px;
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
                                    <a href="#" class="dropdown-toggle " id="ddlmenuItem" data-toggle="dropdown">My Requests<i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="new_Requests.php">New Requests</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="Completed_Requests.php">accepted and Completed</a></li>
                                    </ul>
                                </li>
                                <li><a href="admin_chat.php" class="menu-top-active">Chat with Admin</a></li>
                                <li>
                                    <a href="Completed_Requests.php?logout" title="LOG ME OUT" class="btn btn-danger" style="margin-left: 20px;color: #FFFFFF;height: 20px;margin-top: 10px;background: #ED6C6C">LOG ME OUT</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chat Section -->
        <div class="container chat-container" style="border: 5px solid #DCD4B6;padding: 5px;border-radius: 8px;margin-top: 50px;margin-bottom: 50px">
            <!-- User List -->
            <div class="user-list">
                <div class="user-item">
                    <img src="../img/profile.png" alt="User 1">
                        <div>
                            <strong>Admin</strong>
                        </div>
                        <div class="status-dot active"></div>
                </div>

                <!-- Add more users with status as needed -->
            </div>
            <!-- Chat Content -->
            <div class="chat-content">
                <div class="messages" id="chatArea">
                    <!-- Messages will be dynamically added here -->

                    <?php
                    if (!isset($message)) {
                        // Check if chat results exist
                        if ($chats && mysqli_num_rows($chats) > 0) {
                            while ($chat = mysqli_fetch_assoc($chats)) {
                                $sender = $chat['sender'];

                                // Determine if the sender is 'admin' or 'user' and display accordingly
                                if ($sender === 'user') {
                                    echo '
                    <div class="message user">' . htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8') . '</div>';
                                } else {
                                    echo '<div class="message">' . htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8') . '</div>';
                                }
                            }
                        } else {
                            // Display if no chats exist
                            echo '<div class="message user">No chat yet!</div>';
                        }
                    } else {
                        // If $message array exists, display each message
                        foreach ($message as $msg) {
                            echo '<div class="message user">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</div>';
                        }
                    }
                    ?>
                </div>
                <form method="post" enctype="multipart/form-data">
                <div class="chat-input-container">
                    <input type="text"  id="messageInput" class="form-control" name="message" placeholder="Type your message...">
                    <button class="btn btn-primary" type="submit" name="submit" style="background: #DCD4B6" >Send</button>
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
        
    </body>
</html>
