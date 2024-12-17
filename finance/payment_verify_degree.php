<?php
// Include the database connection file
include('../db.php');

// Fetch Degree students for payment verification, including faculty details
$stmt = $conn->query("SELECT StudentID, FullName, Email, MobileNumber, payment_status, faculty_enrolled FROM tbldegreestudents ORDER BY faculty_enrolled, StudentID DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle payment verification action
if (isset($_GET['action']) && $_GET['action'] == 'verify' && isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Update payment status to 'Verified'
    $stmt = $conn->prepare("UPDATE tbldegreestudents SET payment_status = 'Verified' WHERE StudentID = ?");
    $stmt->execute([$student_id]);

    // Redirect to the same page after verification
    header('Location: payment_verify_degree.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification (Degree Students)</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #8a1d39;
            color: white;
        }

        table td a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        table td a:hover {
            text-decoration: underline;
        }

        .action-btn {
            color: #8a1d39;
            padding: 6px 12px;
            border-radius: 5px;
            background-color: #f9f9f9;
            border: 1px solid #8a1d39;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #8a1d39;
            color: white;
        }

        .faculty-group {
            margin-top: 40px;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .faculty-group h3 {
            margin: 0;
            color: #8a1d39;
        }

        .no-records {
            text-align: center;
            color: #999;
        }

    </style>
</head>

<body>
    <div class="container">
        <h2>Degree Students - Payment Verification</h2>

        <?php
        // Group students by faculty
        $grouped_students = [];
        foreach ($students as $student) {
            $grouped_students[$student['faculty_enrolled']][] = $student;
        }

        // Display groups by faculty
        foreach ($grouped_students as $faculty => $faculty_students):
        ?>
            <div class="faculty-group">
                <h3><?php echo htmlspecialchars($faculty); ?> Faculty</h3>
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($faculty_students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['StudentID']); ?></td>
                                <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($student['Email']); ?></td>
                                <td><?php echo htmlspecialchars($student['MobileNumber']); ?></td>
                                <td><?php echo htmlspecialchars($student['payment_status']); ?></td>
                                <td>
                                    <?php if ($student['payment_status'] == 'Pending'): ?>
                                        <a href="payment_verify_degree.php?action=verify&student_id=<?php echo $student['StudentID']; ?>" class="action-btn" onclick="return confirm('Are you sure you want to verify this payment?')">Verify Payment</a>
                                    <?php else: ?>
                                        Verified
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <?php if (empty($students)): ?>
            <p class="no-records">No students found for payment verification.</p>
        <?php endif; ?>
    </div>

</body>
</html>
