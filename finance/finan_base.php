<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'finance') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with Icons</title>
    <link rel="stylesheet" href="../icons/css/all.css">
    <style>
        .icon-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            height: 50px;
            position: fixed;
            top: 0;
            left: 200px;
            right: 0;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 25px;
            left: 0;
            background-color: #8a1d39;
            z-index: 1;
        }
        .sidebar a i {
    margin-right: 10px; /* Adds space between the icon and the text */
}

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 15px;
            color: white;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
        }
        .sidebar a.active {
            margin-top: 80px;
        }
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 10px 20px; /* Increased padding for better spacing */
            font-size: 16px;
            border-bottom: 2px solid #8a1d39;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            margin-top: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 25px; /* Adjusted gap for better logo alignment */
        }

        .logo img {
            max-width: 65px; /* Adjusted logo size */
            height: auto;
        }

        .university-info {
            display: flex;
            flex-direction: column;
        }

        .university-name {
            font-size: 26px;
            font-weight: bold;
            color: #8a1d39;
            white-space: nowrap;
        }

        .slogan {
            font-size: 14px; 
            color: black; 
            margin-top: 5px;
        }

        .navbar-items {
            display: flex;
            gap: 30px; /* Increased gap between navbar items */
            align-items: center;
        }

        .navbar-items a {
            text-decoration: none;
            color: #8a1d39;
            font-size: 18px;
        }

        .search-container {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .search-container input[type="text"] {
            width: 250px; /* Increased width for larger search box */
            padding: 10px; /* Added padding for a bigger input field */
            border: 1px solid #8a1d39;
            border-radius: 20px;
            outline: none;
            font-size: 16px; /* Increased font size */
            margin-right: 10px; /* Increased space between input and button */
        }

        .search-container input[type="submit"] {
            padding: 8px 15px;
            background-color: #8a1d39;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px; /* Increased font size */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-container input[type="submit"]:hover {
            background-color: #a0344f;
        }

        .notification-container i, .settings-container i {
            font-size: 20px;
            color: #8a1d39;
            cursor: pointer;
        }

        .notification-container .badge {
            background-color: #ff0000;
            color: white;
            padding: 2px 8px;
            border-radius: 50%;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -5px;
        }
.dropdown {
    position: relative;
    display: inline-block;
    margin-right: 10px; /* Adjust this value to control the space between each dropdown */

}
.sidebar a, .sidebar .dropdown {
    margin-bottom: 10px; /* Adds space between all sidebar items */
}

.dropdown .dropdown-toggle {
    text-decoration: none;
    font-size: 16px;
    padding: 10px 15px;
    display: inline-block;
    transition: color 0.3s;
}



.dropdown .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: none; /* Hidden by default */
    list-style: none;
    padding: 0;
    margin: 0; /* Ensure no gap between trigger and menu */
    min-width: 200px;
    z-index: 1000;
}

/* Dropdown Items */
.dropdown .dropdown-menu li {
    padding: 0;
}

.dropdown .dropdown-menu li a {
    display: block;
    text-decoration: none;
    color: #333;
    padding: 10px 15px;
    font-size: 14px;
    transition: background-color 0.3s, color 0.3s;
}

.dropdown .dropdown-menu li a:hover {
    background-color: #8a1d39; /* Highlight background */
    color: #ffffff; /* Highlight text */
}

.dropdown .dropdown-menu li a:hover i {
    color: #ffffff; /* Change icon color on hover */
}
/* Show Dropdown on Hover */
.dropdown:hover .dropdown-menu {
    display: block; /* Keep menu visible on hover */
}

/* Add spacing for dropdown toggle icon */
.dropdown .dropdown-toggle .dropdown-icon {
    margin-left: 8px;
    font-size: 12px;
    color: #333; /* Default icon color */
}



/* Dropdown Menu Styling */
.dropdown .dropdown-menu li a i {
    margin-right: 10px; /* Add space between icon and text */
    color: #8a1d39; /* System color for icons */
}

/* Add spacing for dropdown toggle icon */
.dropdown .dropdown-toggle .dropdown-icon {
    margin-left: 8px;
    font-size: 12px;
    transition: transform 0.3s;
    color: #ffffff; /* Set icon color to white */
}

/* Optional: Change color when hovered */
.dropdown:hover .dropdown-toggle .dropdown-icon {
    color: #ffffff; /* Keep the icon white on hover */
}

    </style>
</head>
<body>
<div class="sidebar">
    <a href="./dashboard_finance.php" class="active">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle">
            <i class="fas fa-plus"></i>Fees Management
        </a>
        <ul class="dropdown-menu">
            <li><a href="./master_fees_management.php"><i class="fas fa-user-plus"></i> Master Fees</a></li>
            <li><a href="./degree_fees_management.php"><i class="fas fa-user-plus"></i> Degree Fees</a></li>
        </ul>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle">
            <i class="fas fa-plus"></i>Payment Verification
        </a>
        <ul class="dropdown-menu">
            <li><a href="./master_payment.php"><i class="fas fa-user-plus"></i> Master's Payment</a></li>
            <li><a href="./degree_payment.php"><i class="fas fa-user-plus"></i> Degree Payment</a></li>
        </ul>
    </div>

    <div class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="fas fa-plus"></i> Payment Status
    </a>
    <ul class="dropdown-menu">
        <li><a href="./master_students_status.php"><i class="fas fa-user-graduate"></i> Master Students</a></li>
        <li><a href="./degree_students_status.php"><i class="fas fa-user-graduate"></i> Degree Students</a></li>
    </ul>
</div>


  
<div class="dropdown">
    <a href="#" class="dropdown-toggle">
        <i class="fas fa-plus"></i> Reports
    </a>
    <ul class="dropdown-menu">
    <li><a href="./faculty_report.php"><i class="fas fa-chalkboard-teacher"></i> Faculty Payment Status</a></li>
<li><a href="./master_report.php"><i class="fas fa-graduation-cap"></i> Master Students Report</a></li>
<li><a href="./degree_report.php"><i class="fas fa-graduation-cap"></i> Degree Students Report</a></li>

    </ul>
</div>
    <a href="../registration/dashboard_regis.php">
        <i class="fas fa-user"></i> Registration
    </a>
    <a href="../logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

    <div class="top-navbar">
        <div class="logo">
            <img src="../image/atulogo_small.jpeg" alt="Logo">
            <div class="university-info">
                <span class="university-name">Abaarso Tech University</span>
                <p class="slogan">Imagine. Inspire. Innovate</p>
            </div>
        </div>

        <div class="navbar-items">
            <a href="index.php">Home</a>
            <a href="../profile.php">Profile</a>
            <a href="faculty_report.php">Report</a>

            <form method="get" action="search.php">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Search..." required>
                    <input type="submit">
                </div>
            </form>

           
        </div>
    </div>
</body>
</html>
