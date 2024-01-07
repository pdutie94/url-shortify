

<div class="uk-section uk-animation-fade">
	<h3 class="uk-margin-small-bottom">Danh sách thành viên</h3>
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-middle uk-table-divider" style="max-height: 500px; overflow-y: auto">
			<thead>
				<tr>
					<th class="uk-table-shrink">ID</th>
					<th style="min-width: 180px" class="uk-width-medium">Tên</th>
					<th style="min-width: 120px" class="uk-width-medium">Username</th>
					<th style="min-width: 200px" class="uk-width-medium">Email</th>
					<th style="min-width: 120px" class="uk-width-small">Vai trò</th>
					<th style="min-width: 120px" class="uk-width-small">Ngày tạo</th>
					<th style="min-width: 100px" class="uk-width-small"></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( count( $users ) > 0 ) { ?>
					<?php
					foreach ( $users as $user ) {
						$date = date_create( $user['created_at'] );
						?>
						<tr>
							<td class="uk-text-nowrap"><?php echo $user['id']; ?></td>
							<td class="uk-text-nowrap"><?php echo $user['full_name']; ?></td>
							<td class="uk-text-nowrap"><?php echo $user['username']; ?></td>
							<td class="uk-text-truncate"><?php echo $user['email']; ?></td>
							<td class="uk-text-nowrap"><?php echo $user['role'] == 1 ? 'Admin' : 'Thành viên'; ?></td>
							<td class="uk-text-nowrap"><?php echo date_format( $date, 'd-m-Y' ); ?></td>
							<td class="uk-text-nowrap">
								<div>
									<a uk-tooltip="title: Chỉnh sửa thông tin" href="<?php echo SITE_URL . '/index.php?controller=user&action=edit&uid=' . $user['id']; ?>" class="uk-icon-link uk-margin-small-right" uk-icon="file-edit"></a>
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
					<th class="uk-table-shrink">ID</th>
					<th style="min-width: 180px" class="uk-width-medium">Tên</th>
					<th style="min-width: 120px" class="uk-width-medium">Username</th>
					<th style="min-width: 200px" class="uk-width-medium">Email</th>
					<th class="uk-width-small" style="min-width: 120px">Vai trò</th>
					<th style="min-width: 120px" class="uk-width-small">Ngày tạo</th>
					<th style="min-width: 100px" class="uk-width-small"></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php
	$pagination = User::pagination();
	echo $pagination;
	?>
</div>
