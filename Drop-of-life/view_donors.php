<?php
session_start();
include 'db/db.php';


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];


$bloodgroup = $_GET['bloodgroup'];
$location = $_GET['location'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Available Donors</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Available Donors</h2>
        <div class="requests-box">
            <?php
            
            $sql = "SELECT * FROM users WHERE bloodgroup = ? AND location = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $bloodgroup, $location, $user['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='request-item'>";
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                    echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
                    echo "<p><strong>Medical Issues:</strong> " . htmlspecialchars($row['medical_issues']) . "</p>";
                    echo "<button class='message-button' onclick=\"location.href='send_message.php?receiver_id=" . $row['id'] . "'\">Send Message</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No donors found in this area for the specified blood group.</p>";
            }
            ?>
        </div>
        <button class="red-button" onclick="location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
