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
function update_link_view_ips( $link_id, $ip_address, $current_datetime ) {
	$db   = DB::getInstance();
	$stmt = $db->prepare( 'SELECT ips FROM link_view_ips WHERE short_url = :short_url' );
	$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	$stmt->execute();
	$ips = $stmt->fetchColumn();

	if ( $ips ) {
		$viewed_ips = unserialize( $ips );

		if ( ! in_array( $ip_address, $viewed_ips ) ) {
			$viewed_ips[] = $ip_address;
			$stmt_update  = $db->prepare( 'UPDATE link_view_ips SET ips=:viewed_ips, updated_at=:current_datetime WHERE short_url=:short_url' );
			$stmt_update->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
			$stmt_update->bindParam( ':viewed_ips', serialize( $viewed_ips ), PDO::PARAM_STR );
			$stmt_update->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
			$stmt_update->execute();
		} else {
			return false;
		}
	} else {
		$viewed_ips  = array( $ip_address );
		$stmt_update = $db->prepare( 'INSERT INTO link_view_ips (short_url, ips, created_at, updated_at) VALUES (:short_url, :viewed_ips, :current_datetime, :current_datetime)' );
		$stmt_update->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
		$stmt_update->bindParam( ':viewed_ips', serialize( $viewed_ips ), PDO::PARAM_STR );
		$stmt_update->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
		$stmt_update->execute();
	}
	// var_dump($updateViewIps );
	// exit;

	return true;
}

function update_link_views( $link_id, $ip_address ) {
	$db               = DB::getInstance();
	$current_datetime = date( 'Y-m-d H:i:s' );
	// Check and update link_view_ips
	$next = update_link_view_ips( $link_id, $ip_address, $current_datetime );

	if ( $next ) {
		// Get view count in database.
		$stmt = $db->prepare( 'SELECT short_url FROM link_view_count WHERE short_url = :short_url AND date=:current_date' );
		$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
		$stmt->bindParam( ':current_date', date( 'Y-m-d' ), PDO::PARAM_STR );
		$stmt->execute();
		$result = $stmt->fetchColumn();
		// Update view count
		if ( $result ) {
			$stmt = $db->prepare( 'UPDATE link_view_count SET views_count=views_count + 1 WHERE short_url=:short_url AND date=:current_date' );
			$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
			$stmt->bindParam( ':current_date', date( 'Y-m-d' ), PDO::PARAM_STR );
			$stmt->execute();
		} else {
			$stmt = $db->prepare( 'INSERT INTO link_view_count (short_url, views_count, date) VALUES (:short_url, 1, :current_date)' );
			$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
			$stmt->bindParam( ':current_date', date( 'Y-m-d' ), PDO::PARAM_STR );
			$stmt->execute();
		}

		// Get view country in database.
		$country             = new Country();
		$ip                  = $country->getIpAddress();
		$is_valid_ip_address = $country->isValidIpAddress( $ip );
		if ( $is_valid_ip_address !== '' ) {
			$geo_location_data = $country->getLocation( $ip );

			$stmt = $db->prepare( 'SELECT country_code FROM link_view_country WHERE short_url = :short_url AND country_code=:country_code' );
			$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
			$stmt->bindParam( ':country_code', $geo_location_data['country_code'], PDO::PARAM_STR );
			$stmt->execute();
			$country_code = $stmt->fetchColumn();

			if ( $country_code ) {
				$stmt_update_country = $db->prepare( 'UPDATE link_view_country SET view=view+1, updated_at=:current_datetime WHERE short_url=:short_url AND country_code=:country_code' );
				$stmt_update_country->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
				$stmt_update_country->bindParam( ':country_code', $country_code, PDO::PARAM_STR );
				$stmt_update_country->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
				$stmt_update_country->execute();
			} else {
					$stmt_update_country = $db->prepare( 'INSERT INTO link_view_country (short_url, view, country, country_code, created_at, updated_at) VALUES (:short_url, 1, :country, :country_code, :current_datetime, :current_datetime)' );
					$stmt_update_country->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
					$stmt_update_country->bindParam( ':country', $geo_location_data['country'], PDO::PARAM_STR );
					$stmt_update_country->bindParam( ':country_code', $geo_location_data['country_code'], PDO::PARAM_STR );
					$stmt_update_country->bindParam( ':current_datetime', $current_datetime, PDO::PARAM_STR );
					$stmt_update_country->execute();
			}
		}
	}

	return true;
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

function get_site_title() {
	$title = 'Dashboard';
	if ( isset( $_GET['controller'] ) ) {
		switch ( $_GET['controller'] ) {
			case 'links':
				$title = 'Danh sách link';
				break;
			case 'user':
				$title = 'Thành viên';
				break;
			default:
				$title = 'Dashboard';
		}
	}
	return $title;
}

function get_body_class() {
	$classes = array();
	if ( isset( $_GET['controller'] ) ) {
		switch ( $_GET['controller'] ) {
			case 'links':
				$classes[] = 'page-link';
				break;
			case 'user':
				$classes[] = 'page-user';
				break;
			default:
				$classes[] = 'page-dashboard';
		}
	} else {
		$classes[] = 'page-dashboard';
	}
	return implode( ' ', $classes );
}
