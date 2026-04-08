<?php
include 'db.php';
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorFlash - Admin Panel</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .logo h1 {
            font-size: 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo p {
            color: #666;
            font-size: 12px;
        }

        .add-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 40px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 28px;
            color: #333;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .product-image {
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .product-desc {
            color: #666;
            font-size: 13px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .price-stock {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .stock {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e8f5e9;
            color: #4caf50;
        }

        .stock.low {
            background: #fff3e0;
            color: #ff9800;
        }

        .stock.out {
            background: #ffebee;
            color: #f44336;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .edit-btn, .delete-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .edit-btn {
            background: #ffc107;
            color: #333;
        }

        .edit-btn:hover {
            background: #e0a800;
            transform: scale(1.02);
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
            transform: scale(1.02);
        }

        .no-data {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
            color: #999;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: white;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h1><i class="fas fa-utensils"></i> FlavorFlash Admin</h1>
                <p>Manage your delicious products</p>
            </div>
            <a href="add.php" class="add-btn"><i class="fas fa-plus"></i> Add New Product</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <i class="fas fa-box"></i>
                <h3><?php echo mysqli_num_rows($result); ?></h3>
                <p>Total Products</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-tag"></i>
                <h3><?php 
                    $cat_result = mysqli_query($conn, "SELECT COUNT(DISTINCT category) as cats FROM products");
                    $cat_row = mysqli_fetch_assoc($cat_result);
                    echo $cat_row['cats'];
                ?></h3>
                <p>Categories</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>24/7</h3>
                <p>Active Orders</p>
            </div>
        </div>

        <div class="products-grid">
            <?php 
            // Fallback images (used only if database image_url is empty)
            $fallback_images = [
                'Pizza' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=250&fit=crop',
                'Burger' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=250&fit=crop',
                'Pasta' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?w=400&h=250&fit=crop',
                'Fries' => 'https://images.unsplash.com/photo-1630384060421-cf20c0e2f7b1?w=400&h=250&fit=crop',
                'Beverages' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=250&fit=crop',
                'Desserts' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&h=250&fit=crop'
            ];
            
            if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $stock_class = $row['stock_quantity'] == 0 ? 'out' : ($row['stock_quantity'] < 10 ? 'low' : '');
                    $stock_text = $row['stock_quantity'] == 0 ? 'Out of Stock' : ($row['stock_quantity'] . ' in stock');
                    
                    // PRIORITY 1: Use image_url from database if it exists
                    if(!empty($row['image_url']) && file_exists($row['image_url'])) {
                        $image_url = $row['image_url'];
                    } 
                    // PRIORITY 2: Use image_url from database (even if external URL)
                    elseif(!empty($row['image_url'])) {
                        $image_url = $row['image_url'];
                    }
                    // PRIORITY 3: Use fallback images based on category
                    else {
                        $image_url = isset($fallback_images[$row['category']]) 
                            ? $fallback_images[$row['category']] 
                            : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=250&fit=crop';
                    }
                ?>
                    <div class="product-card">
                        <div class="product-image" style="background-image: url('<?php echo $image_url; ?>'); background-size: cover; background-position: center;">
                            <span class="category-badge"><i class="fas fa-tag"></i> <?php echo $row['category']; ?></span>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></div>
                            <div class="product-desc"><?php echo substr(htmlspecialchars($row['description']), 0, 80); ?>...</div>
                            <div class="price-stock">
                                <span class="price">₹<?php echo number_format($row['price'], 2); ?></span>
                                <span class="stock <?php echo $stock_class; ?>"><i class="fas fa-box"></i> <?php echo $stock_text; ?></span>
                            </div>
                            <div class="product-actions">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?')"><i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 20px;"></i>
                    <h3>No Products Yet</h3>
                    <p>Click "Add New Product" to get started!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>&copy; 2026 FlavorFlash | Delicious Food Delivery</p>
        </div>
    </div>
</body>
</html>