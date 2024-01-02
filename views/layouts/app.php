<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortify</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/uikit/css/uikit.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/main.css">
    <script src="<?= SITE_URL ?>/assets/uikit/js/uikit.min.js"></script>
    <script src="<?= SITE_URL ?>/assets/uikit/js/uikit-icons.min.js"></script>
</head>
<body>
    <?php include_once('header.php'); ?>
    
    <div class="site-main uk-container">
        <?= @$content ?>
    </div>
    
    <?php include_once('footer.php'); ?>

    <?php if( isset($_GET['controller']) && 'login' === $_GET['controller'] ) { ?>
        <script src="<?= SITE_URL ?>/assets/js/login.js"></script>
    <?php } ?>
</body>
</html>