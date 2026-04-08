<?php
ob_start();
session_start();

if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$cart_count = isset($_COOKIE['cart_count']) ? $_COOKIE['cart_count'] : 0;

// Product data
$products = [
    ['id' => 1, 'name' => 'Margherita Pizza', 'price' => 199, 'icon' => '🍕', 'desc' => 'Fresh tomatoes, mozzarella, basil', 'badge' => 'Bestseller'],
    ['id' => 2, 'name' => 'Classic Burger', 'price' => 99, 'icon' => '🍔', 'desc' => 'Juicy beef patty with cheese', 'badge' => 'Popular'],
    ['id' => 3, 'name' => 'White Sauce Pasta', 'price' => 179, 'icon' => '🍝', 'desc' => 'Creamy Alfredo sauce', 'badge' => 'New'],
    ['id' => 4, 'name' => 'French Fries', 'price' => 89, 'icon' => '🍟', 'desc' => 'Crispy golden fries', 'badge' => ''],
    ['id' => 5, 'name' => 'Cold Coffee', 'price' => 69, 'icon' => '☕', 'desc' => 'Refreshing cold brew', 'badge' => ''],
    ['id' => 6, 'name' => 'Chocolate Shake', 'price' => 129, 'icon' => '🥤', 'desc' => 'Rich chocolate milkshake', 'badge' => 'Limited']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorFlash - Dashboard</title>
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
            background: #f8f9fa;
        }

        .navbar {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo h2 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .cart-link {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #ff5722;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-btn {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 20px;
            border-radius: 25px;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: white;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 30px 40px;
            padding: 40px;
            border-radius: 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-text h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .welcome-text p {
            opacity: 0.9;
        }

        .session-badge {
            background: rgba(255,255,255,0.2);
            padding: 15px 25px;
            border-radius: 15px;
            text-align: center;
        }

        .session-badge .session-id {
            font-family: monospace;
            font-size: 12px;
            opacity: 0.8;
        }

        .products-section {
            padding: 0 40px 40px;
        }

        .section-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ff5722;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            z-index: 1;
        }

        .product-icon {
            font-size: 80px;
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa, #fff);
        }

        .product-info {
            padding: 20px;
            text-align: center;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .product-desc {
            font-size: 13px;
            color: #888;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 15px;
        }

        .add-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .add-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .session-card {
            background: white;
            margin: 0 40px 30px;
            padding: 20px;
            border-radius: 15px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-around;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .info-item {
            text-align: center;
        }

        .info-item i {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .info-item .label {
            font-size: 12px;
            color: #888;
        }

        .info-item .value {
            font-weight: 600;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .navbar, .welcome-banner {
                margin: 15px;
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .products-section {
                padding: 0 20px 20px;
            }
            .session-card {
                margin: 0 20px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <h2><i class="fas fa-utensils"></i> FlavorFlash</h2>
        </div>
        <div class="nav-links">
            <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="cart.php" class="cart-link">
                <i class="fas fa-shopping-cart"></i> Cart
                <span class="cart-count"><?php echo $cart_count; ?></span>
            </a>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="welcome-banner">
        <div class="welcome-text">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>! <?php echo $_SESSION['avatar']; ?></h1>
            <p>Craving something delicious? Order your favorite food now!</p>
        </div>
        <div class="session-badge">
            <i class="fas fa-clock"></i> Logged in: <?php echo $_SESSION['login_time']; ?><br>
            <span class="session-id"><i class="fas fa-id-card"></i> Session: <?php echo substr(session_id(), 0, 8); ?>...</span>
        </div>
    </div>

    <div class="session-card">
        <div class="info-item">
            <i class="fas fa-user-circle"></i>
            <div class="label">Logged in as</div>
            <div class="value"><?php echo htmlspecialchars($_SESSION['user']); ?></div>
        </div>
        <div class="info-item">
            <i class="fas fa-cookie-bite"></i>
            <div class="label">Cookie Status</div>
            <div class="value"><?php echo isset($_COOKIE['remember_user']) ? '✅ Active (7 days)' : '📱 Session only'; ?></div>
        </div>
        <div class="info-item">
            <i class="fas fa-shopping-cart"></i>
            <div class="label">Cart Items</div>
            <div class="value"><?php echo $cart_count; ?> items</div>
        </div>
        <div class="info-item">
            <i class="fas fa-tachometer-alt"></i>
            <div class="label">Session ID</div>
            <div class="value" style="font-family: monospace; font-size: 12px;"><?php echo substr(session_id(), 0, 12); ?>...</div>
        </div>
    </div>

    <div class="products-section">
        <h2 class="section-title"><i class="fas fa-fire"></i> Today's Specials</h2>
        <div class="products-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <?php if($product['badge']): ?>
                        <div class="product-badge"><?php echo $product['badge']; ?></div>
                    <?php endif; ?>
                    <div class="product-icon">
                        <?php echo $product['icon']; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?php echo $product['name']; ?></div>
                        <div class="product-desc"><?php echo $product['desc']; ?></div>
                        <div class="product-price">₹<?php echo $product['price']; ?></div>
                        <button class="add-btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>)">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 FlavorFlash | Experiment 10 - Session & Cookies | Delicious Food Delivery</p>
    </footer>

    <script>
        function addToCart(id, name, price) {
            let cart = getCookie('shopping_cart');
            if(cart) {
                cart = JSON.parse(cart);
            } else {
                cart = [];
            }
            cart.push({id: id, name: name, price: price});
            setCookie('shopping_cart', JSON.stringify(cart), 7);
            setCookie('cart_count', cart.length, 7);
            
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Added!';
            btn.style.background = '#4caf50';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                location.reload();
            }, 800);
        }
        
        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + "; expires=" + date.toUTCString() + "; path=/";
        }
        
        function getCookie(name) {
            const cookies = document.cookie.split(';');
            for(let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if(cookie.startsWith(name + '=')) {
                    return cookie.substring(name.length + 1);
                }
            }
            return null;
        }
    </script>
</body>
</html>