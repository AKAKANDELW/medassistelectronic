<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --secondary-blue: #4285f4;
            --light-blue: #e8f0fe;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --text-dark: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--gray-100) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-blue);
            position: relative;
        }

        .header h2 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 3px;
            background: var(--primary-blue);
            border-radius: 2px;
        }

        .form-container {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-blue);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--gray-100);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 2.5rem;
            color: var(--primary-blue);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        button {
            width: 100%;
            padding: 1rem;
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 115, 232, 0.3);
        }

        button::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        button:hover::after {
            left: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Prescription Form</h2>
        </div>
        
        <div class="form-container">
            <form id="prescriptionForm" action="submit_prescription.php" method="POST">
                <div class="row">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="text" id="patient_id" name="patient_id" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor ID</label>
                        <input type="text" id="doctor_id" name="doctor_id" required>
                        <i class="fas fa-user-md input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="medication">Medication</label>
                    <input type="text" id="medication" name="medication" required>
                    <i class="fas fa-pills input-icon"></i>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="dosage">Dosage</label>
                        <input type="text" id="dosage" name="dosage" required>
                        <i class="fas fa-prescription input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="frequency">Frequency</label>
                        <input type="text" id="frequency" name="frequency" required>
                        <i class="fas fa-clock input-icon"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" required>
                        <i class="fas fa-calendar-alt input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" required>
                        <i class="fas fa-calendar-alt input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="4"></textarea>
                    <i class="fas fa-notes-medical input-icon"></i>
                </div>

                <button type="submit">
                    Submit Prescription
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>