<?php
require_once 'models/user.php';
?>
<div class="uk-section my-padding-top-none uk-padding-remove-bottom uk-margin-medium-top">
	<div class="uk-container">
		<div class="create-short-link-form uk-margin uk-margin-auto uk-card uk-card-default uk-card-body my-padding my-border-radius my-box-shadow-none">
			<h2 class="my-heading my-margin-bottom">Tạo Short Link</h2>
			<form class="form-short_link" action="" method="post">
				<div class="form-control uk-margin">
					<div class="uk-inline uk-width-1-1">
						<span class="uk-form-icon" uk-icon="icon: link"></span>
						<input name="long_url" class="uk-input uk-form-medium long_url" required type="url" placeholder="Nhập hoặc dán link vào đây">
					</div>
				</div>
				<div class="uk-margin uk-margin-remove-bottom">
					<button class="uk-button uk-button-primary uk-button-medium">Tạo Link</button>
				</div>
			</form>
		</div>
		<div class="links-list-arena uk-margin-medium-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
			<h2 class="my-heading my-margin-bottom">Danh sách link đã tạo</h2>
			<div class="uk-overflow-auto">
				<table class="table-list uk-table uk-table-small uk-table-middle">
					<thead>
						<tr>
							<th class="uk-width-small" style="min-width: 120px;">Tài khoản</th>
							<th class="uk-width-medium" style="min-width: 300px;">Link đích</th>
							<th class="uk-width-medium" style="min-width: 300px;">Link rút gọn</th>
							<th class="uk-width-small" style="min-width: 180px;">Thời gian</th>
							<!-- <th class="uk-width-small" style="min-width: 180px;">Lượt xem</th> -->
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
											<a uk-tooltip="title: Xóa link" href="#" class="uk-icon-link" uk-icon="trash"></a>
										</div>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<th class="uk-width-small" style="min-width: 120px;">Tài khoản</th>
							<th class="uk-width-medium" style="min-width: 300px;">Link đích</th>
							<th class="uk-width-medium" style="min-width: 300px;">Link rút gọn</th>
							<th class="uk-width-small" style="min-width: 180px;">Thời gian</th>
							<!-- <th class="uk-width-small" style="min-width: 180px;">Lượt xem</th> -->
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
	</div>
</div>
<div id="short_link-popup" class="uk-flex-middle" uk-modal>
	<div class="uk-modal-dialog uk-overflow-hidden my-border-radius">
		<div class="uk-modal-body uk-margin-auto-vertical">
			<div class="uk-margin-small-bottom uk-text-left uk-text-success">Link rút gọn đã được tạo thành công!</div>
			<form class="form-save-short_link">
				<div class="form-control uk-margin uk-margin-remove-bottom">
					<div class="uk-inline uk-width-1-1">
						<input type="hidden" name="short_url_id" value="">
						<input type="hidden" name="long_url" value="">
						<input id="short_url" name="short_url" class="uk-input uk-form-medium short_url" type="text" readonly>
						<a class="short_link-copy uk-form-icon uk-form-icon-flip" uk-tooltip="title: Sao chép; pos: bottom-right"><span uk-icon="icon: copy"></span></a>
					</div>
				</div>
			</form>
		</div>
		<div class="uk-modal-footer uk-text-right">
			<button class="uk-button uk-button-primary short_link-save my-border-radius-small" type="button">Lưu</button>
			<button class="uk-button uk-button-default uk-modal-close my-border-radius-small" type="button">Hủy</button>
		</div>
	</div>
</div>
