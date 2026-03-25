<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    header("Location: devs.php"); 
    exit();
}

require "connection.php";

// 2. FETCH: Added 'password' to the SELECT statement
$sql = "SELECT id, username, email, password, last_login_date FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - PayGearPlan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #8B0000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #8B0000; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        /* Style for the password text to handle long hash strings */
        .pass-text { font-family: monospace; font-size: 12px; color: #666; word-break: break-all; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; color: white; font-size: 14px; }
        .btn-delete { background-color: #ff4d4d; }
        .btn-edit { background-color: #4CAF50; }
        .logout { float: right; color: #8B0000; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="\PayGearPlan\html\index.html" class="logout">Logout</a>
    <h1>Admin User Management</h1>
    <p>Logged in as: Admin</p>
<div class="container">
    
    <a href="add_user.php" style="background-color: #8B0000; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 20px;">+ Add New User</a>

    <?php 
    if (isset($_GET['msg']) && $_GET['msg'] == 'created') echo "<p style='color: green;'>User created successfully!</p>"; 
    ?>
    
    <table>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password (Hashed)</th> <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td class='pass-text'>" . $row["password"] . "</td> <td>" . $row["last_login_date"] . "</td>
                            <td>
                                <a href='edit_user.php?id=" . $row["id"] . "' class='btn btn-edit'>Edit</a>
                                <a href='delete_user.php?id=" . $row["id"] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                // Changed colspan to 6 because we added a column
                echo "<tr><td colspan='6' style='text-align:center;'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>