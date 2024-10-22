<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];  // No password hashing

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header("Location: dashboard.php");
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
  <div class="login-container">
  <h2>Capone Bank Login</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <p><a href="admin_login.php">Admin Login</a></p>
  </div>
   

</body>
<style>

body {
  font-family: Arial, sans-serif;
  background-color: #f0f0f0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}
.login-container{
  height: 450px;
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 10px black ;
  width: 350px;

}
h2 {
  text-align: center;
  margin-bottom: 20px;
}
label {
  display: block;
  margin-bottom: 5px;
}
input[type="password"],input[type="text"] {
  width: 95%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
input[type="submit"]{
  background-color: rgb(22, 107, 234);
  color: white;
  border: none;
  padding: 10px;
  margin-left: 65px;
  border-radius: 4px;
  cursor: pointer;
  width: 65%;
}
input[type="submit"]:hover {
  background-color: #0056b3;
}

</style>
</html>
