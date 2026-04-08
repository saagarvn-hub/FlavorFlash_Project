<?php
ob_start();
session_start();

if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$cart_items = [];
if(isset($_COOKIE['shopping_cart'])) {
    $cart_items = json_decode($_COOKIE['shopping_cart'], true);
}
$cart_count = count($cart_items);
$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'];
}

// Handle remove
if(isset($_GET['remove'])) {
    $index = $_GET['remove'];
    array_splice($cart_items, $index, 1);
    setcookie('shopping_cart', json_encode($cart_items), time() + (86400 * 7), "/");
    setcookie('cart_count', count($cart_items), time() + (86400 * 7), "/");
    header("Location: cart.php");
    exit();
}

// Handle clear
if(isset($_GET['clear'])) {
    setcookie('shopping_cart', '', time() - 3600, "/");
    setcookie('cart_count', 0, time() - 3600, "/");
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - FlavorFlash</title>
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
        }

        .logo h2 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cart-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cart-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h1 {
            font-size: 24px;
        }

        .clear-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .clear-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .cart-items {
            padding: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .item-icon {
            font-size: 40px;
            background: #f8f9fa;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
        }

        .item-details h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .item-details p {
            color: #888;
            font-size: 12px;
        }

        .item-price {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
        }

        .remove-btn {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s;
        }

        .remove-btn:hover {
            background: #dc2626;
            color: white;
        }

        .cart-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total {
            font-size: 24px;
        }

        .total span {
            font-size: 14px;
            color: #888;
        }

        .total .amount {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }

        .empty-cart {
            text-align: center;
            padding: 60px;
        }

        .empty-cart i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            color: #888;
            margin-bottom: 20px;
        }

        .empty-cart a {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
        }

        .cookie-info {
            background: #e8f4f8;
            margin: 20px;
            padding: 15px;
            border-radius: 15px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-around;
            font-size: 12px;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .cart-footer {
                flex-direction: column;
                gap: 20px;
                text-align: center;
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
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="cart-card">
            <div class="cart-header">
                <h1><i class="fas fa-shopping-cart"></i> Your Cart</h1>
                <?php if($cart_count > 0): ?>
                    <a href="?clear=1" class="clear-btn" onclick="return confirm('Clear all items from cart?')"><i class="fas fa-trash"></i> Clear Cart</a>
                <?php endif; ?>
            </div>

            <?php if($cart_count > 0): ?>
                <div class="cart-items">
                    <?php 
                    $icons = ['🍕', '🍔', '🍝', '🍟', '☕', '🥤'];
                    foreach($cart_items as $index => $item): 
                    ?>
                        <div class="cart-item">
                            <div class="item-info">
                                <div class="item-icon"><?php echo $icons[$index % count($icons)]; ?></div>
                                <div class="item-details">
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p>Freshly prepared • Premium quality</p>
                                </div>
                            </div>
                            <div class="item-price">₹<?php echo $item['price']; ?></div>
                            <a href="?remove=<?php echo $index; ?>" class="remove-btn" onclick="return confirm('Remove this item?')"><i class="fas fa-times"></i> Remove</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-footer">
                    <div class="total">
                        <span>Total Amount</span><br>
                        <span class="amount">₹<?php echo $total; ?></span>
                    </div>
                    <button class="checkout-btn" onclick="checkout()"><i class="fas fa-credit-card"></i> Proceed to Checkout</button>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-basket"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any items yet.</p>
                    <br>
                    <a href="dashboard.php"><i class="fas fa-utensils"></i> Browse Menu</a>
                </div>
            <?php endif; ?>

            <div class="cookie-info">
                <div><i class="fas fa-cookie-bite"></i> <strong>Cookie Storage</strong><br>Cart data stored in browser cookies</div>
                <div><i class="fas fa-clock"></i> <strong>Expires</strong><br>7 days from now</div>
                <div><i class="fas fa-box"></i> <strong>Items in Cookie</strong><br><?php echo $cart_count; ?> items</div>
                <div><i class="fas fa-id-card"></i> <strong>Session ID</strong><br><?php echo substr(session_id(), 0, 10); ?>...</div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 FlavorFlash | Experiment 10 - Session & Cookies | Data stored in cookies for 7 days</p>
    </footer>

    <script>
        function checkout() {
            if(confirm('🎉 Proceed to checkout?\n\nTotal amount: ₹<?php echo $total; ?>')) {
                alert('🎉 Order placed successfully!\n\nThank you for shopping with FlavorFlash!\nYour order will be delivered in 30-45 minutes.');
                // Clear cart after checkout
                document.cookie = "shopping_cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "cart_count=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = "dashboard.php";
            }
        }
    </script>
</body>
</html>