<?php
$user       = User::get_user_by_id( intval( $_GET['uid'] ) );
$curr_month = date( 'm' );
$curr_year  = date( 'Y' );
// var_dump($links);
?>

<div class="uk-section my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h1 class="my-heading">Thống kê lượt xem</h1>
	<div class="member-info uk-flex uk-flex-middle" style="gap:10px; flex-wrap: wrap">
		<?php echo User::get_user_avatar( array( '50px', '50px' ), $user['id'] ); ?>
		<span class="uk-text-large uk-text-bold"><?php echo $user['username']; ?></span> <span class="uk-text-large uk-text-bold">(<?php echo $user['full_name']; ?>)</span>
	</div>
</div>
<div class="section-general-stats">
	<div class="uk-grid-medium uk-child-width-expand@m" uk-grid>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Hôm nay</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_day; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tuần này</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $weekly_views; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tháng này</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_month; ?></div>
			</div>
		</div>
	</div>
</div>
<div class="section-chart my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Lượt xem</h2>
	<canvas id="myChart" style="max-height: 150px"></canvas>
</div>
<div class="section-link-list my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Danh sách link</h2>
	<div>
		<?php foreach ( $links as $row ) { ?>
			<div class="uk-text-truncate">
				<?php
					echo 'Link ID: ' . $row['id'] . '<br>';
					echo 'Long URL: ' . $row['long_url'] . '<br>';
					echo 'Short URL: ' . $row['short_url'] . '<br>';
					echo 'Total Views in Current Month: ' . $row['total_views'] . '<br>';
					echo '---------------------------<br>';
				?>
				<div class="uk-width-1-1">
					
				</div>
			</div>
		<?php } ?>
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

	var dataArray = <?php echo json_encode( $daily_view ); ?>;
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
					max: milestone,
					beginAtZero: true,
					maxTicksLimit: 3,
					ticks: {
						stepSize: milestone / 2,
						callback: function (value) {
							return value.toString();
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