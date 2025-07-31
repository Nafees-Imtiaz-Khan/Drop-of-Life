<?php
session_start();
include 'db/db.php';


if (!isset($_SESSION['user']) || strpos($_SESSION['user']['username'], 'admin') !== 0) {
    header('Location: login.php');
    exit();
}

$admin = $_SESSION['user'];
$success = "";
$error = "";


if (isset($_POST['ban_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET status = 'banned' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $success = "User banned successfully.";
    } else {
        $error = "Failed to ban user.";
    }
}

if (isset($_POST['unban_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET status = 'active' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $success = "User unbanned successfully.";
    } else {
        $error = "Failed to unban user.";
    }
}


if (isset($_POST['delete_request'])) {
    $request_id = $_POST['request_id'];
    $sql = "DELETE FROM blood_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    if ($stmt->execute()) {
        $success = "Request deleted successfully.";
    } else {
        $error = "Failed to delete request.";
    }
}


$users_sql = "SELECT * FROM users";
$users_result = $conn->query($users_sql);


$requests_sql = "SELECT br.id, br.bloodgroup, br.location, br.urgency, u.name FROM blood_requests br 
                JOIN users u ON br.user_id = u.id";
$requests_result = $conn->query($requests_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome Admin, <?php echo htmlspecialchars($admin['name']); ?>!</h2>
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        
        <h3>Manage Users</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($user = $users_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['status']); ?></td>
            <td>
                <?php if ($user['status'] === 'active'): ?>
                    <!-- Ban Button for Active Users -->
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="ban_user" class="red-button">Ban</button>
                    </form>
                <?php else: ?>
                    
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="unban_user" style="    background-color:rgb(9, 158, 83);color:black;border: none;padding: 10px 20px;margin-left: 325px;text-align: center;font-size: 16px;border-radius: 5px;cursor: pointer;margin-top: 20px;display: block;width: 50%;">Unban</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>


        <h3>Manage Blood Requests</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Blood Group</th>
                <th>Location</th>
                <th>Urgency</th>
                <th>Requested By</th>                     
                <th>Action</th>
            </tr>
            <?php while ($request = $requests_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['id']); ?></td>
                    <td><?php echo htmlspecialchars($request['bloodgroup']); ?></td>
                    <td><?php echo htmlspecialchars($request['location']); ?></td>
                    <td><?php echo htmlspecialchars($request['urgency']); ?></td>
                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="delete_request" class="red-button">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <button class="red-button" onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
