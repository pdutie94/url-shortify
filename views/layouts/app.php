<?php
if ( isset( $_COOKIE['user_token'] ) && isset( $_SESSION['user_id'] ) ) {
	$user_id = $_SESSION['user_id'];

	$expiration_time = time() + 3600;
	setcookie( 'user_token', $user_id, $expiration_time, '/' );
}

$title = is_user_logged_in() ? get_site_title() : 'Đăng nhập';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/uikit/css/uikit.min.css">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body class="<?php echo get_body_class(); ?>">
	<?php if ( is_user_logged_in() ) { ?>
		<?php include_once 'header.php'; ?>
	<?php } ?>

	<div class="site-main uk-container">

		<?php echo @$content; ?>
	</div>


	<?php if ( is_user_logged_in() ) { ?>
		<?php include_once 'footer.php'; ?>
	<?php } ?>

	

	<script src="<?php echo SITE_URL; ?>/assets/uikit/js/uikit.min.js"></script>
	<script	script src="<?php echo SITE_URL; ?>/assets/uikit/js/uikit-icons.min.js"></script>
	<script>
		/* <![CDATA[ */
		var site_params = {'site_url': '<?php echo SITE_URL; ?>'}
		/* ]]> */
	</script>
	<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/dashboard.js"></script>
	<?php if ( isset( $_GET['controller'] ) ) { ?>
		<?php if ( 'login' === $_GET['controller'] ) { ?>
			<script src="<?php echo SITE_URL; ?>/assets/js/login.js"></script>
		<?php } ?>

		<?php if ( 'links' === $_GET['controller'] && ! isset( $_GET['action'] ) ) { ?>
			<script src="<?php echo SITE_URL; ?>/assets/js/shortlink.js"></script>
		<?php } ?>
	<?php } ?>
</body>

</html>
