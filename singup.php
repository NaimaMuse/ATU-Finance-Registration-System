<?php
include 'db.php';
session_start();

$username = $password = $role = "";
$usernameError = $passwordError = $roleError = "";
$valid = true;

// Process form data if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $usernameError = "Username is required.";
        $valid = false; // Mark as invalid
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $passwordError = "Password is required.";
        $valid = false; // Mark as invalid
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $roleError = "Role is required.";
        $valid = false; // Mark as invalid
    } else {
        $role = trim($_POST["role"]);
    }

    // Process form data if valid
    if ($valid) {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM tblusers WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usernameError = "Username already taken.";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO tblusers (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                // Set session variable for logged-in user
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;  // Storing the username in session

                // Redirect to login page with success message
                header("Location: login.php?success=1");
                exit;
            } else {
                echo "Something went wrong. Please try again.";
            }
        }
    }
}

// Close database connection
$conn = null; // Close the connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
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
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            font-size: 14px;
        }

        .container {
            display: flex;
            width: 900px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            flex-wrap: wrap;
        }

        .left-panel, .right-panel {
            width: 50%;
            padding: 40px;
        }

        .left-panel {
            background-color: #fff;
        }

        .left-panel h2 {
            color: #8a1d39;
            font-size: 24px;
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

        .btn-signup {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #8a1d39, #f06292);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-signup:hover {
            background: linear-gradient(45deg, #f06292, #8a1d39);
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        .right-panel {
            background: linear-gradient(45deg, #f06292, #8a1d39);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .right-panel h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .right-panel p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .right-panel .btn-signup {
            padding: 12px 25px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .right-panel .btn-signup:hover {
            background-color: white;
            color: #8a1d39;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 100%;
            }

            .left-panel, .right-panel {
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h2>Create a New Account</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                    <span class="error-message"><?php echo $usernameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-message"><?php echo $passwordError; ?></span>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="finance" <?php echo ($role == "finance" ? "selected" : ""); ?>>Finance</option>
                        <option value="registration" <?php echo ($role == "registration" ? "selected" : ""); ?>>Registration</option>
                    </select>
                    <span class="error-message"><?php echo $roleError; ?></span>
                </div>
                
                <button type="submit" class="btn-signup">Sign Up</button>
            </form>
            <?php if (isset($_GET['success'])): ?>
                <p style="color: green;">Registration successful! You can now <a href="login.php">log in</a>.</p>
            <?php endif; ?>
        </div>

        <div class="right-panel">
            <h2>Welcome!</h2>
            <p>Already have an account? <a href="login.php" class="btn-signup">Login</a></p>
        </div>
    </div>
</body>
</html>
