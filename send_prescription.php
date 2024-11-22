<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Include your database connection script

$patient_id = $_POST['patient_id'] ?? '';
$prescription_number = $_POST['prescription_number'] ?? '';
$doctor_name = $_POST['doctor_name'] ?? '';
$medication_name = $_POST['medication_name'] ?? '';
$dosage = $_POST['dosage'] ?? '';
$frequency = $_POST['frequency'] ?? '';
$duration = $_POST['duration'] ?? '';
$diagnosis = $_POST['diagnosis'] ?? '';

if ($patient_id && $medication_name) {
    $stmt = $conn->prepare("
        INSERT INTO prescriptions (patient_id, prescription_number, patient_name, doctor_name, diagnosis, prescription_date, medication_name, dosage, frequency, duration)
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssssss", $patient_id, $prescription_number, $patient_name, $doctor_name, $diagnosis, $medication_name, $dosage, $frequency, $duration);

    // Assuming you fetch patient_name based on patient_id
    $patient_stmt = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
    $patient_stmt->bind_param("s", $patient_id);
    $patient_stmt->execute();
    $patient_result = $patient_stmt->get_result();
    $patient_data = $patient_result->fetch_assoc();
    $patient_name = $patient_data['full_name'] ?? 'Unknown';

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Prescription sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send prescription.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}

$conn->close();
?>