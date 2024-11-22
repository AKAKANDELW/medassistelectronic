<?php
// Database connection settings
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "hpc"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT patient_id, full_name FROM patients WHERE patient_id LIKE ? OR full_name LIKE ?");
$searchParam = '%' . $searchQuery . '%';
$stmt->bind_param("ss", $searchParam, $searchParam);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to hold results
$patientsArray = [];

// Fetch the results
while ($row = $result->fetch_assoc()) {
    $patientsArray[] = $row;
}

// Close connections
$stmt->close();
$conn->close();

// Return results as a JSON array
header('Content-Type: application/json');
echo json_encode($patientsArray);
?>