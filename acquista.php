<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verify database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Calculate the total purchase amount (sum of product prices in the cart)
$total = 0;
if (!empty($_SESSION['carrello'])) {
    foreach ($_SESSION['carrello'] as $prodotto) {
        $total += $prodotto['prezzo']; // Add each product's price to the total
    }
}

// Retrieve the user's balance from the database
$user_id = $_SESSION['user']['id']; // Retrieve user ID from the session
$query = "SELECT username, portafoglio FROM utenti WHERE id = $user_id";
$result = $conn->query($query);

// Verify that the user exists in the database
if ($result->num_rows == 0) {
    echo "User not found!";
    exit();
}

$user = $result->fetch_assoc(); // Retrieve user data

// Check if the user has sufficient balance
if ($user['portafoglio'] >= $total) {
    // Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Insert a new order into the "ordini" table
        $query_order = "INSERT INTO ordini (id_utente, totale) VALUES ($user_id, $total)";
        if (!$conn->query($query_order)) {
            throw new Exception("Error inserting the order");
        }

        // Get the ID of the newly created order
        $order_id = $conn->insert_id;

        // Update the user's balance by subtracting the total
        $new_balance = $user['portafoglio'] - $total;
        $query_update_balance = "UPDATE utenti SET portafoglio = $new_balance WHERE id = $user_id";
        if (!$conn->query($query_update_balance)) {
            throw new Exception("Error updating the balance");
        }

        // Remove products from the cart (clear the cart in the session)
        unset($_SESSION['carrello']); // Clear the cart

        // Commit the transaction
        $conn->commit();

        // Success message
        $message = "Purchase successful! The total of €$total has been deducted from your balance.";
        $alert_class = "success"; // CSS class for success styling

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $message = "Error processing the purchase: " . $e->getMessage();
        $alert_class = "error"; // CSS class for error styling
    }

} else {
    // Error message in case of insufficient balance
    $message = "Insufficient balance in your wallet. You cannot proceed with the purchase.";
    $alert_class = "error"; // CSS class for error styling
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Outcome</title>
    <style>
        /* Basic styling for the page body */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #56ccf2, #2f80ed);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            height: 100vh;
            color: #333;
        }

        /* Styling for the message container */
        .message-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Limit content width */
            text-align: center;
        }

        /* Styling for the title */
        .message-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Styling for the message */
        .message-container p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Styling for success messages */
        .success {
            color: #2ecc71;
            font-weight: bold;
        }

        /* Styling for error messages */
        .error {
            color: #e74c3c;
            font-weight: bold;
        }

        /* Styling for buttons */
        .button {
            background-color: #2ecc71;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        /* Hover effect for buttons */
        .button:hover {
            background-color: #27ae60;
        }

        /* Styling for the footer */
        footer {
            text-align: center;
            margin-top: 20px;
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

    <div class="message-container">
        <!-- Display the outcome title -->
        <h2>Purchase Outcome</h2>
        <!-- Display the success or error message -->
        <p class="<?php echo $alert_class; ?>"><?php echo $message; ?></p>
        <!-- Button to return to the shop -->
        <a href="index.php" class="button">Return to Shop</a>
    </div>

    <footer>
        <!-- Footer with copyright -->
        <p>&copy; 2024 Online Store - All rights reserved.</p>
    </footer>

</body>
</html>
