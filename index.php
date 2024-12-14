<?php
session_start(); 
// Start a session to manage session variables such as the logged-in user or the cart.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";
// Configuration to connect to the database.

$conn = new mysqli($servername, $username, $password, $dbname);
// Initialize the database connection using the parameters defined above.

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the database connection was successful. If it fails, terminate the script with an error message.

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
// Check if the user is logged in. If not, redirect them to the login page and stop the execution.

if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = [];
}
// Initialize the cart as an empty array in the session if it's not already set.

if (isset($_GET['aggiungi'])) {
    $id_prodotto = $_GET['aggiungi'];
    // Check if the user has requested to add a product to the cart via the "aggiungi" parameter.

    $sql = "SELECT * FROM prodotti WHERE id = $id_prodotto";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $prodotto = $result->fetch_assoc();
        $_SESSION['carrello'][] = $prodotto;
    }
    // Search for the product in the database using its ID and, if found, add it to the session's cart.
}

$sql_prodotti = "SELECT * FROM prodotti";
$result_prodotti = $conn->query($sql_prodotti);
// Retrieve all available products from the database to display them on the page.

$conn->close();
// Close the database connection.
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Online Shop</title>
    <style>
        /* CSS styles to make the page visually appealing */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .prodotto {
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 30%;
            margin-bottom: 40px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .prodotto img {
            max-width: 100%;
            border-radius: 8px;
        }
        .prodotto h3 {
            color: #333;
        }
        .prezzo {
            color: #e74c3c;
            font-size: 1.2em;
            margin: 10px 0;
        }
        .descrizione {
            color: #555;
            font-size: 0.9em;
        }
        .button {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #27ae60;
        }
        .carrello-button {
            text-align: right;
            margin: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to our Online Shop!</h1>
</header>

<!-- Buttons for the cart and logout -->
<div class="carrello-button">
    <a href="carrello.php" class="button">Go to Cart (<?php echo count($_SESSION['carrello']); ?>)</a>
    <!-- Show the number of items in the cart -->
    <a href="logout.php" class="button">Logout</a>
</div>

<!-- Product section -->
<div class="container">
    <?php while ($prodotto = $result_prodotti->fetch_assoc()): ?>
        <div class="prodotto">
            <img src="<?php echo $prodotto['immagine']; ?>" alt="<?php echo $prodotto['nome']; ?>">
            <!-- Display the product image -->
            <h3><?php echo $prodotto['nome']; ?></h3>
            <!-- Display the product name -->
            <p class="descrizione"><?php echo $prodotto['descrizione']; ?></p>
            <!-- Display the product description -->
            <p class="prezzo">€ <?php echo number_format($prodotto['prezzo'], 2, ',', '.'); ?></p>
            <!-- Display the price formatted in European style -->
            <br>
            <a href="index.php?aggiungi=<?php echo $prodotto['id']; ?>" class="button">Add to Cart</a>
            <!-- Button to add the product to the cart -->
            <br><br>
        </div>
    <?php endwhile; ?>
</div>

<footer>
    <p>&copy; 2024 Online Shop - All rights reserved.</p>
</footer>

</body>
</html>
