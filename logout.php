<?php
session_start();

// Check if logout is requested
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy all session data
    $_SESSION = [];
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit;
}

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-cancel, .btn-logout {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(45deg, #8a1d39, #f06292);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s, transform 0.3s;
            margin: 5px;
        }

        .btn-cancel:hover, .btn-logout:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #f06292, #8a1d39);
        }

        .alert {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Do you want to logout?</h1>
    <p>
        Hello, 
        <?php 
        // Display the username of the logged-in user or "Guest" if not logged in
        if (isset($_SESSION['username'])) {
            echo htmlspecialchars($_SESSION['username']);
        } else {
            echo "Guest";
        }
        ?>!
    </p>
    
    <div class="button-container">
        <!-- Cancel button redirects to the home page -->
        <a href="index.php" class="btn-cancel">Cancel</a>
        
        <!-- Logout button triggers the logout action -->
        <a href="logout.php?action=logout" class="btn-logout">Logout</a>
    </div>
</body>
</html>
