<?php
$host = "localhost";
$user = "root";         // Change if using a different DB user
$pass = "";             // Change if your MySQL has a password
$dbname = "inventory_system";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
