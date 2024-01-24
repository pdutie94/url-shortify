<?php

date_default_timezone_set( 'Asia/Ho_Chi_Minh' ); // Chọn múi giờ tương ứng

if ( session_status() == PHP_SESSION_NONE ) {
	session_start();
}
require '../connection.php';
require '../models/user.php';
require '../models/link.php';
require '../helper.php';

if ( isset( $_POST['action_name'] ) ) {
	$action = $_POST['action_name'];

	switch ( $action ) {
		case 'generate_short_url_id':
			generate_short_url_id();
			break;
		case 'save_short_url_id':
			save_short_url_id();
			break;
		case 'refresh_dashboard_data':
			refresh_dashboard_data();
			break;
		default:
			break;
	}
}

function get_country_html( $data ) {
	$total_views_all_countries = array_sum( array_column( $data, 'total_views' ) );

	foreach ( $data as &$country ) {
		$country['percentage'] = round( ( $country['total_views'] / $total_views_all_countries ) * 100 );
	}
	ob_start();
	?>
	<?php if ( count( $data ) > 0 ) { ?>
		<?php
		foreach ( $data as $k => $country_data ) {
			$index = $k + 1;
			?>
			<tr>
				<td><?php echo $index; ?></td>
				<td><?php echo $country_data['country']; ?></td>
				<td class="uk-visible@s"><progress class="uk-progress" value="<?php echo $country_data['percentage']; ?>" max="100"></progress></td>
				<td class="uk-text-right"><?php echo $country_data['total_views']; ?></td>
				<td class="uk-text-right"><?php echo $country_data['percentage'] . '%'; ?></td>
			</tr>
		<?php } ?>
	<?php } ?>
	<?php
	$html = ob_get_clean();
	return $html;
}

function get_user_list_html( $users ) {
	ob_start();
	?>
	<?php if ( count( $users ) > 0 ) { ?>
		<?php
		foreach ( $users as $k => $user ) {
			$user_id      = $user['id'];
			$daily_view   = User::get_view_in_day( $user_id );
			$weekly_view  = Link::get_all_weekly_views( $user_id );
			$monthly_view = User::get_view_in_month( $user_id );
			?>
			<div class="user-list-item my-margin-bottom">
				<?php if ( $k <= 2 ) { ?>
					<span class="rank-number"><?php echo $k + 1; ?></span>
				<?php } ?>
				<a href="<?php echo SITE_URL . '/index.php?controller=user&action=stats&uid=' . $user['id']; ?>" class="uk-flex uk-flex-between uk-flex-wrap">
					<div class="user-item-info uk-flex uk-flex-middle" style="gap: 10px;">
						<div class="user-info__avatar">
							<?php echo User::get_user_avatar( array( '50px', '50px' ), $user['id'] ); ?>
						</div>
						<div class="user-info__name uk-flex uk-flex-column">
							<span class="user-name__fullname"><?php echo $user['full_name']; ?></span>
							<span class="user-name__account uk-text-small"><?php echo $user['username']; ?></span>
						</div>
					</div>
					<div class="user-item-stat uk-flex uk-flex-middle" style="gap: 10px;">
						<div class="stat-box stat__daily-view uk-flex uk-flex-column uk-flex-middle" uk-tooltip="Hôm nay">
							<span class="stat-box__number"><?php echo $daily_view; ?></span>
						</div>
						<div class="stat-box stat__weekly-view uk-flex uk-flex-column uk-flex-middle" uk-tooltip="Tuần này">
							<span class="stat-box__number"><?php echo $weekly_view; ?></span>
						</div>
						<div class="stat-box stat__monthly-view uk-flex uk-flex-column uk-flex-middle" uk-tooltip="Tháng này">
							<span class="stat-box__number"><?php echo $monthly_view; ?></span>
						</div>
					</div>
				</a>
			</div>
		<?php } ?>
	<?php } ?>
	<?php
	$html = ob_get_clean();
	return $html;
}

function refresh_dashboard_data() {
	$users                = User::get_all_users();
	$all_daily_views      = Link::get_all_daily_views();
	$all_weekly_views     = Link::get_all_weekly_views();
	$all_monthly_views    = Link::get_all_monthly_views();
	$all_countries_views  = Link::get_all_countries_views();
	$all_daily_views_list = Link::get_total_daily_views();

	$country_html_part   = get_country_html( $all_countries_views );
	$user_list_html_part = get_user_list_html( $users );

	$data = array(
		'all_daily_views'   => $all_daily_views,
		'all_weekly_views'  => $all_weekly_views,
		'all_monthly_views' => $all_monthly_views,
		'country'           => $country_html_part,
		'user_list'         => $user_list_html_part,
	);
	echo json_encode(
		array(
			'success' => true,
			'data'    => $data,
		)
	);
	exit();
}

function generate_short_url_id() {
	$long_url = $_POST['long_url'];

	if ( ! empty( $long_url ) && filter_var( $long_url, FILTER_VALIDATE_URL ) ) {
		$ran_url = generateRandomString( 6 );
		echo json_encode(
			array(
				'success'  => true,
				'short_id' => $ran_url,
				'long_url' => $long_url,
			)
		);
	} else {
		echo json_encode(
			array(
				'success' => false,
				'message' => 'Link không hợp lệ',
			)
		);
	}
	exit();
}

function save_short_url_id() {
	$db = DB::getInstance();

	$long_url         = $_POST['long_url'];
	$short_id         = $_POST['short_url_id'];
	$current_datetime = date( 'd-m-Y H:i:s' );

	if ( ! empty( $long_url ) && ! empty( $short_id ) && filter_var( $long_url, FILTER_VALIDATE_URL ) ) {
		$sql_get_short_id = 'SELECT short_url FROM links WHERE short_url = :short_url';
		$query            = $db->prepare( $sql_get_short_id );
		$query->execute(
			array(
				':short_url' => $short_id,
			)
		);
		$count = $query->rowCount();
		if ( $count > 0 ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => 'Link rút gọn đã tồn tại!',
				)
			);
		} else {
			$curr_user  = User::get_current_user();
			$sql_insert = 'INSERT INTO links (long_url, short_url, user_id) VALUES (:long_url, :short_url, :user_id)';
			$query      = $db->prepare( $sql_insert );
			$query->bindValue( ':long_url', $long_url, PDO::PARAM_STR );
			$query->bindValue( ':short_url', $short_id, PDO::PARAM_STR );
			$query->bindValue( ':user_id', intval( $curr_user['id'] ), PDO::PARAM_INT );
			$query->execute();

			echo json_encode(
				array(
					'success' => true,
					'data'    => array(
						'username'   => $curr_user['username'],
						'long_url'   => $long_url,
						'short_url'  => SITE_URL . '/' . $short_id,
						'created_at' => $current_datetime,
					),
				)
			);
		}
	}
}
