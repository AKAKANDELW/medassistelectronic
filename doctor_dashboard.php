<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

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

// Get the logged-in doctor's details
$doctor_id = $_SESSION['doctor_id'];
$sql = "SELECT doctor_name, specialization, email, phone_number, gender, residential_address, city FROM doctors WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $doctor = $result->fetch_assoc();
} else {
    echo "Error: Doctor not found.";
    exit();
}

// Fetch lab results for the logged-in doctor
$sql_results = "SELECT lr.id, lr.test_type, lr.result, lr.created_at, p.full_name, p.patient_id FROM lab_results lr JOIN patients p ON lr.patient_id = p.patient_id WHERE lr.doctor_id = ?";
$stmt_results = $conn->prepare($sql_results);
$stmt_results->bind_param("i", $doctor_id);
$stmt_results->execute();
$result_lab = $stmt_results->get_result();

$stmt->close();

// Fetch appointments for the logged-in doctor
$sql_appointments = "SELECT a.appointment_id, a.patient_id, p.full_name, a.appointment_date, a.appointment_time, a.reason, a.status 
                     FROM appointments a 
                     JOIN patients p ON a.patient_id = p.patient_id 
                     WHERE a.doctor_id = ?";
$stmt_appointments = $conn->prepare($sql_appointments);
$stmt_appointments->bind_param("i", $doctor_id);
$stmt_appointments->execute();
$result_appointments = $stmt_appointments->get_result();

// Handle sending lab result notifications
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_notification'])) {
    $patient_id = $_POST['patient_id'];
    $lab_result_id = $_POST['lab_result_id'];
    $message = $_POST['message'];

    // Prepare SQL to insert notification
    $sql_send = "INSERT INTO lab_results_notifications (doctor_id, patient_id, lab_result_id, message) VALUES (?, ?, ?, ?)";
    $stmt_send = $conn->prepare($sql_send);
    $stmt_send->bind_param("iiss", $doctor_id, $patient_id, $lab_result_id, $message);
    
    if ($stmt_send->execute()) {
        echo "<script>alert('Notification sent successfully!');</script>";
    } else {
        echo "<script>alert('Error sending notification: " . $stmt_send->error . "');</script>";
    }
    
    $stmt_send->close();
}

// Fetch messages for the doctor
$messages = [];
$stmt_messages = $conn->prepare("SELECT m.message_id, m.sender_id, m.message, m.created_at, p.full_name FROM messages m JOIN patients p ON m.sender_id = p.patient_id WHERE m.recipient_id = ?");
$stmt_messages->bind_param("i", $doctor_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();

while ($row = $result_messages->fetch_assoc()) {
    $messages[] = $row;
}
$stmt_messages->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard | Healthcare Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c7be5;
            --secondary: #6e84a3;
            --success: #00d97e;
            --danger: #e63757;
            --warning: #f6c343;
            --info: #39afd1;
            --light: #edf2f9;
            --dark: #12263f;
            --white: #ffffff;
            --light-blue: #e0f7fa;
            --dark-blue: #003366;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light-blue);
            color: var(--dark);
            line-height: 1.6;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: var(--dark-blue);
            color: var(--white);
            border-right: 1px solid var(--light);
            height: 100vh;
            position: fixed;
            padding: 2rem 1rem;
        }
        .sidebar a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--white);
            text-decoration: none;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: var(--light);
        }
        .dashboard {
            margin-left: 270px;
            padding: 2rem;
            width: calc(100% - 270px);
        }
        .header {
            background: var(--white);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
            margin-bottom: 2rem;
        }
        .card {
            background: var(--white);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light);
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light);
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--secondary);
        }
        tr:hover {
            background: #f8f9fa;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--light);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out;
        }
        textarea:focus {
            border-color: var(--primary);
            outline: none;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            text-align: center;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }
        .btn-primary:hover {
            background: #2567c3;
        }
        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }
        .btn-danger:hover {
            background: #d32f2f;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .dashboard {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Menu</h2>
        <a href="#lab-results">Lab Results</a>
        <a href="#messages">Messages</a>
        <a href="#appointments">Appointments</a> <!-- Link to the appointments section -->
        <a href="prescription.php">Prescriptions</a>
        <a href="doctor_login.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="dashboard">
        <div class="header">
            <h1>Welcome, Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?>!</h1>
        </div>

        <div class="card info">
            <div class="card-header">
                <h2 class="card-title">Your Details</h2>
            </div>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($doctor['phone_number']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($doctor['gender']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($doctor['residential_address']); ?>, <?php echo htmlspecialchars($doctor['city']); ?></p>
        </div>

        <div class="card" id="lab-results">
            <div class="card-header">
                <h2 class="card-title">Patients Lab Results</h2>
            </div>
            <?php if ($result_lab->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Patient Name</th>
                        <th>Test Type</th>
                        <th>Result</th>
                        <th>Date</th>
                        <th>Send Notification</th>
                    </tr>
                    <?php while ($row = $result_lab->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['test_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['result']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($row['patient_id']); ?>">
                                    <input type="hidden" name="lab_result_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <textarea name="message" placeholder="Type your message here..." required></textarea>
                                    <button type="submit" name="send_notification" class="btn btn-primary">Send Notification</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No lab results found for you.</p>
            <?php endif; ?>
        </div>

        <div class="card" id="appointments">
            <div class="card-header">
                <h2 class="card-title">Your Appointments</h2>
            </div>
            <?php if ($result_appointments->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($row = $result_appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['appointment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No appointments found for you.</p>
            <?php endif; ?>
        </div>

        <div class="card" id="messages">
            <div class="card-header">
                <h2 class="card-title">Your Messages</h2>
            </div>
            <?php if (count($messages) > 0): ?>
                <table>
                    <tr>
                        <th>From</th>
                        <th>Message</th>
                        <th>Sent At</th>
                        <th>Reply</th>
                    </tr>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['message']); ?></td>
                            <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
                            <td>
                                <form method="POST" action="send_reply.php">
                                    <input type="hidden" name="sender_id" value="<?php echo htmlspecialchars($msg['sender_id']); ?>">
                                    <textarea name="reply_message" placeholder="Type your reply here..." required></textarea>
                                    <button type="submit" name="send_reply" class="btn btn-primary">Reply</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No messages found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>