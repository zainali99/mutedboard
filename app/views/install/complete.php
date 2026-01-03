<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Installation Complete' ?></title>
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
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 1s ease;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .next-steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }
        .next-steps h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .next-steps ol {
            margin-left: 20px;
        }
        .next-steps li {
            margin-bottom: 10px;
            color: #555;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 14px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
            font-size: 16px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">🎉</div>
        <h1>Installation Complete!</h1>
        <div class="subtitle">MutedBoard is ready to use</div>
        
        <div class="success-message">
            <strong>Congratulations!</strong><br>
            Your MutedBoard installation has been completed successfully. All database tables have been created and the system is ready to use.
        </div>
        
        <div class="warning">
            <strong>⚠️ Important:</strong> For security reasons, the installer has been locked. Delete the <code>.installed</code> file to run the installer again.
        </div>
        
        <div class="next-steps">
            <h3>📝 Next Steps:</h3>
            <ol>
                <li>Create your first admin account</li>
                <li>Configure your board settings</li>
                <li>Create groups and start discussions</li>
                <li>Customize the appearance (optional)</li>
            </ol>
        </div>
        
        <a href="/" class="btn">Go to Homepage →</a>
    </div>
</body>
</html>
