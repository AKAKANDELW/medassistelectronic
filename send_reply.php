<?php
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "hpc"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
    $sender_id = $_POST['sender_id']; // Patient ID
    $reply_message = $_POST['reply_message']; // The reply message
    $doctor_id = $_SESSION['doctor_id'];

    // Insert the reply into the messages table
    $sql_reply = "INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)";
    $stmt_reply = $conn->prepare($sql_reply);
    $stmt_reply->bind_param("sss", $doctor_id, $sender_id, $reply_message);

    if ($stmt_reply->execute()) {
        echo "<script>alert('Reply sent successfully!');</script>";
    } else {
        echo "<script>alert('Error sending reply: " . $stmt_reply->error . "');</script>";
    }

    $stmt_reply->close();
}

$conn->close();
header("Location: doctor_dashboard.php"); // Redirect back to the dashboard
exit();
?>