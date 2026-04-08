<?php
// Initialize variables
$name = $email = $phone = $message = "";
$name_err = $email_err = $phone_err = $message_err = "";
$success_msg = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Name
    if (empty($_POST["name"])) {
        $name_err = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $name_err = "Only letters and spaces allowed";
        }
    }
    
    // Validate Email
    if (empty($_POST["email"])) {
        $email_err = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        }
    }
    
    // Validate Phone
    if (!empty($_POST["phone"])) {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phone_err = "Enter valid 10-digit phone number";
        }
    }
    
    // Validate Message
    if (empty($_POST["message"])) {
        $message_err = "Message is required";
    } else {
        $message = test_input($_POST["message"]);
        if (strlen($message) < 10) {
            $message_err = "Message must be at least 10 characters";
        }
    }
    
    // If no errors, save data
    if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($message_err)) {
        $success_msg = "Thank you $name! Your message has been sent.";
        $data = date("Y-m-d H:i:s") . " | Name: $name | Email: $email | Phone: $phone | Message: $message\n";
        file_put_contents("contacts.txt", $data, FILE_APPEND);
        $name = $email = $phone = $message = "";
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
    <title>Exercise 1 - Contact Form with PHP Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #f0f2f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1a2b4c;
            border-bottom: 3px solid #1a2b4c;
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
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background: #1a2b4c;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #2a3b5c;
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
        .proof-section {
            margin-top: 50px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #1a2b4c;
        }
        .proof-section h3 {
            color: #1a2b4c;
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
        
        <h2>📧 Exercise 1: Contact Form</h2>
        <p><strong>PHP Form Handling & Validation</strong></p>
        
        <?php if($success_msg): ?>
            <div class="success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Full Name: *</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <div class="error"><?php echo $name_err; ?></div>
            
            <label>Email Address: *</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <div class="error"><?php echo $email_err; ?></div>
            
            <label>Phone Number:</label>
            <input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="10 digits only">
            <div class="error"><?php echo $phone_err; ?></div>
            
            <label>Message: *</label>
            <textarea name="message" rows="5"><?php echo $message; ?></textarea>
            <div class="error"><?php echo $message_err; ?></div>
            
            <button type="submit">Send Message</button>
            <button type="reset">Clear Form</button>
        </form>
        
        <!-- PROOF 1: PHP CODE SCREENSHOT BELOW THE FORM -->
        <div class="proof-section">
            <h3>📸 Proof: PHP Code Used in This Form</h3>
            <?php if(file_exists("proof1.png")): ?>
                <img src="proof1.png" alt="PHP Code Proof - Exercise 1">
            <?php else: ?>
                <div class="proof-placeholder">
                    ⚠️ proof1.png not found.<br>
                    Please add the screenshot showing the PHP validation code from this file.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>