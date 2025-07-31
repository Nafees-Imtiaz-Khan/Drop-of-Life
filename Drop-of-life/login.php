<?php
session_start();
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $statement = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $statement->bind_param("ss", $username, $password);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['status'] === 'banned') {
            $error = "Your account is banned.";
        } else {
            $_SESSION['user'] = $user;
            if (strpos($user['username'], 'admin') === 0) {
                header('Location: admin.php');
            } else {
                header('Location: dashboard.php');
            } 
            exit();
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>If you don't have an account, <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>