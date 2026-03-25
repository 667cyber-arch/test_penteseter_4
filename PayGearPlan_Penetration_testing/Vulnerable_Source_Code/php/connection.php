<?php

$servername = "localhost";
$connUsername = "paygear_user";
$connPassword = "PenTester12!";
$dbname = "paygearplanDB";
$port = 3306;

$conn = mysqli_connect($servername, $connUsername, $connPassword, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Successfully connected to paygearplanDB on port 3306";

?>