<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hpc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM patients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patients</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8;
            --primary-light: #4285f4;
            --primary-dark: #0d47a1;
            --background: #f0f2f5;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-primary: #333;
            --shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
        }

        .dark-mode {
            --primary-color: #4285f4;
            --primary-light: #64b5f6;
            --primary-dark: #1565c0;
            --background: #1a1a1a;
            --card-bg: rgba(30, 30, 30, 0.9);
            --text-primary: #fff;
            --shadow: 20px 20px 60px #0a0a0a, -20px -20px 60px #2a2a2a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            opacity: 0.1;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover {
            transform: rotate(180deg);
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--primary-color);
            color: var(--text-primary);
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.05);
        }

    </style>
</head>
<body>
    <div class="particles" id="particles"></div>

    <button class="theme-toggle" id="themeToggle">
        <i class='bx bx-moon'></i>
    </button>

    <div class="container">
        <h2>Patients List</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Blood Group</th>
                    <th>Sex</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Primary Physician</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['patient_id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['dob']}</td>
                            <td>{$row['blood_group']}</td>
                            <td>{$row['sex']}</td>
                            <td>{$row['height']}</td>
                            <td>{$row['weight']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['city']}</td>
                            <td>{$row['state']}</td>
                            <td>{$row['primary_physician']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No patients found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const icon = themeToggle.querySelector('i');

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            icon.classList.toggle('bx-moon');
            icon.classList.toggle('bx-sun');
        });

        particlesJS('particles', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#1a73e8' },
                shape: { type: 'circle' },
                opacity: { value: 0.5 },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: '#1a73e8', opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, out_mode: 'out' }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' } }
            },
            retina_detect: true
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
