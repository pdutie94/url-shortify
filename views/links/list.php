<div class="uk-section uk-margin-medium-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<h1 class="my-heading my-margin-bottom">Danh sách link</h1>
	<div class="uk-overflow-auto">
		<table class="table-list uk-table uk-table-small uk-table-middle">
			<thead>
				<tr>
					<th class="uk-width-small" style="min-width: 120px;">Tài khoản</th>
					<th class="uk-table-expand" style="min-width: 300px;">Link đích</th>
					<th class="uk-width-medium" style="min-width: 300px;">Link rút gọn</th>
					<th class="uk-width-small" style="min-width: 180px;">Thời gian</th>
					<th class="uk-width-small" style="min-width: 180px;">Lượt xem</th>
					<th class="uk-width-small" style="min-width: 100px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( count( $links ) > 0 ) { ?>
					<?php
					foreach ( $links as $link ) {
						$user = User::get_user_by_id( $link['user_id'] );
						$date = date_create( $link['created_at'] );
						?>
						<tr>
							<td class="uk-text-nowrap"><?php echo $user['username']; ?></td>
							<td class="uk-text-truncate"><?php echo $link['long_url']; ?></td>
							<td class="uk-text-truncate">
								<div class="uk-inline uk-width-1-1">
									<input value="<?php echo SITE_URL . '/' . $link['short_url']; ?>" class="uk-input uk-form-medium short_url" style="padding-right: 40px" type="text" readonly>
									<a class="short_link-copy uk-form-icon uk-form-icon-flip" uk-tooltip="title: Sao chép; pos: bottom-right"><span uk-icon="icon: copy"></span></a>
								</div>
							</td>
							<td class="uk-text-nowrap"><?php echo date_format( $date, 'd-m-Y H:i:s' ); ?></td>
							<!-- <td><?php display_link_views( $link['short_url'] ); ?></td> -->
							<td class="uk-text-nowrap">
								<div>
									<a uk-tooltip="title: Xem thống kê chi tiết" href="<?php echo SITE_URL . '/index.php?controller=user&action=stats&uid=' . $user['id']; ?>" class="uk-icon-link uk-margin-small-right" uk-icon="bolt"></a>
									<a uk-tooltip="title: Xóa thành viên" href="#" class="uk-icon-link" uk-icon="trash"></a>
								</div>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="uk-width-small" style="min-width: 120px;">Tài khoản</th>
					<th class="uk-table-expand" style="min-width: 300px;">Link đích</th>
					<th class="uk-width-medium" style="min-width: 300px;">Link rút gọn</th>
					<th class="uk-width-small" style="min-width: 180px;">Thời gian</th>
					<th class="uk-width-small" style="min-width: 180px;">Lượt xem</th>
					<th class="uk-width-small" style="min-width: 100px;">&nbsp;</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php
	$pagination = Link::pagination();
	echo $pagination;
	?>
</div>
