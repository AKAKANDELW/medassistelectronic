<?php
// Database configuration
$host = 'localhost'; // Database host
$db_name = 'hpc'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4"); // Set character set to utf8mb4 for full Unicode support

// Optional: Set timezone
date_default_timezone_set('UTC');

?>