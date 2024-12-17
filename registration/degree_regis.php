<?php
// session_start();
// ob_start(); // Start output buffering
include('../db.php'); 
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $date = $_POST['date'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $faculty = $_POST['faculty'] ?? '';

    if (!$name) {
        $errors[] = "Name is required.";
    }

    if (!$date) {
        $errors[] = "Date of birth is required.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
    }

    if (!$address) {
        $errors[] = "Address is required.";
    }

    if (!$email) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!$phone) {
        $errors[] = "Phone number is required.";
    }

    if (!$faculty) {
        $errors[] = "Faculty is required.";
    }

    if (empty($errors)) {
        $checkQuery = "SELECT * FROM tbldegreestudents WHERE Email = :email OR MobileNumber = :phone";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = "A student with this email or phone number already exists.";
        }
    }

    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO tbldegreestudents (FullName, DateOfBirth, Address, Email, MobileNumber, faculty_enrolled)
                    VALUES (:name, :date, :address, :email, :phone, :faculty)';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':faculty', $faculty);
            $stmt->execute();
            header('Location:./degree_students.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
ob_end_flush(); // Flush the output buffer and turn off output buffering
include('./dash_base.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Registration Form</title>
    <style>
        body {
            background-color: rgb(238, 238, 238);
        }
        .container {
            width: 850px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 40px;
            margin-left: 260px;
            position: absolute;
            top:80px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-messages {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
            margin-right: 10px;
        }
        .form-group:last-child {
            margin-right: 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .faculty-selection {
            margin-top: 20px;
        }
        .faculty-selection h4 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size:17px;
        }
        .col-md-6 {
            flex: 0 0 48%; /* Two columns, each taking up 48% of the width */
            margin-bottom: 10px; /* Add some space between the rows */
        }
        button {
            padding: 10px 20px;
            background-color: #8a1d39;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: auto;
            font-size: 16px;
        }
        button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h1>Master Registration Form</h1>

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" >
                </div>
                <div class="form-group">
                    <label for="date">Date Of Birth:</label>
                    <input type="date" id="date" name="date" value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>" >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>" >
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tel">Mobile Phone:</label>
                    <input type="tel" id="tel" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" >
                </div>
            </div>

            <div class="faculty-selection">
                <h4>Course Enrolled (Please tick):</h4>
                <div class="row">
                    <div class="col-md-6">
                        <input type="radio" name="faculty" value="Master in project planning & management">BSc in Midwife<br>
                        <input type="radio" name="faculty" value="BSc in Public Health"> BSc in Public Health<br>
                        <input type="radio" name="faculty" value="BSc in Nursing"> BSc in Nursing<br>
                        <input type="radio" name="faculty" value="BSc in Clinical Laboratory"> BSc in Clinical Laboratory<br>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" name="faculty" value="BSc in Software Engineering"> BSc in Software Engineering<br>
                        <input type="radio" name="faculty" value="BSc in Business Management"> BSc in Business Management<br>
                        <input type="radio" name="faculty" value="BSc in Medicine and Surgery"> BSc in Medicine and Surgery<br>
                        <input type="radio" name="faculty" value="Bachelor Laws"> Bachelor Laws<br>
                    </div>
                </div>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
