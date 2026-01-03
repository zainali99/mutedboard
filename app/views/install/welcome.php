<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MutedBoard Installation' ?></title>
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
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .welcome-content {
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }
        .welcome-content h2 {
            color: #667eea;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .welcome-content ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .welcome-content li {
            margin-bottom: 8px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .steps h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .steps ol {
            margin-left: 20px;
        }
        .steps li {
            margin-bottom: 8px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Welcome to MutedBoard</h1>
        <div class="subtitle">Let's get your board up and running!</div>
        
        <div class="welcome-content">
            <p>Thank you for choosing MutedBoard! This installer will guide you through the setup process in just a few simple steps.</p>
            
            <h2>What we'll do:</h2>
            <div class="steps">
                <ol>
                    <li>Check system requirements</li>
                    <li>Configure database connection</li>
                    <li>Create necessary database tables</li>
                    <li>Complete installation</li>
                </ol>
            </div>
            
            <h2>Before you begin:</h2>
            <ul>
                <li>Make sure you have a MySQL database ready</li>
                <li>Have your database credentials handy</li>
                <li>Ensure PHP 7.4 or higher is installed</li>
            </ul>
        </div>
        
        <a href="/install/requirements" class="btn">Get Started →</a>
    </div>
</body>
</html>
