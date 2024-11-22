<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Include your database connection script

// Get POST data
$technician_username = $_POST['technician_username'] ?? '';
$doctor_id = $_POST['doctor_id'] ?? '';
$results = $_POST['results'] ?? '';

if ($technician_username && $doctor_id && $results) {
    // Here, we will log the sent results in a hypothetical `sent_results` table
    $stmt = $conn->prepare("INSERT INTO sent_results (doctor_id, technician_username, results, sent_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $doctor_id, $technician_username, $results);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Lab results sent successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to send lab results."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$stmt->close();
$conn->close();
?>