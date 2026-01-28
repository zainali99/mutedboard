<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Database Configuration' ?></title>
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
