<?php
// Initialize variables
$fullname = $phone = $address = $payment = "";
$fullname_err = $phone_err = $address_err = $payment_err = "";
$order_success = "";
$order_id = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Full Name
    if (empty($_POST["fullname"])) {
        $fullname_err = "Full name is required";
    } else {
        $fullname = test_input($_POST["fullname"]);
        if (!preg_match("/^[a-zA-Z ]{3,50}$/", $fullname)) {
            $fullname_err = "Name must be 3-50 characters (letters only)";
        }
    }
    
    // Validate Phone
    if (empty($_POST["phone"])) {
        $phone_err = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
            $phone_err = "Enter valid 10-digit mobile number";
        }
    }
    
    // Validate Address
    if (empty($_POST["address"])) {
        $address_err = "Delivery address is required";
    } else {
        $address = test_input($_POST["address"]);
        if (strlen($address) < 10) {
            $address_err = "Address must be at least 10 characters";
        }
    }
    
    // Validate Payment
    if (empty($_POST["payment"])) {
        $payment_err = "Please select a payment method";
    } else {
        $payment = $_POST["payment"];
    }
    
    // If no errors, process order
    if (empty($fullname_err) && empty($phone_err) && empty($address_err) && empty($payment_err)) {
        $order_id = "ORD" . date("Ymd") . rand(1000, 9999);
        $order_success = "Order placed successfully!";
        
        $order_data = "========================================\n";
        $order_data .= "Order ID: $order_id\n";
        $order_data .= "Date: " . date("Y-m-d H:i:s") . "\n";
        $order_data .= "Customer: $fullname\n";
        $order_data .= "Phone: $phone\n";
        $order_data .= "Address: $address\n";
        $order_data .= "Payment: " . ucfirst($payment) . "\n";
        $order_data .= "========================================\n\n";
        
        file_put_contents("orders.txt", $order_data, FILE_APPEND);
        $fullname = $phone = $address = $payment = "";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exercise 2 - Order Form with PHP Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h2 {
            color: #4CAF50;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
            display: inline-block;
        }
        .back-btn {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .back-btn:hover {
            background: #5a6268;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: -8px;
            margin-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        .order-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #4CAF50;
        }
        .proof-section {
            margin-top: 50px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #4CAF50;
        }
        .proof-section h3 {
            color: #4CAF50;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .proof-section img {
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .proof-placeholder {
            background: #e9ecef;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">← Back to Home</a>
        
        <h2>🛒 Exercise 2: Order Form</h2>
        <p><strong>PHP Form Handling & Validation</strong></p>
        
        <?php if($order_success): ?>
            <div class="success">
                <?php echo $order_success; ?><br>
                Your Order ID: <strong><?php echo $order_id; ?></strong>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Full Name: *</label>
            <input type="text" name="fullname" value="<?php echo $fullname; ?>" placeholder="Enter your full name">
            <div class="error"><?php echo $fullname_err; ?></div>
            
            <label>Phone Number: *</label>
            <input type="tel" name="phone" value="<?php echo $phone; ?>" placeholder="10-digit mobile number">
            <div class="error"><?php echo $phone_err; ?></div>
            
            <label>Delivery Address: *</label>
            <textarea name="address" rows="3" placeholder="Full address with landmark"><?php echo $address; ?></textarea>
            <div class="error"><?php echo $address_err; ?></div>
            
            <label>Payment Method: *</label>
            <select name="payment">
                <option value="">-- Select Payment Method --</option>
                <option value="cod" <?php echo ($payment == "cod") ? "selected" : ""; ?>>Cash on Delivery</option>
                <option value="upi" <?php echo ($payment == "upi") ? "selected" : ""; ?>>UPI / Google Pay</option>
                <option value="card" <?php echo ($payment == "card") ? "selected" : ""; ?>>Credit/Debit Card</option>
            </select>
            <div class="error"><?php echo $payment_err; ?></div>
            
            <button type="submit">Place Order</button>
            <button type="reset">Reset Form</button>
        </form>
        
        <?php if(!empty($order_id)): ?>
        <div class="order-summary">
            <strong>📋 Order Summary:</strong><br>
            Order ID: <?php echo $order_id; ?><br>
            Customer: <?php echo $fullname; ?><br>
            Payment: <?php echo ucfirst($payment); ?>
        </div>
        <?php endif; ?>
        
        <!-- PROOF 2: PHP CODE SCREENSHOT BELOW THE FORM -->
        <div class="proof-section">
            <h3>📸 Proof: PHP Code Used in This Form</h3>
            <?php if(file_exists("proof2.png")): ?>
                <img src="proof2.png" alt="PHP Code Proof - Exercise 2">
            <?php else: ?>
                <div class="proof-placeholder">
                    ⚠️ proof2.png not found.<br>
                    Please add the screenshot showing the PHP validation code from this file.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>