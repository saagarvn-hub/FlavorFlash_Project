<?php
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Get image to delete
$result = mysqli_query($conn, "SELECT image_url FROM products WHERE id = $id");
if($row = mysqli_fetch_assoc($result)) {
    if($row['image_url'] && strpos($row['image_url'], 'uploads/') === 0 && file_exists($row['image_url'])) {
        unlink($row['image_url']); // Delete image file
    }
}

mysqli_query($conn, "DELETE FROM products WHERE id = $id");
header("Location: index.php");
exit();
?>