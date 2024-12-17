<?php
require '../db.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$students = []; 

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searched = '%' . $_GET['search'] . '%'; 
    try {
        $sql = '
            SELECT 
                StudentID, 
                FullName, 
                faculty_enrolled, 
                payment_status, 
                registration_date, 
                "Degree" AS student_type 
            FROM tbldegreestudents 
            WHERE FullName LIKE :searched OR StudentID LIKE :searched OR registration_date LIKE :searched
            UNION ALL
            SELECT 
                StudentID, 
                FullName, 
                faculty_enrolled, 
                payment_status, 
                registration_date, 
                "Master" AS student_type 
            FROM tblmasterstudents 
            WHERE FullName LIKE :searched OR StudentID LIKE :searched OR registration_date LIKE :searched
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':searched', $searched);
        $stmt->execute();
        $students = $stmt->fetchAll();
    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
            margin-bottom: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #8a1d39; 
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-results {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Search Results</h1>
    <div class="row justify-content-center">
        <div class="m-4">
            <?php if (count($students) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Faculty Enrolled</th>
                            <th>Payment Status</th>
                            <th>Registration Date</th>
                            <th>Student Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['StudentID']); ?></td>
                                <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($student['faculty_enrolled']); ?></td>
                                <td><?php echo htmlspecialchars($student['payment_status']); ?></td>
                                <td><?php echo htmlspecialchars($student['registration_date']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_type']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">No results found for your search.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
