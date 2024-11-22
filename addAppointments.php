<?php
// Similar connection code as above
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientId = $_POST['patient_id'];
    $appointmentTime = $_POST['appointment_time'];

    $sql = "INSERT INTO appointments (patient_id, appointment_time, status) VALUES ('$patientId', '$appointmentTime', 'scheduled')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
$conn->close();
?>
