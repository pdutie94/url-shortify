<?php
require_once('models/user.php');
?>
<div class="uk-section uk-animation-fade">
	<div class="uk-width-1-1">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-margin-auto uk-card uk-card-default uk-card-medium uk-card-body uk-box-shadow-small">
						<h3 class="uk-card-title uk-text-left uk-margin-small-bottom">Tạo Short Link</h3>
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
				</div>
			</div>
			<div class="links-list-arena uk-margin-large-top">
				<h3 class="uk-margin-small-bottom">Danh sách link đã tạo</h3>
				<div class="table-list uk-overflow-auto">
					<table class="uk-table uk-table-middle uk-table-divider" style="max-height: 500px; overflow-y: auto">
						<thead>
							<tr>
								<th class="uk-width-small" style="min-width: 120px;">Tài khoản</th>
								<th class="uk-table-expand" style="min-width: 300px;">Link đích</th>
								<th class="uk-width-medium" style="min-width: 300px;">Link rút gọn</th>
								<th class="uk-width-small" style="min-width: 180px;">Thời gian</th>
							</tr>
						</thead>
						<tbody>
							<?php if ( count( $links ) > 0 ) { ?>
								<?php foreach($links as $link) { 
									$user = User::get_user_by_id($link['user_id']);
									$date = date_create($link['created_at']);
									?>
									<tr>
										<td class="uk-text-nowrap"><?= $user['username']; ?></td>
										<td class="uk-text-truncate"><?= $link['long_url']; ?></td>
										<td class="uk-text-truncate">
											<div class="uk-inline uk-width-1-1">
												<input value="<?= SITE_URL . '/' . $link['short_url']; ?>" class="uk-input uk-form-medium short_url" style="padding-right: 40px" type="text" readonly>
												<a class="short_link-copy uk-form-icon uk-form-icon-flip" uk-tooltip="title: Sao chép; pos: bottom-right"><span uk-icon="icon: copy"></span></a>
											</div>
										</td>
										<td class="uk-text-nowrap"><?= date_format($date,"d-m-Y H:i:s"); ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="short_link-popup" class="uk-flex-middle" uk-modal>
    <div class="uk-modal-dialog">
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
            <button class="uk-button uk-button-primary short_link-save" type="button">Lưu</button>
            <button class="uk-button uk-button-default uk-modal-close" type="button">Hủy</button>
        </div>
    </div>
</div>