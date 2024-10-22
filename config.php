<?php
// config.php: Database Configuration
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "capone_bank"; // Ensure this is the correct database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
