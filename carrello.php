<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Remove product from the cart
if (isset($_GET['rimuovi'])) {
    $index = $_GET['rimuovi'];
    unset($_SESSION['carrello'][$index]);
    $_SESSION['carrello'] = array_values($_SESSION['carrello']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #333; color: white; text-align: center; padding: 10px 0; }
        .container { width: 90%; margin: 20px auto; display: flex; flex-wrap: wrap; justify-content: space-between; }
        .prodotto { background-color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 30%; margin-bottom: 40px; padding: 10px; border-radius: 8px; text-align: center; }
        .button { background-color: #2ecc71; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; margin: 5px; }
        .button:hover { background-color: #27ae60; }
        footer { display: flex; justify-content: center; align-items: center; padding: 20px 0; background-color: #333; }
        .footer-buttons { display: flex; justify-content: space-around; width: 50%; }
    </style>
</head>
<body>
<header><h1>Cart</h1></header>
<div class="container">
    <?php if (!empty($_SESSION['carrello'])): ?>
        <?php foreach ($_SESSION['carrello'] as $index => $prodotto): ?>
            <div class="prodotto">
                <img src="<?php echo $prodotto['immagine']; ?>" alt="<?php echo $prodotto['nome']; ?>">
                <h3><?php echo $prodotto['nome']; ?></h3>
                <p class="descrizione"><?php echo $prodotto['descrizione']; ?></p>
                <p class="prezzo">€ <?php echo number_format($prodotto['prezzo'], 2, ',', '.'); ?></p>
                <a href="carrello.php?rimuovi=<?php echo $index; ?>" class="button">Remove from Cart</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>The cart is empty.</p>
    <?php endif; ?>
</div>
<footer>
    <div class="footer-buttons">
        <a href="index.php" class="button">Return to Shop</a>
        <a href="acquista.php" class="button">Checkout</a>
        <a href="ricarica.php" class="button">Recharge Balance</a>
    </div>
</footer>
</body>
</html>
