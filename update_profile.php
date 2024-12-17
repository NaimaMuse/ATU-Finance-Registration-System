<?php
session_start(); // Start the session
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors on the screen

include 'db.php'; // Ensure this file connects to your database

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Set the username from session
$username = $_SESSION['username'];

try {
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        $updateQuery = "UPDATE tblusers SET date_of_birth = :dob, email = :email, phone_number = :phone, address = :address WHERE username = :username";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':dob', $dob);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':phone', $phone);
        $updateStmt->bindParam(':address', $address);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->execute();
        header('Location: profile.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1 {
            color: #8a1d39;
            margin-bottom: 20px;
            text-align: left;
        }
        .form-container {
            max-width: 500px;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(45deg, #8a1d39, #f06292);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            display: block;
            width: 100%;
        }
        button:hover {
            background: linear-gradient(45deg, #f06292, #8a1d39);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Update Profile</h1>
        <form method="post" action="">
            <p>Date of Birth: <input type="date" name="dob" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required></p>
            <p>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required></p>
            <p>Phone Number: <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required></p>
            <p>Address: <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required></p>
            <button type="submit">Update</button>

        </form>
    </div>
</body>
</html>
