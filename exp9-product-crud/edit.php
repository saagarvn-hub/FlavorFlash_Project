<?php
ob_start();
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

if(mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($result);
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock_quantity'];
    $image_url = $product['image_url']; // Keep existing image by default
    
    // Handle image upload
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['product_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            // Create uploads folder if not exists
            if(!file_exists("uploads")) {
                mkdir("uploads", 0777, true);
            }
            
            // Delete old image if exists
            if($product['image_url'] && file_exists($product['image_url'])) {
                unlink($product['image_url']);
            }
            
            $new_filename = time() . "_" . preg_replace('/[^a-zA-Z0-9]/', '_', $name) . "." . $ext;
            $upload_path = "uploads/" . $new_filename;
            
            if(move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
                $image_url = $upload_path;
            }
        }
    }
    
    // Update database
    $sql = "UPDATE products SET 
            product_name='$name', 
            description='$desc', 
            price='$price', 
            category='$category', 
            stock_quantity='$stock',
            image_url='$image_url'
            WHERE id=$id";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Product updated successfully!";
        // Refresh product data
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        $product = mysqli_fetch_assoc($result);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - FlavorFlash</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 28px;
            background: linear-gradient(135deg, #ffc107, #e0a800);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        label i {
            color: #ffc107;
            margin-right: 8px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255,193,7,0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #333;
            padding: 14px;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255,193,7,0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            color: #ffc107;
            text-decoration: none;
        }

        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .current-image {
            margin-top: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
        }

        .current-image img {
            max-width: 150px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .image-preview {
            margin-top: 10px;
            display: none;
        }

        .image-preview img {
            max-width: 150px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="fas fa-edit"></i> Edit Product</h1>
                <p>Update your product details</p>
            </div>

            <?php if($error): ?>
                <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Product Name *</label>
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Price (₹) *</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-folder"></i> Category</label>
                    <select name="category">
                        <option value="Pizza" <?php echo ($product['category'] == 'Pizza') ? 'selected' : ''; ?>>🍕 Pizza</option>
                        <option value="Burger" <?php echo ($product['category'] == 'Burger') ? 'selected' : ''; ?>>🍔 Burger</option>
                        <option value="Pasta" <?php echo ($product['category'] == 'Pasta') ? 'selected' : ''; ?>>🍝 Pasta</option>
                        <option value="Fries" <?php echo ($product['category'] == 'Fries') ? 'selected' : ''; ?>>🍟 Fries</option>
                        <option value="Beverages" <?php echo ($product['category'] == 'Beverages') ? 'selected' : ''; ?>>🥤 Beverages</option>
                        <option value="Desserts" <?php echo ($product['category'] == 'Desserts') ? 'selected' : ''; ?>>🍰 Desserts</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-boxes"></i> Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Product Image</label>
                    <input type="file" name="product_image" class="file-input" accept="image/*" onchange="previewImage(this)">
                    <div class="image-preview" id="imagePreview"></div>
                    <?php if($product['image_url']): ?>
                        <div class="current-image">
                            <small>Current Image:</small><br>
                            <img src="<?php echo $product['image_url']; ?>" alt="Current">
                            <p><small>Upload new image to replace this one</small></p>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update Product</button>
            </form>

            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Products</a>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if(input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 150px; border-radius: 10px; margin-top: 10px;">';
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>