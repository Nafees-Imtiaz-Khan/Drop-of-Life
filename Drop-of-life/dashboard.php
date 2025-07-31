<?php
session_start();
include 'db/db.php';


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
    <meta name="viewport" content="width=device-width, initial-scale=2.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>User Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="box" >
        <div>
        <div style="position: absolute; top: 20px; right: 20px;">
                <button class="profile-button" onclick="location.href='profile.php'">
                    <img src="assets/user-icon.png" alt="Profile">
                </button>
        </div>    

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        </div>
        <button class="red-button" onclick="location.href='logout.php'">Logout</button>

        
        <div class="requests-box">
            <h3>Blood Requests Near You</h3>
            <?php
            
            $sql = "SELECT br.id, br.bloodgroup, br.location, br.urgency, u.name, u.phone FROM blood_requests br 
                    JOIN users u ON br.user_id = u.id 
                    WHERE br.user_id != ? AND u.location = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $user['location']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='request-item'>";
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                    echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
                    echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                    echo "<p><strong>Blood Group:</strong> " . htmlspecialchars($row['bloodgroup']) . "</p>";
                    echo "<p><strong>Urgency:</strong> " . htmlspecialchars($row['urgency']) . "</p>";
                    echo "<button class='message-button' onclick=\"location.href='send_message.php?request_id=" . $row['id'] . "'\">Send Message</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No blood requests available in your location.</p>";
            }
            ?>
        </div>
        <button class="red-button" onclick="location.href='request_blood.php'">Request Blood</button>
        <button class="red-button" onclick="location.href='inbox.php'">Inbox</button>
        </div>
    </div>
</body>
</html>


