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

-- Relational schema of the tables in geometric form:

/*
[users]----------------------------
| id (PK)                        |
| username                       |
| password                       |
| portafoglio                    |
----------------------------------
        |
        | (1:N)
        ↓
[orders]--------------------------
| id (PK)                        |
| id_utente (FK) → users.id      |
| totale                         |
| data_ordine                    |
----------------------------------

[products]------------------------
| id (PK)                        |
| nome                           |
| prezzo                         |
| descrizione                    |
| immagine                       |
----------------------------------

DERIVED TABLES:

Users(id, username, password, portafoglio)
Orders(id, id_utente, totale, data_ordine)
Products(id, nome, prezzo, descrizione, immagine)

*/