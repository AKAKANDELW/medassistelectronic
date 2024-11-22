<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id']; // Assuming you store user_id in session
$user_type = $_SESSION['user_type']; // 'doctor' or 'patient'

try {
    if ($user_type == 'doctor') {
        $stmt = $pdo->prepare("SELECT DISTINCT p.patient_id, p.full_name 
                              FROM patients p 
                              INNER JOIN messages m 
                              ON p.patient_id = m.patient_id 
                              WHERE m.doctor_id = ?");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT DISTINCT d.doctor_id, d.doctor_name 
                              FROM doctors d 
                              INNER JOIN messages m 
                              ON d.doctor_id = m.doctor_id 
                              WHERE m.patient_id = ?");
        $stmt->execute([$user_id]);
    }
    
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($contacts);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>