<?php
$curr_month = date( 'm' );
$curr_year  = date( 'Y' );
?>
<div class="section-general-stats">
	<div class="uk-grid-medium uk-child-width-expand@m" uk-grid>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Hôm nay</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_daily_views; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tuần này</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_weekly_views; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tháng này <?php echo $curr_month; ?>/<?php echo $curr_year; ?></h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_monthly_views; ?></div>
			</div>
		</div>
	</div>
</div>
<div class="section-chart my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Lượt xem trong tháng: <?php echo $curr_month; ?>/<?php echo $curr_year; ?></h2>
	<canvas id="myLineChart" width="400" height="200"></canvas>
</div>
