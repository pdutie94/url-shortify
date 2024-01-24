<?php
$curr_month = date( 'm' );
$curr_year  = date( 'Y' );
// Tính tổng số lượng view của tất cả quốc gia trong tháng
$total_views_all_countries = array_sum( array_column( $all_countries_views, 'total_views' ) );

foreach ( $all_countries_views as &$country ) {
	$country['percentage'] = round( ( $country['total_views'] / $total_views_all_countries ) * 100 );
}
?>
<!-- <button id="refresh_dashboard_data">Refresh</button> -->
<div class="section-general-stats">
	<div class="uk-grid-medium uk-child-width-expand@m" uk-grid>
		<div class="my-box box__all-daily-views">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Hôm nay</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_daily_views; ?></div>
			</div>
		</div>
		<div class="my-box box__all-weekly-views">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tuần này</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_weekly_views; ?></div>
			</div>
		</div>
		<div class="my-box box__all-monthly-views">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tháng này</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $all_monthly_views; ?></div>
			</div>
		</div>
	</div>
</div>
<div class="section-chart my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Lượt xem</h2>
	<canvas id="myChart" style="max-height: 150px;"></canvas>
</div>
<div class="section-top-country my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Top Quốc Gia</h2>
	<div class="uk-overflow-auto">
		<table class="table-list uk-table uk-table-small uk-table-middle">
			<thead>
				<tr>
					<th class="uk-table-shrink" style="width: 30px">#</th>
					<th class="uk-width-medium" style="min-width: 120px;max-width:250px">Quốc gia</th>
					<th class="uk-table-expand uk-visible@s" style="min-width: 200px">&nbsp;</th>
					<th class="uk-width-small uk-text-right" style="min-width: 100px">Lượt xem</th>
					<th class="uk-width-small uk-text-right" style="max-width: 100px">%</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( count( $all_countries_views ) > 0 ) { ?>
					<?php
					foreach ( $all_countries_views as $k => $country_data ) {
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
			</tbody>
		</table>
	</div>
</div>
<div class="section-user-list my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<div class="page-header my-margin-bottom uk-flex uk-flex-between uk-flex-middle uk-flex-wrap">
		<h2 class="my-heading uk-margin-remove">Danh sách thành viên</h2>
	</div>
	<div class="section-body">
		<div class="users-list">
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
		</div>
	</div>
</div>

<script>
	var findNextMilestone = function (inputNumber) {
		var power = Math.floor(Math.log10(inputNumber));
		var base = Math.pow(10, power);

		for (var i = 1; i <= 5; i++) {
			var milestone = base * i;

			if (milestone > inputNumber) {
				return milestone;
			}
		}

		return base * 10;
	};

	var getMaxValue = function (data) {
		return Math.max.apply(null, data.datasets[0].data);
	};

	var dataArray = <?php echo json_encode( $all_daily_views_list ); ?>;
	dataArray.forEach(function (item) {
		item.x = new Date(item.x);
	});

	var startDate = new Date(Math.min.apply(null, dataArray.map(function (item) {
		return item.x.getTime();
	})));
	var endDate = new Date(Math.max.apply(null, dataArray.map(function (item) {
		return item.x.getTime();
	})));

	var currentDate = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
	var lastDayOfMonth = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0);

	while (currentDate <= lastDayOfMonth) {
		var dateString = currentDate.toISOString().split('T')[0];
		var existingData = dataArray.find(function (item) {
			return item.x.toISOString().split('T')[0] === dateString;
		});

		if (!existingData) {
			dataArray.push({ "x": new Date(currentDate), "y": "0" });
		}

		currentDate.setDate(currentDate.getDate() + 1);
	}

	dataArray.sort(function (a, b) {
		return a.x - b.x;
	});

	var data = {
		labels: dataArray.map(function (item) {
			return item.x;
		}),
		datasets: [{
			label: 'Lượt xem',
			data: dataArray.map(function (item) {
				return item.y;
			}),
			backgroundColor: '#1e87f0',
			borderColor: 'blue',
			borderWidth: 0
		}]
	};

	var delayed;
	var ctx = document.getElementById('myChart').getContext('2d');
	var milestone = findNextMilestone(getMaxValue(data))
	if ( getMaxValue(data) < 10 ) {
		milestone = 10;
	}
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: data,
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false
				},
				tooltip: {
					callbacks: {
						title: function (context) {
							var d = new Date(context[0].parsed.x);
							var formattedDate = d.toLocaleString([], {
								day: 'numeric',
								month: 'numeric',
								year: 'numeric'
							});
							return formattedDate;
						}
					}
				}
			},
			scales: {
				x: {
					type: 'time',
					time: {
						unit: 'day',
						displayFormats: {
							day: 'd'
						}
					},
					grid: {
						display: false
					}
				},
				y: {
					barPercentage: 1,
					categoryPercentage: 1,
					max: milestone,
					beginAtZero: true,
					maxTicksLimit: 3,
					ticks: {
						stepSize: milestone / 2,
						callback: function (value) {
							if (value >= 1000 ) {
								return value / 1000 + 'k';
							} else {
								return value;
							}
						}
					}
				}
			},
			animation: {
				onComplete: function () {
					delayed = true;
				},
				delay: function (context) {
					var delay = 100;
					return delay;
				},
			}
		},
	});
</script>