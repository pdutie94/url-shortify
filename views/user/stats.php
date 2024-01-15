<?php
$user       = User::get_user_by_id( intval( $_GET['uid'] ) );
$curr_month = date( 'm' );
$curr_year  = date( 'Y' );
// var_dump($links);
?>

<div class="uk-section my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h1 class="my-heading">Thống kê lượt xem</h1>
	<div class="member-info uk-flex uk-flex-middle" style="gap:10px; flex-wrap: wrap">
		<?php echo User::get_user_avatar(); ?>
		<span class="uk-text-large uk-text-bold"><?php echo $user['username']; ?></span> <span class="uk-text-large uk-text-bold">(<?php echo $user['full_name']; ?>)</span>
	</div>
</div>
<div class="section-general-stats">
	<div class="uk-grid-medium uk-child-width-expand@m" uk-grid>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Lượt xem hôm nay</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_day; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Lượt xem tháng <?php echo $curr_month; ?>/<?php echo $curr_year; ?></h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $view_in_month; ?></div>
			</div>
		</div>
		<div class="my-box">
			<div class="my-margin-small-top uk-card uk-card-default my-padding-small uk-card-body my-border-radius my-box-shadow-none">
				<h3 class="my-card-title">Tổng lượt xem</h3>
				<div class="card-number uk-text-large uk-text-bold"><?php echo $total_view; ?></div>
			</div>
		</div>
	</div>
</div>
<div class="section-chart my-margin-small-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h2 class="my-heading">Lượt xem trong tháng: <?php echo $curr_month; ?>/<?php echo $curr_year; ?></h2>
	<canvas id="myLineChart" width="400" height="200"></canvas>
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

	const data = <?php echo json_encode( $daily_view ); ?>;

	const minDate = new Date(Math.min(...data.map(item => new Date(item.x))));
	const maxDate = new Date(Math.max(...data.map(item => new Date(item.x))));
	
	const result = [];
	let currentDate = new Date(minDate);
	while (currentDate <= maxDate) {
		const formattedDate = currentDate.toISOString().split('T')[0];
		result.push({ x: formattedDate, y: 0 });
		currentDate.setDate(currentDate.getDate() + 1);
	}

	result.forEach(day => {
		const matchingItem = data.find(item => item.x === day.x);
		if (matchingItem) {
			day.y = matchingItem.y;
		}
	});

	var delayed
	const ctx = document.getElementById('myLineChart').getContext('2d');
	const myLineChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: result.map(item => item.x),
			datasets: [{
				label: 'Lượt xem',
				data: result.map(item => item.y),
				backgroundColor: 'blue',
				borderColor: 'blue',
				borderWidth: 1
			}]
		},
		options: {
			plugins: {
				tooltip: {
					callbacks: {
						title:  function(context) {
							console.log(context);
							const d = new Date(context[0].parsed.x)
							const formattedDate = d.toLocaleString([], {
								day: 'numeric',
								month: 'numeric',
								year: 'numeric'
							})
							return formattedDate
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
							day: 'd',
						}
					},
					title: {
						display: false,
						text: 'Ngày',
					}
				},
				y: {
					title: {
						display: false,
						text: 'Lượt xem',
					}
				}
			},
			animation: {
				onComplete: function() {
					delayed = true;
				},
				delay: function(context) {
					let delay = 0;
					if (context.type === 'data' && context.mode === 'default' && !delayed) {
						delay = context.dataIndex * 100 + context.datasetIndex * 100;
					}
					return delay;
				},
			}
		}
	});
</script>
