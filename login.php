<?php
session_start(); // Start the session to manage the login

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verify the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If the connection fails, terminate the process and display an error
}

// Login management
if (isset($_POST['login'])) { // Check if the form has been submitted
    $username = $_POST['username']; // Retrieve the entered username
    $password = $_POST['password']; // Retrieve the entered password

    // Query to verify user credentials in the database
    $sql = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { // If the query result contains rows, the user exists
        // Login successful, save the user in the session
        $_SESSION['user'] = $result->fetch_assoc(); // Save user data in the session
        header('Location: index.php'); // Redirect to the main page after login
        exit(); // End script execution
    } else {
        $error = "Invalid credentials!"; // If the credentials are incorrect, set an error message
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Online Store</title>
    <style>
        /* Basic styles for the page body */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #56ccf2, #2f80ed);
            margin: 0;
            padding: 0;
        }
        
        /* Header style */
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        
        /* Login form container */
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        /* Login form title */
        .login-container h2 {
            text-align: center;
            color: #333;
        }
        
        /* Styles for the form inputs */
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box; /* Prevent padding from increasing the width */
        }
        
        /* Input focus styles */
        .login-container input:focus {
            border-color: #2ecc71; /* Change input border color when focused */
        }
        
        /* Login button style */
        .button {
            background-color: #2ecc71;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        /* Hover effect on the button */
        .button:hover {
            background-color: #27ae60;
        }
        
        /* Error message style */
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        
        /* Footer style */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to the Online Store</h1>
</header>

<div class="login-container">
    <h2>Login to Your Account</h2>
    <!-- Login form -->
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username" required> <!-- Field for username -->
        <input type="password" name="password" placeholder="Password" required> <!-- Field for password -->
        <button type="submit" name="login" class="button">Login</button> <!-- Button to submit the form -->
    </form>
    
    <!-- Display the error message if the credentials are incorrect -->
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 Online Store - All rights reserved.</p>
</footer>

</body>
</html>
