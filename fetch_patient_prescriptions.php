<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Include your database connection script

$patient_id = $_GET['patient_id'] ?? '';

if ($patient_id) {
    $stmt = $conn->prepare("SELECT * FROM prescriptions WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $prescriptions = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($prescriptions);
} else {
    echo json_encode([]);
}

$conn->close();
?>