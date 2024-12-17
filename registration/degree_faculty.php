<?php
include('../db.php');

$error_message = '';

// Handle Add Faculty Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faculty'])) {
    $faculty_name = trim($_POST['faculty_name']);
    $price = trim($_POST['price']);
    
    // Validate inputs
    if (empty($faculty_name) || empty($price) || !is_numeric($price) || $price <= 0) {
        $error_message = 'Please fill in all fields correctly. Faculty name cannot be empty, and price must be a valid number greater than zero.';
    } else {
        // Insert new faculty into the database
        $sql = 'INSERT INTO tbldegreefaculty (faculty_name, price) VALUES (:faculty_name, :price)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':faculty_name', $faculty_name);
        $stmt->bindParam(':price', $price);
        
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_message = 'Error adding faculty.';
        }
    }
}

// Handle Update Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $faculty_id = $_POST['faculty_id'];
    $faculty_name = trim($_POST['faculty_name']);
    $price = trim($_POST['price']);

    if (empty($faculty_name) || empty($price) || !is_numeric($price) || $price <= 0) {
        $error_message = 'Please fill in all fields correctly.';
    } else {
        $sql = 'UPDATE tbldegreefaculty SET faculty_name = :faculty_name, price = :price WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':faculty_name', $faculty_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $faculty_id);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error_message = 'Error updating faculty.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $sql = 'DELETE FROM tbldegreefaculty WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $delete_id);
    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error_message = 'Error deleting faculty.';
    }
}


// Fetch existing faculties
$sql = 'SELECT * FROM tbldegreefaculty';
$stmt = $conn->query($sql);
$faculties = $stmt->fetchAll();

// Fetch a specific faculty to update if requested
$faculty_to_update = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $update_id = $_POST['update_id'];
    $sql = 'SELECT * FROM tbldegreefaculty WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $update_id);
    $stmt->execute();
    $faculty_to_update = $stmt->fetch();
}
include('./dash_base.php');

?>

<style>
    h2 {
        color: #8a1d39;
        margin-top: 100px;
        margin-left: 250px;
    }
    table {
        border-collapse: collapse;
        width: 60%;
        margin-left: 240px;
        background-color: white;
        border: 2px solid #8a1d39;
        margin-top: 40px;
    }
    th {
        background-color: #8a1d39;
        color: white;
        padding: 12px;
        border: 1px solid #dee2e6;
    }
    td {
        padding: 12px;
        text-align: left;
        border: 1px solid #dee2e6;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .form-container {
        width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #e0e0e0;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .form-container input[type="text"], .form-container input[type="number"] {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-container button {
        background-color: #8a1d39;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        border-radius:20px;
    }
    
    .form-container button:hover {
        background-color: #a62e52;
    }
    .error-message {
        color: red;
        margin-bottom: 20px;
        font-weight: bold;
        margin-left: 240px;
    }
</style>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Degree Faculties</h2>

        <!-- Display error message if there's any -->
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Add New Faculty Form -->
        <div class="form-container">
            <h3>Add New Faculty</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="faculty_name">Faculty Name:</label>
                    <input type="text" id="faculty_name" name="faculty_name" value="<?php echo isset($_POST['faculty_name']) ? htmlspecialchars($_POST['faculty_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" min="0" step="any" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                </div>
                <button type="submit" name="add_faculty">Add Faculty</button>
            </form>
        </div>

        <!-- Update Faculty Form -->
        <?php if ($faculty_to_update): ?>
            <div class="form-container">
                <h3>Update Faculty</h3>
                <form method="post" action="">
                    <input type="hidden" name="faculty_id" value="<?php echo $faculty_to_update['id']; ?>">
                    <div class="form-group">
                        <label for="faculty_name">Faculty Name:</label>
                        <input type="text" id="faculty_name" name="faculty_name" value="<?php echo htmlspecialchars($faculty_to_update['faculty_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" id="price" name="price" min="0" step="any" value="<?php echo htmlspecialchars($faculty_to_update['price']); ?>">
                    </div>
                    <button type="submit" name="update">Save Changes</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Display Faculties Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-danger text-white">
                    <tr>
                        <th>Faculty Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($faculties as $faculty): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($faculty['faculty_name']); ?></td>
                            <td><?php echo htmlspecialchars($faculty['price']); ?>$</td>
                            <td>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="update_id" value="<?php echo $faculty['id']; ?>">
                                    <a href="update_deg_faculty.php?id=<?php echo $faculty['id']; ?>" 
                                       style="background-color: #8a1d39; color: white; text-decoration: none; 
                                              display: inline-block; width: 90px; height: 35px; 
                                              line-height: 35px; text-align: center; 
                                              border-radius: 20px; cursor: pointer;">
                                       Update
                                    </a>
                                </form>
                                <!-- Delete Button -->
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $faculty['id']; ?>">
                                    <button type="submit" name="delete" 
                                            style="background-color: #8a1d39; color: white; border: none; 
                                                   padding: 10px 20px; border-radius: 20px; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                    </table>

        </div>
    </div>
</body>
</html>
