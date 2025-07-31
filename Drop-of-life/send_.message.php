<?php
session_start();
include 'db/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}


if (!isset($_GET['request_id'])) {
    header('Location: dashboard.php');
    exit();
}

$request_id = $_GET['request_id'];
$user = $_SESSION['user'];
$sender_id = $user['id'];

$sql = "SELECT br.user_id AS requester_id, u.name, u.phone 
        FROM blood_requests br 
        JOIN users u ON br.user_id = u.id 
        WHERE br.id = ?";
$statement = $conn->prepare($sql);
$statement->bind_param("i", $request_id);
$statement->execute();
$result = $statement->get_result();
$requester = $result->fetch_assoc();

if (!$requester) {
    echo "Invalid blood request.";
    exit();
}


$success = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (empty($message)) {
        $error = "Message cannot be empty.";
    } else {
        
        $receiver_id = $requester['requester_id'];
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, created_at) 
                VALUES (?, ?, ?, NOW())";
        $statement = $conn->prepare($sql);
        $statement->bind_param("iis", $sender_id, $receiver_id, $message);

        if ($statement->execute()) {
            $success = "Message sent successfully!";
        } else {
            $error = "Failed to send the message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=2.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Send Message</title>
</head>
<body>
    <div class="message-container">
        <h2>Send Message to <?php echo htmlspecialchars($requester['name']); ?></h2>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($requester['phone']); ?></p>

        <!-- Display success or error messages -->
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Message form -->
        <form method="POST">
            <textarea name="message" placeholder="Type your message here..." required></textarea>
            <button type="submit" class="green-button">Send Message</button>
        </form>

        <!-- Back to Dashboard -->
        <button onclick="location.href='dashboard.php'" class="red-button">Back to Dashboard</button>
    </div>
</body>
</html>

