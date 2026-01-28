<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MutedBoard Installation' ?></title>
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
