<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['lab_technician'])) {
    header("Location: lab_technician_login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_email = $_POST['doctor_email'];
    $subject = "New Lab Result for Patient ID: " . $_POST['patient_id'];
    $message = "Lab Result: " . $_POST['result'] . "\nTest Date: " . $_POST['test_date'];

    // Send email
    if (mail($doctor_email, $subject, $message)) {
        echo "Lab result sent to doctor successfully!";
    } else {
        echo "Error sending email.";
    }
} else {
    echo "Invalid request.";
}
?>