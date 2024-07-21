<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-register.css">


    <title>Login</title>
   
</head>
<body>
<div style="text-align:center; margin-bottom:20px;">
        <img src="logo.svg" alt="Hospital Billing App Logo" style="width:100px; height:100px;">
    </div>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>

    <p>If you don't have an account, you can register here:</p>
    <form action="register.php">
        <button type="submit">Register</button>
    </form>
</body>
</html>
