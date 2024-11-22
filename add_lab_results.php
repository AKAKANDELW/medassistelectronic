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
        /* Your existing styles here */
        :root {
            --primary-color: #ffffff;
            --text-color: #003366;
            --shadow-light: #e0e5ec;
            --shadow-dark: #a3b1c6;
            --accent-color: #007bff;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
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
            box-shadow: 5px 5px 10px var(--shadow-dark),
                        -5px -5px 10px var(--shadow-light);
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

        .logout-button {
            margin-top: auto; /* Push the logout button to the bottom */
            background: var(--danger-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: background 0.3s ease;
        }

        .logout-button:hover {
            background: #c0392b; /* Darker red on hover */
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
            box-shadow: 8px 8px 16px var(--shadow-dark),
                        -8px -8px 16px var(--shadow-light);
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: var(--shadow-light);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                        inset -4px -4px 8px var(--shadow-light);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                        inset -6px -6px 12px var(--shadow-light);
        }

        button {
            background: var(--accent-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                        -4px -4px 8px var(--shadow-light);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 12px var(--shadow-dark),
                        -6px -6px 12px var(--shadow-light);
        }

        /* Additional styles for responsive design and animations */
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
            <!-- Additional Navigation Items can be added here -->
            <a href="lab_technician_login.php" class="logout-button">Logout</a>
        </div>

        <div class="main-content">
            <div class="card">
                <h2><i class="fas fa-plus-circle"></i> Add New Lab Result</h2>
                <form id="labResultForm" method="POST" action="submit_lab_result.php">
                    <div class="search-bar">
                        <input type="text" id="patientSearch" name="patientSearch" placeholder="Search patient by ID or name...">
                        <button type="button" onclick="searchPatient()">Search</button>
                    </div>

                    <div class="search-results">
                        <ul id="searchResultsList"></ul>
                    </div>

                    <div class="form-group">
                        <label for="patientId">Patient ID</label>
                        <input type="text" id="patientId" name="patientId" required>
                    </div>

                    <div class="form-group">
                        <label for="patientName">Patient Name</label>
                        <input type="text" id="patientName" name="patientName" required>
                    </div>

                    <div class="form-group">
                        <label for="testType">Test Type</label>
                        <select id="testType" name="testType" required>
                            <option value="">Select Test Type</option>
                            <option value="blood">Blood Test</option>
                            <option value="urine">Urine Test</option>
                            <option value="xray">X-Ray</option>
                            <option value="mri">MRI</option>
                            <option value="ct">CT Scan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="result">Test Results</label>
                        <textarea id="result" name="result" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="doctorId">Doctor ID</label>
                        <input type="text" id="doctorId" name="doctorId" placeholder="Enter Doctor ID" required>
                    </div>

                    <div class="form-group">
                        <label for="doctor">Assign to Doctor</label>
                        <select id="doctor" name="doctor" required>
                            <option value="">Select Doctor</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= htmlspecialchars($doctor['doctor_id']) ?>">
                                    <?= htmlspecialchars($doctor['doctor_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit">Submit Results</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to search for patients
        function searchPatient() {
            const query = document.getElementById('patientSearch').value;

            fetch(`search_patients.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const resultsList = document.getElementById('searchResultsList');
                    resultsList.innerHTML = ''; // Clear existing results

                    if (data.length === 0) {
                        resultsList.innerHTML = '<li>No results found</li>';
                    } else {
                        data.forEach(patient => {
                            const listItem = document.createElement('li');
                            listItem.textContent = `${patient.patient_id} - ${patient.full_name}`;
                            listItem.onclick = () => {
                                document.getElementById('patientId').value = patient.patient_id;
                                document.getElementById('patientName').value = patient.full_name;
                                resultsList.innerHTML = ''; // Clear the results after selection
                            };
                            resultsList.appendChild(listItem);
                        });
                    }
                })
                .catch(error => console.error('Error searching patients:', error));
        }
    </script>
</body>
</html>