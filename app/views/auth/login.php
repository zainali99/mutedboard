<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
 
    <style>
        * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
                    line-height: 1.6;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    padding: 20px;
                }
                
                .login-container {
                    width: 100%;
                    max-width: 400px;
                    padding: 40px;
                    border: 1px solid #ddd;
                }
                
                .logo {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .logo h1 {
                    font-size: 28px;
                    margin-bottom: 8px;
                }
                
                .logo p {
                    font-size: 14px;
                }
                
                .alert {
                    padding: 12px;
                    margin-bottom: 20px;
                    border: 1px solid #000;
                }
                
                .form-group {
                    margin-bottom: 20px;
                }
                
                .form-group label {
                    display: block;
                    margin-bottom: 6px;
                    font-weight: 500;
                }
                
                .form-group input {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ddd;
                    font-size: 14px;
                }
                
                .checkbox-group {
                    margin-bottom: 20px;
                }
                
                .checkbox-group input {
                    margin-right: 6px;
                }
                
                .btn {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #000;
                    background: #000;
                    color: #fff;
                    font-size: 16px;
                    cursor: pointer;
                }
                
                .btn:hover {
                    background: #333;
                }
                
                .footer {
                    text-align: center;
                    margin-top: 20px;
                }
                
                .footer a {
                    text-decoration: none;
                    color: #000;
                }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🔐 MutedBoard</h1>
            <p>Sign in to your account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form action="/auth/authenticate" method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>
            
            <button type="submit" class="btn">Sign In</button>
        </form>
        
        <div class="footer">
            <a href="/">← Back to Home</a>
        </div>
    </div>
</body>
</html>
