<?php
// send_lab_results.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $patient_id = $_POST['patient_id'];
    $technician_username = 'Bwalya'; // Replace this with the actual technician username or retrieve it from session
    $result = $_POST['lab_results'];
    $test_date = date('Y-m-d'); // Current date

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO lab_results (patient_id, technician_username, result, test_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $technician_username, $result, $test_date);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["message" => "Lab results sent successfully."]);
    } else {
        echo json_encode(["message" => "Error sending lab results: " . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["message" => "Invalid request method."]);
}
?>