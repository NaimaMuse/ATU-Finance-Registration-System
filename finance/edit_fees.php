<?php
// Include the database connection file
include('../db.php');

// Handle fee update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_fee'])) {
    $fee_id = $_POST['fee_id'];
    $program_name = $_POST['program_name'];
    $fee_amount = $_POST['fee_amount'];
    $due_date = $_POST['due_date'];

    // Update the fee in the database
    $stmt = $conn->prepare("UPDATE tblfees SET program_name = ?, fee_amount = ?, due_date = ? WHERE id = ?");
    $stmt->execute([$program_name, $fee_amount, $due_date, $fee_id]);

    // Redirect to fees management page after update
    header('Location: fees_management.php');
    exit();
}

// Fetch the fee details to pre-fill the form
if (isset($_GET['id'])) {
    $fee_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tblfees WHERE id = ?");
    $stmt->execute([$fee_id]);
    $fee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fee) {
        echo "Fee not found!";
        exit();
    }
} else {
    echo "Invalid fee ID!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fee</title>
    <style>
        /* General styles for the entire website */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            color: #8a1d39; /* Your color */
        }

        /* Fees Management Section Styles */
        .fees-management-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .fees-management-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .fees-management-container form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .fees-management-container form input[type="text"],
        .fees-management-container form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .fees-management-container form input[type="submit"] {
            background-color: #8a1d39; /* Your color */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .fees-management-container form input[type="submit"]:hover {
            background-color: #f06292; /* Lighter pink */
        }
    </style>
</head>
<body>
    <div class="fees-management-container">
        <h2>Edit Fee</h2>
        <form method="POST" action="edit_fees.php">
            <input type="hidden" name="fee_id" value="<?php echo htmlspecialchars($fee['id']); ?>">
            
            <label for="program_name">Program Name:</label><br>
            <input type="text" id="program_name" name="program_name" value="<?php echo htmlspecialchars($fee['program_name']); ?>" required><br><br>

            <label for="fee_amount">Fee Amount:</label><br>
            <input type="text" id="fee_amount" name="fee_amount" value="<?php echo htmlspecialchars($fee['fee_amount']); ?>" required><br><br>

            <label for="due_date">Due Date:</label><br>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($fee['due_date']); ?>" required><br><br>

            <input type="submit" name="update_fee" value="Update Fee">
        </form>
    </div>
</body>
</html>
