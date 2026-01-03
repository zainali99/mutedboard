<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Database Migration' ?></title>
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
            max-width: 700px;
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
            margin-bottom: 10px;
        }
        .info-box ul {
            margin-left: 20px;
            color: #0c5460;
        }
        .info-box li {
            margin-bottom: 5px;
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
        .message {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .messages {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Database Migration</h1>
        <div class="subtitle">Creating database tables...</div>
        
        <?php if (!empty($errors) || !empty($success)): ?>
            <div class="messages">
                <?php foreach ($success as $msg): ?>
                    <div class="message success">
                        <span>✅</span>
                        <span><?= htmlspecialchars($msg) ?></span>
                    </div>
                <?php endforeach; ?>
                
                <?php foreach ($errors as $msg): ?>
                    <div class="message error">
                        <span>❌</span>
                        <span><?= htmlspecialchars($msg) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (!empty($errors)): ?>
                <form action="/install/migrate" method="POST">
                    <button type="submit" class="btn">Try Again</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <div class="info-box">
                <p>We're about to create the following database tables:</p>
                <ul>
                    <li><strong>users</strong> - Store user accounts and authentication</li>
                    <li><strong>groups</strong> - Discussion groups/forums</li>
                    <li><strong>threads</strong> - Discussion threads within groups</li>
                    <li><strong>posts</strong> - User posts/replies in threads</li>
                    <li><strong>group_members</strong> - Group membership tracking</li>
                </ul>
                <p style="margin-top: 15px;">Click below to start the migration process.</p>
            </div>
            
            <form action="/install/migrate" method="POST">
                <button type="submit" class="btn">Create Tables →</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
