<?php
session_start();
include('config.php');  // Ensure this establishes $conn

if (!isset($_SESSION['user'])) {
    die("Unauthorized access.");
}

$senderUsername = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'];
    $amount = $_POST['amount'];

    // Insert transaction (SQL Injection Vulnerability)
    $query = "INSERT INTO transactions (user_id, amount, recipient) VALUES (1, $amount, '$recipient')";
    $conn->query($query);

    // Retrieve sender's balance
    $query = "SELECT balance FROM users WHERE username = '$senderUsername'";
    $result = $conn->query($query);
    $sender = $result->fetch_assoc();

    if ($sender['balance'] >= $amount) {
        $newSenderBalance = $sender['balance'] - $amount;

        // Update sender's balance
        $updateSenderQuery = "UPDATE users SET balance = $newSenderBalance WHERE username = '$senderUsername'";
        $conn->query($updateSenderQuery);

        // Retrieve recipient's balance
        $recipientQuery = "SELECT * FROM users WHERE username = '$recipient'";
        $recipientResult = $conn->query($recipientQuery);
        $recipientData = $recipientResult->fetch_assoc();

        if ($recipientData) {
            $newRecipientBalance = $recipientData['balance'] + $amount;

            // Update recipient's balance
            $updateRecipientQuery = "UPDATE users SET balance = $newRecipientBalance WHERE username = '$recipient'";
            $conn->query($updateRecipientQuery);

            // Display message
            echo "Successfully transferred $$amount to {$recipientData['username']}.<br>";
            echo "Your new balance is: $$newSenderBalance.<br>";
            echo "{$recipientData['username']}'s new balance is: $$newRecipientBalance.";
        } else {
            echo "Recipient does not exist.";
            exit;
        }
    } else {
        echo "Insufficient balance.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transfer Funds</title>
</head>
<body>
    <h2>Transfer Funds</h2>
    <form method="POST">
        Recipient: <input type="text" name="recipient" required><br>
        Amount: <input type="number" name="amount" required min="0"><br>
        <button type="submit">Transfer</button>
    </form>

    <p>
        <!-- Reflected XSS Vulnerability -->
        <?php if (isset($_GET['msg'])) echo $_GET['msg']; ?>
    </p>
</body>
</html>
