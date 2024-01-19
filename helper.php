<?php

require_once 'includes/class.country.php';

function site_url() {
	$protocol    = ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ? 'https://' : 'http://';
	$domain_name = $_SERVER['HTTP_HOST'];
	return $protocol . $domain_name;
}

define( 'SITE_URL', site_url() );

function is_user_logged_in() {
	return isset( $_COOKIE['user_token'] ) && isset( $_SESSION['user_id'] );
}

function is_admin_user() {
	require_once 'models/user.php';
	$user = User::get_user_by_id( $_SESSION['user_id'] );
	return $user['role'] == 1;
}

function generateRandomString( $length = 10 ) {
	$characters       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$charactersLength = strlen( $characters );
	$randomString     = '';

	for ( $i = 0; $i < $length; $i++ ) {
		$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
	}

	return $randomString;
}

// Function to check and update link_view_ips
function update_link_view_ips( $db, $link_id, $ip_address, $current_datetime ) {
	$db   = DB::getInstance();
	$stmt = $db->prepare( 'SELECT ips FROM link_view_ips WHERE short_url = :short_url' );
	$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	$stmt->execute();
	$ips           = $stmt->fetchColumn();
	$updateViewIps = false;

	if ( $ips ) {
		$viewed_ips = unserialize( $ips );

		if ( ! in_array( $ip_address, $viewed_ips ) ) {
			$viewed_ips[]  = $ip_address;
			$updateViewIps = true;
		} else {
			$updateViewIps = false;
		}
	} else {
		$viewed_ips    = array( $ip_address );
		$updateViewIps = true;
	}

	if ( $updateViewIps ) {
		$stmt_update = $db->prepare( 'INSERT INTO link_view_ips (short_url, ips, created_at, updated_at) VALUES (:short_url, :viewed_ips, :current_datetime, :current_datetime) ON DUPLICATE KEY UPDATE ips = :viewed_ips, updated_at = :current_datetime' );
		$stmt_update->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
		$stmt_update->bindParam( ':viewed_ips', serialize( $viewed_ips ), PDO::PARAM_STR );
		$stmt_update->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
		$stmt_update->execute();

		return true;
	}

	return false;
}

function update_link_views( $link_id, $ip_address ) {
	$db               = DB::getInstance();
	$current_datetime = date( 'Y-m-d H:i:s' );
	// Check and update link_view_ips
	$next = update_link_view_ips( $db, $link_id, $ip_address, $current_datetime );

	if ( $next ) {
		// Get view count in database.
		$stmt = $db->prepare( 'INSERT INTO link_view_count (short_url, views_count, date) VALUES (:short_url, 1, :current_date) ON DUPLICATE KEY UPDATE views_count = views_count + 1' );
		$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
		$stmt->bindParam( ':current_date', date( 'Y-m-d' ), PDO::PARAM_STR );
		$stmt->execute();

		// Get view country in database.
		$country             = new Country();
		$ip                  = $country->getIpAddress();
		$is_valid_ip_address = $country->isValidIpAddress( $ip );

		if ( $is_valid_ip_address !== '' ) {
			$geo_location_data = $country->getLocation( $ip );

			$stmt_update_country = $db->prepare( 'INSERT INTO link_view_country (short_url, view, country, country_code, created_at, updated_at) VALUES (:short_url, 1, :country, :country_code, :current_datetime, :current_datetime) ON DUPLICATE KEY UPDATE view = view + 1' );
			$stmt_update_country->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
			$stmt_update_country->bindParam( ':country', $geo_location_data['country'], PDO::PARAM_STR );
			$stmt_update_country->bindParam( ':country_code', $geo_location_data['country_code'], PDO::PARAM_STR );
			$stmt_update_country->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
			$stmt_update_country->execute();
		}
	}

	return true;

	// $stmt2 = $db->prepare( 'SELECT views_count, date FROM link_view_count WHERE short_url = :short_url ORDER BY date DESC LIMIT 1' );
	// $stmt2->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	// $stmt2->execute();
	// $view_count_data = $stmt2->fetch( PDO::FETCH_ASSOC );

	// if ( $ips ) {
	// $views_count = $view_count_data['view_count'] + 1;
	// $viewed_ips  = unserialize( $ips );
	// if ( ! in_array( $ip_address, $viewed_ips ) ) {
	// $viewed_ips[] = $ip_address;
	// $last_date    = $view_count_data['date'];

	// if ( $last_date != $current_date ) {
	// $view_count = 1;
	// $stmt_insert = $db->prepare( 'INSERT INTO link_views (short_url, views_count, viewed_ips, date) VALUES (:short_url, :views_count, :viewed_ips, :current_date)' );
	// $stmt_insert->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	// $stmt_insert->bindParam( ':views_count', $view_count, PDO::PARAM_INT );
	// $stmt_insert->bindParam( ':viewed_ips', json_encode( $viewed_ips ), PDO::PARAM_STR );
	// $stmt_insert->bindParam( ':current_date', $current_date, PDO::PARAM_STR );
	// $stmt_insert->execute();
	// } else {
	// $stmt_update = $db->prepare( 'UPDATE link_views SET views_count = :views_count, viewed_ips = :viewed_ips, viewed_at = :viewed_at WHERE short_url = :short_url AND date = :last_date' );
	// $stmt_update->bindParam( ':views_count', $views_count, PDO::PARAM_INT );
	// $stmt_update->bindParam( ':viewed_ips', json_encode( $viewed_ips ), PDO::PARAM_STR );
	// $stmt_update->bindParam( ':viewed_at', $current_datetime, PDO::PARAM_STR );
	// $stmt_update->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	// $stmt_update->bindParam( ':last_date', $last_date, PDO::PARAM_STR );
	// $stmt_update->execute();
	// }
	// }
	// } else {
	// $views_count     = 1;
	// $viewed_ips      = array( $ip_address );
	// $json_viewed_ips = json_encode( $viewed_ips );

	// $stmt_insert = $db->prepare( 'INSERT INTO link_views (short_url, views_count, viewed_ips, date) VALUES (:short_url, :views_count, :viewed_ips, :current_date)' );
	// $stmt_insert->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	// $stmt_insert->bindParam( ':views_count', $views_count, PDO::PARAM_INT );
	// $stmt_insert->bindParam( ':viewed_ips', $json_viewed_ips, PDO::PARAM_STR );
	// $stmt_insert->bindParam( ':current_date', $current_date, PDO::PARAM_STR );
	// $stmt_insert->execute();
	// }
}

