<?php
// Include the database connection
include('../db.php');
include('finan_base.php');

// Fetch students grouped by faculty and payment status
$sql = "SELECT * FROM tbldegreestudents WHERE faculty_enrolled IS NOT NULL";
$stmt = $conn->query($sql);

$paidStudents = [];
$notPaidStudents = [];

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['payment_status'] === 'Paid') {
            $paidStudents[$row['faculty_enrolled']][] = $row;
        } else {
            $notPaidStudents[$row['faculty_enrolled']][] = $row;
        }
    }
} else {
    echo "No students found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degree Students Payment Status</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 75%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-left:220px;
            margin-top:100px;
        }
        h1 {
            text-align: center;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            padding: 10px;
            border-radius: 4px;
            text-align:center;
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
    </style>
</head>
<body>

<div class="container">
    <h1>Degree Students Payment Status</h1>

    <!-- Paid Students Section -->
    <div class="section">
        <h2 class="section-title">Verified Students</h2>
        <?php if (!empty($paidStudents)): ?>
            <?php foreach ($paidStudents as $faculty => $students): ?>
                <h3><?php echo htmlspecialchars($faculty); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['StudentID']); ?></td>
                                <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($student['Email']); ?></td>
                                <td><?php echo htmlspecialchars($student['MobileNumber']); ?></td>
                                <td><?php echo htmlspecialchars($student['registration_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No paid students found.</p>
        <?php endif; ?>
    </div>

    <!-- Not Paid Students Section -->
    <div class="section">
        <h2 class="section-title">UnVerified Students</h2>
        <?php if (!empty($notPaidStudents)): ?>
            <?php foreach ($notPaidStudents as $faculty => $students): ?>
                <h3><?php echo htmlspecialchars($faculty); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['StudentID']); ?></td>
                                <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($student['Email']); ?></td>
                                <td><?php echo htmlspecialchars($student['MobileNumber']); ?></td>
                                <td><?php echo htmlspecialchars($student['registration_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No unpaid students found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
