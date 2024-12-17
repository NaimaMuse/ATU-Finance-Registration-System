<?php
include('../db.php');

$error_message = '';

if (isset($_GET['id'])) {
    $fee_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM tbldegreefaculty WHERE id = ?");
    $stmt->execute([$fee_id]);
    $fee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fee) {
        die("Fee not found.");
    }

    $faculty_name = $fee['faculty_name'];
    $price = $fee['price'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_fee'])) {
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
        $stmt1 = $conn->prepare("UPDATE tbldegreefaculty SET faculty_name = ?, price = ? WHERE id = ?");
        $stmt1->execute([$faculty_name, $price, $fee_id]);

        header('Location: degree_fees_management.php');
        exit();
    }
}
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
        margin-left: 230px;
        margin-top: 50px;
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
    <h2>Update Fee</h2>

    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="update_degree_fee.php?id=<?php echo $fee_id; ?>">
        <label for="faculty_name">Faculty Name:</label><br>
        <input type="text" id="faculty_name" name="faculty_name" value="<?php echo htmlspecialchars($faculty_name); ?>"><br><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>"><br><br>

        <input type="submit" name="update_fee" value="Update Fee">
    </form>
</div>
