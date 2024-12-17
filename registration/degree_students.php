
<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <style>
        h2 {
            color: #8a1d39;
            margin-top: 100px;
            text-align: center;
        }
        .table-container {
            margin-left: 220px;
            width: 83%;
        }
        .table {
            width: 96%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f51661;
            color: #FFFFFF;
        }
        .table td, .table th {
            padding: 12px;
            vertical-align: middle;
            border: 1px solid #E2E8F0;
        }
        .view-button {
            background-color: #8a1d39;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
        }
        .view-button:hover {
            background-color: #a52a45;
            transform: scale(1.05);
        }
        .view-button:active {
            transform: scale(0.95);
        }
        .custom-alert {
    padding: 15px;
    margin: 20px 0;
    border: 1px solid #8a1d39; /* Border color matching your theme */
    background-color: #f2dede; /* Light red background */
    color: #a94442; /* Dark red text */
    border-radius: 5px; /* Rounded corners */
    position: relative; /* Position relative for absolute child positioning */
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    color: #8a1d39; /* Match this to your theme */
    font-size: 20px; /* Size of the close icon */
    cursor: pointer; /* Cursor pointer on hover */
}

.close-btn:hover {
    color: #c82333; /* Darker shade on hover */
}

    </style>
</head>
<body>

<h2>Registered Students</h2>

<div class="table-container">
    
    <?php
    include('./dash_base.php');
    include('../db.php');

    $sql = "SELECT * FROM tbldegreestudents ORDER BY faculty_enrolled";
    $stmt = $conn->query($sql);
    $students = $stmt->fetchAll();

    $studentsByFaculty = [];
    foreach ($students as $student) {
        $faculty = $student['faculty_enrolled'];
        $studentsByFaculty[$faculty][] = $student;
    }

    foreach ($studentsByFaculty as $faculty => $students) : ?>
        <h3><?php echo htmlspecialchars($faculty); ?> Faculty</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Date Of Birth</th>
                    <th>Date registered</th>
                    <th>Faculty</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['FullName']); ?></td>
                        <td><?php echo htmlspecialchars($student['DateOfBirth']); ?></td>
                        <td><?php echo htmlspecialchars($student['registration_date']); ?></td>
                        <td><?php echo htmlspecialchars($student['faculty_enrolled']); ?></td>
                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

</body>
</html>
