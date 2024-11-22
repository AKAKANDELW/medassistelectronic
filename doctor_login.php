<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "hpc"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email and password are set
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $input_email = mysqli_real_escape_string($conn, $_POST['email']);
        $input_password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepared statement to check if the doctor exists
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
        $stmt->bind_param("s", $input_email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query was successful
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($input_password, $row['password'])) {
                // Successful login
                $_SESSION['doctor_id'] = $row['doctor_id']; // Store doctor ID in session
                $_SESSION['email'] = $row['email'];
                header("Location: doctor_dashboard.php");
                exit();
            } else {
                // Password mismatch
                header("Location: doctor_login.php?error=1");
                exit();
            }
        } else {
            // No doctor found
            header("Location: doctor_login.php?error=1");
            exit();
        }
    } else {
        // Missing parameters
        header("Location: doctor_login.php?error=1");
        exit();
    }
}

$conn->close();
?>