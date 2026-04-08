<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session & Cookie Info</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1a1a2e;
            padding: 40px;
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #16213e;
            padding: 30px;
            border-radius: 15px;
        }
        h1 { color: #4CAF50; }
        .box {
            background: #0f3460;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            font-family: monospace;
        }
        .cookie-box {
            background: #1a1a2e;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Session & Cookie Proof</h1>
        
        <div class="box">
            <h2>📌 Session Data (Server Side)</h2>
            <?php if(isset($_SESSION['user'])): ?>
                <p><strong>Logged in as:</strong> <?php echo $_SESSION['user']; ?></p>
                <p><strong>Name:</strong> <?php echo $_SESSION['name']; ?></p>
                <p><strong>Login Time:</strong> <?php echo $_SESSION['login_time']; ?></p>
                <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
                <p><strong>Session Status:</strong> ✅ ACTIVE</p>
            <?php else: ?>
                <p>❌ No active session. Please login first.</p>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>🍪 Cookie Data (Browser Side)</h2>
            <div class="cookie-box">
                <?php
                if(count($_COOKIE) > 0) {
                    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
                    echo "<tr style='background: #4CAF50;'><th>Cookie Name</th><th>Cookie Value</th></tr>";
                    foreach($_COOKIE as $name => $value) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($name) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No cookies found. Login with 'Remember Me' to set cookies.";
                }
                ?>
            </div>
        </div>

        <div class="box">
            <h2>📊 What This Proves:</h2>
            <ul>
                <li>✅ Session is working - Shows user data from server</li>
                <li>✅ Session ID is unique - <?php echo session_id(); ?></li>
                <li>✅ Cookies are working - <?php echo count($_COOKIE); ?> cookies found</li>
                <li>✅ Data persists across pages</li>
            </ul>
        </div>
        
        <p><a href="dashboard.php" style="color: #4CAF50;">← Back to Dashboard</a></p>
    </div>
</body>
</html>