<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'System Requirements' ?></title>
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
