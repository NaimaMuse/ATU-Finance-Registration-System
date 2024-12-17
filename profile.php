<?php
session_start();
include 'db.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Set the username from session
$username = $_SESSION['username'];

try {
    // Fetch user details from the database
    $query = "SELECT * FROM tblusers WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #8a1d39;
            text-align: center;
            margin-top: 50px;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin: 0 auto;
            text-align: center;
        }

        .profile-container:hover {
            transform: scale(1.02);
            transition: transform 0.3s;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }

        .a1 {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(45deg, #8a1d39, #f06292);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        .a1:hover {
            background: linear-gradient(45deg, #f06292, #8a1d39);
            transform: scale(1.05);
        }

    </style>
</head>
<body>

<div class="profile-container">
    <h1>User Profile</h1>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['date_of_birth']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    <a class="a1" href="update_profile.php">Update Profile</a>
    
</div>

</body>
</html>
