<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'System Requirements' ?></title>
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
        .requirement {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .requirement.pass {
            border-left: 4px solid #28a745;
        }
        .requirement.fail {
            border-left: 4px solid #dc3545;
            background: #fff5f5;
        }
        .req-name {
            font-weight: 500;
            color: #333;
        }
        .req-value {
            color: #666;
            font-size: 14px;
        }
        .status {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-icon {
            font-size: 20px;
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
            margin-top: 20px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn:disabled, .btn.disabled {
            background: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }
        .alert {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert.error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 System Requirements</h1>
        <div class="subtitle">Checking if your system meets the requirements...</div>
        
        <?php if (!$canProceed): ?>
            <div class="alert error">
                <strong>⚠️ Warning:</strong> Some required components are missing. Please fix the issues below before continuing.
            </div>
        <?php endif; ?>
        
        <div class="requirements-list">
            <?php foreach ($requirements as $key => $req): ?>
                <div class="requirement <?= $req['status'] ? 'pass' : 'fail' ?>">
                    <div>
                        <div class="req-name"><?= htmlspecialchars($req['name']) ?></div>
                        <div class="req-value"><?= htmlspecialchars($req['current']) ?></div>
                    </div>
                    <div class="status">
                        <span class="status-icon"><?= $req['status'] ? '✅' : '❌' ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($canProceed): ?>
            <a href="/install/database" class="btn">Continue →</a>
        <?php else: ?>
            <a href="#" class="btn disabled">Fix Requirements First</a>
        <?php endif; ?>
    </div>
</body>
</html>
