<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MutedBoard Framework' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>


    <div class="container">
        <header>
            <div class="container">
                <h1><?= $title ?? 'MutedBoard' ?></h1>
                <nav>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="/dashboard">Dashboard</a></li>
                            <li><a href="/logout">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
                        <?php else: ?>
                            <li><a href="/login">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </header>



        <main>
            <?= $content ?>
        </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> MutedBoard Framework. All rights reserved.</p>
        </div>
    </footer>

    </div>



    <script src="/js/app.js"></script>
    <script src="/js/main.js"></script>
</body>
</html>
