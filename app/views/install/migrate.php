<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Database Migration' ?></title>
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
