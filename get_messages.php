<?php
// get_messages.php

$host = 'localhost'; 
$db = 'hpc';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode([]);
    exit;
}

$patient_id = '123'; // Replace with the actual logged-in patient's ID
$doctor_id = '1'; // Replace with the actual doctor's ID

$stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?)");
$stmt->execute([$patient_id, $doctor_id, $doctor_id, $patient_id]);
$messages = $stmt->fetchAll();

echo json_encode($messages);
?>