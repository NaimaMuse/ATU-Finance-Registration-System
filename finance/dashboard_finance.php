<?php
include('finan_base.php');
include('../db.php');
session_start();
ob_start();




$sql = "SELECT COUNT(*) AS verified_master_students 
        FROM tblmasterstudents 
        WHERE payment_status = 'Verified'";
$stmt = $conn->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$verifiedMasterStudents = $row['verified_master_students'];

$sql = "SELECT COUNT(*) AS verified_degree_students 
        FROM tbldegreestudents 
        WHERE  payment_status = 'paid'";
$stmt = $conn->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$verifieddegreeStudents = $row['verified_degree_students'];



$sqlMaster = "SELECT FullName, registration_date 
              FROM tbldegreestudents 
              WHERE payment_status  = 'Paid'
              ORDER BY registration_date DESC 
              LIMIT 3";
$stmtMaster = $conn->query($sqlMaster);

// Query for recently registered Degree students (limit to 3 most recent)
$sqlDegree = "SELECT FullName, registration_date 
              FROM tbldegreestudents 
              WHERE faculty_enrolled = 'Degree' 
              ORDER BY registration_date DESC 
              LIMIT 3";
$stmtDegree = $conn->query($sqlDegree);


// Check if the user is logged in and has the correct role (finance)
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'finance') {
    // If not logged in or not the correct role, redirect to the login page
    header("Location: ../login.php");
    exit;
}
ob_end_flush();

?>



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
text-align:center;
}
.dashboard-cards { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
    gap: 10px; 
    margin-bottom: 10px; 
}

.card { 
    background-color: #ffffff; 
    border-radius: 8px; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    padding: 10px; 
    text-align: center; 
    transition: transform 0.2s; 
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
    margin-left:200px;
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
    margin-left:200px;

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
    <h1>Finance Dashboard</h1>
    
    <div class="dashboard-cards">
    <div class="card">
        <h3>Master Students Verified</h3>
        <p><?php echo $verifiedMasterStudents; ?></p> <!-- Display the count -->
        <i class="fas fa-user-check fa-2x"></i> <!-- Changed to user-check icon -->
    </div>
    <div class="card">
        <h3> Degree Students Verified</h3>
        <p><?php echo $verifieddegreeStudents; ?></p> <!-- Display the count -->
        <i class="fas fa-user-graduate fa-2x"></i> <!-- Changed to user-graduate icon -->
    </div>
    <div class="card">
        <h3>Fee Management</h3>
        <p>Access</p>
        <i class="fas fa-credit-card fa-2x"></i> <!-- Changed to credit-card icon -->
    </div>
    <div class="card">
        <h3>Reports</h3>
        <p>Generate</p>
        <i class="fas fa-chart-line fa-2x"></i> <!-- Changed to chart-line icon -->
    </div>
</div>

        </div>
    </div>
</div>

<div class="actions">
    <h2>Quick Actions</h2>
    <div class="action-buttons">
    <a href="master_students_status.php" class="action-button"><i class="fas fa-user-check"></i> Master Students Status</a>
<a href="degree_students_status.php" class="action-button"><i class="fas fa-user-graduate"></i> Degree Students status </a>
<a href="degree_fees_management.php" class="action-button"><i class="fas fa-wallet"></i> Manage Master Fees</a>
<a href="master_fees_management.php" class="action-button"><i class="fas fa-wallet"></i> Manage Degree Fees</a>

    </div>
</div>
<div class="recent-activities">
    <h2>Recent Activities</h2>

    <!-- Recently Paid Master Students -->
    <div class="recent-master-students">
        <h3>Recently Paid Master Students</h3>
        <ul>
            <?php while ($rowMaster = $stmtMaster->fetch(PDO::FETCH_ASSOC)) { ?>
                <li><?php echo htmlspecialchars($rowMaster['FullName']); ?> - <span><?php echo date('F d, Y, h:i A', strtotime($rowMaster['registration_date'])); ?></span></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Add other sections for other student categories if necessary -->
</div>

</body>
</html>