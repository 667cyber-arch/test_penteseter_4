<?php
session_start();
if (!isset($_SESSION['admin_auth'])) { exit("Unauthorized"); }

require "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Use a prepared statement for security (professors love this!)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>