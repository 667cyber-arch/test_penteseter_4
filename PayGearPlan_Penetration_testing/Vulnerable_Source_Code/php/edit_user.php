<?php
session_start();

// 1. Security Check: Only admins can edit
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    header("Location: devs.php"); 
    exit();
}

require "connection.php";

$user = null;

// 2. FETCH: Get the user's current data to show in the form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
    $stmt->close();
}

// 3. UPDATE: Save the changes when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']); // Taking plain text password

    // Update query - No MD5 used here
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $username, $email, $password, $id);

    if ($updateStmt->execute()) {
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $updateStmt->close();
}

// Helper function to clean data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .edit-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { color: #8B0000; margin-top: 0; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-save { background-color: #8B0000; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 20px; font-size: 16px; }
        .btn-save:hover { background-color: #8B0000; }
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit User</h2>
    <form action="edit_user.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label>Username</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

        <label>Password (Plain Text)</label>
        <input type="text" name="password" value="<?php echo $user['password']; ?>" required>

        <button type="submit" class="btn-save">Save Changes</button>
        <a href="admin_dashboard.php" class="cancel-link">Back to Dashboard</a>
    </form>
</div>

</body>
</html>