<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Installation Complete' ?></title>
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
