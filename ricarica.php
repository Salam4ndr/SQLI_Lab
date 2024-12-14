<?php
session_start(); 
// Starts a new session or resumes the existing one to manage session data.

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "ecommerce_db"; 
// Database connection settings.

$conn = new mysqli($servername, $username, $password, $dbname); 
// Establishes a connection to the database using the configured data.

if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
// Checks if the database connection was successful. If not, stops the execution and outputs an error message.

if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit(); 
} 
// Checks if the user is logged in. If not, redirects to the login page and stops further execution.

if (isset($_POST['ricarica'])) { 
    $importo = $_POST['importo']; 
    // Checks if the reload form was submitted and assigns the value of the amount.

    if ($importo <= 0) { 
        $error = "The amount must be greater than zero."; 
    } else { 
        $user_id = $_SESSION['user']['id']; 
        // Retrieves the user's ID from the session.

        $query = "SELECT portafoglio FROM utenti WHERE id = $user_id"; 
        $result = $conn->query($query); 
        $user = $result->fetch_assoc(); 
        // Executes a query to get the user's current wallet balance and stores it in a variable.

        $new_balance = $user['portafoglio'] + $importo; 
        // Calculates the new balance by adding the entered amount.

        $query_update_balance = "UPDATE utenti SET portafoglio = $new_balance WHERE id = $user_id"; 
        if ($conn->query($query_update_balance) === TRUE) { 
            $success = "Wallet successfully recharged. The new balance is €$new_balance."; 
        } else { 
            $error = "Error updating balance: " . $conn->error; 
        } 
        // Updates the user's wallet balance in the database and handles the result of the operation.
    }
}

$conn->close(); 
// Closes the connection to the database.
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharge Wallet</title>
    <style>
        /* CSS styles for the page */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #56ccf2, #2f80ed);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .container h2 {
            text-align: center;
            color: #333;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .button {
            background-color: #2ecc71;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        .button:hover {
            background-color: #27ae60;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            font-size: 14px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Recharge Your Wallet</h2>
        <form method="post">
            <input type="number" name="importo" class="input-field" placeholder="Amount to recharge" required min="0">
            <button type="submit" name="ricarica" class="button">Recharge</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <!-- If there is an error or success message, it will be displayed here. -->
    </div>

    <footer>
        <p>&copy; 2024 Online Store - All rights reserved.</p>
    </footer>

</body>
</html>
