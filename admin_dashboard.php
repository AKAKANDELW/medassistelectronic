<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Healthcare Management</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        :root {
            --primary-color: #1a73e8;
            --primary-light: #4285f4;
            --primary-dark: #0d47a1;
            --background: #f0f2f5;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-primary: #333;
            --text-secondary: #666;
            --button-text: #ffffff;
            --shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
        }

        .dark-mode {
            --primary-color: #4285f4;
            --primary-light: #64b5f6;
            --primary-dark: #1565c0;
            --background: #1a1a1a;
            --card-bg: rgba(30, 30, 30, 0.9);
            --text-primary: #fff;
            --text-secondary: #ccc;
            --button-text: #ffffff;
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

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            opacity: 0.1;
        }

        .dashboard {
            width: 90%;
            max-width: 1200px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
            position: relative;
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
            color: var(--text-primary);
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 700;
        }

        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            border-radius: 15px;
            background: var(--primary-color);
            color: var(--button-text);
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.2);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .button i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .button:hover {
            transform: translateY(-5px);
            background: var(--primary-light);
            box-shadow: 0 8px 25px rgba(26, 115, 232, 0.3);
        }

        .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .button:hover::before {
            left: 100%;
        }

        .logout-button {
            background: #dc3545;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .logout-button:hover {
            background: #c82333;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 768px) {
            .dashboard {
                padding: 1rem;
                width: 95%;
            }

            .button-grid {
                grid-template-columns: 1fr;
            }

            h2 {
                font-size: 2rem;
            }

            .button {
                padding: 1rem;
            }
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Button Press Effect */
        .button:active {
            transform: scale(0.98);
            box-shadow: 0 2px 10px rgba(26, 115, 232, 0.1);
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="background-animation"></div>
    
    <div class="dashboard">
        <button class="theme-toggle" id="themeToggle">
            <i class='bx bx-moon'></i>
        </button>
        
        <h2>Welcome, Admin!</h2>

        <div class="button-grid">
            <a href="patient_registration.html" class="button">
                <i class='bx bx-user-plus'></i>Register Patient
            </a>
            <a href="doctor_registration.html" class="button">
                <i class='bx bx-plus-medical'></i>Register Doctor
            </a>
            <a href="view_patients.php" class="button">
                <i class='bx bx-group'></i>View Patients
            </a>
            <a href="view_lab_results.php" class="button">
                <i class='bx bx-test-tube'></i>Lab Results
            </a>
            <a href="delete_doctor.php" class="button">
                <i class='bx bx-user-x'></i>Delete Doctor
            </a>
            <a href="delete_patient.php" class="button">
                <i class='bx bx-user-minus'></i>Delete Patient
            </a>
            <a href="view_doctors.php" class="button">
                <i class='bx bx-user-pin'></i>View Doctors
            </a>
            <a href="lab_technician_registration.php" class="button">
                <i class='bx bx-user-voice'></i>Register Lab Tech
            </a>
        </div>

        <div class="button-grid">
            <a href="admin.php?action=logout" class="button logout-button">
                <i class='bx bx-log-out'></i>Sign Out
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const icon = themeToggle.querySelector('i');

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            icon.classList.toggle('bx-moon');
            icon.classList.toggle('bx-sun');
        });

        // Particles.js Configuration
        particlesJS('particles', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#1a73e8'
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: false
                },
                size: {
                    value: 3,
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#1a73e8',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>