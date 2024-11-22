<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: index.html");
    exit();
}

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'hpc';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient_id = $_SESSION['patient_id'];

// Initialize variables
$full_name = '';
$email = '';
$prescriptions = [];
$notifications = [];
$doctors = [];
$messages = [];

// Fetch patient details
$stmt = $conn->prepare("SELECT full_name, email FROM patients WHERE patient_id = ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($full_name, $email);
$stmt->fetch();
$stmt->close();

// Fetch prescriptions for the patient
$stmt = $conn->prepare("SELECT medication, dosage, frequency, start_date, end_date, notes FROM prescriptions WHERE patient_id = ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $prescriptions[] = $row;
}
$stmt->close();

// Fetch lab result notifications for the patient
$stmt = $conn->prepare("SELECT message, sent_at FROM lab_results_notifications WHERE patient_id = ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
$stmt->close();

// Fetch available doctors for message sending and appointment scheduling
$stmt = $conn->prepare("SELECT doctor_id, doctor_name FROM doctors");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
$stmt->close();

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $recipient_id = $_POST['recipient_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $patient_id, $recipient_id, $message);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch messages for the patient
$stmt = $conn->prepare("SELECT sender_id, message, created_at FROM messages WHERE recipient_id = ? OR sender_id = ?");
$stmt->bind_param("ss", $patient_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();

// Handle appointment scheduling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_appointment'])) {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $patient_id, $doctor_id, $appointment_date, $appointment_time, $reason);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard | Healthcare Portal</title>
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
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .avatar {
            width: 64px;
            height: 64px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.5rem;
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
            border-collapse: separate;
            border-spacing: 0;
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
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary);
        }
        select, textarea, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--light);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out;
        }
        select:focus, textarea:focus, input:focus {
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
        .logout {
            text-decoration: none;
            display: inline-block;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Menu</h2>
        <a href="#active-prescriptions">Active Prescriptions</a>
        <a href="#lab-results">Lab Results</a>
        <a href="#message-doctor">Message Your Doctor</a>
        <a href="#message-history">Message History</a>
        <a href="#schedule-appointment">Schedule Appointment</a>
        <a href="login.html" class="btn btn-danger logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="dashboard">
        <div class="header">
            <div class="welcome-section">
                <div class="avatar">
                    <?php echo strtoupper(substr($full_name, 0, 1)); ?>
                </div>
                <div>
                    <h1>Welcome back, <?php echo htmlspecialchars($full_name); ?></h1>
                    <p style="color: var(--secondary);">Patient ID: <?php echo htmlspecialchars($patient_id); ?></p>
                </div>
            </div>
        </div>

        <div class="card" id="active-prescriptions">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-prescription-bottle-alt"></i> Active Prescriptions
                </h2>
            </div>
            <?php if (!empty($prescriptions)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <tr>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Notes</th>
                        </tr>
                        <?php foreach ($prescriptions as $prescription): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($prescription['medication']); ?></strong></td>
                                <td><?php echo htmlspecialchars($prescription['dosage']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['frequency']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($prescription['notes']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>No active prescriptions at the moment.</p>
            <?php endif; ?>
        </div>

        <div class="card" id="lab-results">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-flask"></i> Lab Results
                </h2>
            </div>
            <?php if (!empty($notifications)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                        <?php foreach ($notifications as $notification): ?>
                            <tr>
                                <td>
                                    <span class="notification-dot"></span>
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($notification['sent_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>No lab results available.</p>
            <?php endif; ?>
        </div>

        <div class="card" id="message-doctor">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-comment-medical"></i> Message Your Doctor
                </h2>
            </div>
            <form method="post">
                <div class="form-group">
                    <label for="recipient_id">Select Doctor:</label>
                    <select id="recipient_id" name="recipient_id" required>
                        <option value="">Choose a doctor...</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo htmlspecialchars($doctor['doctor_id']); ?>">
                                Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="4" required 
                        placeholder="Type your message here..."></textarea>
                </div>
                
                <button type="submit" name="send_message" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>

        <div class="card" id="message-history">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-history"></i> Message History
                </h2>
            </div>
            <?php if (!empty($messages)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <tr>
                            <th>From</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['sender_id']); ?></td>
                                <td><?php echo htmlspecialchars($msg['message']); ?></td>
                                <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>No message history available.</p>
            <?php endif; ?>
        </div>

        <div class="card" id="schedule-appointment">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-calendar-alt"></i> Schedule an Appointment
                </h2>
            </div>
            <form method="post">
                <div class="form-group">
                    <label for="doctor_id">Select Doctor:</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <option value="">Choose a doctor...</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo htmlspecialchars($doctor['doctor_id']); ?>">
                                Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="appointment_date">Appointment Date:</label>
                    <input type="date" id="appointment_date" name="appointment_date" required>
                </div>

                <div class="form-group">
                    <label for="appointment_time">Appointment Time:</label>
                    <input type="time" id="appointment_time" name="appointment_time" required>
                </div>

                <div class="form-group">
                    <label for="reason">Reason for Appointment:</label>
                    <textarea id="reason" name="reason" rows="4" required placeholder="Type your reason here..."></textarea>
                </div>
                
                <button type="submit" name="schedule_appointment" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Schedule Appointment
                </button>
            </form>
        </div>
    </div>

    <script>
        // Add smooth scrolling for links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>