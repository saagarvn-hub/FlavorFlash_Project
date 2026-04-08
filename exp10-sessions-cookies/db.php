<?php
// Database connection for XAMPP (port 3307)
$conn = mysqli_connect("localhost:3307", "root", "", "ecommerce_db");

if (!$conn) {
    $conn = mysqli_connect("127.0.0.1", "root", "", "ecommerce_db", 3307);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

// Create database if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS ecommerce_db");
mysqli_select_db($conn, "ecommerce_db");

// Create users table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create products table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255)
)");

// Insert sample products if empty
$check = mysqli_query($conn, "SELECT * FROM products");
if(mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO products (name, price, description, image) VALUES
        ('Margherita Pizza', 199, 'Cheesy delight with fresh tomatoes', 'pizza'),
        ('Classic Burger', 99, 'Juicy patty with cheese and lettuce', 'burger'),
        ('White Sauce Pasta', 179, 'Creamy and delicious pasta', 'pasta'),
        ('French Fries', 89, 'Crispy golden fries', 'fries'),
        ('Cold Coffee', 69, 'Refreshing cold coffee', 'coffee')
    ");
}
?>