<?php
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $location = $_POST['location'];
    $bloodgroup = $_POST['bloodgroup'];
    $medical_issues = $_POST['medical_issues'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $qur = "INSERT INTO users (name, email, username, location, bloodgroup, medical_issues, dob, phone, password)
            VALUES ('$name', '$email', '$username', '$location', '$bloodgroup', '$medical_issues', '$dob', '$phone', '$password')";

    if ($conn->query($qur)) {
        echo "Account created successfully!";
        echo 'hudai';
        header('Location: index.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="post">
            <input type="text" name="name" placeholder="full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="location" placeholder="Location" required>
            <input type="text" name="bloodgroup" placeholder="Blood Group" required>
            <textarea name="medical_issues" placeholder="Medical Issues"></textarea>
            <input type="date" name="dob" placeholder="Date of Birth" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
