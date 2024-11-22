<?php
session_start();

// Database connection parameters
$host = 'localhost'; // Database host
$db_user = 'root'; // Database username
$db_pass = ''; // Database password
$db_name = 'hpc'; // Database name

// Create a new database connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT password FROM patients WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Verify the password
    if ($hashed_password && password_verify($password, $hashed_password)) {
        $_SESSION['patient_id'] = $patient_id; // Save the patient ID in session
        echo "Success";
    } else {
        echo 'Invalid login';
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
