<?php
// Include the database connection
include('../db.php');
include('finan_base.php');

// Check if an action is requested
if (isset($_POST['action']) && isset($_POST['student_id'])) {
    $studentID = $_POST['student_id'];
    $newStatus = $_POST['action'] === 'verify' ? 'Verified' : 'Unverified';

    // Update payment status in the database
    $sql = "UPDATE tbldegreestudents SET payment_status = :payment_status WHERE StudentID = :studentID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':payment_status', $newStatus);
    $stmt->bindParam(':studentID', $studentID);

    if ($stmt->execute()) {
        echo "Payment status updated successfully";
    } else {
        echo "Error updating payment status";
    }
}

// Fetch students from tblmasterstudents
$sql = "SELECT * FROM tbldegreestudents";
$stmt = $conn->query($sql); // Execute the query

// Group students by faculty
$studentsByFaculty = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $faculty = $row['faculty_enrolled'];
        $studentsByFaculty[$faculty][] = $row;
    }
} else {
    echo "No records found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Payment Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-left:220px;
            margin-top:110px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        table th {
            background-color: #8a1d39;
            color: #fff;
        }
      
        .actions button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .verify-btn {
            background-color: #28a745;
        }
        .reject-btn {
            background-color: #dc3545;
        }
        .verify-btn:disabled, .reject-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .faculty-section {
            margin-bottom: 40px;
        }
        .faculty-title {
            background-color: #8a1d39;
            color: #fff;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Degree Payment Verification</h1>

        <!-- Loop through each faculty and display students -->
        <?php foreach ($studentsByFaculty as $faculty => $students): ?>
            <div class="faculty-section">
                <h2 class="faculty-title"><?php echo htmlspecialchars($faculty); ?></h2>

                <!-- Table displaying student details for each faculty -->
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['StudentID']); ?></td>
                                <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($student['Address']); ?></td>
                                <td><?php echo htmlspecialchars($student['Email']); ?></td>

                                <!-- Display the payment status directly from the database -->
                                <td><?php echo htmlspecialchars($student['payment_status']); ?></td>
                                <td class="actions">
                                    <!-- Conditional buttons based on payment status -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['StudentID']); ?>" />
                                        <?php if ($student['payment_status'] === 'Unpaid'): ?>
                                            <button type="submit" name="action" value="verify" class="verify-btn">Verify </button>
                                            <button type="submit" name="action" value="reject" class="reject-btn">Reject </button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="verify" class="verify-btn" >Verified</button>
                                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
