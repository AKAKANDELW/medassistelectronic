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

// Fetch recent lab results
$sql = "SELECT lr.test_date, p.full_name, lr.patient_id, lr.technician_username, lr.result
        FROM lab_results lr
        JOIN patients p ON lr.patient_id = p.patient_id
        ORDER BY lr.test_date DESC";
$result = $conn->query($sql);

// Initialize an empty array to hold results
$resultsArray = [];

// Check if there are results and populate the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resultsArray[] = $row;
    }
}

// Close connection
$conn->close();

// Return results as a JSON array
header('Content-Type: application/json');
echo json_encode($resultsArray);
?>