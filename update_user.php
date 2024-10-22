<?php
session_start();
include('config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$userId = $_GET['id'];

// Retrieve user details
$userQuery = "SELECT * FROM users WHERE id = $userId";
$userResult = $conn->query($userQuery);
$user = $userResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newBalance = $_POST['balance'];
    $newRole = $_POST['role'];

    // Update the user in the database (Vulnerable to SQL Injection)
    $updateQuery = "UPDATE users SET balance = $newBalance, role = '$newRole' WHERE id = $userId";
    $conn->query($updateQuery);

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>
    <form method="POST">
        <label>Username: <?php echo $user['username']; ?></label><br>
        <label>Balance:</label>
        <input type="number" name="balance" value="<?php echo $user['balance']; ?>" required><br>
        <label>Role:</label>
        <select name="role">
            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
