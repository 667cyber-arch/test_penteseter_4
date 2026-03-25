<?php
session_start();
// Security Check
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    header("Location: devs.php"); 
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "connection.php";

    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']); // Plain text as requested

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, last_login_date) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?msg=created");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { color: #8B0000; margin-top: 0; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-add { background-color: #8B0000; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New User</h2>
    <form action="add_user.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="password" placeholder="Password" required>
        <button type="submit" class="btn-add">Create User</button>
        <a href="admin_dashboard.php" class="cancel-link">Back to Dashboard</a>
    </form>
</div>

</body>
</html>