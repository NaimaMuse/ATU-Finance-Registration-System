<?php
include('../db.php');

$error_message = '';
$success_message = '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $faculty_id = $_GET['id'];

    // Fetch faculty details
    $sql = 'SELECT * FROM tblmasterfaculty WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $faculty_id);
    $stmt->execute();
    $faculty = $stmt->fetch();

    if (!$faculty) {
        die('Faculty not found.');
    }

    // Update logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $faculty_name = trim($_POST['faculty_name']);
        $price = trim($_POST['price']);

        if (empty($faculty_name) || empty($price) || !is_numeric($price) || $price <= 0) {
            $error_message = 'Please fill in all fields correctly. Faculty name cannot be empty, and price must be a valid number greater than zero.';
        } else {
            $update_sql = 'UPDATE tblmasterfaculty SET faculty_name = :faculty_name, price = :price WHERE id = :id';
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':faculty_name', $faculty_name);
            $update_stmt->bindParam(':price', $price);
            $update_stmt->bindParam(':id', $faculty_id);

            if ($update_stmt->execute()) {
                $success_message = 'Faculty updated successfully.';
                header('Location: ./master_faculty.php'); // Redirect to faculty list
                exit();
            } else {
                $error_message = 'Error updating faculty.';
            }
        }
    }
} else {
    die('Invalid ID.');
}
include('dash_base.php');

?>

<style>

    .form-container {
        width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #f7f7f7;
        border-radius: 5px;
        box-shadow: 10px 5px 10px rgba(0, 0, 0, 0.1);
        margin-top:80px;
    }
    .form-container input[type="text"], .form-container input[type="number"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-container button {
        background-color: #8a1d39;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    .form-container button:hover {
        background-color: #a62e52;
    }
    .error-message {
        color: red;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .success-message {
        color: green;
        margin-bottom: 20px;
        font-weight: bold;
    }
</style>

<div class="container mt-5">
    <h2 class="text-center">Update Faculty</h2>

    <?php if ($error_message): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="post" action="">
            <div class="form-group">
                <label for="faculty_name">Faculty Name:</label>
                <input type="text" id="faculty_name" name="faculty_name" value="<?php echo htmlspecialchars($faculty['faculty_name']); ?>">
            </div>
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" min="0" step="any" value="<?php echo htmlspecialchars($faculty['price']); ?>">
            </div>
            <button type="submit">Update Faculty</button>
        </form>
    </div>
</div>
