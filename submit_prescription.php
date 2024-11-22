<?php
include 'db2.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $notes = $_POST['notes'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, frequency, start_date, end_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssss", $patient_id, $doctor_id, $medication, $dosage, $frequency, $start_date, $end_date, $notes);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Prescription submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>