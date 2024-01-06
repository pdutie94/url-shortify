<?php
require_once '../helper.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="canonical" href="<?php echo SITE_URL; ?>">
		<meta property="og:url" content="<?php echo SITE_URL; ?>">
		<meta property="og:title" content="Flatsomix">
		<title>Flatsomix</title>
		<?php
		if ( isset( $_GET['url'] ) ) {
			$long_url = urldecode( $_GET['url'] );
			?>
			<script>window.location.href = '<?php echo $long_url; ?>';</script>
		<?php } ?>
	</head>
	<body>
		<h1>Redirecting...</h1>
	</body>
</html>
