<?php
// Database connection
$host = 'localhost';
$db = 'hpc';
$user = 'root'; // Replace with your DB username
$pass = ''; // Replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch doctors from the database
    $stmt = $pdo->query("SELECT doctor_id, doctor_name FROM doctors");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission for adding lab results
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $patientId = $_POST['patientId'];
        $patientName = $_POST['patientName'];
        $testType = $_POST['testType'];
        $result = $_POST['result'];
        $doctorId = $_POST['doctorId'];
        $doctor = $_POST['doctor'];

        // Insert the lab result into the database
        $stmt = $pdo->prepare("INSERT INTO lab_results (patient_id, patient_name, test_type, result, doctor_id) VALUES (:patient_id, :patient_name, :test_type, :result, :doctor_id)");
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->bindParam(':patient_name', $patientName);
        $stmt->bindParam(':test_type', $testType);
        $stmt->bindParam(':result', $result);
        $stmt->bindParam(':doctor_id', $doctorId);
        $stmt->execute();

        // Redirect to the view_lab_results.php page
        header("Location: view_lab_results.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- HTML code for the lab technician dashboard -->
</html>