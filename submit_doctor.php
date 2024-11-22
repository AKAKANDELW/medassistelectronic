<?php
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "hpc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO doctors (doctor_name, specialization, email, password, phone_number, gender, residential_address, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $doctorName, $specialization, $email, $password, $phone, $gender, $address, $city);

// Set parameters and execute
$doctorName = $_POST['doctorName'];
$specialization = $_POST['specialization'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$city = $_POST['city'];

if ($stmt->execute()) {
    echo "New doctor registered successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>