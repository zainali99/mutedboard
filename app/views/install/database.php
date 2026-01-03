<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Database Configuration' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .alert {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .help-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .info-box p {
            color: #0c5460;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Database Configuration</h1>
        <div class="subtitle">Enter your database connection details</div>
        
        <?php if ($error): ?>
            <div class="alert">
                <strong>Connection Failed:</strong><br>
                <?= nl2br(htmlspecialchars($error)) ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <p>💡 If the database doesn't exist, we'll create it for you automatically!</p>
        </div>
        
        <form action="/install/test-connection" method="POST">
            <div class="form-group">
                <label for="host">Database Host</label>
                <input type="text" id="host" name="host" value="<?= htmlspecialchars($config['host'] ?? '127.0.0.1') ?>" required>
                <div class="help-text">Usually '127.0.0.1' or 'localhost'</div>
            </div>
            
            <div class="form-group">
                <label for="dbname">Database Name</label>
                <input type="text" id="dbname" name="dbname" value="<?= htmlspecialchars($config['dbname'] ?? 'mutedboard') ?>" required>
                <div class="help-text">The name of your database</div>
            </div>
            
            <div class="form-group">
                <label for="username">Database Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($config['username'] ?? 'root') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Database Password</label>
                <input type="password" id="password" name="password" value="<?= htmlspecialchars($config['password'] ?? '') ?>">
                <div class="help-text">Leave empty if no password is set</div>
            </div>
            
            <button type="submit" class="btn">Test Connection & Continue →</button>
        </form>
    </div>
</body>
</html>
