<?php
// Start session
session_start();

// Include database connection file
include 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to check username and password
    $sql = "SELECT * FROM admin WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // Verify the password (plain text comparison)
    if ($user && $password == $user['password']) {
        // Store admin info in session
        $_SESSION['admin'] = $user['username'];
        header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
            margin-bottom: 5px;
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
        }
        .back-button:hover {
            background-color: #c62828; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        
        <form method="post" action="admin.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
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