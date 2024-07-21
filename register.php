<?php
include 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-register.css">

    <title>Register</title>
</head>
<body>
<div style="text-align:center; margin-bottom:20px;">
        <img src="logo.svg" alt="Hospital Billing App Logo" style="width:100px; height:100px;">
    </div>
    <h1>Register</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
        
    </form>
    <form action="login.php">
        <button type="submit">Go Back</button>
    </form>
</body>
</html>
