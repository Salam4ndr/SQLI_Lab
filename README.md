
---

# Blind SQL Injection Lab Setup with XAMPP

This document guides you through setting up a **Blind SQL Injection** lab using **XAMPP**, and configuring the database `ecommerce_db` with the necessary tables and data.

## Requirements

Before you begin, make sure you have the following installed:

- **XAMPP**: A local server solution including Apache, MySQL, PHP, and Perl.
- **A web browser**: To test the lab.

## Step 1: Install XAMPP

1. Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   
2. After installation, open **XAMPP Control Panel**.

3. Start the following services:
   - **Apache** (for the web server)
   - **MySQL** (for the database server)

4. Ensure that Apache and MySQL are running by navigating to `http://localhost/` in your browser. You should see the XAMPP welcome page.

## Step 2: Create the Database

1. Open your browser and go to **http://localhost/phpmyadmin**.

2. Click on **New** in the left sidebar to create a new database. Name it `ecommerce_db`.

3. Once the database is created, select it, and proceed to create the tables as described below.

## Step 3: SQL Script to Set Up the Database

Copy and paste the following SQL code into **phpMyAdmin** (or any MySQL client) to set up the database structure:

```sql
CREATE DATABASE ecommerce_db; -- Creation of the database

-- Creation of the "orders" table
-- This table stores the orders placed by users.
-- Each order has a unique ID, an associated user ID, a total, and a creation date.
CREATE TABLE ordini (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each order
    id_utente INT(11) NOT NULL,            -- ID of the user who placed the order
    totale DECIMAL(10,2) NOT NULL,         -- Total amount of the order, with 2 decimal places
    data_ordine TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Creation date and time of the order
    FOREIGN KEY (id_utente) REFERENCES utenti(id) -- FK on id_utente referencing utenti.id
);

-- Creation of the "products" table
-- This table stores the products available in the system.
-- Each product has a unique ID, a name, a price, an optional description, and an optional image.
CREATE TABLE prodotti (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each product
    nome VARCHAR(100) NOT NULL,            -- Name of the product
    prezzo DECIMAL(10,2) NOT NULL,         -- Price of the product, with 2 decimal places
    descrizione TEXT NULL,                 -- Description of the product (optional)
    immagine VARCHAR(255) NULL             -- Path or URL to the product image (optional)
);

-- Creation of the "users" table
-- This table stores the users registered in the system.
-- Each user has a unique ID, a username, a password, and an initial wallet balance.
CREATE TABLE utenti (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each user
    username VARCHAR(50) NOT NULL,         -- Username
    password VARCHAR(255) NOT NULL,        -- User's password (encrypt in production!)
    portafoglio DECIMAL(10,2) DEFAULT 100.00 -- Initial wallet balance, with 2 decimal places
);

-- Inserting a record into the "users" table
-- This command inserts a new user with username 'admin' and password 'password123'.
-- Note: In a real application, make sure to encrypt the password before inserting it into the database.
INSERT INTO utenti (username, password) VALUES ('admin', 'password123');

-- Inserting records into the "products" table
INSERT INTO prodotti (id, nome, prezzo, descrizione, immagine) VALUES
(1, 'Smartphone XYZ', 299.99, 'A smartphone with advanced features.', 'https://via.placeholder.com/150'),
(2, 'Bluetooth Headphones', 99.99, 'High-quality wireless headphones.', 'https://via.placeholder.com/150'),
(3, 'Laptop Pro', 899.99, 'A powerful laptop for work and play.', 'https://via.placeholder.com/150'),
(4, 'Smartwatch Series 5', 199.99, 'A stylish and functional smartwatch.', 'https://via.placeholder.com/150'),
(5, 'Wireless Mouse', 29.99, 'Ergonomic wireless mouse for comfortable use.', 'https://via.placeholder.com/150');
```

Once you've executed the above SQL script, the database `ecommerce_db` will contain the following tables:

- **utenti** (Users)
- **ordini** (Orders)
- **prodotti** (Products)

## Step 4: Place the Files in the `htdocs` Folder

1. Go to the **htdocs** directory in your XAMPP installation (usually located at `C:\xampp\htdocs`).

2. Create a folder named `blind_sqli_lab`.

3. Place your existing PHP files (such as `login.php` and others) inside the `blind_sqli_lab` folder.

Make sure that the PHP files are set up correctly to handle the database connections and the form submission.

## Step 5: Test the Vulnerability

Once everything is set up:

1. Open your browser and navigate to `http://localhost/blind_sqli_lab/login.php` (or any other page where you want to test SQL Injection).
2. Try submitting the form with various SQL injection payloads to see how the system responds (e.g., input like `' OR 1=1 --` or `' AND 1=0 --`).
3. Observe how the system behaves with valid and invalid inputs.

You can now test **Blind SQL Injection** by using techniques like time-based or boolean-based inference to determine if the database is vulnerable.

---

## Conclusion

This setup will help you test and explore **Blind SQL Injection** in a controlled environment. By exploiting the vulnerabilities in the PHP scripts (e.g., `login.php`), you can learn how Blind SQL Injection works and how it can be mitigated. Make sure to use proper security measures in production, such as parameterized queries or prepared statements, to prevent such attacks.

