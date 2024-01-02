<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortify</title>
    <link rel="stylesheet" href="http://url-shortify.test/assets/uikit/css/uikit.min.css">
    <link rel="stylesheet" href="http://url-shortify.test/assets/css/main.css">
</head>
<body>
    <?php include_once('header.php'); ?>
    
    <div class="site-main uk-container">
        <?= @$content ?>
    </div>
    
    <?php include_once('footer.php'); ?>

    <script src="http://url-shortify.test/assets/uikit/js/uikit.min.js"></script>
    <script src="http://url-shortify.test/assets/uikit/js/uikit-icons.min.js"></script>
</body>
</html>