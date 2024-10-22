<?php
session_start();
include('config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle individual user deletion
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id = $userId");
    header("Location: admin_dashboard.php");
    exit();
}

// Handle individual transaction deletion
if (isset($_GET['delete_transaction'])) {
    $transactionId = $_GET['delete_transaction'];
    $conn->query("DELETE FROM transactions WHERE id = $transactionId");
    header("Location: admin_dashboard.php");
    exit();
}

// Handle deleting all transactions
if (isset($_POST['delete_all_transactions'])) {
    $conn->query("DELETE FROM transactions");
    header("Location: admin_dashboard.php");
    exit();
}

// Handle balance updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_balance'])) {
    $userId = $_POST['user_id'];
    $newBalance = $_POST['new_balance'];

    $conn->query("UPDATE users SET balance = $newBalance WHERE id = $userId");
    header("Location: admin_dashboard.php");
    exit();
}

// Retrieve all users
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

// Retrieve all transactions
$transactionsQuery = "SELECT * FROM transactions";
$transactionsResult = $conn->query($transactionsQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="logout.php">Logout</a>

    <h3>All Users</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Balance</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $usersResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="number" name="new_balance" value="<?php echo $user['balance']; ?>" required>
                        <button type="submit" name="update_balance">Update Balance</button>
                    </form>
                </td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="update_user.php?id=<?php echo $user['id']; ?>">Update</a> |
                    <a href="admin_dashboard.php?delete_user=<?php echo $user['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h3>All Transactions</h3>
    <form method="POST" action="">
        <button type="submit" name="delete_all_transactions" 
                onclick="return confirm('Are you sure you want to delete all transactions?')">
            Delete All Transactions
        </button>
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Recipient</th>
            <th>Actions</th>
        </tr>
        <?php while ($transaction = $transactionsResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $transaction['id']; ?></td>
                <td><?php echo $transaction['user_id']; ?></td>
                <td><?php echo $transaction['amount']; ?></td>
                <td><?php echo $transaction['recipient']; ?></td>
                <td>
                    <a href="admin_dashboard.php?delete_transaction=<?php echo $transaction['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
