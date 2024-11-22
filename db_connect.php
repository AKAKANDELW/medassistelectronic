<?php
// db_connect.php
$servername = "localhost";
$username = "root";  // Update this with your DB username
$password = "";      // Update this with your DB password
$dbname = "hpc";     // Update this with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
