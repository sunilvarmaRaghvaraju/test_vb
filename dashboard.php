<?php
// dashboard.php: Broken Access Control
session_start();
include('config.php');


if (!isset($_SESSION['user'])) {
    die("Unauthorized access.");
}

// Fetch user data (Insecure Query)
$username = $_SESSION['user'];
$result = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $user['username']; ?>!</h2>
    <p>Your balance: $<?php echo $user['balance']; ?></p>
    <a href="transfer.php">Transfer Funds</a>
</body>
</html>
