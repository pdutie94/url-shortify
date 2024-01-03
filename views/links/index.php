<div class="uk-section uk-animation-fade">
	<div class="uk-width-1-1">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-margin-auto uk-card uk-card-default uk-card-medium uk-card-body uk-box-shadow-small">
						<h3 class="uk-card-title uk-text-left">Tạo Short Link</h3>
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