<?php
session_start();

// 1. THE TRAFFIC CONTROLLER (Decides who is logging in)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // A. Check if it's an ADMIN LOGIN
    if (isset($_POST['login_type']) && $_POST['login_type'] == 'admin') {
        loginAdmin();
    } 
    // B. Check if it's a REGULAR USER (Sign-up has email, Login only has username)
    else {
        if (isset($_POST["email"]) && !empty($_POST["email"])) {
            // It's a Registration
            $email = test_input($_POST["email"]);
            $username = test_input($_POST["username"]);
            $password = test_input($_POST["password"]);
            registerUser($email, $username, $password);
        } else {
            // It's a regular User Login
            $username = test_input($_POST["username"]);
            $password = test_input($_POST["password"]);
            loginUser($username, $password);
        }
    }
}

//ADMIN LOGIN FUNCTION (Messy/Vulnerable Version for Portfolio)
function loginAdmin() {
    global $conn; // Make sure this is here!
    require "connection.php";
   
    // THE BYPASS: We take the RAW post data. No test_input()!
    $email = $_POST["email"];
    $password = $_POST["password"];

    // THE DIRTY QUERY
    $sql = "SELECT id FROM admins WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['admin_auth'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // This will show us EXACTLY what the database saw
        echo "<h2>Login Failed</h2>";
        echo "Query tried: <code style='color:red;'>" . htmlspecialchars($sql) . "</code>";
        exit();
    }
}

// 3. REGULAR USER LOGIN FUNCTION (Now Plain Text)
function loginUser($username, $password) {
    require "connection.php"; 
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $update = $conn->prepare("UPDATE users SET last_login_date = CURRENT_TIMESTAMP WHERE username = ?");
        $update->bind_param("s", $username);
        $update->execute();
        
        header("Location: ../php/produktet.php");
        exit(); 
    } else {
        echo "<script>alert('Incorrect credentials, try again!'); window.location.href='login.php';</script>";
        exit();
    }
}

// 4. REGISTRATION FUNCTION (Now Plain Text)
function registerUser($email, $username, $password) {
    require "connection.php";

    // Removed md5() - inserting plain text password
    $stmt = $conn->prepare("INSERT INTO users (email, username, password, last_login_date) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sss", $email, $username, $password);

    if ($stmt->execute()) {
        header("Location: ../php/produktet.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// 5. HELPER FUNCTION
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>