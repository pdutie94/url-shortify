<?php
$user       = User::get_user_by_id( intval( $_GET['uid'] ) );
$curr_month = date( 'n' );
$curr_year  = date( 'Y' );
?>

<div class="uk-section uk-animation-fade">
	<div class="page-header uk-margin-medium-bottom">
		<h3>Thống kê lượt xem: <span class="uk-text-large uk-text-bold"><?php echo $user['username']; ?></span><span class="uk-text-large uk-text-bold">(<?php echo $user['full_name']; ?>)</span></h3>
	</div>
	<div class="content-body">
		<div class="charts-wrapper">
		<div class="uk-child-width-expand@s uk-text-center" uk-grid>
			<div>
				<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-text-left">
					<div class="uk-card-title uk-text-default uk-margin-small-bottom uk-text-uppercase">Lượt xem hôm nay</div>
					<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_day; ?></div>
				</div>
			</div>
			<div>
				<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-text-left">
					<div class="uk-card-title uk-text-default uk-margin-small-bottom uk-text-uppercase">Lượt xem tháng <?php echo $curr_month; ?>/<?php echo $curr_year; ?></div>
					<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_day; ?></div>
				</div>
			</div>
			<div>
				<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-text-left">
					<div class="uk-card-title uk-text-default uk-margin-small-bottom uk-text-uppercase">Tổng lượt xem</div>
					<div class="card-number uk-text-large uk-text-bold"><?php echo $total_view; ?></div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
