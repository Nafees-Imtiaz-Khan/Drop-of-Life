<?php
session_start();
include 'db/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Inbox</title>
</head>
<body>
    <div class="inbox-container">
        <h2>Inbox</h2>
        <h3>Received Messages</h3>
        <?php
        $sql = "SELECT m.id, m.message, m.created_at, u.name AS sender_name, u.phone AS sender_phone
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE m.receiver_id = ?
                ORDER BY m.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='message-item'>";
                echo "<p><strong>From:</strong> " . htmlspecialchars($row['sender_name']) . " (" . htmlspecialchars($row['sender_phone']) . ")</p>";
                echo "<p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>";
                echo "<p><strong>Received At:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No received messages.</p>";
        }
        ?>

        
        <h3>Sent Messages</h3>
        <?php
        $sql = "SELECT m.id, m.message, m.created_at, u.name AS receiver_name, u.phone AS receiver_phone
                FROM messages m
                JOIN users u ON m.receiver_id = u.id
                WHERE m.sender_id = ?
                ORDER BY m.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='message-item'>";
                echo "<p><strong>To:</strong> " . htmlspecialchars($row['receiver_name']) . " (" . htmlspecialchars($row['receiver_phone']) . ")</p>";
                echo "<p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>";
                echo "<p><strong>Sent At:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No sent messages.</p>";
        }
        ?>
        
        <button class="inb" onclick="location.href='dashboard.php'">Back to Dashboard</button>
        
    </div>
</body>
</html>