// Hàm để hiển thị lượt xem theo ngày, tháng và tất cả thời gian
function display_link_views( $shortUrl ) {
	$db = DB::getInstance();

	$currentDate  = date( 'Y-m-d' ); // Ngày hiện tại
	$currentMonth = date( 'Y-m' ); // Tháng hiện tại

	// Hiển thị lượt xem cho ngày hiện tại
	$dailyViewsQuery     = 'SELECT IFNULL(SUM(views_count), 0) as daily_views FROM link_view_count WHERE short_url = :shortUrl AND date = :currentDate';
	$dailyViewsStatement = $db->prepare( $dailyViewsQuery );
	$dailyViewsStatement->bindParam( ':shortUrl', $shortUrl, PDO::PARAM_STR );
	$dailyViewsStatement->bindParam( ':currentDate', $currentDate, PDO::PARAM_STR );
	$dailyViewsStatement->execute();

	echo 'Hôm nay: ' . $dailyViewsStatement->fetch( PDO::FETCH_ASSOC )['daily_views'] . '<br>';

	// Hiển thị lượt xem cho tháng hiện tại
	$monthlyViewsQuery     = "SELECT IFNULL(SUM(views_count), 0) as monthly_views FROM link_view_count WHERE short_url = :shortUrl AND DATE_FORMAT(date, '%Y-%m') = :currentMonth";
	$monthlyViewsStatement = $db->prepare( $monthlyViewsQuery );
	$monthlyViewsStatement->bindParam( ':shortUrl', $shortUrl, PDO::PARAM_STR );
	$monthlyViewsStatement->bindParam( ':currentMonth', $currentMonth, PDO::PARAM_STR );
	$monthlyViewsStatement->execute();

	echo 'Tháng này: ' . $monthlyViewsStatement->fetch( PDO::FETCH_ASSOC )['monthly_views'] . '<br>';

	// Hiển thị tổng lượt xem toàn thời gian
	// $totalViewsQuery     = 'SELECT IFNULL(SUM(views_count), 0) as total_views FROM link_views WHERE short_url = :shortUrl';
	// $totalViewsStatement = $db->prepare( $totalViewsQuery );
	// $totalViewsStatement->bindParam( ':shortUrl', $shortUrl, PDO::PARAM_STR );
	// $totalViewsStatement->execute();

	// echo 'Tất cả: ' . $totalViewsStatement->fetch( PDO::FETCH_ASSOC )['total_views'] . '';

	$db = null;
}

function is_active_menu_item( $menu_item, $action = '' ) {
	$is_active = false;
	if ( isset( $_GET['controller'] ) && $menu_item === $_GET['controller'] ) {
		if ( '' !== $action ) {
			if ( isset( $_GET['action'] ) && $action === $_GET['action'] ) {
				$is_active = true;
			}
		} else {
			$is_active = true;
		}
	}

	return $is_active;
}
