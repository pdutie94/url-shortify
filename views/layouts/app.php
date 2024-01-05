<?php
$title = is_user_logged_in() ? 'Dashboard' : "Đăng nhập";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/uikit/css/uikit.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/main.css">
    <script src="<?= SITE_URL ?>/assets/uikit/js/uikit.min.js"></script>
    <script src="<?= SITE_URL ?>/assets/uikit/js/uikit-icons.min.js"></script>
</head>
<body>
    <?php if (is_user_logged_in()) { ?>
        <?php include_once('header.php'); ?>
    <?php } ?>
    
    <div class="site-main uk-container">
        <?= @$content ?>
    </div>
    
    <?php if (is_user_logged_in()) { ?>
        <?php include_once('footer.php'); ?>
    <?php } ?>
    <script src="<?= SITE_URL ?>/assets/js/main.js"></script>
    <?php if( isset($_GET['controller']) ) { ?>
        <?php if ( 'login' === $_GET['controller'] ) { ?>
            <script src="<?= SITE_URL ?>/assets/js/login.js"></script>
        <?php } ?>
        <?php if ( 'links' === $_GET['controller'] && ! isset( $_GET['action'] ) ) { ?>
            <script>/* <![CDATA[ */
            var site_params = {'site_url': '<?= SITE_URL; ?>'}
            /* ]]> */</script>
            <script src="<?= SITE_URL ?>/assets/js/shortlink.js"></script>
        <?php } ?>
    <?php } ?>
</body>
</html>