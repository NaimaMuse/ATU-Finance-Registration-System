<?php
// Include the database connection file
include('db.php');

// Initialize variables to avoid undefined variable warnings
$username = $password = $role = "";
$usernameError = $passwordError = $roleError = "";
$valid = false; // Initialize the valid flag to false

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Validate form input
    if (empty($username)) {
        $usernameError = "Username is required.";
    }

    if (empty($password)) {
        $passwordError = "Password is required.";
    }

    if (empty($role)) {
        $roleError = "Role is required.";
    }

    // Proceed with authentication if no errors
    if (empty($usernameError) && empty($passwordError) && empty($roleError)) {
        // Prepare SQL query to check if the user exists in the database
        $stmt = $conn->prepare("SELECT * FROM tblusers WHERE username = :username AND role = :role");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            // User exists, now verify password
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                session_start(); // Start the session
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect based on the role
                if ($role === 'registration') {
                    header("Location: registration/dashboard_regis.php");
                } elseif ($role === 'finance') {
                    header("Location: finance/dashboard_finance.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                // Invalid password
                $passwordError = "Invalid password.";
            }
        } else {
            // No user found with that username and role
            $usernameError = "No user found with that username and role.";
        }
    }
}

$conn = null; // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            font-size: 13px;
        }
        .container {
            display: flex;
            width: 800px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .left-panel, .right-panel {
            width: 50%;
            padding: 40px;
        }
        .left-panel {
            background-color: #ffffff;
        }
        .left-panel h2 {
            color: #8a1d39;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-signin {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #8a1d39, #f06292);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .btn-signin:hover {
            background: linear-gradient(45deg, #f06292, #8a1d39);
        }
        .extra-options {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-top: 10px;
        }
        .extra-options a {
            color: #8a1d39;
            text-decoration: none;
        }
        .extra-options a:hover {
            text-decoration: underline;
        }
        .right-panel {
            background: linear-gradient(45deg, #f06292, #8a1d39);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .right-panel h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .right-panel p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .btn-signup {
            padding: 10px 20px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        .btn-signup:hover {
            background-color: white;
            color: #8a1d39;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h2>Log In</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span style="color:red;"><?php echo $usernameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <span style="color:red;"><?php echo $passwordError; ?></span>
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="">Select Role</option>
                        <option value="finance" <?php echo ($role == 'finance') ? 'selected' : ''; ?>>Finance</option>
                        <option value="registration" <?php echo ($role == 'registration') ? 'selected' : ''; ?>>Registration</option>
                    </select>
                    <span style="color:red;"><?php echo $roleError; ?></span>
                </div>

                <button type="submit" class="btn-signin">Log In</button>
                <div class="extra-options">
                    <label><input type="checkbox" name="remember"> Remember Me</label>
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>

        <div class="right-panel">
            <h2>Welcome to Login</h2>
            <p>Don't have an account?</p>
            <a href="singup.php" class="btn-signup">Sign Up</a>
        </div>
    </div>
</body>
</html>
