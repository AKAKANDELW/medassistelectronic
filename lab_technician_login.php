<?php
// Start session
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'hpc');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM lab_technicians WHERE username = ?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($_POST['password'], $hashed_password)) {
            $_SESSION['lab_technician'] = $_POST['username'];
            header("Location: add_lab_results.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Technician Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f4fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.2);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            text-align: center;
        }
        h2 {
            color: #007BFF;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"], 
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #007BFF;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            margin-bottom: 15px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, 
        input[type="password"]:focus {
            border-color: #0056b3;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px; /* Space between buttons */
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #ff4d4f;
            font-size: 14px;
            margin-top: 10px;
        }
        .back-button {
            background-color: #f44336; /* Red color for back button */
            color: white;
            font-weight: normal;
            border: none;
            border-radius: 6px;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #c62828; /* Darker red on hover */
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Lab Technician Login</h2>
    
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    
    <button type="button" class="back-button" onclick="goBack()">Back to Home</button>
</div>

<script>
    function goBack() {
        window.location.href = 'index.html'; // Redirect to index.html
    }
</script>

</body>
</html>