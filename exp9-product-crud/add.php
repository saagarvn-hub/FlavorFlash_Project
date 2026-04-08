<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock_quantity'];
    
    // Handle image upload
    $image_url = "";
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['product_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $new_filename = time() . "_" . preg_replace('/[^a-zA-Z0-9]/', '_', $name) . "." . $ext;
            $upload_path = "uploads/" . $new_filename;
            
            // Create uploads folder if not exists
            if(!file_exists("uploads")) {
                mkdir("uploads", 0777, true);
            }
            
            if(move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
                $image_url = $upload_path;
            }
        }
    }
    
    // If no image uploaded, use placeholder based on category
    if(empty($image_url)) {
        $placeholders = [
            'Pizza' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=300',
            'Burger' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300',
            'Pasta' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?w=300',
            'Fries' => 'https://images.unsplash.com/photo-1630384060421-cf20c0e2f7b1?w=300',
            'Beverages' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300',
            'Desserts' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=300'
        ];
        $image_url = isset($placeholders[$category]) ? $placeholders[$category] : 'https://via.placeholder.com/300x200/667eea/white?text=' . urlencode($name);
    }
    
    $sql = "INSERT INTO products (product_name, description, price, category, stock_quantity, image_url) 
            VALUES ('$name', '$desc', '$price', '$category', '$stock', '$image_url')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
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
    <title>Add Product - FlavorFlash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            color: #666;
            margin-top: 5px;
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
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-input {
            padding: 10px;
            background: #f8f9fa;
            border: 2px dashed #667eea;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            color: #667eea;
            text-decoration: none;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .image-preview {
            margin-top: 10px;
            display: none;
        }

        .image-preview img {
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="fas fa-plus-circle"></i> Add New Product</h1>
                <p>Fill in the details to add a delicious item</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Product Name *</label>
                    <input type="text" name="product_name" required placeholder="e.g., Margherita Pizza">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description" placeholder="Describe your product..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Price (₹) *</label>
                    <input type="number" step="0.01" name="price" required placeholder="199.99">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-folder"></i> Category</label>
                    <select name="category">
                        <option value="Pizza">🍕 Pizza</option>
                        <option value="Burger">🍔 Burger</option>
                        <option value="Pasta">🍝 Pasta</option>
                        <option value="Fries">🍟 Fries</option>
                        <option value="Beverages">🥤 Beverages</option>
                        <option value="Desserts">🍰 Desserts</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-boxes"></i> Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="0">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Product Image</label>
                    <input type="file" name="product_image" class="file-input" accept="image/*" onchange="previewImage(this)">
                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Add Product</button>
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
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; border-radius: 10px; margin-top: 10px;">';
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>