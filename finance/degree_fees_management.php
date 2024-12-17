<?php
include('../db.php');
include('finan_base.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_fee'])) {
    $faculty_name = trim($_POST['faculty_name']);
    $price = trim($_POST['price']);

    if (empty($faculty_name)) {
        $error_message .= 'Faculty Name is required.<br>';
    }

    if (empty($price)) {
        $error_message .= 'Price is required.<br>';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error_message .= 'Price must be a positive number.<br>';
    }

    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO tbldegreefaculty (faculty_name, price) VALUES (?, ?)");
        $stmt->execute([$faculty_name, $price]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $fee_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tbldegreefaculty WHERE id = ?");
    $stmt->execute([$fee_id]);

 
}
$stmt = $conn->prepare("SELECT * FROM tbldegreefaculty ORDER BY faculty_name");
$stmt->execute();
$fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h2, h3 {
        color: #8a1d39; 
    }

    .fees-management-container {
        width: 65%;
        margin: 0 auto;
        padding: 20px;
        margin-left:230px;
        margin-top:50px;
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
    .fees-management-container form input[type="number"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .fees-management-container form input[type="submit"] {
        background-color: #8a1d39; 
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .fees-management-container form input[type="submit"]:hover {
        background-color: #f06292; 
    }

    .fees-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .fees-table, th, td {
        border: 1px solid #ddd;
    }

    .fees-table th, .fees-table td {
        padding: 12px;
        text-align: left;
    }

    .fees-table th {
        background-color: #8a1d39; 
        color: white;
    }

    .fees-table td a {
        color: #8a1d39; 
        text-decoration: none;
    }

    .fees-table td a:hover {
        text-decoration: underline;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        font-size: 14px;
    }
</style>

<div class="fees-management-container">
    <h2>Degree Fees Management</h2>

    <h3>Add New Fee</h3>

    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="degree_fees_management.php">
        <label for="faculty_name">Faculty Name:</label><br>
        <input type="text" id="faculty_name" name="faculty_name" value="<?php echo isset($faculty_name) ? htmlspecialchars($faculty_name) : ''; ?>"><br><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>"><br><br>

        <input type="submit" name="add_fee" value="Add Fee">
    </form>

    <h3>Existing Fee Structures</h3>
    <table class="fees-table">
        <thead>
            <tr>
                <th>Faculty Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fees as $fee): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fee['faculty_name']); ?></td>
                    <td><?php echo htmlspecialchars($fee['price']); ?></td>
                   <td>
                        <a href="degree_fees_management.php?id=<?php echo $fee['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this fee?')">Delete</a>
                        |
                        <a href="update_degree_fee.php?id=<?php echo $fee['id']; ?>">Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
