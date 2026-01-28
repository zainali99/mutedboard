<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MutedBoard Framework' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/components.js"></script>



</head>
<body>

<div class="container">
    <div class="sidebar">
        <h1>MutedBoard <?php echo $_version ?></h1>
    </div>
    <div class="main-content">
        <?= $content ?>
    </div>


</div>






</body>
</html>
