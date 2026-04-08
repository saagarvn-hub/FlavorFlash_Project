<?php
ob_start();
session_start();

// Database connection
$conn = mysqli_connect("localhost:3307", "root", "", "user_db");

if (!$conn) {
    $conn = mysqli_connect("127.0.0.1", "root", "", "user_db", 3307);
    if (!$conn) {
        $conn = mysqli_connect("localhost", "root", "");
    }
}

// Create database and table if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS user_db");
mysqli_select_db($conn, "user_db");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$error = "";
$success = "";

// Handle Registration
if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['reg_name']);
    $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['reg_confirm_password'];
    
    // Validation
    if(empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif($password != $confirm_password) {
        $error = "Passwords do not match!";
    } elseif(strlen($password) < 4) {
        $error = "Password must be at least 4 characters!";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if(mysqli_num_rows($check) > 0) {
            $error = "Email already registered! Please login.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if(mysqli_query($conn, $insert)) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}

// Handle Login
if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if($row = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user'] = $row['email'];
            $_SESSION['name'] = $row['username'];
            $_SESSION['avatar'] = "👤";
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            
            if(isset($_POST['remember'])) {
                setcookie('remember_user', $email, time() + (86400 * 7), "/");
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found! Please register first.";
    }
}

// Check for remember me cookie
if(!isset($_SESSION['user']) && isset($_COOKIE['remember_user'])) {
    $email = $_COOKIE['remember_user'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user'] = $row['email'];
        $_SESSION['name'] = $row['username'];
        $_SESSION['avatar'] = "👤";
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorFlash - Login & Register</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveDots 20s linear infinite;
            opacity: 0.3;
        }

        @keyframes moveDots {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            max-width: 1100px;
            width: 90%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '🍔';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            bottom: -50px;
            right: -50px;
            transform: rotate(-15deg);
        }

        .left-side h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .left-side p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .features {
            margin-top: 30px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .feature i {
            width: 30px;
            font-size: 20px;
        }

        .right-side {
            flex: 1;
            padding: 50px;
            background: white;
        }

        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            color: #999;
            transition: all 0.3s;
            position: relative;
        }

        .tab-btn.active {
            color: #667eea;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: #667eea;
            border-radius: 3px;
        }

        .form {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .form.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .input-group i {
            margin-right: 8px;
            color: #667eea;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }

        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .btn-login, .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover, .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-credentials {
            margin-top: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            font-size: 12px;
            text-align: center;
        }

        .demo-credentials p {
            margin: 5px 0;
            color: #666;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .left-side {
                padding: 30px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <h1><i class="fas fa-utensils"></i> FlavorFlash</h1>
            <p>Experience the finest food delivery with our premium service. Fresh, fast, and delicious!</p>
            <div class="features">
                <div class="feature"><i class="fas fa-check-circle"></i> <span>Fresh Ingredients</span></div>
                <div class="feature"><i class="fas fa-truck"></i> <span>Fast Delivery</span></div>
                <div class="feature"><i class="fas fa-shield-alt"></i> <span>Secure Payment</span></div>
            </div>
        </div>
        <div class="right-side">
            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('login')">🔐 Login</button>
                <button class="tab-btn" onclick="showTab('register')">📝 Register</button>
            </div>

            <!-- Login Form -->
            <div id="login-form" class="form active">
                <?php if($error && !isset($_POST['register'])): ?>
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="input-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" placeholder="••••••" required>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me for 7 days</label>
                    </div>
                    <button type="submit" name="login" class="btn-login"><i class="fas fa-arrow-right"></i> Login</button>
                </form>
                
                <div class="demo-credentials">
                    <p><i class="fas fa-info-circle"></i> <strong>New user?</strong> Click "Register" to create an account</p>
                    <p><i class="fas fa-database"></i> All data stored in database</p>
                </div>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="form">
                <?php if($error && isset($_POST['register'])): ?>
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="success-message"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="input-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" name="reg_name" placeholder="John Doe" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="reg_email" placeholder="your@email.com" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="reg_password" placeholder="Min 4 characters" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Confirm Password</label>
                        <input type="password" name="reg_confirm_password" placeholder="Re-enter password" required>
                    </div>
                    <button type="submit" name="register" class="btn-register"><i class="fas fa-user-plus"></i> Create Account</button>
                </form>
                
                <div class="demo-credentials">
                    <p><i class="fas fa-info-circle"></i> <strong>Already have an account?</strong> Login above</p>
                    <p><i class="fas fa-shield-alt"></i> Your password is securely encrypted</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const buttons = document.querySelectorAll('.tab-btn');
            
            if(tab === 'login') {
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
                buttons[0].classList.add('active');
                buttons[1].classList.remove('active');
            } else {
                loginForm.classList.remove('active');
                registerForm.classList.add('active');
                buttons[0].classList.remove('active');
                buttons[1].classList.add('active');
            }
        }
    </script>
</body>
</html>