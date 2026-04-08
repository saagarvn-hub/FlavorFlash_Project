<!DOCTYPE html>
<html>
<head>
    <title>Experiment 8 - PHP Form Handling & Validation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            text-align: center;
            padding: 40px;
        }
        
        h1 {
            color: white;
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .subtitle {
            color: white;
            font-size: 18px;
            margin-bottom: 50px;
            opacity: 0.9;
        }
        
        .buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            background: white;
            color: #667eea;
            padding: 20px 50px;
            font-size: 24px;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        .btn-exercise1 {
            color: #1a2b4c;
        }
        
        .btn-exercise2 {
            color: #4CAF50;
        }
        
        .footer {
            margin-top: 60px;
            color: white;
            font-size: 14px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐘 Experiment 8</h1>
        <div class="subtitle">PHP Form Handling & Validation</div>
        
        <div class="buttons">
            <a href="exercise1.php" class="btn btn-exercise1">📧 Exercise 1<br><small style="font-size: 14px;">Contact Form</small></a>
            <a href="exercise2.php" class="btn btn-exercise2">🛒 Exercise 2<br><small style="font-size: 14px;">Order Form</small></a>
        </div>
        
        <div class="footer">
            ✅ Server-side validation | ✅ Data sanitization | ✅ Error messages | ✅ File storage
        </div>
    </div>
</body>
</html>