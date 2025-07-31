<?php
session_start();
include 'db/db.php';


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $bloodgroup = $_POST['bloodgroup'];
    $urgency = $_POST['urgency'];


    if (!empty($location) && !empty($bloodgroup) && !empty($urgency)) {
        
        $sql = "INSERT INTO blood_requests (user_id, location, bloodgroup, urgency) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user['id'], $location, $bloodgroup, $urgency);

        if ($stmt->execute()) {
            
            header("Location: view_donors.php?location=" . urlencode($location) . "&bloodgroup=" . urlencode($bloodgroup));
            exit();
        } else {
            $error = "Failed to submit your request. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Request Blood</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Request Blood</h2>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="POST" action="request_blood.php">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter location" required>

            <label for="bloodgroup">Blood Group:</label>
            <select id="bloodgroup" name="bloodgroup" required>
                <option value="">Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>

            <label for="urgency">Urgency:</label>
            <select id="urgency" name="urgency" required>
                <option value="">Select Urgency</option>
                <option value="urgent">Urgent</option>
                <option value="not urgent">Not Urgent</option>
            </select>

            <button class="red-button" type="submit">Submit Request</button>
        </form>
        <button class="red-button" onclick="location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
