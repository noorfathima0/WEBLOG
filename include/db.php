<?php
// Database connection parameters
$servername = "localhost"; // Change if your MySQL server is hosted elsewhere
$username = "root"; // Change to your MySQL username
$password = ""; // Change to your MySQL password
$database = "weblogdb"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Set timezone (adjust as per your requirement)
date_default_timezone_set('UTC');
?>
