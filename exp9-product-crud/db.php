<?php
$conn = mysqli_connect("localhost:3307", "root", "", "product_db");

if (!$conn) {
    $conn = mysqli_connect("127.0.0.1", "root", "", "product_db", 3307);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

if (!mysqli_select_db($conn, "product_db")) {
    mysqli_query($conn, "CREATE DATABASE product_db");
    mysqli_select_db($conn, "product_db");
    
    mysqli_query($conn, "CREATE TABLE products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(50),
        stock_quantity INT(11) DEFAULT 0,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}
?>