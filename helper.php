<?php

function site_url() {
	$protocol    = ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ? 'https://' : 'http://';
	$domain_name = $_SERVER['HTTP_HOST'];
	return $protocol . $domain_name;
}

define( 'SITE_URL', site_url() );

function is_user_logged_in() {
	return isset( $_SESSION['logged_in'] ) && $_SESSION['logged_in'] === true;
}

function is_admin_user() {
	require_once 'models/user.php';
	$user = User::get_user_by_id( $_SESSION['id'] );
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

function update_link_views( $link_id, $ip_address ) {
	$db               = DB::getInstance();
	$current_date     = date( 'Y-m-d' );
	$current_datetime = date( 'Y-m-d H:i:s' );

	$stmt = $db->prepare( 'SELECT views_count, viewed_ips, date FROM link_views WHERE short_url = :short_url ORDER BY date DESC LIMIT 1' );
	$stmt->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
	$stmt->execute();
	$result = $stmt->fetch( PDO::FETCH_ASSOC );

	if ( $result ) {
		$views_count = $result['views_count'] + 1;
		$viewed_ips  = json_decode( $result['viewed_ips'], true );

		if ( ! in_array( $ip_address, $viewed_ips ) ) {
			$viewed_ips[] = $ip_address;
			$last_date    = $result['date'];

			if ( $last_date != $current_date ) {
				$stmt_insert = $db->prepare( 'INSERT INTO link_views (short_url, views_count, viewed_ips, date) VALUES (:short_url, :views_count, :viewed_ips, :current_date)' );
				$stmt_insert->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
				$stmt_insert->bindParam( ':views_count', $views_count, PDO::PARAM_INT );
				$stmt_insert->bindParam( ':viewed_ips', $json_viewed_ips, PDO::PARAM_STR );
				$stmt_insert->bindParam( ':current_date', $current_date, PDO::PARAM_STR );
				$stmt_insert->execute();
			} else {
				$stmt_update = $db->prepare( 'UPDATE link_views SET views_count = :views_count, viewed_ips = :viewed_ips, viewed_at = :viewed_at WHERE short_url = :short_url' );
				$stmt_update->bindParam( ':views_count', $views_count, PDO::PARAM_INT );
				$stmt_update->bindParam( ':viewed_ips', json_encode( $viewed_ips ), PDO::PARAM_STR );
				$stmt_update->bindParam( ':viewed_at', $current_datetime, PDO::PARAM_STR );
				$stmt_update->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
				$stmt_update->execute();
			}
		}
	} else {
		$views_count     = 1;
		$viewed_ips      = array( $ip_address );
		$json_viewed_ips = json_encode( $viewed_ips );

		$stmt_insert = $db->prepare( 'INSERT INTO link_views (short_url, views_count, viewed_ips, date) VALUES (:short_url, :views_count, :viewed_ips, :current_date)' );
		$stmt_insert->bindParam( ':short_url', $link_id, PDO::PARAM_STR );
		$stmt_insert->bindParam( ':views_count', $views_count, PDO::PARAM_INT );
		$stmt_insert->bindParam( ':viewed_ips', $json_viewed_ips, PDO::PARAM_STR );
		$stmt_insert->bindParam( ':current_date', $current_date, PDO::PARAM_STR );
		$stmt_insert->execute();
	}

	return true;
}
