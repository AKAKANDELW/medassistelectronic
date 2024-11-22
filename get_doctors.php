<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'hpc');

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Query to fetch doctors
$sql = "SELECT doctor_id, doctor_name FROM doctors";
$result = $conn->query($sql);

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

$conn->close();
echo json_encode($doctors);
?>