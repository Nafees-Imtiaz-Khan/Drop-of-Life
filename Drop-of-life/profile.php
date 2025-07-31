<?php
session_start();
include 'db/db.php';


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>User Profile</title>
</head>
<body>
    <div class="profile-container">
        <h2><?php echo htmlspecialchars($user['name']); ?>'s Profile</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>
        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($user['bloodgroup']); ?></p>
        <p><strong>Medical Issues:</strong> <?php echo htmlspecialchars($user['medical_issues']); ?></p>
        <button class="red-button" onclick="location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
