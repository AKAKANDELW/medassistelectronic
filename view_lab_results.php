<?php
// Database connection
$host = 'localhost';
$db = 'hpc';
$user = 'root'; // Replace with your DB username
$pass = ''; // Replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch submitted lab results
    $resultsStmt = $pdo->query("SELECT lr.id, lr.patient_id, lr.test_type, lr.result, d.doctor_name 
                                 FROM lab_results lr
                                 JOIN doctors d ON lr.doctor_id = d.doctor_id");
    $labResults = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Technician Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ffffff;
            --text-color: #003366;
            --shadow-light: #e0e5ec;
            --shadow-dark: #a3b1c6;
            --accent-color: #007bff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--primary-color);
            color: var(--text-color);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--accent-color);
            padding: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            background: var(--accent-color);
            border-radius: 15px;
            box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);
            color: white;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            background: var(--primary-color);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: var(--text-color);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--shadow-light);
        }

        th {
            background: var(--accent-color);
            color: white;
        }

        tr:hover {
            background: rgba(0, 123, 255, 0.1);
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">Lab Dashboard</div>
            <a href="add_lab_results.php" class="nav-item"><i class="fas fa-plus-circle"></i> Add Lab Result</a>
            <a href="view_lab_results.php" class="nav-item"><i class="fas fa-list"></i> View Submitted Results</a>
        </div>

        <div class="main-content">
            <div class="card">
                <h2><i class="fas fa-list"></i> Submitted Lab Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Test Type</th>
                            <th>Result</th>
                            <th>Doctor Assigned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($labResults) > 0): ?>
                            <?php foreach ($labResults as $result): ?>
                                <tr>
                                    <td><?= htmlspecialchars($result['patient_id']) ?></td>
                                    <td><?= htmlspecialchars($result['test_type']) ?></td>
                                    <td><?= htmlspecialchars($result['result']) ?></td>
                                    <td><?= htmlspecialchars($result['doctor_name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No lab results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>