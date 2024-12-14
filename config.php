<?php
$servername = "localhost"; // Server name (typically "localhost" for local servers)
$username = "root";        // Database username
$password = "";            // Password associated with the user
$dbname = "ecommerce_db";  // Name of the database to use

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate script if the connection fails
}
?>
