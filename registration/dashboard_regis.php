<?php
include('./dash_base.php');
include('../db.php');
session_start();

// Check if the user is logged in and has the correct role (registration)
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'registration') {
    // If not logged in or not the correct role, redirect to the login page
    header("Location: ../login.php");
    exit;
}


$stmtmasterstudent = $conn->query("SELECT COUNT(*) FROM tblmasterstudents");
$totalmaster = $stmtmasterstudent->fetchColumn();

$stmtdegreestudent = $conn->query("SELECT COUNT(*) FROM tbldegreestudents");
$totaldegrees = $stmtdegreestudent->fetchColumn();

$stmtdegreefaculty = $conn->query("SELECT COUNT(*) FROM tbldegreefaculty");
$totaldegreesfaculty = $stmtdegreefaculty->fetchColumn();

$stmtmasterfaculty = $conn->query("SELECT COUNT(*) FROM tblmasterfaculty");
$totalmasterfaculty = $stmtmasterfaculty->fetchColumn();

// Fetch recent master students
$stmtMaster = $conn->query("
    SELECT FullName, registration_date 
    FROM tblmasterstudents 
    ORDER BY registration_date DESC 
    LIMIT 2
");
$masterRegistrations = $stmtMaster->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent degree students
$stmtDegree = $conn->query("
    SELECT FullName, registration_date 
    FROM tbldegreestudents 
    ORDER BY registration_date DESC 
    LIMIT 2
");
$degreeRegistrations = $stmtDegree->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<style>
    body { 
    background-color: rgb(238, 238, 238); 
}

.main-content { 
    margin-left: 190px; 
    padding: 20px;  
}

h1 {
    margin-top: 30px;
    text-align: center; 

}

.dashboard-cards { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
    gap: 10px; 
    margin-bottom: 20px; 
}

.card { 
    background-color: #ffffff; 
    border-radius: 8px; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    padding: 10px; 
    text-align: center; 
    transition: transform 0.2s; 
}

.card h2 { 
    font-size: 18px; 
    margin-bottom: 10px; 
    color: #333; 
    margin-top: 30px;
}

.card p { 
    font-size: 24px; 
    font-weight: bold; 
    color: #8a1d39; 
}

.card i { 
    color: #8a1d39; 
}

.card:hover { 
    transform: scale(1.05); 
}

.actions { 
    margin: 20px 0; 
}

.actions a { 
    text-decoration: none; 
}

.actions h2 { 
    margin-bottom: 10px; 
}

.action-buttons { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 20px; 
}

.action-button { 
    background-color: #f51661; 
    color: white; 
    border: none; 
    border-radius: 5px; 
    padding: 10px 20px; 
    cursor: pointer; 
    font-size: 16px; 
    transition: background-color 0.3s; 
}

.action-button i { 
    margin-right: 5px; 
}

.action-button:hover { 
    background-color: #a83b54; 
}

.recent-activities { 
    background-color: #ffffff; 
    padding: 20px; 
    border-radius: 8px; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
}

.recent-activities h2 { 
    font-size: 20px; 
    margin-bottom: 10px; 
}

.recent-activities ul { 
    list-style-type: none; 
    padding: 0; 
}

.recent-activities li { 
    padding: 10px 0; 
    border-bottom: 1px solid #ddd; 
}

.recent-activities li span { 
    color: #888; 
    font-size: 12px; 
    float: right; 
}

</style>
<div class="main-content">
    <h1>Regisrtation Dashboard</h1>
    
    <div class="dashboard-cards">
        <div class="card">
            <h2>Total Master Students</h2>
            <p><?php echo htmlspecialchars($totalmaster); ?></p>
            <i class="fas fa-graduation-cap fa-2x"></i>
       
        </div>
        <div class="card">
            <h2>Total Degree Students</h2>
            <p><?php echo htmlspecialchars($totaldegrees); ?></p>
            <i class="fas fa-user-plus fa-2x"></i>
        </div>
        <div class="card">
            <h2>Total Degree Faculty</h2>
            <p><?php echo htmlspecialchars($totaldegreesfaculty); ?></p>
            <i class="fas fa-book fa-2x"></i>
            </div>
        <div class="card">
            <h2>Total Master Faculty</h2>
            <p><?php echo htmlspecialchars($totalmasterfaculty); ?></p> 

            <i class="fas fa-user-graduate fa-2x"></i>
            </div>
    </div>

    <div class="actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="master_regis.php" class="action-button"><i class="fas fa-user-plus"></i> Add Master Registration</a>
            <a href="degree_regis.php" class="action-button"><i class="fas fa-user-plus"></i> Add Degree Registration</a>
            <a href="master_students.php" class="action-button"><i class="fas fa-users"></i> List of Master Students</a>
            <a href="degree_students.php" class="action-button"><i class="fas fa-users"></i> List of Degree Students</a>
        </div>
    </div>

    <div class="recent-activities">
    <h2>Recent Activities</h2>

    <div class="recent-master-students">
        <h3>Recently Registered Master Students</h3>
        <ul>
            <?php if (!empty($masterRegistrations)): ?>
                <?php foreach ($masterRegistrations as $registration): ?>
                    <li>
                        <?php echo htmlspecialchars($registration['FullName']); ?> - 
                        <span><?php echo date('F j, Y, g:i a', strtotime($registration['registration_date'])); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No recent master student registrations found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="recent-degree-students">
        <h3>Recently Registered Degree Students</h3>
        <ul>
            <?php if (!empty($degreeRegistrations)): ?>
                <?php foreach ($degreeRegistrations as $registration): ?>
                    <li>
                        <?php echo htmlspecialchars($registration['FullName']); ?> - 
                        <span><?php echo date('F j, Y, g:i a', strtotime($registration['registration_date'])); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No recent degree student registrations found.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>


</body>
</html>
